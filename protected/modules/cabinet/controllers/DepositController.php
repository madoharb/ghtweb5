<?php

Yii::import('application.modules.deposit.extensions.Deposit.*');

class DepositController extends CabinetBaseController
{
	public function actionIndex()
	{
        $aggregatorList = Deposit::getAggregatorsList();

        if(!$this->gs->deposit_allow || !isset($aggregatorList[$this->gs->deposit_payment_system]))
        {
            throw new CHttpException(503, Yii::t('main', 'Пополнение баланса отключено.'));
        }

        $model = new DepositForm();

        if(isset($_POST['DepositForm']))
        {
            $model->setAttributes($_POST['DepositForm']);

            if($model->validate())
            {
                try
                {
                    db()->createCommand()->insert('{{transactions}}', array(
                        'payment_system'  => $this->gs->deposit_payment_system,
                        'user_id'         => user()->getId(),
                        'sum'             => $model->sum * $this->gs->deposit_course_payments,
                        'count'           => $model->sum,
                        'status'          => 0,
                        'user_ip'         => userIp(),
                        'params'          => NULL,
                        'gs_id'           => user()->getGsId(),
                        'created_at'      => date('Y-m-d H:i:s'),
                    ));

                    app()->session['transaction_id'] = db()->getLastInsertID();
                    $this->redirect(array('/cabinet/deposit/processed'));
                }
                catch(Exception $e)
                {
                    Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'deposit');
                    user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Произошла ошибка! Попробуйте повторить позже.'));
                    $this->refresh();
                }
            }
        }

        $deposit = new Deposit();
        $deposit->init($this->gs->deposit_payment_system);

        $isSms = FALSE;

        if($this->gs->deposit_payment_system == Deposit::PAYMENT_SYSTEM_WAYTOPAY && config('waytopay.sms.allow'))
        {
            $isSms = TRUE;
        }

        if($isSms)
        {
            $smsList      = $deposit->getSmsNumbers();
            $smsCountries = array();

            foreach(array_keys($smsList) as $countryCode)
            {
                $smsCountries[$countryCode] = app()->getLocale()->getTerritory($countryCode);
            }
        }

        $this->render('//cabinet/deposit/index', array(
            'model'         => $model,
            'isSms'         => $isSms,
            'deposit'       => $deposit,
            'smsList'       => (isset($smsList) ? $smsList : array()),
            'smsCountries'  => (isset($smsCountries) ? $smsCountries : array()),
        ));
	}

    public function actionProcessed()
    {
        $transactionId = app()->session['transaction_id'];

        $model = Transactions::model()->findByPk($transactionId);

        // Транзакция не найдена
        if($model === NULL)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Транзакция не найдена.'));
            $this->redirect(array('index'));
        }

        if($model->isPaid())
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Транзакция уже обработана.'));
            $this->redirect(array('index'));
        }

        $deposit = new Deposit();
        $deposit->init($model->payment_system);

        $this->render('//cabinet/deposit/processed', array(
            'model'      => $model,
            'fields'     => $deposit->getFields($model),
            'formAction' => $deposit->getFormAction(),
            'deposit'    => $deposit,
        ));
    }
}
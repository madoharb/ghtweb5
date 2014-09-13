<?php

Yii::import('application.modules.cabinet.models.Bonuses');
Yii::import('application.modules.cabinet.models.BonusesItems');
Yii::import('application.modules.cabinet.models.BonusCodes');
Yii::import('application.modules.cabinet.models.BonusCodesActivatedLogs');

class BonusesController extends BackendBaseController
{
	public function actionIndex()
	{
        $dataProvider = new CActiveDataProvider('Bonuses', array(
            'criteria' => array(
                'order' => 'title',
                'with' => array('items', 'itemCount'),
            ),
            'pagination' => array(
                'pageSize' => 20,
                'pageVar'  => 'page',
            ),
        ));

		$this->render('//bonuses/index', array(
            'dataProvider' => $dataProvider,
        ));
	}

    public function actionForm($id = NULL)
    {
        if($id === NULL)
        {
            $model = new Bonuses();
        }
        else
        {
            $model = $this->loadBonusesModel($id);
        }

        if(isset($_POST['Bonuses']))
        {
            $model->setAttributes($_POST['Bonuses']);

            if($model->save())
            {
                $msg = Yii::t('backend', 'Бонус сохранен.');

                if($id === NULL)
                {
                    $msg = Yii::t('backend', 'Бонус добавлен.');
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->refresh();
            }
        }

        $this->render('//bonuses/form', array(
            'model' => $model,
        ));
    }

    public function actionAllow($id)
    {
        $model = $this->loadBonusesModel($id);

        $status = ($model->status == ActiveRecord::STATUS_ON ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);
        $model->setAttribute('status', $status);

        if($model->save(FALSE, array('status')))
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Статус изменен на <b>:status</b>', array(':status' => $model->getStatus())));
        }

        $this->redirectBack();
    }

    public function actionDel($id)
    {
        $model = $this->loadBonusesModel($id);

        if($model->delete())
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Бонус <b>:name</b> удален', array(':name' => e($model->title))));
        }

        $this->redirectBack();
    }

    /**
     * Предметы в бонусе
     *
     * @param int $bonus_id
     * @throws CHttpException
     *
     * @return void
     */
    public function actionItems($bonus_id)
    {
        $bonus = $this->loadBonusesModel($bonus_id);

        $dataProvider = new CActiveDataProvider('BonusesItems', array(
            'criteria' => array(
                'condition' => 'bonus_id = :bonus_id',
                'params' => array(
                    ':bonus_id' => $bonus_id
                ),
                'with' => array('itemInfo'),
            ),
            'pagination' => array(
                'pageSize' => 20,
                'pageVar'  => 'page',
            ),
        ));

        $this->render('//bonuses/items/index', array(
            'bonus'        => $bonus,
            'dataProvider' => $dataProvider,
        ));
    }

    public function loadBonusesModel($id)
    {
        $model = Bonuses::model()->findByPk($id);

        if(!$model)
        {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }

    /**
     * Добавление предмета к бонусу
     *
     * @param int $bonus_id
     */
    public function actionItemAdd($bonus_id)
    {
        $model = new BonusesItems();

        if(isset($_POST[CHtml::modelName($model)]))
        {
            $model->setAttributes($_POST[CHtml::modelName($model)]);
            $model->bonus_id = $bonus_id;

            if($model->save())
            {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Предмет добавлен.'));
                $this->refresh();
            }
        }

        $this->render('//bonuses/items/form', array(
            'bonus' => Bonuses::model()->findByPk($bonus_id),
            'model' => $model,
        ));
    }

    /**
     * Редактирование предмета в бонусе
     *
     * @param int $bonus_id
     * @param int $item_id
     */
    public function actionItemEdit($bonus_id, $item_id)
    {
        $model = BonusesItems::model()->findByPk($item_id);

        if($model === NULL)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('backend', 'Предмет не найден.'));
            $this->redirect(array('/backend/bonuses/items'));
        }

        $model->item_name = $model->itemInfo->name . ($model->itemInfo->add_name ? ' (' . $model->itemInfo->add_name . ')' : '');

        if(isset($_POST['BonusesItems']))
        {
            $model->setAttributes($_POST['BonusesItems']);

            if($model->save())
            {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Предмет сохранен.'));
                $this->refresh();
            }
        }

        $this->render('//bonuses/items/form', array(
            'bonus' => Bonuses::model()->findByPk($bonus_id),
            'model' => $model,
        ));
    }

    /**
     * Изменения статуса предмету
     *
     * @param int $bonus_id
     * @param int $item_id
     */
    public function actionItemAllow($bonus_id, $item_id)
    {
        $model = BonusesItems::model()->findByPk($item_id);

        if($model === NULL)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('backend', 'Предмет не найден.'));
            $this->redirectBack();
        }

        $status = ($model->status == ActiveRecord::STATUS_ON ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);
        $model->setAttribute('status', $status);

        if($model->save(FALSE, array('status')))
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Статус изменен на <b>:status</b>', array(':status' => $model->getStatus())));
        }

        $this->redirectBack();
    }

    /**
     * Удаление предмета из бонуса
     *
     * @param int $bonus_id
     * @param int $item_id
     */
    public function actionItemDel($bonus_id, $item_id)
    {
        $model = BonusesItems::model()->with('itemInfo')->findByPk($item_id);

        if($model === NULL)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('backend', 'Предмет не найден.'));
            $this->redirectBack();
        }

        if($model->delete())
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Предмет <b>:item_name</b> удален', array(':item_name' => CHtml::encode($model->itemInfo->name))));
        }

        $this->redirectBack();
    }

    /**
     * Коды для бонусов
     */
    public function actionCodes()
    {
        $dataProvider = new CActiveDataProvider('BonusCodes', array(
            'criteria' => array(
                'with' => array('bonusInfo', 'bonusLog'),
            ),
            'pagination' => array(
                'pageSize' => 20,
                'pageVar'  => 'page',
            ),
        ));

        $this->render('//bonuses/codes/index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Создание кода
     */
    public function actionCodeAdd()
    {
        $model = new BonusCodes('code_form');

        if(isset($_POST[CHtml::modelName($model)]))
        {
            $model->setAttributes($_POST[CHtml::modelName($model)]);

            if($model->save())
            {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Код добавлен.'));
                $this->refresh();
            }
        }

        $this->render('//bonuses/codes/form', array(
            'model' => $model,
        ));
    }

    /**
     * Редактирование кода
     *
     * @params int $code_id
     */
    public function actionCodeEdit($code_id)
    {
        $model = BonusCodes::model()->findByPk($code_id);

        if(!$model)
        {
            throw new CHttpException(404, Yii::t('backend', 'Код не найден'));
        }

        if(isset($_POST[CHtml::modelName($model)]))
        {
            $model->setScenario('form_code');
            $model->setAttributes($_POST[CHtml::modelName($model)]);

            if($model->save())
            {
                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Изменения сохранены.'));
                $this->refresh();
            }
        }

        $this->render('//bonuses/codes/form', array(
            'model' => $model,
        ));
    }

    /**
     * Удаление кода
     *
     * @param int $code_id
     */
    public function actionCodeDel($code_id)
    {
        $model = BonusCodes::model()->findByPk($code_id);

        if($model === NULL)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('backend', 'Код не найден.'));
            $this->redirectBack();
        }

        if($model->delete())
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Код удален, ID :id', array(':id' => $code_id)));
        }

        $this->redirectBack();
    }

    /**
     * Изменения статуса коду
     *
     * @param int $code_id
     */
    public function actionCodeAllow($code_id)
    {
        $model = BonusCodes::model()->findByPk($code_id);

        if($model === NULL)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('backend', 'Код не найден.'));
            $this->redirectBack();
        }

        $status = ($model->status == ActiveRecord::STATUS_ON ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);
        $model->setAttribute('status', $status);

        if($model->save(FALSE, array('status')))
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Статус изменен на <b>:status</b>', array(':status' => $model->getStatus())));
        }

        $this->redirectBack();
    }

    /**
     * Генерация бонус кода
     */
    public function actionGenerateCode($parts = 4, $length = 4, $divider = '-')
    {
        $code = '';

        for($i = 0; $i < $parts; $i++)
        {
            $code .= strtoupper(randomString($length)) . $divider;
        }

        echo substr($code, 0, -1);
    }
}
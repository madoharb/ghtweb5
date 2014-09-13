<?php

class LoginServersController extends BackendBaseController
{
	public function actionIndex()
	{
        $model = new Ls('search');
        $model->unsetAttributes();

        if(isset($_GET['Ls']))
        {
            $model->setAttributes($_GET['Ls']);
        }

        $dataProvider = $model->search();

		$this->render('//ls/index', array(
            'dataProvider' => $dataProvider,
            'model'        => $model,
        ));
	}

    public function actionForm($ls_id = NULL)
    {
        if($ls_id === NULL)
        {
            $model = new Ls();
        }
        else
        {
            $model = $this->loadModel($ls_id);
        }

        if(isset($_POST['Ls']))
        {
            $model->setAttributes($_POST['Ls']);

            if($model->save())
            {
                $msg = Yii::t('backend', 'Изменения сохранены.');

                if($ls_id === NULL)
                {
                    $msg = Yii::t('backend', 'Логин добавлен.');
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->refresh();
            }
        }

        $this->render('//ls/form', array(
            'model' => $model,
        ));
    }

    public function actionAllow($ls_id)
    {
        $model = $this->loadModel($ls_id);

        $status = ($model->status == ActiveRecord::STATUS_ON ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);
        $model->setAttribute('status', $status);

        if($model->save(FALSE, array('status')))
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Статус изменен на <b>:status</b>', array(':status' => $model->getStatus())));
        }

        $this->redirectBack();
    }

    public function actionDel($ls_id)
    {
        $model = $this->loadModel($ls_id);

        $model->status = ActiveRecord::STATUS_DELETED;

        if($model->save(TRUE))
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Логин сервер <b>:name</b> удален', array(':name' => e($model->name))));
        }

        $this->redirectBack();
    }

    public function actionAccounts($ls_id)
    {
        $perPage = 20;

        try
        {
            $l2 = l2('ls', $ls_id)->connect();

            $accounts = $l2->accounts()->queryAll();

            $dataProvider = new CArrayDataProvider($accounts, array(
                'id' => 'accounts',
                'sort' => array(
                    'attributes' => array('login'),
                ),
                'pagination' => array(
                    'pageSize' => $perPage,
                    'pageVar'  => 'page',
                ),
            ));
        }
        catch(Exception $e)
        {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'LoginServersController::' . __LINE__);
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('backend', $e->getMessage()));
            $this->redirect(array('index'));
        }

        $ls = $this->loadModel($ls_id);

        $this->render('//ls/accounts/index', array(
            'ls'           => $ls,
            'dataProvider' => $dataProvider,
            'perPage'      => $perPage,
        ));
    }


    public function loadModel($id)
    {
        $model = Ls::model()->findByPk($id);

        if(!$model)
        {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }
}

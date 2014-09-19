<?php

class PagesController extends BackendBaseController
{
	public function actionIndex()
	{
        $model = new Pages('search');
        $model->unsetAttributes();

        if(isset($_GET['Pages']))
        {
            $model->setAttributes($_GET['Pages']);
        }

        $dataProvider = $model->search();

		$this->render('//pages/index', array(
            'dataProvider' => $dataProvider,
            'model'        => $model,
        ));
	}

    public function actionForm($id = NULL)
    {
        if($id === NULL)
        {
            $model = new Pages();
        }
        else
        {
            $model = $this->loadModel($id);
        }

        if(isset($_POST['Pages']))
        {
            $model->setAttributes($_POST['Pages']);

            if($model->save())
            {
                $msg = Yii::t('backend', 'Страница сохранена.');

                if($id === NULL)
                {
                    $msg = Yii::t('backend', 'Страница добавлена.');
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->refresh();
            }
        }

        $this->render('//pages/form', array(
            'model' => $model,
        ));
    }

    public function actionAllow($id)
    {
        $model = $this->loadModel($id);

        $status = ($model->status == ActiveRecord::STATUS_ON ? ActiveRecord::STATUS_OFF : ActiveRecord::STATUS_ON);
        $model->setAttribute('status', $status);

        if($model->save(FALSE, array('status')))
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Статус изменен на <b>:status</b>.', array(':status' => $model->getStatus())));
        }

        $this->redirectBack();
    }

    public function actionDel($id)
    {
        $model = $this->loadModel($id);

        $model->status = ActiveRecord::STATUS_DELETED;

        if($model->save(TRUE))
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Страница <b>:name</b> удалена', array(':name' => e($model->title))));
        }

        $this->redirectBack();
    }

    public function loadModel($id)
    {
        $model = Pages::model()->not_deleted()->findByPk($id);

        if(!$model)
        {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }
}
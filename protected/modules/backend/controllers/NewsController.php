<?php

Yii::import('modules.news.models.News');

class NewsController extends BackendBaseController
{
	public function actionIndex()
	{
        $model = new News('search');
        $model->unsetAttributes();

        if(isset($_GET['News']))
        {
            $model->setAttributes($_GET['News']);
        }

        $dataProvider = $model->search();

		$this->render('//news/index', array(
            'dataProvider' => $dataProvider,
            'model'        => $model,
        ));
	}

    public function actionForm($id = NULL)
    {
        if($id === NULL)
        {
            $model = new News();
        }
        else
        {
            $model = $this->loadModel($id);
        }

        if(isset($_POST['News']))
        {
            $model->setAttributes($_POST['News']);

            if($model->save())
            {
                $msg = Yii::t('backend', 'Новость сохранена.');

                if($id === NULL)
                {
                    $msg = Yii::t('backend', 'Новость добавлена.');
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->refresh();
            }
        }

        $this->render('//news/form', array(
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
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Новость <b>:name</b> удалена', array(':name' => e($model->title))));
        }

        $this->redirectBack();
    }

    public function loadModel($id)
    {
        $model = News::model()->not_deleted()->findByPk($id);

        if(!$model)
        {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }
}
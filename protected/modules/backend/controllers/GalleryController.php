<?php

class GalleryController extends BackendBaseController
{
	public function actionIndex()
	{
        $dataProvider = new CActiveDataProvider('Gallery', array(
            'criteria' => array(
                'order' => 'sort',
            ),
            'pagination' => array(
                'pageSize' => 20,
                'pageVar'  => 'page',
            ),
        ));

		$this->render('//gallery/index', array(
            'dataProvider' => $dataProvider,
        ));
	}

    public function actionForm($id = NULL)
    {
        if($id === NULL)
        {
            $model = new Gallery();
        }
        else
        {
            $model = $this->loadModel($id);
        }

        if(isset($_POST['Gallery']))
        {
            $model->setAttributes($_POST['Gallery']);

            if($model->save())
            {
                $msg = Yii::t('backend', 'Изменения сохранены.');

                if($id === NULL)
                {
                    $msg = Yii::t('backend', 'Картинка добавлена.');
                }

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, $msg);
                $this->refresh();
            }
        }

        $this->render('//gallery/form', array(
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
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Статус изменен на <b>:status</b>', array(':status' => $model->getStatus())));
        }

        $this->redirectBack();
    }

    public function actionDel($id)
    {
        $model = $this->loadModel($id);

        if($model->delete())
        {
            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('backend', 'Картинка удалена, ID :id', array(':id' => $id)));
        }

        $this->redirectBack();
    }

    public function loadModel($id)
    {
        $model = Gallery::model()->findByPk($id);

        if(!$model)
        {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }
}
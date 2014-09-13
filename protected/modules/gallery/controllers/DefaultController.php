<?php

class DefaultController extends FrontendBaseController
{
	public function actionIndex()
	{
        $dependency = new CDbCacheDependency('SELECT MAX(UNIX_TIMESTAMP(updated_at)), COUNT(0) FROM {{gallery}} WHERE status = :status');
        $dependency->params = array('status' => ActiveRecord::STATUS_ON);
        $model      = Gallery::model()->cache(3600 * 24, $dependency, 2)->opened();

        $dataProvider = new CActiveDataProvider($model, array(
            'criteria' => array (
                'order' => 'sort',
            ),
            'pagination' => array (
                'pageSize' => (int) config('gallery.limit'),
                'pageVar'  => 'page',
            ),
        ));

		$this->render('//gallery', array(
            'dataProvider' => $dataProvider,
        ));
	}
}
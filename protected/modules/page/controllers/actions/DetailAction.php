<?php 
 
class DetailAction extends CAction
{
    public function run($page_name = 'index')
    {
        $dependency = new CDbCacheDependency("SELECT MAX(UNIX_TIMESTAMP(updated_at)) FROM {{pages}} WHERE page = :page AND status = :status");
        $dependency->params = array(
            'page'   => $page_name,
            'status' => ActiveRecord::STATUS_ON,
        );

        $model = Pages::model()->cache(3600 * 24, $dependency)->opened()->find('page = :page', array(':page' => $page_name));

        if($model === NULL)
        {
            throw new CHttpException(404, Yii::t('main', 'Страница не найдена.'));
        }

        app()->getController()->render('//page', array(
            'model' => $model,
        ));
    }
}

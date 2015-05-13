<?php 
 
class DetailAction extends CAction
{
    public function run($page_name = 'index')
    {
        $dependency = new CDbCacheDependency("SELECT MAX(UNIX_TIMESTAMP(updated_at)) FROM {{pages}} WHERE page = :page AND status = :status AND lang = :lang");
        $dependency->params = array(
            'page'   => $page_name,
            'status' => ActiveRecord::STATUS_ON,
            'lang'   => app()->getLanguage(),
        );

        $model = Pages::model()->cache(3600 * 24, $dependency)->opened()->find('page = :page AND lang = :lang', array(
            'page' => $page_name,
            'lang' => app()->getLanguage(),
        ));

        if($model === NULL)
        {
            throw new CHttpException(404, Yii::t('main', 'Страница не найдена.'));
        }

        app()->getController()->render('//page', array(
            'model' => $model,
        ));
    }
}

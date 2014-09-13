<?php 
 
class DetailAction extends CAction
{
    public function run($news_id = 0)
    {
        $dependency = new CDbCacheDependency('SELECT MAX(UNIX_TIMESTAMP(updated_at)) FROM {{news}} WHERE status = :status AND id = :id');
        $dependency->params = array(
            'status' => ActiveRecord::STATUS_ON,
            'id'     => $news_id,
        );

        $model = News::model()->cache(3600 * 24, $dependency)->opened()->findByPk($news_id);

        if(!$model)
        {
            throw new CHttpException(404, Yii::t('main', 'Новость не найдена.'));
        }

        app()->getController()->render('//news-detail', array(
            'model' => $model,
        ));
    }
}
 
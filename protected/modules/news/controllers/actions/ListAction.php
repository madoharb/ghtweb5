<?php 
 
class ListAction extends CAction
{
    public function run()
    {
        $dependency = new CDbCacheDependency('SELECT MAX(UNIX_TIMESTAMP(updated_at)), COUNT(0) FROM {{news}} WHERE status = :status AND lang = :lang');
        $dependency->params = array(
            'status' => ActiveRecord::STATUS_ON,
            'lang'   => app()->getLanguage(),
        );

        $model = News::model()->cache(3600 * 24, $dependency, 2)->opened();

        $dataProvider = new CActiveDataProvider($model, array(
            'criteria' => array (
                'condition' => 'lang = :lang',
                'params' => array(
                    'lang' => app()->getLanguage(),
                ),
                'order' => 't.created_at DESC',
            ),
            'pagination' => array (
                'pageSize' => (int) config('news.per_page'),
                'pageVar'  => 'page',
            ),
        ));

        app()->getController()->render('//news', array(
            'dataProvider' => $dataProvider,
        ));
    }
}

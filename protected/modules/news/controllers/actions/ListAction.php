<?php 
 
class ListAction extends CAction
{
    public function run()
    {
        $dependency = new CDbCacheDependency('SELECT MAX(UNIX_TIMESTAMP(updated_at)), COUNT(0) FROM {{news}} WHERE status = :status');
        $dependency->params = array('status' => ActiveRecord::STATUS_ON);
        $model      = News::model()->cache(3600 * 24, $dependency, 2)->opened();

        $dataProvider = new CActiveDataProvider($model, array(
            'criteria' => array (
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

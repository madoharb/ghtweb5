<?php

class AuthHistoryController extends CabinetBaseController
{
    public function actionIndex()
    {
        $dependency = new CDbCacheDependency('SELECT MAX(UNIX_TIMESTAMP(created_at)) FROM {{users_auth_logs}} WHERE user_id = :user_id');
        $dependency->params = array(
            'user_id' => user()->getId(),
        );

        $model = UsersAuthLogs::model()->cache(3600 * 24, $dependency, 2);

        $dataProvider = new CActiveDataProvider($model, array(
            'criteria' => array (
                'condition' => 'user_id = :user_id',
                'params' => array(
                    'user_id' => user()->getId(),
                ),
                'order' => 't.created_at DESC',
            ),
            'pagination' => array (
                'pageSize' => (int) config('cabinet.auth_logs_limit'),
                'pageVar' => 'page',
            ),
        ));

        $this->render('//cabinet/auth-history', array(
            'dataProvider' => $dataProvider,
        ));
    }
}

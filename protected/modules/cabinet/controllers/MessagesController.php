<?php

class MessagesController extends CabinetBaseController
{
    public function actionIndex()
    {
        $userId = user()->getId();

        $dependency = new CDbCacheDependency('SELECT COUNT(0), MAX(UNIX_TIMESTAMP(updated_at)) FROM {{user_messages}} WHERE user_id = :user_id');
        $dependency->params = array(
            'user_id' => $userId,
        );

        $model = UserMessages::model()->cache(3600 * 24, $dependency, 2);

        $dataProvider = new CActiveDataProvider($model, array(
            'criteria' => array (
                'condition' => 'user_id = :user_id',
                'params' => array(
                    'user_id' => $userId,
                ),
                'order' => 'created_at DESC',
            ),
            'pagination' => array (
                'pageSize' => (int) config('cabinet.user_messages_limit'),
                'pageVar' => 'page',
            ),
        ));


        $this->render('//cabinet/messages/index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    public function actionDetail($id)
    {
        $userId = user()->getId();

        $dependency = new CDbCacheDependency('SELECT UNIX_TIMESTAMP(updated_at) FROM {{user_messages}} WHERE user_id = :user_id AND id = :id LIMIT 1');
        $dependency->params = array(
            'user_id' => $userId,
            'id'      => $id,
        );

        $model = UserMessages::model()->cache(3600 * 24, $dependency, 2)->findByPk($id, 'user_id = :user_id', array('user_id' => user()->getId()));

        if(!$model)
        {
            throw new CHttpException(400);
        }

        // Меняю статус на прочитаный
        if($model->read == UserMessages::STATUS_NOT_READ)
        {
            $model->read = UserMessages::STATUS_READ;
            $model->save(FALSE);
        }

        $this->render('//cabinet/messages/detail', array(
            'model' => $model,
        ));
    }
}

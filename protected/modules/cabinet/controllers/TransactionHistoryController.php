<?php

class TransactionHistoryController extends CabinetBaseController
{
    public function actionIndex()
    {
        $dependency = new CDbCacheDependency('SELECT COUNT(0) FROM {{transactions}} WHERE user_id = :user_id');
        $dependency->params = array(
            'user_id' => user()->getId(),
        );

        $model = Transactions::model()->cache(3600 * 24, $dependency, 2);

        $dataProvider = new CActiveDataProvider($model, array(
            'criteria' => array (
                'condition' => 'user_id = :user_id',
                'params'    => array(
                    'user_id' => user()->getId(),
                ),
                'order'     => 'created_at DESC',
            ),
            'pagination' => array (
                'pageSize' => config('cabinet.transaction_history.limit'),
                'pageVar' => 'page',
            ),
        ));

        $this->render('//cabinet/transaction-history', array(
            'dataProvider' => $dataProvider,
        ));
    }
}

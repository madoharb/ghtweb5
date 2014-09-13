<?php

class ReferalsController extends CabinetBaseController
{
    public function actionIndex()
    {
        if(!config('referral_program.allow'))
        {
            throw new CHttpException(404, Yii::t('main', 'Реферальная программа отключена.'));
        }

        $dependency = new CDbCacheDependency('SELECT COUNT(0) FROM {{referals_profit}} WHERE referer_id = :referer_id');
        $dependency->params = array('referer_id' => user()->getId());
        $model      = ReferalsProfit::model()->cache(3600 * 24, $dependency, 2);

        $dataProvider = new CActiveDataProvider($model, array(
            'criteria' => array (
                'order' => 'created_at DESC',
            ),
            'pagination' => array (
                'pageSize' => (int) config('cabinet.referals.limit'),
                'pageVar'  => 'page',
            ),
        ));

        $this->render('//cabinet/referrals', array(
            'dataProvider'  => $dataProvider,
            'countReferals' => Referals::model()->count('referer = :referer', array(':referer' => user()->getId())),
        ));
    }
}

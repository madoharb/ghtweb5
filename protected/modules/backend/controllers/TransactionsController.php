<?php

class TransactionsController extends BackendBaseController
{
	public function actionIndex($user_id = NULL)
	{
        Yii::import('application.modules.deposit.extensions.Deposit.Deposit');

        $model = new Transactions('search');
        $model->unsetAttributes();

        if(isset($_GET['Transactions']))
        {
            $model->setAttributes($_GET['Transactions']);
        }

        $dataProvider = $model->search();

		$this->render('//transactions/index', array(
            'dataProvider'    => $dataProvider,
            'model'           => $model,
            'aggregatorsList' => Deposit::getAggregatorsList(),
        ));
	}
}
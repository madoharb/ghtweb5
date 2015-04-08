<?php

class LoginController extends BackendBaseController
{
    public $layout;



	public function actionIndex()
	{
        Yii::import('application.modules.backend.models.LoginForm');

        $model = new LoginForm();

        if(isset($_POST['LoginForm']))
        {
            $model->setAttributes($_POST['LoginForm']);

            if($model->validate() && $model->login())
            {
                $this->redirect(array('/backend/default/index'));
            }
        }


		$this->render('//login/index', array(
            'model' => $model,
        ));
	}

    public function actionLogout()
    {
        admin()->logout();
        $this->redirect(array('/index/default/index'));
    }
}
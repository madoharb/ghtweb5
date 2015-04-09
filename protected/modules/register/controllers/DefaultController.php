<?php

class DefaultController extends FrontendBaseController
{
	public function actionIndex()
	{
        if(!config('register.allow'))
        {
            throw new CHttpException(404, Yii::t('main', 'Регистрация отключена.'));
        }

        $formModel = new RegisterForm();

        if(!$formModel->gs_list)
        {
            throw new CHttpException(404, Yii::t('main', 'Регистрация невозможна из за отсутствия серверов.'));
        }

        if(isset($_POST['RegisterForm']))
        {
            $formModel->setAttributes($_POST['RegisterForm']);

            if($formModel->validate())
            {
                $formModel->registerAccount();

                $this->refresh();
            }
        }


        $this->render('//register', array(
            'model' => $formModel,
        ));
	}

    public function actionActivated($_hash)
    {
        $cache = new CFileCache();
        $cache->init();

        $hash = $cache->get('registerActivated' . $_hash);
        $cache->delete('registerActivated' . $_hash);

        // Ключ не найден, возможно пытаются подобрать или истекло время отведенное для активации аккаунта
        if($hash === FALSE)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Ключ для активации аккаунта не найден.'));
            $this->redirect(array('index'));
        }

        $user = Users::model()->findByPk($hash['user_id']);

        if(!$user)
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Аккаунт не найден.'));
        }
        elseif($user->isActivated())
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Аккаунт уже активирован.'));
        }
        else
        {
            // Создаю игровой аккаунт
            try
            {
                $l2 = l2('ls', $user->ls_id)->connect();

                $l2->insertAccount($user->login, $hash['password']);

                $user->activated = Users::STATUS_ACTIVATED;
                $user->save(FALSE);

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('main', 'Активация аккаунта прошла успешно. Приятной игры!'));

                notify()->registerStep2($hash['email'], array(
                    'login'    => $user->login,
                    'password' => $hash['password'],
                ));
            }
            catch(Exception $e)
            {
                user()->setFlash(FlashConst::MESSAGE_ERROR, $e->getMessage());
            }
        }

        $this->redirect(array('index'));
    }
}

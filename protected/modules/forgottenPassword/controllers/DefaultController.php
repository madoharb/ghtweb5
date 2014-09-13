<?php

class DefaultController extends FrontendBaseController
{
    private $_cacheName = 'forgottenPassword';



	public function actionIndex()
	{
        if(!user()->isGuest)
        {
            // Если авторизирован
            $this->redirect(array('/cabinet/default/index'));
        }

        $model = new ForgottenPasswordForm();

        if(isset($_POST['ForgottenPasswordForm']))
        {
            $model->attributes = $_POST['ForgottenPasswordForm'];

            if($model->validate())
            {
                $cacheData = array(
                    'hash'  => md5(randomString(rand(10,30)) . userIp() . time()),
                    'login' => $model->login,
                    'ls_id' => $model->gs_list[$model->gs_id]['login_id'],
                    'email' => $model->email,
                );

                cache()->set($this->_cacheName . $cacheData['hash'], $cacheData, config('forgotten_password.cache_time') * 60);

                app()->notify->forgottenPasswordStep1($model->email, array(
                    'hash' => $cacheData['hash'],
                ));

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('main', 'На Email <b>:email</b> отправлены инструкции по восстановлению пароля.', array(':email' => $model->email)));
                $this->refresh();
            }
        }

        $this->render('//forgotten-password', array(
            'model' => $model,
        ));
	}

    public function actionStep2($hash)
    {
        if(($hashInfo = cache()->get($this->_cacheName . $hash)) !== FALSE)
        {
            cache()->delete($this->_cacheName . $hash);

            $user = db()->createCommand("SELECT COUNT(0) FROM `{{users}}` WHERE `email` = :email AND `login` = :login LIMIT 1")
                ->bindParam('email', $hashInfo['email'], PDO::PARAM_STR)
                ->bindParam('login', $hashInfo['login'], PDO::PARAM_STR)
                ->queryScalar();

            if($user)
            {
                $newPassword = Users::generatePassword(rand(Users::PASSWORD_MIN_LENGTH, Users::PASSWORD_MAX_LENGTH));

                // Обновляю пароль на сервере
                try
                {
                    $l2 = l2('ls', $hashInfo['ls_id'])->connect();
                    $encryptPassword = $l2->passwordEncrypt($newPassword);
                    $login = $hashInfo['login'];
                    $email = $hashInfo['email'];

                    $res = $l2->getDb()->createCommand("UPDATE {{accounts}} SET password = :password WHERE login = :login LIMIT 1")
                        ->bindParam('password', $encryptPassword, PDO::PARAM_STR)
                        ->bindParam('login', $login, PDO::PARAM_STR)
                        ->execute();

                    if($res)
                    {
                        $encryptPassword = Users::hashPassword($newPassword);

                        db()->createCommand("UPDATE {{users}} SET password = :password WHERE email = :email AND login = :login LIMIT 1")
                            ->bindParam('password', $encryptPassword, PDO::PARAM_STR)
                            ->bindParam('email', $email, PDO::PARAM_STR)
                            ->bindParam('login', $login, PDO::PARAM_STR)
                            ->execute();

                        app()->notify->forgottenPasswordStep2($email, array(
                            'password' => $newPassword,
                        ));

                        user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('main', 'На почту указанную при регистрации отправлен новый пароль.'));
                    }
                    else
                    {
                        user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Произошла ошибка! Попробуйте повторить позже.'));
                    }
                }
                catch(Exception $e)
                {
                    user()->setFlash(FlashConst::MESSAGE_ERROR, $e->getMessage());
                }
            }
            else
            {
                user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Аккаунт не найден.'));
            }
        }
        else
        {
            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Ключ для восстановления пароля не найден.'));
        }

        if(user()->hasFlash(FlashConst::MESSAGE_ERROR))
        {
            $this->redirect(array('index'));
        }

        $this->redirect(array('/login/default/index'));
    }
}
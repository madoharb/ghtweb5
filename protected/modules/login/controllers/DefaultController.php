<?php

class DefaultController extends FrontendBaseController
{
	public function actionIndex()
	{
        // Если уже авторизован
        if(!user()->isGuest)
        {
            $this->redirect(array('/cabinet/default/index'));
        }

        $cache = new CFileCache();
        $cache->init();

        $model         = new LoginForm();
        $cacheName     = 'countFailedAttempts' . userIp();
        $failedAttempt = (is_numeric($cache->get($cacheName)) ? $cache->get($cacheName) : 0);
        $blocked       = ($failedAttempt >= config('login.count_failed_attempts_for_blocked') ? TRUE : FALSE);

        if(!$model->gs_list)
        {
            throw new CHttpException(404, Yii::t('main', 'Авторизация невозможна из за отсутствия серверов.'));
        }

        if($blocked)
        {
            $min = Yii::t('main', '{n} минуту|{n} минуты|{n} минут|{n} минуты', config('login.failed_attempts_blocked_time'));
            throw new CHttpException(403, Yii::t('main', 'Вы заблокированы на :min.', array(':min' => $min)));
        }

        if(isset($_POST[CHtml::modelName($model)]) && $blocked === FALSE && $model->gs_list)
        {
            $model->setAttributes($_POST[CHtml::modelName($model)]);

            if($model->validate())
            {
                // Ищю аккаунт на сервере
                try
                {
                    $login = $model->login;
                    $lsId  = $model->ls_id;

                    $l2 = l2('ls', $lsId)->connect();

                    $command = $l2->getDb()->createCommand();

                    $command->where('login = :login AND password = :password', array(':login' => $login, ':password' => $l2->passwordEncrypt($model->password)));

                    $account = $l2->accounts($command)->queryRow();

                    // Ищю на сайте
                    $statusActivated = Users::STATUS_ACTIVATED;
                    $roleBanned      = Users::ROLE_BANNED;

                    $siteAccount = db()->createCommand("SELECT password FROM {{users}} WHERE login = :login AND ls_id = :ls_id AND activated = :activated AND role != :role LIMIT 1")
                        ->bindParam('login', $login, PDO::PARAM_STR)
                        ->bindParam('ls_id', $lsId, PDO::PARAM_INT)
                        ->bindParam('activated', $statusActivated, PDO::PARAM_INT)
                        ->bindParam('role', $roleBanned, PDO::PARAM_INT)
                        ->queryRow();

                    // Найден
                    if($account)
                    {
                        // Если не найден на сайте то создаю
                        if(!$siteAccount)
                        {
                            $userModel = new Users();

                            $userModel->login       = $login;
                            $userModel->email       = NULL;
                            $userModel->password    = $model->password;
                            $userModel->activated   = Users::STATUS_ACTIVATED;
                            $userModel->role        = Users::ROLE_DEFAULT;
                            $userModel->ls_id       = $lsId;

                            $userModel->save(FALSE);
                        }
                    }
                    // Не найден на сервере но есть на сайте
                    else
                    {
                        // Если пароли совпали значит создаю аккаунт на сервере
                        if($siteAccount && Users::validatePassword($model->password, $siteAccount['password']))
                        {
                            // Создаю на сервере
                            $l2->insertAccount($model->login, $model->password);
                        }
                    }

                    if($model->login())
                    {
                        $cache->delete($cacheName);
                        $this->redirect(array('/cabinet/default/index'));
                    }

                    $cache->delete($cacheName);
                    $cache->set($cacheName, ++$failedAttempt, config('login.failed_attempts_blocked_time') * 60);

                    if($failedAttempt >= config('login.count_failed_attempts_for_blocked'))
                    {
                        $this->refresh();
                    }
                }
                catch(Exception $e)
                {
                    $model->addError('login', Yii::t('main', 'Произошла ошибка! Попробуйте повторить позже.'));
                    Yii::log("Ошибка авторизации\nMessage: " . $e->getMessage() . "\n", CLogger::LEVEL_ERROR, 'login');
                }
            }
        }

        if($failedAttempt >= config('login.count_failed_attempts_for_blocked'))
        {
            $this->refresh();
        }


        $this->render('//login', array(
            'model'         => $model,
            'formBlocked'   => $blocked,
            'failedAttempt' => $failedAttempt,
        ));
	}
}
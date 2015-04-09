<?php

class ChangePasswordController extends CabinetBaseController
{
    public function actionIndex()
    {
        $model = new ChangePasswordForm();

        if(isset($_POST['ChangePasswordForm']))
        {
            $model->attributes = $_POST['ChangePasswordForm'];

            if($model->validate())
            {
                // Меняю пароль от аккаунта
                try
                {
                    $l2 = l2('ls', user()->getLsId())->connect();

                    $newPassword = $l2->passwordEncrypt($model->new_password);
                    $login       = user()->getLogin();

                    $res = $l2->getDb()->createCommand("UPDATE {{accounts}} SET password = :password WHERE login = :login LIMIT 1")
                        ->bindParam('password', $newPassword, PDO::PARAM_STR)
                        ->bindParam('login', $login, PDO::PARAM_STR)
                        ->execute();

                    if($res !== FALSE)
                    {
                        $newPassword = Users::hashPassword($model->new_password);
                        $userId      = user()->getId();

                        db()->createCommand("UPDATE {{users}} SET password = :password WHERE user_id = :user_id LIMIT 1")
                            ->bindParam('password', $newPassword, PDO::PARAM_STR)
                            ->bindParam('user_id', $userId, PDO::PARAM_INT)
                            ->execute();

                        user()->setState('password', $newPassword);

                        if(user()->get('email'))
                        {
                            notify()->changePassword(user()->get('email'), array(
                                'password' => $model->new_password,
                            ));
                        }

                        // Логирую действие юзера
                        if(app()->params['user_actions_log'])
                        {
                            $log = new UserActionsLog();

                            $log->user_id = user()->getId();
                            $log->action_id = UserActionsLog::ACTION_CHANGE_PASSWORD;

                            $log->save(FALSE);
                        }

                        user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('main', 'Пароль успешно изменен.'));
                    }
                }
                catch(Exception $e)
                {
                    user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Произошла ошибка! Попробуйте повторить позже.'));
                    Yii::log("Не удалось сменить пароль от аккаунта\nOld password: " . $model->old_password . "\nNew password: " . $model->new_password . "\nError: " .  $e->getMessage() . "\n", CLogger::LEVEL_ERROR, 'cabinet_change_password');
                }

                $this->refresh();
            }
        }

        $this->render('//cabinet/change-password', array(
            'model' => $model,
        ));
    }
}

<?php 
 
class LoginForm extends Users
{
    public function rules()
    {
        return array(
            array('login, password', 'filter', 'filter' => 'trim'),
            array('login, password', 'required'),
            array('login', 'length', 'max' => Users::LOGIN_MAX_LENGTH, 'min' => Users::LOGIN_MIN_LENGTH),
            array('password', 'length', 'max' => Users::PASSWORD_MAX_LENGTH, 'min' => Users::PASSWORD_MIN_LENGTH),
        );
    }

    public function attributeLabels()
    {
        return array(
            'login'    => Users::model()->getAttributeLabel('login'),
            'password' => Users::model()->getAttributeLabel('password'),
        );
    }

    public function login()
    {
        $login    = parent::getLogin();
        $password = parent::getPassword();
        $identity = new AdminIdentity($login, $password);
        $identity->authenticate();

        switch($identity->errorCode)
        {
            case AdminIdentity::ERROR_USERNAME_INVALID:
            case AdminIdentity::ERROR_PASSWORD_INVALID:
                $this->addError('login', Yii::t('backend', 'Аккаунт не найден.'));
                break;
            case AdminIdentity::ERROR_STATUS_BANNED:
                $this->addError('login', Yii::t('backend', 'Аккаунт забанен.'));
                break;
            case AdminIdentity::ERROR_STATUS_INACTIVE:
                $this->addError('login', Yii::t('backend', 'Аккаунт не активирован.'));
                break;
            case AdminIdentity::ERROR_STATUS_IP_NO_ACCESS:
                $this->addError('login', Yii::t('backend', 'Доступ к аккаунту для вашего IP запрещён.'));
                break;
            default:
                $duration = 3600 * 24 * 7; // 7 days
                admin()->login($identity, $duration);
                return TRUE;
        }

        return FALSE;
    }
}
 
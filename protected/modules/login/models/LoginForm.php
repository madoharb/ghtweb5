<?php

/**
 * Модель формы авторизации
 *
 * Class LoginForm
 */
class LoginForm extends Users
{
    /**
     * Список серверов
     * @var Gs[]
     */
    public $gs_list;

    /**
     * Выбранный сервер
     * @var int
     */
    public $gs_id;

    /**
     * ID логина от выбранного сервера
     * @var
     */
    public $ls_id;

    /**
     * Код с картинки
     * @var string
     */
    public $verifyCode;



    public function rules()
    {
        $rules = array(
            array('gs_id,login,password', 'filter', 'filter' => 'trim'),
            array('gs_id,login,password', 'required'),
            array('login', 'length', 'min' => Users::LOGIN_MIN_LENGTH, 'max' => Users::LOGIN_MAX_LENGTH),
            array('password', 'length', 'min' => Users::PASSWORD_MIN_LENGTH, 'max' => Users::PASSWORD_MAX_LENGTH),
            array('gs_id', 'gsIsExists'),
        );

        // Captcha
        $captcha = config('login.captcha.allow') && CCaptcha::checkRequirements();

        if($captcha)
        {
            $rules[] = array('verifyCode', 'filter', 'filter' => 'trim');
            $rules[] = array('verifyCode', 'required');
            $rules[] = array('verifyCode', 'validators.CaptchaValidator');
        }

        return $rules;
    }

    protected function afterConstruct()
    {
        $this->gs_list = Gs::model()->getOpenServers();

        if(count($this->gs_list) == 1)
        {
            $this->gs_id = key($this->gs_list);
        }

        parent::afterConstruct();
    }

    protected function afterValidate()
    {
        $this->ls_id = $this->gs_list[$this->gs_id]['login_id'];

        parent::afterValidate();
    }

    /**
     * Проверка сервера
     *
     * @param string $attribute
     * @param array $params
     */
    public function gsIsExists($attribute, array $params)
    {
        if(!isset($this->gs_list[$this->gs_id]))
        {
            $this->addError($attribute, Yii::t('main', 'Выберите сервер.'));
        }
    }

    public function attributeLabels()
    {
        return array(
            'gs_id'      => Yii::t('main', 'Сервер'),
            'login'      => Users::model()->getAttributeLabel('login'),
            'password'   => Users::model()->getAttributeLabel('password'),
            'verifyCode' => Yii::t('main', 'Код с картинки'),
        );
    }

    public function login()
    {
        $identity = new UserIdentity($this->login, $this->password, $this->ls_id, $this->gs_id);
        $identity->authenticate();

        switch($identity->errorCode)
        {
            case UserIdentity::ERROR_USERNAME_INVALID:
            case UserIdentity::ERROR_PASSWORD_INVALID:
            {
                $this->addError('status', Yii::t('main', 'Неправильный Логин или Пароль.'));
                break;
            }
            case UserIdentity::ERROR_STATUS_INACTIVE:
            {
                $this->addError('status', Yii::t('main', 'Аккаунт не активирован.'));
                break;
            }
            case UserIdentity::ERROR_STATUS_BANNED:
            {
                $this->addError('status', Yii::t('main', 'Аккаунт заблокирован.'));
                break;
            }
            case UserIdentity::ERROR_STATUS_IP_NO_ACCESS:
            {
                $this->addError('status', Yii::t('main', 'С Вашего IP нельзя зайти на аккаунт.'));
                break;
            }
            case UserIdentity::ERROR_NONE:
            {
                $identity->setState('gs_id', $this->gs_id);

                $duration = 3600 * 24 * 7; // 7 days
                user()->login($identity, $duration);
                return TRUE;
            }
        }

        return FALSE;
    }

    /**
     * Логин
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * ID логин сервера
     *
     * @return int
     */
    public function getLsId()
    {
        return $this->gs_list[$this->gs_id]['login_id'];
    }

    /**
     * @return Gs[]
     */
    public function getGsList()
    {
        return $this->gs_list;
    }

    /**
     * @return int
     */
    public function getGsId()
    {
        return $this->gs_id;
    }
}

<?php 
 
class LoginForm extends CFormModel
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
     * Логин
     * @var string
     */
    public $login;

    /**
     * Пароль
     * @var string
     */
    public $password;

    /**
     * Код с картинки
     * @var string
     */
    public $verifyCode;



    public function rules()
    {
        return array(
            array('gs_id,login,password,verifyCode', 'filter', 'filter' => 'trim'),
            array('gs_id,login,password', 'required'),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements() || config('login.captcha.allow') == 0, 'message' => Yii::t('main', 'Код с картинки введен не верно.')),
            array('gs_id', 'gsIsExists'),
        );
    }

    protected function afterConstruct()
    {
        $dependency = new CDbCacheDependency("SELECT COUNT(0), MAX(UNIX_TIMESTAMP(updated_at)) FROM {{gs}} WHERE status = :status");
        $dependency->params = array('status' => ActiveRecord::STATUS_ON);
        $dependency->reuseDependentData = TRUE;

        $res = Gs::model()->cache(3600 * 24, $dependency)->opened()->findAll();

        foreach($res as $gs)
        {
            $this->gs_list[$gs->getPrimaryKey()] = $gs;
        }

        unset($res, $gs);

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
            'login'      => Yii::t('main', 'Логин'),
            'password'   => Yii::t('main', 'Пароль'),
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
     * ID логин сервера
     *
     * @return int
     */
    public function getLsId()
    {
        return (int) $this->ls_id;
    }
}

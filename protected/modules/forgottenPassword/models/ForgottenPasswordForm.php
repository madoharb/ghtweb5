<?php

/**
 * Class ForgottenPasswordForm
 *
 * @property array $gs_list
 * @property int $gs_id
 * @property string $login
 * @property string $email
 * @property string $verifyCode
 */
class ForgottenPasswordForm extends CFormModel
{
    public $gs_list;
    public $gs_id;
    public $login;
    public $email;
    public $verifyCode;



    public function rules()
    {
        return array(
            array('gs_id,login,email,verifyCode', 'filter', 'filter' => 'trim'),
            array('gs_id,login,email', 'required'),
            array('login', 'length', 'min' => Users::LOGIN_MIN_LENGTH, 'max' => Users::LOGIN_MAX_LENGTH),
            array('email', 'email', 'message' => Yii::t('main', 'Введите корректный Email адрес.')),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements() || config('forgotten_password.captcha.allow') == 0, 'message' => Yii::t('main', 'Код с картинки введен не верно.')),
            array('login', 'loginIsExists'),
            array('gs_id', 'gsIsExists'),
        );
    }

    public function afterConstruct()
    {
        $dependency = new CDbCacheDependency("SELECT COUNT(0), MAX(UNIX_TIMESTAMP(updated_at)) FROM {{gs}} WHERE status = :status");
        $dependency->params = array('status' => ActiveRecord::STATUS_ON);
        $dependency->reuseDependentData = TRUE;

        $res = Gs::model()->cache(3600 * 24, $dependency)->opened()->findAll();

        if($res)
        {
            foreach($res as $gs)
            {
                $this->gs_list[$gs['id']] = $gs;
            }
        }

        unset($res);

        if(count($this->gs_list) == 1)
        {
            $this->gs_id = key($this->gs_list);
        }

        parent::afterConstruct();
    }

    /**
     * Проверка сервера
     *
     * @param $attributs
     * @param $params
     */
    public function gsIsExists($attributs, $params)
    {
        if(!isset($this->gs_list[$this->gs_id]))
        {
            $this->addError(__FUNCTION__, Yii::t('main', 'Выберите сервер.'));
        }
    }

    /**
     * Проверка Логина и Email
     *
     * @param $attribute
     * @param $params
     */
    public function loginIsExists($attribute, $params)
    {
        if(!$this->hasErrors())
        {
            $user = Users::model()->find('login = :login AND email = :email', array(':login' => $this->login, ':email' => $this->email));

            if($user === NULL)
            {
                $this->addError(__FUNCTION__, Yii::t('main', 'Аккаунт не найден'));
            }
            elseif($user->role == Users::ROLE_BANNED)
            {
                $this->addError(__FUNCTION__, Yii::t('main', 'Аккаунт заблокирован, восстановление пароля невозможно'));
            }
            elseif($user->activated == Users::STATUS_INACTIVATED)
            {
                $this->addError(__FUNCTION__, Yii::t('main', 'Аккаунт не активирован, восстановление пароля невозможно'));
            }
            else
            {
                // Ищю аккаунт на сервере
                try
                {
                    $l2 = l2('ls', $this->gs_list[$this->gs_id]['login_id'])->connect();

                    $res = $l2->getDb()->createCommand("SELECT * FROM `{{accounts}}` WHERE `login` = :login LIMIT 1")
                        ->bindParam('login', $this->login, PDO::PARAM_STR)
                        ->queryScalar();

                    if(!$res)
                    {
                        $this->addError(__FUNCTION__, Yii::t('main', 'Аккаунт не найден'));
                    }
                }
                catch(Exception $e)
                {
                    $this->addError(__FUNCTION__, $e->getMessage());
                }
            }
        }
    }

    public function attributeLabels()
    {
        return array(
            'gs_id'      => Yii::t('main', 'Сервер'),
            'login'      => Yii::t('main', 'Логин'),
            'email'      => Yii::t('main', 'Email'),
            'verifyCode' => Yii::t('main', 'Код с картинки'),
        );
    }
}
 
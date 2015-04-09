<?php

/**
 * Class Step4Form
 *
 * @property string $login
 * @property string $password
 * @property string $email
 */
class Step4Form extends CFormModel
{
    /**
     * Название сервера
     * @var string
     */
    public $login;

    /**
     * Пароль
     * @var string
     */
    public $password;

    /**
     * Email
     * @var string
     */
    public $email;



    public function rules()
    {
        return array(
            array('login, password, email', 'filter', 'filter' => 'trim'),
            array('login, password, email', 'required'),
            array('login', 'length', 'min' => Users::LOGIN_MIN_LENGTH, 'max' => Users::LOGIN_MAX_LENGTH),
            array('password', 'length', 'min' => Users::PASSWORD_MIN_LENGTH, 'max' => Users::PASSWORD_MAX_LENGTH),
            array('login', 'loginUnique'),
            array('email', 'email'),
        );
    }

    public function loginUnique($attr)
    {
        if(!$this->hasErrors($attr))
        {
            $res = db()->createCommand("SELECT COUNT(0) FROM {{users}} WHERE login = :login")
                ->queryScalar(array(
                    'login' => $this->login
                ));

            if($res)
            {
                $this->addError($attr, Yii::t('install', 'Логин уже занят.'));
            }
        }
    }

    public function attributeLabels()
    {
        return array(
            'login'     => Yii::t('install', 'Логин'),
            'password'  => Yii::t('install', 'Пароль'),
            'email'     => Yii::t('install', 'Email'),
        );
    }
}
 
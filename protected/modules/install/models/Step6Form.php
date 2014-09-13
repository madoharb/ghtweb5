<?php

/**
 * Class Step6Form
 *
 * @property string $login
 * @property string $password
 * @property string $email
 */
class Step6Form extends CFormModel
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
            array('email', 'email'),
        );
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
 
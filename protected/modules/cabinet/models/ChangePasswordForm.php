<?php 

class ChangePasswordForm extends CFormModel
{
    public $original_password;
    public $old_password;
    public $new_password;



    public function rules()
    {
        return array(
            array('old_password,new_password', 'filter', 'filter' => 'trim'),
            array('old_password,new_password', 'required'),
            array('old_password', 'length', 'min' => Users::PASSWORD_MIN_LENGTH),
            array('new_password', 'length', 'min' => Users::PASSWORD_MIN_LENGTH),
            array('old_password', 'isValidPassword'),
        );
    }

    public function isValidPassword()
    {
        if(!$this->hasErrors())
        {
            if(!Users::validatePassword($this->old_password, user()->get('password')))
            {
                $this->addError('old_password', Yii::t('main', 'Введенный пароль не совпадает с текущем паролем.'));
            }
        }
    }

    public function attributeLabels()
    {
        return array(
            'old_password' => Yii::t('main', 'Старый пароль'),
            'new_password' => Yii::t('main', 'Новый пароль'),
        );
    }
}
 
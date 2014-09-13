<?php 
 
class EditUserForm extends CFormModel
{
    public $role;
    public $activated;
    public $vote_balance;
    public $balance;
    public $phone;
    public $protected_ip;



    public function rules()
    {
        Yii::import('ext.MyValidators.ValidIp');

        return array(
            array('role, activated, vote_balance, balance', 'required'),
            array('role', 'in', 'range' => array_keys($this->getRoleList()), 'message' => Yii::t('backend', 'Выберите роль')),
            array('activated', 'in', 'range' => array_keys($this->getActivatedStatusList()), 'message' => Yii::t('backend', 'Выберите статус аккаунта')),
            array('vote_balance, balance', 'numerical', 'message' => Yii::t('backend', 'Введите число')),
            array('protected_ip', 'ValidIp'),
            array('phone', 'length', 'max' => 54),
        );
    }

    public function attributeLabels()
    {
        $userModel        = new Users();
        $userProfileModel = new UserProfiles();

        return array(
            'role'          => $userModel->getAttributeLabel('role'),
            'activated'     => $userModel->getAttributeLabel('activated'),
            'vote_balance'  => $userProfileModel->getAttributeLabel('vote_balance'),
            'balance'       => $userProfileModel->getAttributeLabel('balance'),
            'phone'         => $userProfileModel->getAttributeLabel('phone'),
            'protected_ip'  => $userProfileModel->getAttributeLabel('protected_ip'),
        );
    }

    public function getRoleList()
    {
        return Users::model()->getRoleList();
    }

    public function getActivatedStatusList()
    {
        return Users::model()->getActivatedStatusList();
    }
}
 
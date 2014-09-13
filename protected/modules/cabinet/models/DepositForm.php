<?php 
 
class DepositForm extends CFormModel
{
    public $sum = 1;



    public function rules()
    {
        return array(
            array('sum', 'filter', 'filter' => 'trim'),
            array('sum', 'required'),
            array('sum', 'numerical', 'integerOnly' => TRUE, 'min' => 1, 'message' => Yii::t('main', 'Введите число'), 'tooSmall' => Yii::t('main', 'Кол-во должно быть больше нуля')),
        );
    }

    /**
     * Валидация суммы
     *
     * @param $attributes
     * @param $params
     *
     * @return bool
     */
    public function validCount($attributes, $params)
    {
        if(!is_numeric($this->sum))
        {
            $this->addError(__FUNCTION__, Yii::t('main', 'Сумма должна быть числом.'));
            return FALSE;
        }
    }

    public function attributeLabels()
    {
        return array(
            'sum' => Yii::t('main', 'Кол-во :currency_name', array(':currency_name' => app()->controller->gs->currency_name)),
        );
    }
}
 
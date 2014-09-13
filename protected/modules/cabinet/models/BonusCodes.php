<?php

/**
 * This is the model class for table "{{bonus_codes}}".
 *
 * The followings are the available columns in table '{{bonus_codes}}':
 * @property string $id
 * @property string $bonus_id
 * @property string $code
 * @property string $limit
 * @property integer $status
 */
class BonusCodes extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{bonus_codes}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('code, limit, status, bonus_id', 'filter', 'filter' => 'trim'),
			array('code, limit, status, bonus_id', 'required', 'on' => 'code_form'),
			array('code', 'required', 'on' => 'activated_code'),

			array('code', 'length', 'max' => 128),
			array('status', 'in', 'range' => array_keys($this->getStatusList()), 'on' => 'code_form'),
			array('bonus_id', 'bonusIsExists', 'on' => 'code_form'),

			array('id, bonus_id, code, limit', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'bonusInfo' => array(self::HAS_ONE, 'Bonuses', array('id' => 'bonus_id')),
            'bonusLog'  => array(self::HAS_MANY, 'BonusCodesActivatedLogs', array('code_id' => 'id')),
		);
	}

    public function bonusIsExists()
    {
        if(!$this->hasErrors())
        {
            $model = Bonuses::model()->findByPk($this->bonus_id);

            if($model === NULL)
            {
                $this->addError(__FUNCTION__, Yii::t('backend', 'Выберите бонус.'));
            }
        }
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'       => 'ID',
			'bonus_id' => Yii::t('main', 'Бонус'),
			'code'     => Yii::t('main', 'Бонус код'),
			'limit'    => Yii::t('main', 'Лимит'),
			'status'   => Yii::t('main', 'Статус'),
		);
	}


    public function getStatusList()
    {
        $data = parent::getStatusList();
        unset($data[ActiveRecord::STATUS_DELETED]);

        return $data;
    }
}

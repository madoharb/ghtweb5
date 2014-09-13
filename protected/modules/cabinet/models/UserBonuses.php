<?php

/**
 * This is the model class for table "{{user_bonuses}}".
 *
 * The followings are the available columns in table '{{user_bonuses}}':
 * @property string $id
 * @property string $bonus_id
 * @property string $user_id
 * @property integer $status
 * @property string $created_at
 *
 * @property Bonuses $bonusesModel
 * @property Bonuses $bonusInfo
 */
class UserBonuses extends ActiveRecord
{
    // Состояния
    const STATE_ACTIVE     = 1;
    const STATE_NOT_ACTIVE = 0;


    /**
     * При добавлении тут хранится инфа о добавляемом бонусе
     * @var Bonuses
     */
    public $bonusesModel;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{user_bonuses}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('bonus_id, user_id', 'required'),
			array('status', 'numerical', 'integerOnly' => TRUE),
			array('bonus_id, user_id', 'length', 'max' => 10),

            array('bonus_id', 'bonusIsExists', 'on' => ActiveRecord::SCENARIO_CREATE),

			array('id, bonus_id, user_id, status, created_at', 'safe', 'on' => 'search'),
		);
	}

    public function bonusIsExists($attribute)
    {
        $this->bonusesModel = Bonuses::model()->findByPk($this->bonus_id);

        if(!$this->bonusesModel)
        {
            $this->addError($attribute, Yii::t('backend', 'Выберите бонус'));
        }
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'bonusInfo' => array(self::HAS_ONE, 'Bonuses', array('id' => 'bonus_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'         => 'ID',
			'bonus_id'   => Yii::t('main', 'Бонус'),
			'user_id'    => Yii::t('main', 'Юзер'),
			'status'     => Yii::t('main', 'Состояние'),
			'created_at' => Yii::t('main', 'Дата создания'),
		);
	}

    /**
     * Сосотояния бонуса
     *
     * @return array
     */
    public function getStateList()
    {
        return array(
            self::STATE_ACTIVE     => Yii::t('main', 'Активирован'),
            self::STATE_NOT_ACTIVE => Yii::t('main', 'Не активирован'),
        );
    }

    public function getState()
    {
        $data = $this->getStateList();
        return isset($data[$this->status]) ? $data[$this->status] :t('backend', '*unknown*');
    }
}

<?php

/**
 * This is the model class for table "{{bonuses_items}}".
 *
 * The followings are the available columns in table '{{bonuses_items}}':
 * @property string $id
 * @property string $item_id
 * @property string $count
 * @property string $enchant
 * @property string $bonus_id
 * @property integer $status
 *
 * @property Bonuses $bonus
 * @property AllItems $itemInfo
 */
class BonusesItems extends ActiveRecord
{
    // Create/Edit item
    public $item_name;


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{bonuses_items}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('item_name, item_id, count, enchant, bonus_id, status', 'filter', 'filter' => 'trim'),
			array('item_id, count, enchant, bonus_id, status', 'required'),
			array('item_id, count, enchant, bonus_id, status', 'numerical', 'integerOnly' => TRUE),

			array('id, item_id, count, enchant, bonus_id, status', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'bonus' => array(self::HAS_ONE, 'Bonuses', 'bonus_id'),
            'itemInfo' => array(self::HAS_ONE, 'AllItems', array('item_id' => 'item_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'        => 'ID',
			'item_id'   => Yii::t('backend', 'ID предмета'),
			'count'     => Yii::t('backend', 'Кол-во'),
			'enchant'   => Yii::t('backend', 'Заточка'),
			'bonus_id'  => Yii::t('backend', 'ID бонуса'),
			'status'    => Yii::t('backend', 'Статус'),
			'item_name' => Yii::t('backend', 'Название'),
		);
	}

    public function getStatusList()
    {
        $data = parent::getStatusList();
        unset($data[ActiveRecord::STATUS_DELETED]);

        return $data;
    }
}

<?php

/**
 * This is the model class for table "{{tickets_categories}}".
 *
 * The followings are the available columns in table '{{tickets_categories}}':
 * @property string $id
 * @property string $title
 * @property integer $status
 * @property integer $sort
 */
class TicketsCategories extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tickets_categories}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('title, sort, status', 'filter', 'filter' => 'trim'),
			array('title', 'filter', 'filter' => 'strip_tags'),
			array('title, sort, status', 'required'),

			array('status, sort', 'numerical', 'integerOnly' => TRUE),

            array('status', 'in', 'range' => array_keys($this->getStatusList())),

			array('title', 'length', 'max' => 255),

			array('id, title, status, sort', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'tickets' => array(self::BELONGS_TO, 'Tickets', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'    => 'ID',
			'title' => Yii::t('main', 'Название'),
			'status' => Yii::t('main', 'Статус'),
			'sort'  => Yii::t('main', 'Сортировка'),
		);
	}

    public function getStatusList()
    {
        $data = parent::getStatusList();
        unset($data[ActiveRecord::STATUS_DELETED]);

        return $data;
    }
}

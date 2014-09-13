<?php

/**
 * This is the model class for table "{{bonuses}}".
 *
 * The followings are the available columns in table '{{bonuses}}':
 * @property string $id
 * @property string $title
 * @property string $date_end
 * @property integer $status
 *
 * @property BonusesItems[] $items
 * @property int $itemCount
 */
class Bonuses extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{bonuses}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('title, status, date_end', 'filter', 'filter' => 'trim'),
			array('title, status', 'required'),

			array('date_end', 'date', 'allowEmpty' => TRUE, 'format' => 'yyyy-mm-dd HH:mm:ss'),
			array('date_end', 'default', 'value' => NULL),

			array('status', 'in', 'range' => array_keys($this->getStatusList())),

            array('title', 'length', 'max' => 255),

			array('id, title, status', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'items' => array(self::HAS_MANY, 'BonusesItems', array('bonus_id' => 'id')),
            'itemCount' => array(self::STAT, 'BonusesItems', 'bonus_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'       => 'ID',
			'title'    => Yii::t('backend', 'Название'),
			'date_end' => Yii::t('backend', 'Дата окончания действия бонуса'),
			'status'   => Yii::t('backend', 'Статус'),
		);
	}

    public function getStatusList()
    {
        $data = parent::getStatusList();
        unset($data[ActiveRecord::STATUS_DELETED]);

        return $data;
    }

    public function afterDelete()
    {
        Yii::import('application.modules.cabinet.models.BonusesItems');

        $model = BonusesItems::model()->findAll('bonus_id = :bonus_id', array(':bonus_id' => $this->id));

        if($model)
        {
            foreach($model as $item)
            {
                $item->delete();
            }
        }
    }

    /**
     * Возвращает дату окончания бонуса
     *
     * @return string
     */
    public function getDateEnd()
    {
        if($this->date_end)
        {
            return date('Y-m-d H:i', strtotime($this->date_end));
        }

        return '';
    }
}

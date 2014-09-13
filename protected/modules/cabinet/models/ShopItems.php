<?php

/**
 * This is the model class for table "{{shop_items}}".
 *
 * The followings are the available columns in table '{{shop_items}}':
 * @property string $id
 * @property integer $pack_id
 * @property integer $item_id
 * @property string $description
 * @property double $cost
 * @property float $discount
 * @property string $currency_type
 * @property integer $count
 * @property integer $enchant
 * @property integer $status
 * @property integer $sort
 * @property string $item_name
 *
 * @property ShopCategory $category
 * @property AllItems $itemInfo
 */
class ShopItems extends ActiveRecord
{
    const CURRENCY_TYPE_DONAT = 1;
    const CURRENCY_TYPE_VOTE  = 0;

    // При добавлении нужное поле
    public $item_name;




	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{shop_items}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('pack_id, description, cost, discount, count, enchant, status, sort, item_id, item_name', 'filter', 'filter' => 'trim'),
			array('pack_id, cost, discount, count, enchant, status, sort, item_id', 'required'),

            array('status', 'in', 'range' => array_keys($this->getStatusList())),
            //array('currency_type', 'in', 'range' => array_keys($this->getCurrencyTypeList())),

            array('pack_id, item_id, count, enchant, status, sort', 'numerical', 'integerOnly' => TRUE),

			array('cost, discount', 'numerical'),

			//array('currency_type', 'length', 'max' => 54),

            array('item_id', 'itemIdIsExists'),

			array('id, item_id, cost, count, enchant', 'safe', 'on' => 'search'),
		);
	}

    public function itemIdIsExists()
    {
        if(!$this->hasErrors())
        {
            if($this->getScenario() == 'update' && $this->item_id == $_POST['old_item_id'])
            {
                return;
            }

            $packId = $this->pack_id;
            $itemId = $this->item_id;
            $count  = $this->count;

            $item = db()->createCommand("SELECT COUNT(0) FROM " . $this->tableName() . " WHERE pack_id = :pack_id AND item_id = :item_id AND count = :count LIMIT 1")
                ->bindParam(':pack_id', $packId, PDO::PARAM_INT)
                ->bindParam(':item_id', $itemId, PDO::PARAM_INT)
                ->bindParam(':count', $count, PDO::PARAM_INT)
                ->queryScalar();

            if($item)
            {
                $this->addError(__FUNCTION__, Yii::t('backend', 'Такой предмет уже есть в этом наборе.'));
            }
        }
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'category' => array(self::HAS_ONE, 'ShopCategory', 'category_id',
                'order' => 'sort',
            ),
            'itemInfo' => array(self::HAS_ONE, 'AllItems', array('item_id' => 'item_id'))
		);
	}

    /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'            => 'ID',
			'category_id'   => Yii::t('backend', 'Категория'),
			'item_name'     => Yii::t('backend', 'Название предмета'),
			'item_id'       => Yii::t('backend', 'ID предмета'),
			'description'   => Yii::t('backend', 'Описание'),
			'cost'          => Yii::t('backend', 'Стоимость'),
			'discount'      => Yii::t('backend', 'Скидка'),
			'count'         => Yii::t('backend', 'Кол-во'),
			'enchant'       => Yii::t('backend', 'Заточка'),
			'status'        => Yii::t('backend', 'Статус'),
			'sort'          => Yii::t('backend', 'Сортировка'),
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id', $this->id, TRUE);
		$criteria->compare('pack_id', $this->pack_id);
		$criteria->compare('item_id', $this->item_id, TRUE);
		$criteria->compare('description', $this->description, TRUE);
		$criteria->compare('cost', $this->cost);
		$criteria->compare('discount', $this->discount);
		$criteria->compare('currency_type', $this->currency_type);
		$criteria->compare('count', $this->count, TRUE);
		$criteria->compare('enchant', $this->enchant, TRUE);
		$criteria->compare('status', $this->status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

    /**
     * Расчёт стоймости со скидкой
     *
     * @param $cost
     * @param $discount
     *
     * @return float|int
     */
    public static function costAtDiscount($cost, $discount)
    {
        if($discount == 0)
        {
            return $cost;
        }

        return $cost - ($cost / 100) * $discount;
    }

    public function getCurrencyTypeList()
    {
        return array(
            self::CURRENCY_TYPE_DONAT => Yii::t('main', 'За донат'),
            self::CURRENCY_TYPE_VOTE  => Yii::t('main', 'За голоса'),
        );
    }

    public function getCurrencyType()
    {
        $data = $this->getCurrencyTypeList();
        return isset($data[$this->currency_type]) ? $data[$this->currency_type] : Yii::t('backend', '*unknown*');
    }

    /**
     * Стоимость
     *
     * @return float
     */
    public function getCost()
    {
        return $this->cost;
    }
}

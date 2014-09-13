<?php

/**
 * This is the model class for table "{{shop_categories}}".
 *
 * The followings are the available columns in table '{{shop_categories}}':
 * @property string $id
 * @property string $name
 * @property string $link
 * @property integer $sort
 * @property integer $status
 * @property integer $gs_id
 *
 * @property ShopItemsPacks[] $packs
 * @property ShopItemsPacks $pack
 * @property int $countPacks
 */
class ShopCategories extends ActiveRecord
{
    const LINK_PATTERN = 'a-zA-Z0-9-';



	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{shop_categories}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, link, sort, status', 'filter', 'filter' => 'trim'),
			array('name, link, sort, status', 'required'),

			array('sort, status, gs_id', 'numerical', 'integerOnly' => TRUE),
			array('name, link', 'length', 'max' => 255),
			array('link', 'match', 'pattern' => '#^([' . self::LINK_PATTERN . ']+)$#', 'message' => Yii::t('main', 'Разрешены только следующие символы: :chars', array(':chars' => self::LINK_PATTERN))),

            array('status', 'in', 'range' => array_keys($this->getStatusList())),

            array('name', 'nameUnique'),
            array('link', 'linkUnique'),

			array('id, name, link, sort, status, gs_id', 'safe', 'on' => 'search'),
		);
	}

    /**
     * Проверка имени на уникальность
     *
     * @param string $attribute
     * @param array $params
     */
    public function nameUnique($attribute, $params = array())
    {
        if(!$this->hasErrors())
        {
            $params['criteria'] = array(
                'condition' => 'name = :name AND gs_id = :gs_id',
                'params' => array(
                    'name' => $this->name,
                    'gs_id' => $this->gs_id,
                )
            );

            $validator = CValidator::createValidator('unique', $this, $attribute, $params);
            $validator->validate($this, array($attribute));
        }
    }

    /**
     * Проверка ссылки на уникальность
     *
     * @param string $attribute
     * @param array $params
     */
    public function linkUnique($attribute, $params = array())
    {
        if(!$this->hasErrors())
        {
            $params['criteria'] = array(
                'condition' => 'link = :link AND gs_id = :gs_id',
                'params' => array(
                    'link' => $this->link,
                    'gs_id' => $this->gs_id,
                )
            );

            $validator = CValidator::createValidator('unique', $this, $attribute, $params);
            $validator->validate($this, array($attribute));
        }
    }

    public function nameIsExists()
    {
        if(!$this->hasErrors())
        {
            if($this->getScenario() == 'update' && $this->name == $_POST['old_name'])
            {
                return;
            }

            $gsId = $this->gs_id;
            $name = $this->name;

            $model = db()->createCommand("SELECT COUNT(0) FROM `" . $this->tableName() . "` WHERE `gs_id` = :gs_id AND `name` = :name LIMIT 1")
                ->bindParam(':gs_id', $gsId, PDO::PARAM_INT)
                ->bindParam(':name', $name, PDO::PARAM_STR)
                ->queryScalar();

            if($model)
            {
                $this->addError(__FUNCTION__, Yii::t('backend', 'Название категории уже занято, впишите другое.'));
            }
        }
    }

    public function linkIsExists()
    {
        if(!$this->hasErrors())
        {
            if($this->getScenario() == 'update' && $this->link == $_POST['old_link'])
            {
                return;
            }

            $gsId = $this->gs_id;
            $link = $this->link;

            $model = db()->createCommand("SELECT COUNT(0) FROM `" . $this->tableName() . "` WHERE `gs_id` = :gs_id AND `link` = :link LIMIT 1")
                ->bindParam(':gs_id', $gsId, PDO::PARAM_INT)
                ->bindParam(':link', $link, PDO::PARAM_STR)
                ->queryScalar();

            if($model)
            {
                $this->addError(__FUNCTION__, Yii::t('backend', 'Ссылка на категорию уже занята, впишите другую.'));
            }
        }
    }

    public function afterDelete()
    {
        parent::afterDelete();

        Yii::import('application.modules.cabinet.models.ShopItemsPacks');
        Yii::import('application.modules.cabinet.models.ShopItems');

        $criteria = new CDbCriteria(array(
            'condition' => 'category_id = :category_id',
            'params' => array(
                ':category_id' => $this->getPrimaryKey()
            ),
            'with' => array('items'),
        ));

        $model = ShopItemsPacks::model()->findAll($criteria);

        foreach($model as $pack)
        {
            foreach($pack->items as $item)
            {
                $item->delete();
            }

            $pack->delete();
        }
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'packs' => array(self::HAS_MANY, 'ShopItemsPacks', 'category_id',
                //'order'     => 'packs.sort',
                //'condition' => 'packs.status = 1',
                //'joinType' => 'LEFT JOIN',
            ),
            'pack' => array(self::HAS_ONE, 'ShopItemsPacks', 'category_id'),
            'countPacks' => array(self::STAT, 'ShopItemsPacks', 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'     => 'ID',
			'name'   => Yii::t('backend', 'Название'),
			'link'   => Yii::t('backend', 'Ссылка'),
			'sort'   => Yii::t('backend', 'Сортировка'),
			'status' => Yii::t('backend', 'Статус'),
			'gs_id'  => Yii::t('backend', 'Сервер'),
		);
	}

	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('link',$this->link,true);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('status',$this->status);
		$criteria->compare('gs_id',$this->gs_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getStatusList()
    {
        $data = parent::getStatusList();
        unset($data[ActiveRecord::STATUS_DELETED]);

        return $data;
    }
}

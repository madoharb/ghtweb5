<?php

/**
 * This is the model class for table "{{shop_items_packs}}".
 *
 * The followings are the available columns in table '{{shop_items_packs}}':
 * @property string $id
 * @property string $title
 * @property string $description
 * @property integer $category_id
 * @property string $img
 * @property integer $sort
 * @property integer $status
 *
 * @property ShopItems[] $items
 * @property int $countItems
 * @property ImageUploadBehavior $fileUpload
 */
class ShopItemsPacks extends ActiveRecord
{
    // Путь где лежат картинки
    const PATH_TO_FOLDER_WITH_IMAGE = 'images/shop/packs';



	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{shop_items_packs}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('title, sort, status, description', 'filter', 'filter' => 'trim'),
			array('title, sort, status', 'required'),
			array('sort, status, category_id', 'numerical', 'integerOnly' => TRUE),
            array('status', 'in', 'range' => array_keys($this->getStatusList())),
			array('title', 'length', 'max' => 255),
            array('img', 'file', 'types' => 'jpg,jpeg,png', 'allowEmpty' => TRUE),
            //array('img', 'unsafe'),

			array('id, shop_item_id, sort, status', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'items' => array(self::HAS_MANY, 'ShopItems', 'pack_id'),
            'countItems' => array(self::STAT, 'ShopItems', 'pack_id'),
		);
	}

    public function behaviors()
    {
        return array(
            'fileUpload' => array(
                'class'             => 'application.components.behaviors.ImageUploadBehavior',
                'uploadPath'        => '/' . app()->params['uploadPath'] . '/' . self::PATH_TO_FOLDER_WITH_IMAGE . '/',
                'imageNameCallback' => array($this, 'generateFileName'),
                'imgParams'         => array(
                    array(
                        'width'  => 150,
                        'height' => 150,
                    ),
                ),
            ),
        );
    }

    public function afterDelete()
    {
        Yii::import('application.modules.cabinet.models.ShopItems');

        parent::afterDelete();

        // При удалении удаляю предметы из набора
        $items = ShopItems::model()->findAll('pack_id = :pack_id', array(':pack_id' => $this->id));

        foreach($items as $item)
        {
            $item->delete();
        }
    }

    /**
     * Генерация названия для картинки
     *
     * @return string
     */
    public function generateFileName()
    {
        return md5($this->title . time() . rand());
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'            => 'ID',
			'title'         => Yii::t('backend', 'Название'),
			'description'   => Yii::t('backend', 'Описание'),
			'category_id'   => Yii::t('backend', 'Категория'),
			'img'           => Yii::t('backend', 'Картинка'),
			'sort'          => Yii::t('backend', 'Сортировка'),
			'status'        => Yii::t('backend', 'Статус'),
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, TRUE);
		$criteria->compare('title', $this->title, TRUE);
		$criteria->compare('description', $this->description, TRUE);
		$criteria->compare('category_id', $this->category_id, TRUE);
		$criteria->compare('status', $this->status);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
}

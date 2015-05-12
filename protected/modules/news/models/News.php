<?php

/**
 * This is the model class for table "{{news}}".
 *
 * The followings are the available columns in table '{{news}}':
 * @property integer $id
 * @property integer $user_id
 * @property string $title
 * @property string $description
 * @property string $text
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property string $img
 *
 * The followings are the available model relations:
 * @property Users $author
 */
class News extends ActiveRecord
{
    const PATH_TO_FOLDER_WITH_IMAGE = 'images/news';


	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{news}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('title, description, text, seo_title, seo_description, seo_keywords, status, img', 'filter', 'filter' => 'trim'),
			array('title, description, text, status', 'required'),
			array('status', 'numerical', 'integerOnly' => TRUE),
			array('title, seo_title, seo_keywords, seo_description', 'length', 'max' => 255),
            array('description, text', 'length', 'min' => 15),
            array('title', 'length', 'min' => 4),

            array('img', 'file', 'types' => 'jpg,jpeg,png', 'allowEmpty' => TRUE),

            array('status', 'in', 'range' => array_keys(parent::getStatusList())),

			array('id, title, status', 'safe', 'on' => 'search'),
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
                        'quality' => 70,
                        'width'   => config('news.img.width'),
                        'height'  => config('news.img.height'),
                    ),
                ),
            ),
        );
    }

    public function generateFileName()
    {
        return md5(uniqid() . rand());
    }

    /**
     * Возвращает дату
     *
     * @return string
     */
    public function getDate()
    {
        return date(config('news.date_format'), strtotime($this->created_at));
    }

    /**
     * Обработка данных перед сохранением
     */
    protected function beforeSave()
    {
        if($this->isNewRecord)
        {
            if(is_numeric(user()->getId()))
            {
                $this->user_id = user()->getId();
            }
        }

        return parent::beforeSave();
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'author' => array(self::HAS_ONE, 'Users', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'              => 'ID',
			'title'           => Yii::t('main', 'Название'),
			'description'     => Yii::t('main', 'Короткое описание'),
			'text'            => Yii::t('main', 'Текст'),
            'seo_title'       => Yii::t('main', 'СЕО заголовок'),
            'seo_description' => Yii::t('main', 'СЕО описание'),
            'seo_keywords'    => Yii::t('main', 'СЕО ключевые слова'),
			'status'          => Yii::t('main', 'Статус'),
			'created_at'      => Yii::t('main', 'Дата создания'),
			'updated_at'      => Yii::t('main', 'Дата обновления'),
			'img'             => Yii::t('main', 'Картинка'),
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id, TRUE);
		$criteria->compare('title', $this->title, TRUE);
		$criteria->compare('status', $this->status, TRUE);

        $criteria->scopes = array('not_deleted');

        $criteria->with = array('author');

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 't.created_at DESC'
            ),
            'pagination' => array(
                'pageSize' => 20,
                'pageVar'  => 'page',
            ),
        ));
	}

    public function getStatusList()
    {
        $data = parent::getStatusList();
        unset($data[ActiveRecord::STATUS_DELETED]);

        return $data;
    }
}

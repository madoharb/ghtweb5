<?php

/**
 * This is the model class for table "{{tickets_answers}}".
 *
 * The followings are the available columns in table '{{tickets_answers}}':
 * @property string $id
 * @property string $ticket_id
 * @property string $text
 * @property string $user_id
 * @property string $created_at
 */
class TicketsAnswers extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tickets_answers}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('text', 'filter', 'filter' => 'trim'),
			array('text', 'filter', 'filter' => 'strip_tags'),
			array('text', 'required'),
            array('text', 'length', 'min' => 5),

			array('id, ticket_id, text, user_id, created_at', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'userInfo' => array(self::HAS_ONE, 'Users', array('user_id' => 'user_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'text' => Yii::t('main', 'Текст'),
		);
	}

    public function beforeSave()
    {
        if($this->isNewRecord)
        {
            $this->user_id = user()->getId();
        }

        return parent::beforeSave();
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('ticket_id',$this->ticket_id,true);
		$criteria->compare('text',$this->text,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('created_at',$this->created_at,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

    public function getDate()
    {
        return date('Y-m-d H:i', strtotime($this->created_at));
    }
}

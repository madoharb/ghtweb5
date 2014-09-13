<?php

/**
 * This is the model class for table "{{tickets}}".
 *
 * The followings are the available columns in table '{{tickets}}':
 * @property string $id
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $priority
 * @property string $date_incident
 * @property string $char_name
 * @property string $title
 * @property integer $status
 * @property integer $new_message_for_user
 * @property integer $new_message_for_admin
 * @property integer $gs_id
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property TicketsCategories $category
 * @property TicketsAnswers $answers
 * @property Users $user
 * @property Gs $gs
 */
class Tickets extends ActiveRecord
{
    // Приоритет высокий
    const PRIORITY_HIGH = 2;

    // Приоритет средний
    const PRIORITY_MID  = 1;

    // Приоритет низкий
    const PRIORITY_LOW  = 0;


    // Новые сообщения есть
    const STATUS_NEW_MESSAGE_ON  = 1;

    // Новых сообщений нет
    const STATUS_NEW_MESSAGE_OFF = 0;



    public function getPrioritiesList()
    {
        return array(
            self::PRIORITY_HIGH => Yii::t('main', 'Высокий'),
            self::PRIORITY_MID  => Yii::t('main', 'Средний'),
            self::PRIORITY_LOW  => Yii::t('main', 'Низкий'),
        );
    }

    public function getPriority()
    {
        $data = $this->getPrioritiesList();
        return isset($data[$this->priority]) ? $data[$this->priority] : Yii::t('main', '*unknown*');
    }

    public function getStatusList()
    {
        return array(
            ActiveRecord::STATUS_ON  => Yii::t('main', 'Открыт'),
            ActiveRecord::STATUS_OFF => Yii::t('main', 'Закрыт'),
        );
    }

    /**
     * Есть ли новые сообщения для админа
     *
     * @return string
     */
    public function isNewMessageForAdmin()
    {
        return $this->new_message_for_admin ? Yii::t('main', 'Есть') : Yii::t('main', 'Нет');
    }

    /**
     * Есть ли новые сообщения для юзера
     *
     * @return string
     */
    public function isNewMessageForUser()
    {
        return $this->new_message_for_user ? Yii::t('main', 'Есть') : Yii::t('main', 'Нет');
    }

    /**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{tickets}}';
	}

	public function rules()
	{
		return array(
			array('id, category_id, priority, title, new_message_for_admin, status, gs_id', 'filter', 'filter' => 'trim'),
			array('id, category_id, priority, title, new_message_for_admin, status, gs_id', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
        return array(
            'category' => array(self::HAS_ONE, 'TicketsCategories', array('id' => 'category_id')),
            'answers'  => array(self::HAS_MANY, 'TicketsAnswers', 'ticket_id'),
            'user'     => array(self::HAS_ONE, 'Users', array('user_id' => 'user_id')),
            'gs'       => array(self::HAS_ONE, 'Gs', array('id' => 'gs_id')),
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
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'            => 'ID',
			'category_id'   => Yii::t('main', 'Категория'),
			'priority'      => Yii::t('main', 'Приоритет'),
			'date_incident' => Yii::t('main', 'Дата происшествия'),
			'char_name'     => Yii::t('main', 'Имя персонажа'),
			'title'         => Yii::t('main', 'Тема'),
			'text'          => Yii::t('main', 'Сообщение'),
			'created_at'    => 'Created At',
			'updated_at'    => 'Updated At',
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

        $criteria->compare('id', $this->title);
        $criteria->compare('title', $this->title, TRUE);
        $criteria->compare('category_id', $this->category_id);
        $criteria->compare('priority', $this->priority);
        $criteria->compare('status', $this->status);
        $criteria->compare('new_message_for_admin', $this->new_message_for_admin);
        $criteria->compare('gs_id', $this->gs_id);

        $criteria->order = 't.priority DESC,t.created_at';
        $criteria->with  = array('user');

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => 20,
                'pageVar'  => 'page',
            ),
		));
	}

    public function getDate()
    {
        return date('Y-m-d H:i', strtotime($this->created_at));
    }
}

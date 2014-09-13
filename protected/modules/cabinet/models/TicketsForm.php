<?php 
 
class TicketsForm extends CFormModel
{
    public $category_id;
    public $priority;
    public $date_incident;
    public $char_name;
    public $title;
    public $text;



    public function rules()
    {
        return array(
            array('category_id, priority, date_incident, char_name, title, text', 'filter', 'filter' => 'trim'),
            array('category_id, priority, date_incident, title, text', 'required'),
            array('category_id, priority', 'numerical', 'integerOnly' => TRUE),
            array('text', 'length', 'min' => 5),
            array('date_incident', 'length', 'max' => 128),
            array('title', 'length', 'min' => 5, 'max' => 255),
            array('char_name', 'length', 'min' => 3, 'max' => 255),
            array('category_id', 'categoryIsExists'),
        );
    }

    public function categoryIsExists()
    {
        if(!$this->hasErrors())
        {
            $category = db()->createCommand("SELECT COUNT(0) FROM {{tickets_categories}} WHERE id = :id AND status = :status LIMIT 1")
                ->queryScalar(array(
                    'id'     => $this->category_id,
                    'status' => ActiveRecord::STATUS_ON,
                ));

            if(!$category)
            {
                $this->addError(__FUNCTION__, Yii::t('main', 'Выберите категорию.'));
            }
        }
    }

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
}
 
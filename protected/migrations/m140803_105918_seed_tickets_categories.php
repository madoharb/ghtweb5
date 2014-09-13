<?php

class m140803_105918_seed_tickets_categories extends CDbMigration
{
	public function up()
	{
        Yii::import('application.components.ActiveRecord');

        $this->insert('{{tickets_categories}}', array(
            'title'  => 'Учетная запись (аккаунт)',
            'status' => ActiveRecord::STATUS_ON,
            'sort'   => 1,
        ));

        $this->insert('{{tickets_categories}}', array(
            'title'  => 'Нарушение правил',
            'status' => ActiveRecord::STATUS_ON,
            'sort'   => 2,
        ));

        $this->insert('{{tickets_categories}}', array(
            'title'  => 'Пожертвования (донат)',
            'status' => ActiveRecord::STATUS_ON,
            'sort'   => 3,
        ));

        $this->insert('{{tickets_categories}}', array(
            'title'  => 'Связь (вход в игру; задержки)',
            'status' => ActiveRecord::STATUS_ON,
            'sort'   => 4,
        ));

        $this->insert('{{tickets_categories}}', array(
            'title'  => 'Технические проблемы',
            'status' => ActiveRecord::STATUS_ON,
            'sort'   => 5,
        ));

        $this->insert('{{tickets_categories}}', array(
            'title'  => 'Ваши предложения, пожелания',
            'status' => ActiveRecord::STATUS_ON,
            'sort'   => 6,
        ));

        $this->insert('{{tickets_categories}}', array(
            'title'  => 'Другие проблемы',
            'status' => ActiveRecord::STATUS_ON,
            'sort'   => 7,
        ));

        $this->insert('{{tickets_categories}}', array(
            'title'  => 'Услуги',
            'status' => ActiveRecord::STATUS_ON,
            'sort'   => 8,
        ));
    }

	public function down()
	{
        $this->delete('{{tickets_categories}}');
	}
}
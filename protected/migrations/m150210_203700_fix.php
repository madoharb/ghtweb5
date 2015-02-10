<?php

class m150210_203700_fix extends CDbMigration
{
	public function up()
	{
		$res = $this->getDbConnection()->createCommand("SELECT COUNT(0) FROM {{config}} WHERE param = 'forum_threads.characters_limit'")
			->queryScalar();

		if(!$res)
		{
			// INSERT INTO ghtweb_config (`param`, `value`, `default`, `label`, `group_id`, `order`, `field_type`)
			// VALUES ('forum_threads.characters_limit', 20, 20, 'До скольки символов обрезать название темы', 7, 14, 'textField');


			$this->insert('{{config}}', array(
				'param' 		=> 'forum_threads.characters_limit',
				'value' 		=> 20,
				'default' 		=> 20,
				'label' 		=> 'До скольки символов обрезать название темы',
				'group_id' 		=> 7,
				'order' 		=> 14,
				'field_type' 	=> 'textField',
			));
		}
	}

	public function down()
	{
		$this->delete('{{config}}', 'param = "forum_threads.characters_limit"');
	}
}
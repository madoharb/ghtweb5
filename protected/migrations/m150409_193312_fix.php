<?php

class m150409_193312_fix extends CDbMigration
{
	public function safeUp()
	{
        $this->delete('{{config}}', 'param = :param', array('param' => 'cabinet.change_password.captcha.allow'));

        // Избавляюсь от telnet (наконецто =)))
        $this->dropColumn('{{gs}}', 'telnet_host');
        $this->dropColumn('{{gs}}', 'telnet_port');
        $this->dropColumn('{{gs}}', 'telnet_pass');

        $this->dropColumn('{{ls}}', 'telnet_host');
        $this->dropColumn('{{ls}}', 'telnet_port');
        $this->dropColumn('{{ls}}', 'telnet_pass');
	}

	public function safeDown()
	{
        $this->insert('{{config}}', array(
            'param' => 'cabinet.change_password.captcha.allow',
            'value' => 0,
            'default' => 0,
            'label' => 'Капча при смене пароля от аккаунта',
            'group_id' => 16,
            'order' => 4,
            'method' => NULL,
            'field_type' => 'dropDownList',
            'created_at' => date('Y-m-d H:i:s'),
        ));
	}
}
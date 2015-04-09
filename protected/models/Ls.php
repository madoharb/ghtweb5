<?php

/**
 * This is the model class for table "{{ls}}".
 *
 * The followings are the available columns in table '{{ls}}':
 * @property integer $id
 * @property string $name
 * @property string $ip
 * @property string $port
 * @property string $db_host
 * @property string $db_port
 * @property string $db_user
 * @property string $db_pass
 * @property string $db_name
 * @property string $version
 * @property integer $status
 * @property string $password_type
 * @property string $created_at
 * @property string $updated_at
 *
 * The followings are the available model relations:
 * @property Gs[] $servers
 */
class Ls extends ActiveRecord
{
    // Password
    const PASSWORD_TYPE_SHA1     = 'sha1';
    const PASSWORD_TYPE_WIRLPOOL = 'wirlpool';



	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{ls}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, ip, port, db_host, db_port, db_user, db_pass, db_name, version, status, password_type,
			    created_at', 'filter', 'filter' => 'trim'),

			array('name, ip, port, db_host, db_port, db_user, db_name, version, status, password_type', 'required'),

			array('status', 'in', 'range' => array(ActiveRecord::STATUS_OFF, ActiveRecord::STATUS_ON)),

			array('name, ip, db_host, db_user, db_pass, db_name', 'length', 'max' => 54),
			array('port', 'length', 'max' => 5),

            array('version', 'in', 'range' => array_keys(app()->params['server_versions']), 'message' => Yii::t('main', 'Выберите версию сервера')),
			array('password_type', 'in', 'range' => array_keys($this->getPasswordTypeList())),

			array('db_pass', 'default', 'value' => NULL),

			array('id, name, ip, port, version, status', 'safe', 'on' => 'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'servers' => array(self::HAS_MANY, 'Gs', array('login_id' => 'id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'            => 'ID',
			'name'          => Yii::t('backend', 'Название'),
			'ip'            => 'Ip',
            'port'          => Yii::t('backend', 'Порт'),
            'db_host'       => Yii::t('backend', 'MYSQL host'),
            'db_port'       => Yii::t('backend', 'MYSQL port'),
            'db_user'       => Yii::t('backend', 'MYSQL user'),
            'db_pass'       => Yii::t('backend', 'MYSQL pass'),
            'db_name'       => Yii::t('backend', 'MYSQL bd name'),
			'version'       => Yii::t('backend', 'Версия логина'),
			'status'        => Yii::t('backend', 'Статус'),
			'password_type' => Yii::t('backend', 'Тип пароля'),
            'created_at'    => Yii::t('backend', 'Дата создания'),
            'updated_at'    => Yii::t('backend', 'Дата обновления'),
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare('id', $this->id);
		$criteria->compare('name', $this->name, TRUE);
		$criteria->compare('ip', $this->ip, TRUE);
		$criteria->compare('port', $this->port);
		$criteria->compare('version', $this->version, TRUE);
		$criteria->compare('status', $this->status);

        $criteria->scopes = array('not_deleted');

		return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => $this->tableAlias . '.created_at DESC'
            ),
            'pagination' => array(
                'pageSize'  => 20,
                'pageVar'   => 'page',
            ),
		));
	}

    public function getPasswordTypeList()
    {
        return array(
            self::PASSWORD_TYPE_SHA1     => 'sha1',
            self::PASSWORD_TYPE_WIRLPOOL => 'wirlpool',
        );
    }

    public function getPasswordType()
    {
        $data = $this->getPasswordTypeList();
        return isset($data[$this->password_type]) ? $data[$this->password_type] : Yii::t('backend', '*Unknown*');
    }

    public function getStatusList()
    {
        $data = parent::getStatusList();
        unset($data[ActiveRecord::STATUS_DELETED]);

        return $data;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function getDbHost()
    {
        return $this->db_host;
    }

    /**
     * @return string
     */
    public function getDbPort()
    {
        return $this->db_port;
    }

    /**
     * @return string
     */
    public function getDbUser()
    {
        return $this->db_user;
    }

    /**
     * @return string
     */
    public function getDbPass()
    {
        return $this->db_pass;
    }

    /**
     * @return string
     */
    public function getDbName()
    {
        return $this->db_name;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}

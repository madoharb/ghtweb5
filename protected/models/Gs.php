<?php

/**
 * This is the model class for table "{{gs}}".
 *
 * The followings are the available columns in table '{{gs}}':
 * @property integer $id
 * @property string $name
 * @property string $ip
 * @property string $port
 * @property string $db_host
 * @property string $db_port
 * @property string $db_user
 * @property string $db_pass
 * @property string $db_name
 * @property string $telnet_host
 * @property int $telnet_port
 * @property string $telnet_pass
 * @property integer $login_id
 * @property string $version
 * @property string $fake_online
 * @property integer $allow_teleport
 * @property string $teleport_time
 * @property integer $stats_allow
 * @property string $stats_cache_time
 * @property integer $stats_total
 * @property integer $stats_pvp
 * @property integer $stats_pk
 * @property integer $stats_clans
 * @property integer $stats_castles
 * @property integer $stats_online
 * @property integer $stats_clan_info
 * @property integer $stats_top
 * @property integer $stats_rich
 * @property string $stats_count_results
 * @property string $exp
 * @property string $sp
 * @property string $adena
 * @property string $drop
 * @property string $items
 * @property string $spoil
 * @property string $q_drop
 * @property string $q_reward
 * @property string $rb
 * @property string $erb
 * @property integer $services_premium_allow
 * @property string $services_premium_cost
 * @property integer $services_remove_hwid_allow
 * @property integer $services_change_char_name_allow
 * @property integer $services_change_char_name_cost
 * @property string $services_change_char_name_chars
 * @property integer $services_change_gender_allow
 * @property integer $services_change_gender_cost
 * @property string $currency_name
 * @property integer $deposit_allow
 * @property integer $deposit_payment_system
 * @property string $deposit_desc
 * @property integer $deposit_course_payments
 * @property integer $currency_symbol
 * @property integer $status
 * @property string $created_at
 * @property string $updated_at
 * @property integer $stats_items
 * @property string $stats_items_list
 *
 * The followings are the available model relations:
 * @property Ls $ls
 */
class Gs extends ActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{gs}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
            array('name, ip, port, db_host, db_port, db_user, db_name, telnet_host, telnet_port, telnet_pass, login_id,
                version, status, fake_online, allow_teleport, teleport_time, stats_allow, stats_cache_time, stats_total,
                stats_pvp, stats_pk, stats_clans, stats_castles, stats_online, stats_clan_info, stats_top, stats_rich,
                stats_count_results, exp, sp, adena, drop, items, spoil, q_drop, q_reward, rb, erb, services_premium_allow,
                services_remove_hwid_allow, services_change_char_name_allow, services_change_char_name_cost, services_change_char_name_chars,
                services_change_gender_allow, services_change_gender_cost, currency_name, deposit_allow, deposit_payment_system,
                deposit_desc, deposit_course_payments, currency_symbol, stats_items, stats_items_list', 'filter', 'filter' => 'trim'),

            array('name, ip, port, db_host, db_port, db_user, db_name, login_id, version, status, fake_online,
                allow_teleport, teleport_time, stats_allow, stats_cache_time, stats_total, stats_pvp, stats_pk,
                stats_clans, stats_castles, stats_online, stats_clan_info, stats_top, stats_rich, stats_count_results,
                exp, sp, adena, drop, items, spoil, q_drop, q_reward, rb, erb, services_premium_allow,
                services_remove_hwid_allow, currency_name, deposit_allow, deposit_payment_system, deposit_desc, deposit_course_payments,
                currency_symbol, stats_items', 'required'),

            array('services_premium_allow', 'checkPremiumCost'),
            array('deposit_payment_system', 'checkDepositPaymentSystem'),

            array('allow_teleport, stats_allow, stats_total, stats_pvp, stats_pk, stats_clans, stats_castles,
                stats_online, stats_clan_info, stats_top, stats_rich, services_premium_allow,
                services_remove_hwid_allow, deposit_allow, stats_items', 'in', 'range' => array_keys($this->getStatusList())),

            array('status', 'in', 'range' => array_keys(parent::getStatusList())),
            array('currency_symbol', 'in', 'range' => array_keys($this->getCurrencySymbols()), 'message' => Yii::t('main', 'Валюта')),

			array('port, db_port, telnet_port, login_id, fake_online, teleport_time, stats_cache_time, stats_count_results,
                exp, sp, adena, drop, items, spoil, q_drop, q_reward, rb, erb', 'numerical', 'integerOnly' => TRUE),

			array('currency_name', 'length', 'max' => 128),
			array('name, ip, db_host, db_user, db_pass, db_name, telnet_host, telnet_pass', 'length', 'max' => 54),
			array('port, db_port, telnet_port, fake_online, teleport_time, stats_cache_time, stats_count_results', 'length', 'max' => 11),
			array('exp, sp, adena, drop, items, spoil, q_drop, q_reward, rb, erb', 'length', 'max' => 6),
			array('version', 'length', 'max' => 20),

            array('telnet_host, telnet_port, telnet_pass', 'default', 'value' => NULL),

            array('stats_items', 'checkStatsItemsList'),
            array('stats_items_list', 'default', 'value' => NULL),

			array('login_id', 'loginIsExists'),
			array('version', 'in', 'range' => array_keys(app()->params['server_versions']), 'message' => Yii::t('main', 'Выберите версию сервера')),

			array('services_premium_cost', 'safe'),
			array('id, name, ip, port, version, login_id, status', 'safe', 'on' => 'search'),
		);
	}

    /**
     * Проверка ID предметов
     *
     * @param $attribute
     * @param array $params
     */
    public function checkStatsItemsList($attribute, array $params)
    {
        if($this->stats_items && $this->stats_items_list == '')
        {
            $this->addError($attribute, Yii::t('backend', 'Введите ID предметов для вывода в статистике предметов.'));
        }
    }

    /**
     * Проверка выбранного агрегатора
     */
    public function checkDepositPaymentSystem()
    {
        Yii::import('application.modules.deposit.extensions.Deposit.Deposit');

        $data = Deposit::getAggregatorsList();

        if(!isset($data[$this->deposit_payment_system]))
        {
            $this->addError('deposit_payment_system', Yii::t('main', 'Выберите платежную систему'));
        }
    }

    /**
     * Проверка логина
     */
    public function loginIsExists()
    {
        if(!$this->hasErrors())
        {
            $lsId = $this->login_id;

            $login = db()->createCommand("SELECT COUNT(0) FROM `{{ls}}` WHERE `id` = :id LIMIT 1")
                ->bindParam('id', $lsId, PDO::PARAM_INT)
                ->queryScalar();

            if(!$login)
            {
                $this->addError('login_id', Yii::t('backend', 'Выберите логин.'));
            }
        }
    }

    public function checkPremiumCost()
    {
        if($this->services_premium_allow)
        {
            $error = FALSE;

            if(count($this->services_premium_cost) == 0 || !is_array($this->services_premium_cost))
            {
                $error = TRUE;
            }

            foreach($this->services_premium_cost as $row)
            {
                if(empty($row['cost']) || empty($row['days']))
                {
                    $error = TRUE;
                    break;
                }
            }

            if($error)
            {
                $this->addError('services_premium_allow', Yii::t('backend', 'Введите параметры покупки премиум аккаунта'));
            }
        }
    }

    public function beforeSave()
    {
        if($this->services_premium_allow)
        {
            if((is_array($this->services_premium_cost) && count($this->services_premium_cost)))
            {
                $this->services_premium_cost = serialize($this->services_premium_cost);
            }
        }
        else
        {
            $this->services_premium_cost = NULL;
        }

        return parent::beforeSave();
    }

    public function afterFind()
    {
        // Инфа о стоймости премиума
        if($this->services_premium_cost)
        {
            $res = @unserialize($this->services_premium_cost);

            if(is_array($res))
            {
                $this->services_premium_cost = $res;
            }
        }
    }

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
            'ls' => array(self::HAS_ONE, 'Ls', array('id' => 'login_id')),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'                                => 'ID',
			'name'                              => Yii::t('backend', 'Название'),
			'ip'                                => Yii::t('backend', 'IP адрес'),
			'port'                              => Yii::t('backend', 'Порт'),
			'db_host'                           => Yii::t('backend', 'MYSQL host'),
			'db_port'                           => Yii::t('backend', 'MYSQL port'),
			'db_user'                           => Yii::t('backend', 'MYSQL user'),
			'db_pass'                           => Yii::t('backend', 'MYSQL pass'),
			'db_name'                           => Yii::t('backend', 'MYSQL bd name'),
			'telnet_host'                       => Yii::t('backend', 'TELNET host'),
			'telnet_port'                       => Yii::t('backend', 'TELNET port'),
			'telnet_pass'                       => Yii::t('backend', 'TELNET pass'),
			'login_id'                          => Yii::t('backend', 'Логин'),
			'version'                           => Yii::t('backend', 'Версия сервера'),
			'status'                            => Yii::t('backend', 'Статус'),
			'fake_online'                       => Yii::t('backend', 'Накрутка онлайна'),
			'allow_teleport'                    => Yii::t('backend', 'Телепорт'),
			'teleport_time'                     => Yii::t('backend', 'Время повторного телепорта'),
			'stats_allow'                       => Yii::t('backend', 'Статистика'),
			'stats_cache_time'                  => Yii::t('backend', 'Время кэширования статистики'),
			'stats_total'                       => Yii::t('backend', 'Общая'),
			'stats_pvp'                         => Yii::t('backend', 'Топ пвп'),
			'stats_pk'                          => Yii::t('backend', 'Топ пк'),
			'stats_clans'                       => Yii::t('backend', 'Кланы'),
			'stats_castles'                     => Yii::t('backend', 'Замки'),
			'stats_online'                      => Yii::t('backend', 'В игре'),
			'stats_clan_info'                   => Yii::t('backend', 'Просмотр клана'),
			'stats_top'                         => Yii::t('backend', 'Топ'),
			'stats_rich'                        => Yii::t('backend', 'Богачи'),
			'stats_count_results'               => Yii::t('backend', 'Кол-во результатов'),
			'exp'                               => 'Exp',
			'sp'                                => 'Sp',
			'drop'                              => 'Drop',
			'adena'                             => 'Adena',
			'items'                             => 'Items',
			'spoil'                             => 'Spoil',
			'q_drop'                            => Yii::t('backend', 'Quest drop'),
			'q_reward'                          => Yii::t('backend', 'Quest reward'),
			'rb'                                => 'Rb',
			'erb'                               => 'Erb',
			'services_premium_allow'            => Yii::t('backend', 'Покупка премиум аккаунта'),
			'services_premium_cost'             => Yii::t('backend', 'Параметры покупки премиум аккаунта'),
			'services_remove_hwid_allow'        => Yii::t('backend', 'Удаление привязки по HWID'),
			'services_change_char_name_allow'   => Yii::t('backend', 'Смена имени персонажу'),
			'services_change_char_name_cost'    => Yii::t('backend', 'Стоимость смены имени персонажу'),
			'services_change_char_name_chars'   => Yii::t('backend', 'Символы в смене имени персонажу'),
			'services_change_gender_allow'      => Yii::t('backend', 'Смена пола персонажу'),
			'services_change_gender_cost'       => Yii::t('backend', 'Стоимость смены пола персонажу'),
			'currency_name'                     => Yii::t('backend', 'Название игровой валюты'),
			'deposit_allow'                     => Yii::t('backend', 'Возможность закинуть денег'),
			'deposit_payment_system'            => Yii::t('backend', 'Платежная система'),
			'deposit_desc'                      => Yii::t('backend', 'Описание платежа'),
			'deposit_course_payments'           => Yii::t('backend', 'Курс валют'),
			'currency_symbol'                   => Yii::t('backend', 'Валюта'),
			'created_at'                        => Yii::t('backend', 'Дата создания'),
			'updated_at'                        => Yii::t('backend', 'Дата обновления'),
			'stats_items'                       => Yii::t('backend', 'Статистика предметов'),
			'stats_items_list'                  => Yii::t('backend', 'Список ID предметов для вывода в статистике'),
		);
	}

	public function search()
	{
		$criteria = new CDbCriteria;

		$criteria->compare($this->tableAlias . '.id', $this->id);
		$criteria->compare($this->tableAlias . '.name', $this->name, TRUE);
		$criteria->compare($this->tableAlias . '.ip', $this->ip, TRUE);
		$criteria->compare($this->tableAlias . '.port', $this->port, TRUE);
		$criteria->compare($this->tableAlias . '.login_id', $this->login_id);
		$criteria->compare($this->tableAlias . '.version', $this->version, TRUE);
		$criteria->compare($this->tableAlias . '.status', $this->status);

        $criteria->scopes = array('not_deleted');

        $criteria->with = array('ls' => array(
            'scopes' => array('not_deleted'),
        ));

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

    /**
     * Возвращает символ валюты сервера
     *
     * @return string
     */
    public function getServerCurrencySymbol()
    {
        return $this->currency_symbol;
    }

    /**
     * Название валюты сервера
     *
     * @return string
     */
    public function getCurrencyName()
    {
        return $this->currency_name;
    }

    /**
     * Возвращает название валюты за которую покупают игровую валюту
     *
     * @return string
     */
    public function getCurrencySymbolName()
    {
        return app()->locale->getCurrencySymbol($this->currency_symbol);
    }

    public function getStatusList()
    {
        $data = parent::getStatusList();
        unset($data[ActiveRecord::STATUS_DELETED]);

        return $data;
    }

    /**
     * Список платёжных систем
     *
     * @return array
     */
    public function getAggregatorsList()
    {
        $data = Deposit::getAggregatorsList();
        unset($data[Deposit::PAYMENT_SYSTEM_UNITPAY_SMS], $data[Deposit::PAYMENT_SYSTEM_WAYTOPAY_SMS]);

        return $data;
    }

    public function getCurrencySymbols()
    {
        $data = app()->params['currency_symbols'];
        return $data;
    }
}

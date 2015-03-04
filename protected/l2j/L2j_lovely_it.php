<?php

/**
 * Сайт команды разработчиков: http://l2jlovely.net/
 * Версия сервера на которой писались запросы: 0
 * Автор: ght^^ (http://ghtweb.ru/)
 * Дата создания файла с запросами: 2015-03-03 23:28:39
 */
class L2j_lovely_it
{
    /**
     * @var CDbConnection
     */
    private $_db;

    /**
     * @var Lineage
     */
    private $_context;

    /**
     * Поля из БД
     * @var array
     */
    private $_fields = array(
        'accounts.access_level'   => 'accounts.accessLevel',
        //'characters.access_level' => FALSE,
        'characters.char_id'      => 'characters.charId',
        'clan_data.clan_id'       => 'clan_data.clan_id',
    );



    public function __construct($context)
    {
        $this->_context = $context;
        $this->_db = $context->getDb();
    }

    /**
     * Создание игрового аккаунта
     *
     * @param string $login
     * @param string $password
     * @param int $accessLevel
     *
     * @return bool
     */
    public function insertAccount($login, $password, $accessLevel = 0)
    {
        $encodePassword = $this->_context->passwordEncrypt($password);

        return $this->_db->createCommand('INSERT INTO `accounts` (`login`, `password`, `accessLevel`) VALUES(:login, :password, :access_level)')
            ->bindParam('login', $login, PDO::PARAM_STR)
            ->bindParam('password', $encodePassword, PDO::PARAM_STR)
            ->bindParam('access_level', $accessLevel, PDO::PARAM_INT)
            ->execute();
    }

    /**
     * Возвращает аккаунты
     *
     * @param CDbCommand $command
     *
     * @return CDbCommand
     */
    public function accounts($command = NULL)
    {
        if(!($command instanceof CDbCommand))
        {
            $command = $this->_db->createCommand();
        }

        /*
SELECT 
login, password, accessLevel AS access_level, lastactive AS last_active 
FROM accounts
        */
        return $command
			->select(array('login', 'password', 'accessLevel AS access_level', 'lastactive AS last_active'))
			->from('accounts');
    }

    /**
     * Возвращает список персонажей
     *
     * @param CDbCommand $command
     *
     * @return CDbCommand
     */
    public function characters($command = NULL)
    {
        if(!($command instanceof CDbCommand))
        {
            $command = $this->_db->createCommand();
        }

        /*
SELECT 
characters.account_name, characters.charId AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.clanid AS clan_id, characters.title, "0" AS access_level, characters.online, characters.onlinetime, characters.race AS base_class, characters.level, characters.exp, characters.sp, characters.maxHp, characters.curHp, characters.maxCp, characters.curCp, characters.maxMp, characters.curMp, clan_data.clan_name, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score 
FROM characters 
LEFT JOIN clan_data ON clan_data.clan_id = characters.clanid
        */
        return $command
			->select(array('characters.account_name', 'characters.charId AS char_id', 'characters.char_name', 'characters.sex', 'characters.x', 'characters.y', 'characters.z', 'characters.karma', 'characters.pvpkills', 'characters.pkkills', 'characters.clanid AS clan_id', 'characters.title', new AA, 'characters.online', 'characters.onlinetime', 'characters.race AS base_class', 'characters.level', 'characters.exp', 'characters.sp', 'characters.maxHp', 'characters.curHp', 'characters.maxCp', 'characters.curCp', 'characters.maxMp', 'characters.curMp', 'clan_data.clan_name', 'clan_data.clan_level', 'clan_data.hasCastle', 'clan_data.crest_id AS clan_crest', 'clan_data.reputation_score'))
			->leftJoin('clan_data', 'clan_data.clan_id = characters.clanid')
			->from('characters');
    }

    /**
     * Информация о кланах, лидере, алли и кол-ве персонажей
     *
     * @param CDbCommand $command
     *
     * @return CDbCommand
     */
    public function clans($command = NULL)
    {
        if(!($command instanceof CDbCommand))
        {
            $command = $this->_db->createCommand();
        }

        /*
SELECT 
clan_data.clan_id, clan_data.clan_name, clan_data.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score, (SELECT COUNT(0) FROM characters WHERE characters.clanid = clan_data.clan_id) as ccount, ally_name, ally_crest_id AS ally_crest, ally_id, characters.account_name, characters.charId AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.title, "0" AS access_level, characters.online, characters.onlinetime, characters.race AS base_class, characters.level, characters.exp, characters.sp, characters.maxHp, characters.curHp, characters.maxCp, characters.curCp, characters.maxMp, characters.curMp 
FROM clan_data 
LEFT JOIN characters ON characters.charId = clan_data.leader_id
        */
        return $command
			->select(array('clan_data.clan_id', 'clan_data.clan_name', 'clan_data.leader_id', 'clan_data.clan_level', 'clan_data.hasCastle', 'clan_data.crest_id AS clan_crest', 'clan_data.reputation_score', '(SELECT COUNT(0) FROM characters WHERE characters.clanid = clan_data.clan_id) as ccount', 'ally_name', 'ally_crest_id AS ally_crest', 'ally_id', 'characters.account_name', 'characters.charId AS char_id', 'characters.char_name', 'characters.sex', 'characters.x', 'characters.y', 'characters.z', 'characters.karma', 'characters.pvpkills', 'characters.pkkills', 'characters.title', new AA, 'characters.online', 'characters.onlinetime', 'characters.race AS base_class', 'characters.level', 'characters.exp', 'characters.sp', 'characters.maxHp', 'characters.curHp', 'characters.maxCp', 'characters.curCp', 'characters.maxMp', 'characters.curMp'))
			->leftJoin('characters', 'characters.charId = clan_data.leader_id')
			->from('clan_data');
    }

    /**
     * Предметы персонажей
     *
     * @param CDbCommand $command
     *
     * @return CDbCommand
     */
    public function items($command = NULL)
    {
        if(!($command instanceof CDbCommand))
        {
            $command = $this->_db->createCommand();
        }

        /*
SELECT 
items.owner_id, items.object_id, items.item_id, items.count, items.enchant_level, items.loc, items.loc_data 
FROM items
        */
        return $command
			->select(array('items.owner_id', 'items.object_id', 'items.item_id', 'items.count', 'items.enchant_level', 'items.loc', 'items.loc_data'))
			->from('items');
    }

    /**
     * Добавление предмета в игру
     *
     * @param int $ownerId
     * @param int $itemId
     * @param int $count
     * @param int $enchantLevel
     *
     * @return bool
     */
    public function insertItem($ownerId, $itemId, $count = 1, $enchantLevel = 0)
    {
        $maxId = $this->_db->createCommand('SELECT MAX(object_id) + 1 FROM `items`')->queryScalar();

		return $this->_db->createCommand('INSERT INTO `items` (`owner_id`, `object_id`, `item_id`, `count`, `enchant_level`, `loc`) VALUES(:owner_id, :object_id, :item_id, :count, :enchant_level, :loc)')
            ->bindParam('owner_id', $ownerId, PDO::PARAM_INT)
            ->bindParam('item_id', $itemId, PDO::PARAM_INT)
            ->bindParam('count', $count, PDO::PARAM_INT)
            ->bindParam('enchant_level', $enchantLevel, PDO::PARAM_INT)
			->bindParam('loc', 'INVENTORY', PDO::PARAM_STR)
			->bindParam('object_id', $maxId, PDO::PARAM_INT)
			->execute();
    }

    /**
     * Добавление дохуя предметов в игру одним запросом
     *
     * @param array $items
     * <code>
     *     array(
     *         'owner_id' => 111111
     *         'item_id' => 57
     *         'count' => 100
     *         'enchant' => 0
     *     )
     * </code>
     *
     * @return bool
     */
    public function multiInsertItem(array $items)
    {
        $maxId = $this->_db->createCommand('SELECT MAX(object_id) + 1 FROM `items`')->queryScalar();

		// Заменяю enchant на enchant_level, добавляю object_id
        foreach($items as $k => $v)
        {
            $items[$k]['object_id'] = $maxId++;
            $items[$k]['enchant_level'] = $v['enchant'];
            $items[$k]['loc'] = 'INVENTORY';
            $items[$k]['loc_data'] = 0;
            $items[$k]['price_sell'] = 0;
            unset($items[$k]['enchant']);
        }

        $command = $this->_db->schema->commandBuilder->createMultipleInsertCommand('items', $items);
        return $command->execute();
    }

    /**
     * Возвращает кол-во людей
     *
     * @return int
     */
    public function getCountRaceHuman()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE race = 0')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во эльфов
     *
     * @return int
     */
    public function getCountRaceElf()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE race = 1')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во тэмных эльфов
     *
     * @return int
     */
    public function getCountRaceDarkElf()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE race = 2')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во орков
     *
     * @return int
     */
    public function getCountRaceOrk()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE race = 3')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во гномов
     *
     * @return int
     */
    public function getCountRaceDwarf()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE race = 4')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во камаэлей
     *
     * @return int
     */
    public function getCountRaceKamael()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE race = 5')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во аккаунтов
     *
     * @return int
     */
    public function getCountAccounts()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM accounts')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во персонажей
     *
     * @return int
     */
    public function getCountCharacters()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во персонажей в игре
     *
     * @return int
     */
    public function getCountOnlineCharacters()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE online = 1')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во торговцев в игре
     *
     * @return int
     */
    public function getCountOfflineTraders()
    {
        // TODO!!!
        return 0;
    }

    /**
     * Возвращает кол-во кланов
     *
     * @return int
     */
    public function getCountClans()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM clan_data')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во мужчин
     *
     * @return int
     */
    public function getCountMen()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE sex = 0')
            ->queryScalar();
    }

    /**
     * Возвращает кол-во женщин
     *
     * @return int
     */
    public function getCountWomen()
    {
        return $this->_db->createCommand('SELECT COUNT(0) as count FROM characters WHERE sex = 1')
            ->queryScalar();
    }

    /**
     * Возвращает Топ ПВП
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getTopPvp($limit = 20, $offset = 0)
    {
        $command = $this->_db->createCommand()
            ->where('pvpkills > 0')
            ->order('pvpkills DESC')
            ->limit($limit)
            ->offset($offset);

        return $this->characters($command)
            ->queryAll();
    }

    /**
     * Возвращает Топ ПК
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getTopPk($limit = 20, $offset = 0)
    {
        $command = $this->_db->createCommand()
            ->where('pkkills > 0')
            ->order('pkkills DESC')
            ->limit($limit)
            ->offset($offset);

        return $this->characters($command)
            ->queryAll();
    }

    /**
     * Возвращает Топ игроков
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getTop($limit = 20, $offset = 0)
    {
        $command = $this->_db->createCommand()
            ->order('exp DESC, sp DESC')
            ->limit($limit)
            ->offset($offset);

        return $this->characters($command)
            ->queryAll();
    }

    /**
     * Возвращает Топ богачей
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getTopRich($limit = 20, $offset = 0)
    {
        $command = $this->characters();

        return $command->select($command->getSelect() . ', SUM(items.count) AS adena_count')
            ->where('items.item_id = 57')
            ->order('adena_count DESC')
            ->limit($limit)
            ->offset($offset)
            ->group('characters.charId')
            ->leftJoin('items', 'items.owner_id = characters.charId')
            ->queryAll();
    }

    /**
     * Возвращает кто в игре
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getOnline($limit = 20, $offset = 0)
    {
        $command = $this->_db->createCommand()
            ->where('online = 1')
            ->order('level DESC')
            ->limit($limit)
            ->offset($offset);

        return $this->characters($command)
            ->queryAll();
    }

    /**
     * Возвращает Топ кланов
     *
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getTopClans($limit = 20, $offset = 0)
    {
        $command = $this->_db->createCommand();

        $command->order  = 'reputation_score DESC';
        $command->limit  = $limit;
        $command->offset = $offset;

        return $this->clans($command)
            ->queryAll();
    }

    /**
     * Возвращает список замков и инфу о владельцах
     *
     * @return array
     */
    public function getCastles()
    {
        /*
SELECT 
castle.id, castle.name, castle.taxPercent, castle.siegeDate, clan_data.clan_id, clan_data.clan_name, clan_data.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score, ally_name, ally_crest_id AS ally_crest, ally_id 
FROM castle 
LEFT JOIN clan_data ON castle.id = clan_data.hasCastle
        */
        return $this->_db->createCommand()
			->select(array('castle.id', 'castle.name', 'castle.taxPercent', 'castle.siegeDate', 'clan_data.clan_id', 'clan_data.clan_name', 'clan_data.leader_id', 'clan_data.clan_level', 'clan_data.hasCastle', 'clan_data.crest_id AS clan_crest', 'clan_data.reputation_score', 'ally_name', 'ally_crest_id AS ally_crest', 'ally_id'))
			->leftJoin('clan_data', 'castle.id = clan_data.hasCastle')
			->from('castle')->queryAll();
    }

    /**
     * Возвращает информацию о кланах и алли которые принимают участие в осаде
     *
     * @return array
     */
    public function getSiege()
    {
        /*
SELECT 
siege_clans.castle_id, siege_clans.type, clan_data.clan_id, clan_data.clan_name, clan_data.leader_id, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score, ally_name, ally_crest_id AS ally_crest, ally_id 
FROM siege_clans 
LEFT JOIN clan_data ON siege_clans.clan_id = clan_data.clan_id
        */
        return $this->_db->createCommand()
			->select(array('siege_clans.castle_id', 'siege_clans.type', 'clan_data.clan_id', 'clan_data.clan_name', 'clan_data.leader_id', 'clan_data.clan_level', 'clan_data.hasCastle', 'clan_data.crest_id AS clan_crest', 'clan_data.reputation_score', 'ally_name', 'ally_crest_id AS ally_crest', 'ally_id'))
			->leftJoin('clan_data', 'siege_clans.clan_id = clan_data.clan_id')
			->from('siege_clans')->queryAll();
    }

    /**
     * Хроники сервера
     *
     * @return string
     */
    public function getChronicle()
    {
        return 'it';
    }

    /**
     * Возвращает название поля из таблицы
     *
     * @param string $fieldName
     *
     * @return string
     */
    public function getField($fieldName)
    {
        return isset($this->_fields[$fieldName]) ? $this->_fields[$fieldName] : NULL;
    }

    /**
     * Возвращает название сервера
     *
     * @return string
     */
    public function getServerName()
    {
        return __CLASS__;
    }

    /**
     * Информация о премиум аккаунте
     * Возвращает дату окончания в timestamp формате
     *
     * @param string $accountName
     *
     * @return array
     *
     * <code>
     *     array(
     *         'dateEnd' => 1234567890
     *     )
     * </code>
     *
     */
    public function getPremiumInfo($accountName)
    {
        // @TODO !!!!!
        return '';
    }

    /**
     * Добавление времени к премиум аккаунту
     *
     * @param string $accountName
     * @param int $timeEnd
     *
     * @return bool
     */
    public function addPremium($accountName, $timeEnd)
    {
        // @TODO !!!!!
        return '';
    }

    /**
     * Удаление привязки по HWID
     *
     * @param string $accountName
     *
     * @return bool
     */
    public function removeHWID($accountName)
    {
        // @TODO !!!!!
        return '';
    }

    /**
     * Контроль предметов
     *
     * @param array $itemList (ID предметов по которым будет выборка)
     *
     * @return array
     */
    public function getItemsControl(array $itemList)
    {
        if(!$itemList)
        {
            return array();
        }

        $res = AllItems::model()->findAllByAttributes(array(
            'item_id' => $itemList,
        ));

        $itemNames = array();

        foreach($res as $row)
        {
            $itemNames[$row->getPrimaryKey()] = $row;
        }

        unset($res);

        /*
SELECT 
MAX(items.count) AS maxCountItems, COUNT(items.count) AS countItems, items.owner_id, items.object_id, items.item_id, items.count, items.enchant_level, items.loc, items.loc_data, characters.account_name, characters.charId AS char_id, characters.char_name, characters.sex, characters.x, characters.y, characters.z, characters.karma, characters.pvpkills, characters.pkkills, characters.clanid AS clan_id, characters.title, "0" AS access_level, characters.online, characters.onlinetime, characters.race AS base_class, characters.level, characters.exp, characters.sp, characters.maxHp, characters.curHp, characters.maxCp, characters.curCp, characters.maxMp, characters.curMp, clan_data.clan_name, clan_data.clan_level, clan_data.hasCastle, clan_data.crest_id AS clan_crest, clan_data.reputation_score 
FROM items 
LEFT JOIN characters ON items.owner_id = characters.charId
LEFT JOIN clan_data ON clan_data.clan_id = characters.clanid 
GROUP BY items.owner_id, items.item_id
        */
        $res = $this->_db->createCommand()
            ->select(array('MAX(items.count) AS maxCountItems', 'COUNT(items.count) AS countItems', 'items.owner_id', 'items.object_id', 'items.item_id', 'items.count', 'items.enchant_level', 'items.loc', 'items.loc_data', 'characters.account_name', 'characters.charId AS char_id', 'characters.char_name', 'characters.sex', 'characters.x', 'characters.y', 'characters.z', 'characters.karma', 'characters.pvpkills', 'characters.pkkills', 'characters.clanid AS clan_id', 'characters.title', new AA, 'characters.online', 'characters.onlinetime', 'characters.race AS base_class', 'characters.level', 'characters.exp', 'characters.sp', 'characters.maxHp', 'characters.curHp', 'characters.maxCp', 'characters.curCp', 'characters.maxMp', 'characters.curMp', 'clan_data.clan_name', 'clan_data.clan_level', 'clan_data.hasCastle', 'clan_data.crest_id AS clan_crest', 'clan_data.reputation_score'))
			->leftJoin('characters', 'items.owner_id = characters.charId')
			->leftJoin('clan_data', 'clan_data.clan_id = characters.clanid')
			->andWhere(array('in', 'item_id', $itemList))
			->group('items.owner_id, items.item_id')
			->from('items')
			->queryAll();

        $characters = array();

        foreach($res as $item)
        {
            if(!isset($characters[$item['item_id']]['maxTotalItems']))
            {
                $characters[$item['item_id']]['maxTotalItems'] = 0;
            }

            $characters[$item['item_id']]['itemInfo'] = $itemNames[$item['item_id']];
            $characters[$item['item_id']]['characters'][] = $item;
            $characters[$item['item_id']]['maxTotalItems'] += $item['maxCountItems'];
            $characters[$item['item_id']]['totalItems'] = count($characters[$item['item_id']]['characters']);
        }

        foreach(array_diff_key($itemNames, $characters) as $item)
        {
            $characters[$item->item_id]['itemInfo'] = $item;
            $characters[$item->item_id]['characters'] = array();
            $characters[$item->item_id]['maxTotalItems'] = 0;
            $characters[$item->item_id]['totalItems'] = 0;
        }

        return $characters;
    }
}

class AA
{
    private $f = '"0" AS access_level';

    function __toString()
    {
        return $this->f;
    }
}
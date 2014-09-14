<?php

/**
 * Class Step4Form
 *
 * @property string $mysql_host
 * @property int $mysql_port
 * @property string $mysql_user
 * @property string $mysql_pass
 * @property string $mysql_name
 */
class Step2Form extends CFormModel
{
    /**
     * @var string
     */
    public $mysql_host;

    /**
     * @var int
     */
    public $mysql_port;

    /**
     * @var string
     */
    public $mysql_user;

    /**
     * @var string
     */
    public $mysql_pass;

    /**
     * @var string
     */
    public $mysql_name;

    /**
     * Зарпещенные символы в пароле
     * @var array
     */
    private $_mysql_pass_denied_chars = array("'", "\\");



    public function rules()
    {
        return array(
            array('mysql_host, mysql_port, mysql_user, mysql_pass, mysql_name', 'filter', 'filter' => 'trim'),
            array('mysql_host, mysql_port, mysql_user, mysql_name', 'required'),
            array('mysql_port', 'numerical', 'integerOnly' => TRUE),
            array('mysql_pass', 'checkPassChars'),
            array('mysql_pass', 'checkConnect'),
            array('mysql_pass', 'checkFileIsWritable'),
        );
    }

    /**
     * Пороверка файла database.php на запись
     *
     * @param string $attribute
     * @param array $params
     */
    public function checkFileIsWritable($attribute, array $params)
    {
        if(!$this->hasErrors())
        {
            if(!HTML::isWritable(Yii::getPathOfAlias('application.config') . DIRECTORY_SEPARATOR . 'database.php'))
            {
                $this->addError($attribute, Yii::t('install', 'Необходимо дать файлу protected/config/database.php права на запись 0777'));
            }
        }
    }

    public function checkPassChars($attribute)
    {
        if($this->mysql_pass != '')
        {
            foreach($this->_mysql_pass_denied_chars as $char)
            {
                if(strpos($this->mysql_pass, $char) !== FALSE)
                {
                    $this->addError($attribute, Yii::t('install', 'В пароле не должно быть <b>:char</b> символа', array(':char' => $char)));
                }
            }
        }
    }

    public function checkConnect($attribute)
    {
        if(!$this->hasErrors())
        {
            try
            {
                $db = new PDO('mysql:host=' . $this->mysql_host . ';port=' . $this->mysql_port . ';dbname=' . $this->mysql_name, $this->mysql_user, $this->mysql_pass);

                // Проверка таблиц с префиксом ghtweb_
                $res = $db->prepare('SHOW TABLES FROM ' . $this->mysql_name);
                $res->execute();

                $tables = array();

                foreach($res->fetchAll(PDO::FETCH_COLUMN) as $table)
                {
                    if(strpos($table, 'ghtweb_') !== FALSE)
                    {
                        $tables[] = $table;
                    }
                }

                if($tables)
                {
                    $this->addError($attribute, Yii::t('install', 'Из БД :name надо удалить следующие таблицы: :tables', array(
                        ':name' => '<b>' . $this->mysql_name . '</b>',
                        ':tables' => '<b>' . implode(', ', $tables) . '</b>',
                    )));
                }

                $db = NULL;
            }
            catch(PDOException $e)
            {
                $this->addError($attribute, $e->getMessage());
            }
        }
    }

    public function attributeLabels()
    {
        return array(
            'mysql_host' => Yii::t('install', 'MYSQL host'),
            'mysql_port' => Yii::t('install', 'MYSQL port'),
            'mysql_user' => Yii::t('install', 'MYSQL user'),
            'mysql_pass' => Yii::t('install', 'MYSQL pass'),
            'mysql_name' => Yii::t('install', 'MYSQL name'),
        );
    }
}
 
<?php

// Проверка установлена ли CMS
if(is_dir(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'protected' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'install') && strpos($_SERVER['REQUEST_URI'], 'install') === FALSE)
{
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/install/');
    exit;
}
// ---------------

$yii = dirname(__FILE__) . '/../framework/yii.php'; // Путь к папке с framework

if(in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1')))
{
    error_reporting(-1);

    define('YII_DEBUG', TRUE);
    define('YII_TRACE_LEVEL', 1); // Запись в лог имени файла и номера строки

    $config = dirname(__FILE__) . '/protected/config/main-dev.php';
}
else
{
    error_reporting(0);

    define('YII_DEBUG', FALSE);

    $config = dirname(__FILE__) . '/protected/config/main.php';
}

require_once dirname(__FILE__) . '/protected/helpers/global.php';

require_once $yii;
Yii::createWebApplication($config)->run();


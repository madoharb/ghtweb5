<?php

/**
 * This is the configuration for generating message translations
 * for the Yii framework. It is used by the 'yiic message' command.
 */
return array(
    'sourcePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..',
    'messagePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'messages',
    'languages' => array('en', 'ru'),
    'fileTypes' => array('php'),
    'overwrite' => FALSE,
    'removeOld' => TRUE,
    'sort' => TRUE,
    'exclude' => array(
        '.svn',
        '.gitignore',
        'yiilite.php',
        'yiit.php',
        '/assets',
        '/images',
        '/framework',
        '/uploads',

        '/protected/data',
        '/protected/l2j',
        '/protected/messages',
        '/protected/modules/install',
        '/protected/runtime',
        '/protected/tests',
        '/protected/vendors',
    ),
);
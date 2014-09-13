<?php

return array(
	'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
	'name' => 'GHTWEB Console',

	// preloading 'log' component
	'preload' => array('log'),

    'commandMap' => array(
        'migrate' => array(
            'class' => 'system.cli.commands.MigrateCommand',
            //'migrationPath' => 'application.migrations',
            'migrationTable' => 'ghtweb_migration',
            //'connectionID' => 'db',
            //'templateFile' => 'application.migrations.template',
        ),
    ),

	// application components
	'components' => array(

        'db' => require 'database.php',

		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
			),
		),
	),
);
<?php

return CMap::mergeArray(
    require dirname(__FILE__) . '/main.php',
    array(
        'modules' => array(

            'gii' => array(
                'class'             => 'system.gii.GiiModule',
                'password'          => '123456',
                // If removed, Gii defaults to localhost only. Edit carefully to taste.
                'ipFilters'         => array('127.0.0.1','::1'),
                'generatorPaths'    => array(
                    'ext.gii'
                ),
            ),

        ),
        'components' => array(
            'cache' => array(
                'class' => 'system.caching.CDummyCache',
            ),
            'log' => array(
                'routes' => array(
                    array(
                        'class' => 'CWebLogRoute',
                        'levels' => 'error, warning, trace, notice',
                        'categories' => 'application',
                        'enabled' => TRUE,
                    ),
                    array(
                        'class' => 'CProfileLogRoute',
                        'levels' => 'profile',
                        'enabled' => TRUE,
                    ),
                ),
            ),
        ),
        'params' => array(

        ),
    )
);

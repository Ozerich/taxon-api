<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Taxon API',
    'language' => 'ru',

    'import' => array(
        'application.models.*',
        'application.components.*',
    ),

    'modules' => array(
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'admin',
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
    ),

    'components' => array(

        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(

                'gii' => 'gii',
                'gii/<controller:\w+>' => 'gii/<controller>',
                'gii/<controller:\w+>/<action:\w+>' => 'gii/<controller>/<action>',

                'register' => 'site/register',
                'test' => 'test/index',
                'admin' => 'admin/index',

                '<page:\w+>' => 'site/static/page/<page>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),

        'db' => require_once(DB_CONFIG),

        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
    ),

    'params' => array(
        'api_url' => strpos($_SERVER['SERVER_ADDR'], '127') === 0 ? 'http://taxon/api/' : 'http://taxon.ozis.by/api/',
        'admin_password' => 'admin'
    ),
);
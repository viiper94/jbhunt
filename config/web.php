<?php

use yii\web\UrlNormalizer;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'PGsjfdhwbLwhgdfJUFJNsjss_024jGlgerjajkj',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
                // use temporary redirection instead of permanent for debugging
                'action' => UrlNormalizer::ACTION_REDIRECT_TEMPORARY,
            ],
            'rules' => [
                '' => 'site/index',

                // achievements
                'achievements/page/<page:\d+>/' => 'achievements/index',
                'achievements' => 'achievements/index',
                'achievements/<action:\w+>/<id:\d+>/<operation:\w+>' => 'achievements/<action>',
                'achievements/<action:\w+>/<id:\d+>' => 'achievements/<action>',
                'achievements/<action:\w+>' => 'achievements/<action>',

                // appeals
                'appeals/page/<page:\d+>/' => 'appeals/index',
                'appeals' => 'appeals/index',
                'appeals/<action:\w+>/<id:\d+>/' => 'appeals/<action>',
                'appeals/<action:\w+>' => 'appeals/<action>',

                // claims
                'claims/<action:\w+>/<claim:\w+>/<id:\d+>/' => 'claims/<action>',
                'claims/<action:\w+>/<claim:\w+>/' => 'claims/<action>',
                'claims' => 'claims/index',

                // convoys
                'convoys/<id:\d+>/' => 'convoys/index',
                'convoys' => 'convoys/index',
                'convoys/<action:\w+>/<id:\d+>/' => 'convoys/<action>',
                'convoys/<action:\w+>/<game:\w+>/' => 'convoys/<action>',
                'convoys/<action:\w+>/' => 'convoys/<action>',

                //gallery
                'gallery/sort/<operation:\w+>/<id:\d+>/' => 'gallery/sort',
                'gallery/<action:\w+>/<id:\d+>/' => 'gallery/<action>',
                'gallery/upload' => 'gallery/upload',
                'gallery' => 'gallery/index',

                // members
                'members' => 'members/index',
                'members/<action:\w+>/<id:\d+>/<dir:\w+>/' => 'members/<action>',
                'members/<action:\w+>/<id:\d+>/' => 'members/<action>',
                'members/<action:\w+>/' => 'members/<action>',

                // mods
                'modifications/<game:\w{3}>/<category:\w+>/<subcategory:\w+>/page/<page:\d+>/' => 'modifications/index',
                'modifications/<game:\w{3}>/<category:\w+>/<subcategory:\w+>/' => 'modifications/index',
                'modifications/<game:\w{3}>/<category:\w+>/' => 'modifications/index',
                'modifications/add/' => 'modifications/add',
                'modifications/<game:\w{3}>/' => 'modifications/index',
                'modifications/page/<page:\d+>/' => 'modifications/index',
                'modifications/<action:\w+>/<id:\d+>/' => 'modifications/<action>',
                'modifications' => 'modifications/index',
                'modifications/<action:\w+>/' => 'modifications/<action>',

                // news
                'news/<id:\d+>/<action:\w+>/' => 'site/news',
                'news/<id:\d+>/' => 'site/news',
                'news/<action:\w+>/' => 'site/news',

                // profile
                'profile/<id:\d+>/' => 'site/profile',
                'profile/<action:\w+>/' => 'site/profile',

                // rules
                'rules/<action:\w+>/' => 'site/rules',

                // trailers
                'trailers/add' => 'trailers/add',
                'trailers/getinfo' => 'trailers/getinfo',
                'trailers/page/<page:\d+>/<game:\w{3}>/<category:\w+>/' => 'trailers/index',
                'trailers/page/<page:\d+>/<game:\w{3}>' => 'trailers/index',
                'trailers/page/<page:\d+>/<category:\w+>/' => 'trailers/index',
                'trailers/page/<page:\d+>/' => 'trailers/index',
                'trailers/<game:\w{3}>/<category:\w+>/' => 'trailers/index',
                'trailers/<game:\w{3}>/' => 'trailers/index',
                'trailers/<category:\w+>/' => 'trailers/index',
                'trailers/<action:\w+>/<id:\d+>/' => 'trailers/<action>',
                'trailers' => 'trailers/index',
                'trailers/<action:\w+>/' => 'trailers/<action>',

                // variations
                'variations/edit/' => 'variations/edit',
                'variations/<game:\w+>/' => 'variations/index',
                'variations/<action:\w+>/' => 'variations/<action>',

                // general
                '<action:\w+>/<id:\d+>/' => 'site/<action>',
                '<action>/page/<page:\d+>/' => 'site/<action>',
                '<action>' => 'site/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>'
            ],
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;

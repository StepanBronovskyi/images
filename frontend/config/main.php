<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'modules' => [
        'user' => [
            'class' => 'frontend\modules\user\Module'

        ],
        'post' => [
            'class' => 'frontend\modules\post\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-frontend',
            'enableCsrfValidation' => false,
        ],
        'user' => [
            'identityClass' => 'frontend\modules\user\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'advanced-frontend',
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
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<nickname:\w+>' => 'user/profile/view',
                'post/<id:\d+>' => 'post/default/view',
                'complaint/add/<id:\d+>' => 'complaint/add',
            ],
        ],
        'stringHelper' => [
            'class' => 'common\components\StringHelper',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '196530061109782',
                    'clientSecret' => '0982a6c625e15fa48effec45ad077d2f',
                ],
            ],
        ],
        'redis' => [
            'class'      => 'yii\redis\Connection',
            //'unixSocket' => '/var/run/redis/redis.sock',
            'unixSocket' => null,
            'hostname' => 'localhost',
            'port' => 6379,
            'database'   => 0,
        ],
        'storage' => [
            'class' => 'frontend\components\Storage',
        ],
        'resize' => [
            'class' => 'frontend\components\PictureResize',
        ],
        'feedService' => [
            'class' => 'frontend\components\FeedService',
        ],
    ],
    'params' => $params,
];

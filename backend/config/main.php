<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'language' => 'zh-CN',
    'modules' => [
        'admin' => [
            'class' => 'izyue\admin\Module',
//            'layout' => 'left-menu',
            'layout' => '@app/views/layouts/main.php',
        ],
        'sysManage' => [
            'class' => 'backend\modules\sysManage\Module'
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\AdminModel',
            'enableAutoLogin' => true,
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
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\PhpManager'
        ],
        'i18n' => [
            'translations' => [
                'common' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '/messages',
                    'fileMap' => [
                        'common' => 'common.php',
                    ],
                ],
                'login' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '/messages',
                    'fileMap' => [
                        'login' => 'login.php',
                    ],
                ],
                'signup' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '/messages',
                    'fileMap' => [
                        'admin' => 'sginup.php',
                    ],
                ],
                'admin' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '/messages',
                    'fileMap' => [
                        'admin' => 'admin.php',
                    ],
                ],
            ],
        ],
//        'urlManager' => [
//            'enablePrettyUrl' => true,
//            'showScriptName' => false,
//            'suffix' => '.html',
//            'rules'=>[
//            ],
//        ],

        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],

    ],

    // dev env

//    'as access' => [
//        'class' => 'izyue\admin\components\AccessControl',
//        'allowActions' => [
//            'debug/*',
//            'site/*',
//            'gii/*',
//            'menu/*',
//            'test/*',
//            'arraytest/*',
//            'filetest/*',
//            'admin/*',
//            'admin/auto/*',
//            'sysManage/*'
////            'admin/*',
//            // The actions listed here will be allowed to everyone including guests.
//            // So, 'admin/*' should not appear here in the production, of course.
//            // But in the earlier stages of your development, you may probably want to
//            // add a lot of actions here until you finally completed setting up rbac,
//            // otherwise you may not even take a first step.
//        ]
//    ],
    'params' => $params,

    'timeZone' => 'Etc/GMT-8',
];

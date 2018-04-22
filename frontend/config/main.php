<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    //应用ID
    'id' => 'app-frontend',
    //目录
    'basePath' => dirname(__DIR__),
    //引导组件
    'bootstrap' => ['log'],
    //语言
    'language' => 'zh-CN',
    //设置源语言为英语
    'sourceLanguage' => 'en-US',
    //命名空间
    'controllerNamespace' => 'frontend\controllers',
    //默认路由
    'defaultRoute' => 'site/index',
    //模块
    'modules' => [
        'api' => [
            'class' => 'frontend\modules\api\api',
        ],
        'ios' => [
            'class' => 'frontend\modules\ios\Ios',
        ],
        'cron' => [
            'class' => 'frontend\modules\cron\Module',
        ],
        'task' => [
            'class' => 'frontend\modules\task\Module',
        ],
        'redactor' => [
            'class' => 'yii\redactor\RedactorModule',
            'uploadDir' => '@webroot/upload',
            'uploadUrl' => '@web/upload',
            'imageAllowExtensions'=>['jpg','png','gif']
        ],
    ],
    //组件
    'components' => [
        //授权用户
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
        ],
        //日志
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'trace'], // 'warning', 'trace', 'info' , 'profile'
//                     'categories' => [
//                         'yii\db\*',
//                         'yii\web\HttpException:*',
//                         'test',
//                     ],
                ],
            ],
        ],
        //错误事件处理
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        //Session
        'session' => [
            //php session
            'class' => 'yii\web\Session',

            //db storage
//             'class' => 'yii\web\DbSession',
//             'sessionTable' => 'my_session',
            
            //redis storage
//            'name' => 'advanced-frontend',
//            'class' => 'yii\redis\Session',
//            'redis' => [
//                 'hostname' => 'localhost',
//                 'port' => 6379,
//                 'database' => 0,
//             ],
        ],
        
        //url解析
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,  //不启用严格解析
            'rules' => [
//                 'pattern' => 'post/<page:\d+>/<tag>',
//                 'route' => 'post/index'
//                 'posts' => 'post/index',
//                 'post/<id:\d+>' => 'post/view',
//               '/tools/<id:\d+>' => '/tools/view',                
                // 路由/tools/local/100 映射到 /tools/local?id=100
                // 注意：不要在第一个路由写id, 有时候傻逼了 /tools/local/id/100 这样好难看
                // id是作为操作(action)的参数 
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ],
        ],
        //国际化
        'i18n' => [
            'translations' => [
                'part*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '/messages',
                    'fileMap' => [
                        '*' => 'part.php',
                        //'part/error' => 'error.php',
                    ],
                ]
            ],
        ],
        //格式化 Formatter
        'formatter' => [
            'dateFormat' => 'dd.MM.yyyy',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'CNY',
        ],
        //主题
//         'view' => [
//             'theme' => [
//                 'basePath' => '@app/themes/basic',
//                 'baseUrl' => '@web/themes/basic',
//                 'pathMap' => [
//                     '@app/views' => '@app/themes/basic',
//                 ],
//             ],
//         ],
        
    ], // ----------- //end components -------------------------------
    
    //默认时区
    'timeZone' => 'Etc/GMT-8', //PRC //http://blog.csdn.net/fjnu2008/article/details/7700107
    //开启维护模式 全拦截路由
//     'catchAll' => [
//         'offline/notice',
//     ],
    
    //参数配置
    'params' => $params,
];

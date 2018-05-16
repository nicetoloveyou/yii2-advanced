<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            // 'class' => 'yii\caching\FileCache',
            // 用 redis 来存储缓存
            'class' => 'yii\redis\Cache',
        ],
    ],
];

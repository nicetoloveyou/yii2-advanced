<?php
Yii::setAlias('@common', dirname(__DIR__));
Yii::setAlias('@helpers', dirname(dirname(__DIR__)) . '/helpers');
Yii::setAlias('@frontend', dirname(dirname(__DIR__)) . '/frontend');
Yii::setAlias('@api', dirname(dirname(__DIR__)) . '/api');
Yii::setAlias('@front_public', '@frontend/views/public');
Yii::setAlias('@backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('@console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('@components', dirname(dirname(__DIR__)) . '/components'); //dirname(dirname(__DIR__)) 注意此用法

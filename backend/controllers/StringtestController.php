<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/29 0029
 * Time: 22:33
 */

namespace backend\controllers;
use yii;
use yii\web\Controller;
use yii\helpers\StringHelper;
use helpers\Dump;

class StringtestController extends yii\web\Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    public function actionIndex()
    {

        $string = '为什么呢这样的世界如何';
        //$result = StringHelper::byteSubstr($string, 0, 10);
        //$result = mb_substr($string,0, 5);
        //$result = StringHelper::truncateWords($string, 2,'');
        $result = StringHelper::normalizeNumber(111.00036555);

        Dump::dump($result);

    }




























}
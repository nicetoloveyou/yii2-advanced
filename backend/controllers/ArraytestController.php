<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21 0021
 * Time: 23:21
 */

namespace backend\controllers;
use yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;


class ArraytestController extends yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@', '?'],
                    ],
                ],
            ]
        ];
    }


    public function actionIndex()
    {
        echo 'index';
    }





















}
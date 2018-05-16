<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/14 0014
 * Time: 04:31
 */

namespace backend\controllers;

use Yii;
use yii\web\Controller;
use helpers\Dump;
use backend\models\AuthItem;


class MongoController extends Controller
{

    public function actionIndex()
    {
        //Dump::dump(Yii::$app->mongodb);
        //echo 'hello';
        Yii::beginProfile('begin', 'mongodb');
        $item = AuthItem::find()->select('*')->asArray()->one();

        var_dump($item);


        Yii::endProfile('end', 'mongodb');
    }
























}
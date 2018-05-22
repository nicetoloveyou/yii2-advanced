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
use helpers\Helper;
use backend\models\AuthItem;
use yii\mongodb\Connection;

class MongoController extends Controller
{

    public function actionIndex()
    {
        //Dump::dump(Yii::$app->mongodb);
        //echo 'hello';
        //Yii::beginProfile('begin', 'mongodb');
        //$item = AuthItem::find()->select('*')->asArray()->one();
        //Yii::endProfile('end', 'mongodb');

        //Dump::dump(Yii::$app->mongodb);

        //$dsn = 'mongodb://@localhost:27017/local';
        //$connection = new Connection(['dsn' => $dsn]);

        $className = 'backend\controllers\TestController';
        $class = new \ReflectionClass($className);
        $method = $class->getMethod('actionIndex');
        $comment = Helper::getComment($method);

        var_dump($comment); die();
    }
























}
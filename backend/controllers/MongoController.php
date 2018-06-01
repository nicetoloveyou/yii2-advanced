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
use backend\models\TotalForm;

class MongoController extends Controller
{

    public function actionIndex()
    {
        //Dump::dump(Yii::$app->mongodb);
        //echo 'hello';
        //Yii::beginProfile('begin');
        //$item = AuthItem::find()->select('*')->asArray()->one();
        //Yii::endProfile('end', 'mongodb');

        //Dump::dump(Yii::$app->mongodb);

        //$dsn = 'mongodb://root:root@localhost:27017/admin';
        //$connection = new Connection(['dsn' => $dsn]);

        //$result = $connection->getCollection('articles')->findOne();

        //$query = new \MongoDB\Driver\Query;

        //$result = $connection->getDatabase('admin')->getCollection('articles')->findOne();

        //Dump::dump($result);

        //phpinfo();
        //echo $_SERVER['SERVER_SOFTWARE'];

        die();
    }


    public function actionTest()
    {
//        $sql = "Select * from admin Where username !='' And :where limit 10";
//        $cmd = Yii::$app->db->createCommand($sql);
//        //$cmd->bindParam(':where', $where);
//        $cmd->bindValues([':where' => ' 1=1 ']);
//
//        $sql = str_replace(':where', ' id=:id ', $sql);
//        $cmd = Yii::$app->db->createCommand($sql);
//        $cmd->bindValues([':id' => 1 ]);
//
//        $data = $cmd->queryAll();
//        var_dump($cmd->getRawSql());

//       $model = \backend\models\Admin::find()->where();
//        var_dump($model);


    }





















}
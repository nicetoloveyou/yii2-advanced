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
use backend\components\SearchModel;
use yii\db\Query;


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
        Yii::$app->center->callback = 9999999;
        var_dump(Yii::$app->center);
        die();
        $cc = 1000;
        $func = function($d) use ($cc) {
            var_dump($d, $cc);
        };
        if($func instanceof  \Closure) {
            $func('hhhhhh');
        }
    }

    public function actionTest2()
    {
        $model = new SearchModel;
        $attribute = [
            //'query' => new Query,
            'where' => ' 1=1 ',
            'orderBy' => ' id Desc ',
            'groupBy' => ' stdate ',
            'limit' => 10,
        ];
        //$load = $model->load($attribute, '');
        $model->setAttributes($attribute, false);
        var_dump($model);
    }


    public function actionTest3()
    {
        $rows = [
            ['a' => 1, 'b'=> 2, 'c' => 3],
            //'d' =>['1111']  // 非索引数组, json 会转换成 对象json
        ];
        $rows = array_merge($rows, [['a' => 5, 'b'=> 7, 'e' => 511]]);

        $data = [
           'table1' => [
               'rows' => $rows
           ],
       ];
        //Yii::$app->response->data = $data;
        //Yii::$app->response->send();

        //return $data;

        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


















}
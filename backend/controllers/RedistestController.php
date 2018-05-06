<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/30 0030
 * Time: 23:32
 */

namespace backend\controllers;

use yii;
use yii\web\Controller;

class RedistestController extends Controller
{

    public function actionIndex()
    {

        var_dump(Yii::$app->getTimeZone());
        var_dump(date('Y-m-d H:i:s', time()));

        die();
        //date_default_timezone_set('Asia/Chongqing');

        var_dump((gmdate('D, d M Y H:i:s T')));
        var_dump(mktime(16, 23, 30, 5, 3, 2018));

        echo '<br/>';
        echo date('Y-m-d H:i:s', 1525364610);

        die();


        date_default_timezone_set('ETC/GMT-8');

        var_dump((gmdate('D, d M Y H:i:s T')));
        // var_dump(time());
        echo '<br/>';
        var_dump(date('Y-m-d H:i:s', strtotime(gmdate('D, d M Y H:i:s T'))));
        echo '<br/>';
        // time 会根据你设置的时区生成
        var_dump(date('Y-m-d H:i:s', time()));

        die();

        echo 'redis index';
    }

    public function actionTest()
    {
        //$model = new \backend\models\Menu();
        $model =  \backend\models\Menu::find()->one();

        var_dump($model->data);
    }




















}
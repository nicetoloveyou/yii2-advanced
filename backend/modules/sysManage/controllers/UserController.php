<?php


namespace backend\modules\sysManage\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\VarDumper;
use common\models\AdminModel;
use helpers\Dump;


class UserController extends Controller
{

    public function actionIndex()
    {
        $auth = Yii::$app->authManager;
        $user = Yii::$app->getUser();
        $user_list = AdminModel::find()->asArray()->all();


        Dump::dump($user_list);
    }

























}
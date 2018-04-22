<?php

namespace frontend\controllers;
use yii;

/**
 * Api restful 应用:
 *    应该建立一个新的应用 与 frontend 分开，因为输出配置为json 或 xml 格式，还有一些独立特殊的配置。
 *    为了方便维护你的WEB前端和后端，建议你开发接口作为一个单独的应用程序
 *  
 * @see http://www.yiichina.com/doc/guide/2.0/rest-quick-start
 * @see http://www.yiichina.com/tutorial/843
 * 
 * @author Administrator
 *
 */

class RestController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\TestUser';
    
    public function init()
    {
        parent::init();
        
        //Yii::$app->response->format = yii\web\Response::FORMAT_JSON;
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }

}

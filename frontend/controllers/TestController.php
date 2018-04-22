<?php

namespace frontend\controllers;

use Yii;

class TestController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $event = new \yii\base\Event;
        // 触发相关事件
        // $component->trigger($eventName, $event);
        // 要给事件附加一个事件事件处理器，需要使用 on() 方法：
        // $component->on($eventName, $handler);
        // 解除事件处理器，使用 off 方法：
        // $component->off($eventName, $handler);
        return $this->render('index');
    }

    public function actionTry()
    {
        $arr = [
            ['username', 'string', 'max' => 100, 'message' =>'cantt nod dod']

        ];
        $var = var_export($arr, true);
        // file_put_contents(dirname(__DIR__) . '/data.txt', $var);
        $d = array (
            0 =>
                array (
                    0 => 'username',
                    1 => 'string',
                    'max' => 100,
                    'message' => 'cantt nod dod',
                ),
        );

        var_dump($d);
    }
}

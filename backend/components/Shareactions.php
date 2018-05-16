<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/12 0012
 * Time: 22:28
 */

namespace backend\components;

use Yii;
use yii\base\Action;

/**
 * 公共共享方法:
 *
 * 在控制器中遮掩声明 actions
 *
 * public function actions()
 * {
 *  return [
 *      'hi' => [
 *          'class' => 'backend\components\Shareactions'
 *      ],
 *  ];
 * }
 *
 * Class Shareactions
 * @package backend\components
 *
 */

class Shareactions extends Action
{

    public function run()
    {
        echo "share action run ...";
    }
}
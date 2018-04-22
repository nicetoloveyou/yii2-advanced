<?php
namespace frontend\components;

use yii\base\Behavior;

/**
 * 组件行为
 * HelloWidget 有测试用例
 * @author Administrator
 *        
 */
class MyBehavior extends Behavior
{
    // TODO - Insert your code here
    
    public $prop1;
    
    
    public $prop2;
    
    /**
     */
    function __construct()
    {
        
        // TODO - Insert your code here
    }
    
    
    function what()
    {
        echo 'what ?' ;
        return '';
    }
    
    /**
     */
    function __destruct()
    {
        
        // TODO - Insert your code here
    }
}

?>
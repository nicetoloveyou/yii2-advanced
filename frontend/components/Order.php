<?php
namespace frontend\components;

use yii\base\Component;


/**
 * 订单类：主要用于测试事件
 *
 * @author Administrator
 *        
 */
class Order extends Component
{
    public $userid;
    
    public $id;
    
    const EVENT_ORDER_UPDATE = 'update';
    /**
     * init: 
     * @see \yii\base\Object::init()
     */
    function init()
    {
        parent::init();
        //事件绑定处理器
        //$this->on(self::EVENT_ORDER_UPDATE, ['frontend\components\OrderEvent', 'notify']);
    }
    
    /**
     * 更新
     */
    public function update()
    {
        //$this->trigger(\frontend\components\Order::EVENT_ORDER_UPDATE);
    }
}

?>
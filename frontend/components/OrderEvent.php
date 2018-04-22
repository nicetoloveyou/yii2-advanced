<?php
namespace frontend\components;

use yii;
use yii\base\Event;

/**
 *
 * @author Administrator
 *        
 */
class OrderEvent extends Event
{
    public $userId;
    
    public static function notify($event)
    {
        Yii::trace('I have recieved message yet !' . $event->userId, 'notify');
    }
}

?>
<?php
namespace frontend\components;

use yii\base\Widget;

/**
 *
 * @author Administrator
 *        
 */
class ListWidget extends Widget
{
    // TODO - Insert your code here
    public $items = [];
    
    public function run()
    {
        return $this->render('list', [
            'items' => $this->items,
        ]);
    }
    
    public function getViewPath()
    {
        return '@frontend/views/widget';
    }
    
    /**
     */
    function __destruct()
    {
        
        // TODO - Insert your code here
    }
}

?>
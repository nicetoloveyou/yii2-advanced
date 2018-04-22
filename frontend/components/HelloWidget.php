<?php
namespace frontend\components;

use yii\base\Widget;
use yii\helpers\Html;
/**
 *
 * @author Administrator
 *        
 */
class HelloWidget extends Widget
{
    // TODO - Insert your code here
    
    /**
     *
     * @param array $config
     *            name-value pairs that will be used to initialize the object properties
     *            
     * @return string the rendering result of the widget.
     *        
     * @throws \Exception
     *
     */
    public function init()
    {
        parent::init();
        ob_start();
    }
    public function run()
    {
        $content = ob_get_clean();
        return Html::encode($content);
    }
    
    public function getViewPath()
    {
        return '@frontend/views/widget';
    }
    
    /**
     * 使用行为
     * @see \yii\base\Component::behaviors()
     */
    public function behaviors()
    {
        return [
            'mybehavior' => [
                'class' => 'frontend\components\mybehavior',
                'prop1' => 100,
                'prop2' => 200,
            ],
        ];
    }
    
    // 如果要让行为响应对应组件的事件触发， 就应覆写 yii\base\Behavior::events() 方法
    public function events()
    {
        return [
            \yii\db\ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
        ];
    }

    public function beforeValidate($event)
    {
        // 处理器方法逻辑
    }
    
    
    /**
     */
    function __destruct()
    {
        
        // TODO - Insert your code here
    }
}

?>
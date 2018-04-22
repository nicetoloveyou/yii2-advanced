<?php

namespace frontend\modules\api;

/**
 * api module definition class
 */
class api extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\api\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        // $this->params = [];
        // $this->params = \Yii::configure($this, require(__DIR__ . '/config.php'));
    }
}

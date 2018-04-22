<?php

namespace backend\modules\sysManage;

/**
 * 系统模块
 * @package backend\modules\sysManage
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\sysManage\controllers';

    public $layout = false;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}

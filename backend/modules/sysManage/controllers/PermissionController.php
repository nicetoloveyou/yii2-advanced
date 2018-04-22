<?php

namespace backend\modules\sysManage\controllers;

use backend\modules\sysManage\components\ItemController;
use yii\rbac\Item;

/**
 * PermissionController implements the CRUD actions for AuthItem model.
 *
 */
class PermissionController extends ItemController
{
    public $type = Item::TYPE_PERMISSION;

}

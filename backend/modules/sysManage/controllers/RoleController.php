<?php

namespace backend\modules\sysManage\controllers;

use backend\modules\sysManage\components\ItemController;
use yii\rbac\Item;

/**
 * RoleController implements the CRUD actions for AuthItem model.
 *
 */
class RoleController extends ItemController
{
    public $type = Item::TYPE_ROLE;

}

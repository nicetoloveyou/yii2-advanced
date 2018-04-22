<?php

namespace backend\modules\sysManage\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\VarDumper;
use common\models\AdminModel;
use helpers\Dump;

/**
 * Default controller for the `sysManage` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $permit = $auth->getPermissions();
        $assign = $auth->getAssignments(1);
        $canAccess = $auth->checkAccess(1, '角色管理');

        $user = Yii::$app->user;
        $user_id = Yii::$app->user->getId();
        $user_role = $auth->getRolesByUser($user_id);
        // permission also assign available routes too ...
        $user_permission = $auth->getPermissionsByUser($user_id);

        // all routes
        $routes = [];

        $arr = ['j' => 100, 'd' => 'alibaba', 15 => 'ddddd'];
        $arr = array_flip($arr);


        Dump::dump($arr);
        //Dump::dump($permit);
        //Dump::dump($assign);

    }
}

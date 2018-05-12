<?php
/**

 * Created by PhpStorm.
 * User: chrispaul
 * Date: 2018/4/23
 * Time: 10:27
 */

namespace console\controllers;

use yii\Console\Controller;
use Yii;
use app\modules\sysmanage\components\Helper;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use app\modules\sysmanage\models\AuthItem;
use app\modules\sysmanage\models\AuthItemChild;
use yii\rbac\Item;
use yii\helpers\StringHelper;

/**
 * @description 将路由生成权限: 只需要运行 makeitem 可执行文件即可
 *
 * @hint 注意: 前端VUE定义的路由命名必须与自动生成的模块路由一致，详见数据库auth_item 表 path 字段
 *
 * Class MakeitemController
 *
 * @package app\modules\cron\controllers
 */
class MakeitemController extends Controller
{
    public function actionIndex()
    {
        echo 'index';
    }

    public $type = Item::TYPE_PERMISSION;

    /**
     * execute 之前先从数据库中删除 item 项
     *
     * @throws \yii\db\Exception
     */
    protected function deleteItem()
    {
        $delete = " Delete from auth_item Where type='{$this->type}' ";
        Yii::$app->db->createCommand($delete)->execute();
    }

    const PREFIX = '/';

    /**
     * @description 将路由生成权限
     *
     * @throws
     */
    public function actionExecute()
    {
        $this->deleteItem();

        $routes = $this->getAppRoutes();
        unset($routes['basic-console']);
        // var_dump($routes); die();

        foreach ($routes as $key => $route) {
            $module = [];
            if ($route['id']) {
                $module['name'] = $route['id'];
                $module['path'] = self::PREFIX . $route['id'];
                $ns = str_replace('controllers\\', '' ,$route['ns']). 'Module';
                $class = new \ReflectionClass($ns);
                $module['comment'] = Helper::getComment($class);

                // save module as auth item
                if($module['comment']) {
                    echo "\r\n save module as AuthItem \r\n";
                    $this->saveItem($module['comment'], 1, $module['path']);

                    // save all permissions to admin
                    $this->saveToAdmin($module['comment']);
                }
            }
            // save controllers as auth item
            $controllers = $route['controllers'];
            $this->saveControllersItem($controllers, $module);
        }

        die('---end---');
    }

    /**
     * @param $controllers
     * @param array $module
     * @throws \ReflectionException
     */
    protected function saveControllersItem($controllers, $module = [])
    {
        echo "\r\n save controller as AuthItem \r\n";

        foreach( $controllers as $key => $control ){

            $controller = [];
            $className = $control['className'] ?? '' ;
            if( !$className ) continue;

            $control_class = new \ReflectionClass($className);
            $controller['comment'] = Helper::getComment($control_class);

            // save controller as auth item
            if ($controller['comment']) {
                $path = self::PREFIX . ($module['name'] ?? '') . self::PREFIX . $key;
                $this->saveItem($controller['comment'], 2, $path);
                // build auth parent --> child relation [module ---> controller ]...
                if ($module) {
                    $this->saveItemChild($module['comment'], $controller['comment']);
                }
                // save controller actions as auth item ...
                $actions = ($control['actions']) ?? [];
                $this->saveActionsItem($actions, $control_class, $controller, $module);
            }
        }
    }

    /**
     * @param $actions
     * @param $control_class
     * @param $controller
     * @param string $module
     */
    protected function saveActionsItem($actions, $control_class, $controller, $module = '')
    {
        echo "\r\n save controller actions as AuthItem \r\n";

        foreach( $actions as $m => $url ) {
            if ($m === '*') continue;

            $action = [];
            $method = $control_class->getMethod('action'.str_replace(' ', '', ucwords($m)));
            $action['comment'] = Helper::getComment($method);
            $action['url'] = (self::PREFIX . $module['name'] ?? '') . $url;

            // save action and route url as auth item
            if ($action['comment']) {
                $this->saveItem($action['comment']);
                $this->saveItem($action['url']);
                // build controller ---> action : parent->child
                $this->saveItemChild($controller['comment'], $action['comment']);
                // build action ----> url : parent->child
                $this->saveItemChild($action['comment'], $action['url']);
            }
            else {
                // var_dump($action);die();
            }
        }
    }

    /**
     * 默认将把所有权限赋予 administrator 角色
     *
     * @param $child
     * @param string $parent
     */
    protected function saveToAdmin($child, $parent = 'administrator')
    {
        $this->saveItemChild($parent, $child, Item::TYPE_ROLE);
    }

    /**
     * Save item
     * @param $comment
     * @return string
     */
    protected function saveItem($comment, $ismodule = 0, $path = '')
    {
        if (!$comment ) return '';
        $model = AuthItem::findOne(['name' => $comment]);

        if (empty($model)) {
            $model = new AuthItem();
            $model->name = $comment;
            $model->type = 2;
            $model->ismodule = $ismodule;
            $model->path = $path;

            if (!$model->save()) {
                var_dump($model->getErrors());
                die();
            }
            echo " save item '{$comment}' item succeed ...\n ";
        }
        else {
            echo " the item '{$comment}' is existed ... \n ";
        }

    }

    protected function saveItemChild($parent, $child, $type = Item::TYPE_PERMISSION)
    {
        if (!$parent || !$child) return '';
        $model = AuthItemChild::findOne(['parent' => $parent, 'child' => $child]);

        if (empty($model)) {
            $model = new AuthItemChild();
            $model->parent = $parent;
            $model->child = $child;
            $model->auth_type = $type;
            $model->create_at = time();

            if (!$model->save()) {
                var_dump($model->getErrors());
                die();
            }
            echo " build '{$parent} --> {$child}' item child succeed ...\n ";
        }
        else {
            echo " the item_child '{$parent} --> {$child}' is existed ... \n ";
        }

        return $model;
    }


    /**
     * 获取应用所有路由
     *
     * 注意：由于本controller是继承console 也就是 console App, 所以不会自动读到web模块路由，需要在console.php中手动配置模块
     *
     * @return array
     */
    public function getAppRoutes()
    {
        $result = [];
        $this->getRouteRecrusive(Yii::$app, $result);

        return $result;
    }

    /**
     * 排除模块：不会添加到权限
     *
     * @var array
     */
    protected $excludeModules = [
        // 'basic-console',
        'gii',
        'cron',
        'vii'
    ];

    /**
     * 递归获取路由
     *
     * 注意： console 下不能使用 module->uniqueId, 用module->id 代替
     * @param \yii\base\Module $module
     * @param array $result
     * @return array
     */
    private function getRouteRecrusive($module, &$result)
    {
        $token = "Get Route of '" . get_class($module) . "' with id '" . $module->uniqueId . "'";
        Yii::beginProfile($token, __METHOD__);
        try {
            foreach ($module->getModules() as $id => $child) {
                // echo $module->uniqueId . "\n";
                if(in_array($module->id, $this->excludeModules)) continue;

                if (($child = $module->getModule($id)) !== null) {
                    $this->getRouteRecrusive($child, $result);
                }
            }
            // exclude
            if(in_array($module->id, $this->excludeModules)) return [];

            foreach ($module->controllerMap as $id => $type) {
                $this->getControllerActions($type, $id, $module, $result);
            }

            $namespace = trim($module->controllerNamespace, '\\') . '\\';
            // todo recursive
            $result[$module->id] = [
                'id' => $module->id,
                'ns' => $namespace,
            ];
            $this->getControllerFiles($module, $namespace, '', $result);
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * Get list controller under module
     * @param \yii\base\Module $module
     * @param string $namespace
     * @param string $prefix
     * @param mixed $result
     * @return mixed
     */
    private function getControllerFiles($module, $namespace, $prefix, &$result)
    {
        $path = Yii::getAlias('@' . str_replace('\\', '/', $namespace), false);
        $token = "Get controllers from '$path'";
        Yii::beginProfile($token, __METHOD__);
        try {
            if (!is_dir($path)) {
                return;
            }
            foreach (scandir($path) as $file) {
                if ($file == '.' || $file == '..') {
                    continue;
                }
                if (is_dir($path . '/' . $file)) {
                    $this->getControllerFiles($module, $namespace . $file . '\\', $prefix . $file . '/', $result);
                } elseif (strcmp(substr($file, -14), 'Controller.php') === 0) {
                    $id = Inflector::camel2id(substr(basename($file), 0, -14));
                    $className = $namespace . Inflector::id2camel($id) . 'Controller';
                    if (strpos($className, '-') === false && class_exists($className) && is_subclass_of($className, 'yii\base\Controller')) {
                        // get controllers
                        $result[$module->id]['controllers'][$prefix . $id] = [
                            'className' => $className
                        ];
                        $this->getControllerActions($className, $prefix . $id, $module, $result);
                    }
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * Get list action of controller
     * @param mixed $type
     * @param string $id
     * @param \yii\base\Module $module
     * @param string $result
     */
    private function getControllerActions($type, $id, $module, &$result)
    {
        $token = "Create controller with cofig=" . VarDumper::dumpAsString($type) . " and id='$id'";
        Yii::beginProfile($token, __METHOD__);
        try {
            /* @var $controller \yii\base\Controller */
            $controller = Yii::createObject($type, [$id, $module]);
            $result[$module->id]['controllers'][$id]['actions']['*'] = '/' . $controller->id . '/*';
            $this->getActionRoutes($controller, $id, $module, $result);

        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }

    /**
     * Get route of action
     * @param \yii\base\Controller $controller
     * @param array $result all controller action.
     */
    private function getActionRoutes($controller, $id, $module, &$result)
    {
        $token = "Get actions of controller '" . $controller->id . "'";
        Yii::beginProfile($token, __METHOD__);
        try {
            $prefix = '/' . $controller->id . '/';
            foreach ($controller->actions() as $id => $value) {
                // $result[] = $prefix . $id;
            }
            $class = new \ReflectionClass($controller);
            foreach ($class->getMethods() as $method) {
                $name = $method->getName();
                if ($method->isPublic() && !$method->isStatic() && strpos($name, 'action') === 0 && $name !== 'actions') {
                    $name = strtolower(preg_replace('/(?<![A-Z])[A-Z]/', ' \0', substr($name, 6)));
                    $result[$module->id]['controllers'][$id]['actions'][trim($name)] = $prefix . ltrim(str_replace(' ', '-', $name), '-');
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }









}

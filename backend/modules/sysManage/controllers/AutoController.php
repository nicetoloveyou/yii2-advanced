<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/23 0023
 * Time: 04:12
 */

namespace backend\modules\sysManage\controllers;

use Yii;
use helpers\Helper;
use yii\caching\TagDependency;
use backend\modules\sysmanage\components\RouteRule;
use backend\modules\sysmanage\components\Configs;
use yii\helpers\Inflector;
use yii\helpers\VarDumper;
use Exception;
use helpers\Dump;
use backend\models\AuthItem;
use backend\models\AuthItemChild;
use backend\modules\sysmanage\controllers\RouteController;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use yii\rbac\Item;
use yii\web\NotFoundHttpException;

class AutoController extends RouteController
{
    public $type = Item::TYPE_PERMISSION;
    /**
     * @description 自动将路由生成权限
     *
     * @throws
     */
    public function actionMakeItems()
    {
        // $routes = $this->getAppRoutes();

        $routes = $this->getAppAllRoutes();

        Dump::dump($routes);

        // Dump::dump($routes);
        foreach ($routes as $key => $route) {
            // if is module
            $module = [];
            if ($route['uniqueId']) {
                $module['name'] = $route['uniqueId'];
                $ns = str_replace('controllers\\', '' ,$route['ns']). 'Module';
                $class = new \ReflectionClass($ns);
                $module['comment'] = Helper::getComment($class);
                // var_dump($module); die();
                // save module auth item
                if($module['comment']) {
                    $this->saveItem($module['comment']);
                }
            }
            // Dump::dump($module);
            // save controllers as auth item
            $controllers = $route['controllers'];
            foreach($controllers as $control){
                $controller = [];
                $className = $control['className'] ?? '' ;
                if( !$className ) continue;
                $control_class = new \ReflectionClass($className);
                $controller['comment'] = Helper::getComment($control_class);
                // save controller
                if ($controller['comment']) {
                    $this->saveItem($controller['comment']);
                    // build auth parent --> child relation [module ---> controller ]...
                    if ($module) {
                        $this->saveItemChild($module['comment'], $controller['comment']);
                    }
                }
                // save controller actions as auth item ...
                foreach($control['actions'] as $m => $url) {
                    if ($m === '*') continue;
                    $action = [];
                    $method = $control_class->getMethod('action'.str_replace(' ', '', ucwords($m)));
                    $action['comment'] = Helper::getComment($method);
                    $action['url'] = $url;
                    // save action and route url as auth item
                    if ($action['comment']) {
                        $this->saveItem($action['comment']);
                        $this->saveItem($action['url']);
                        // build controller ---> action
                        $this->saveItemChild($controller['comment'], $action['comment']);
                        // build action ----> url
                        $this->saveItemChild($action['comment'], $action['url']);
                    }
                }
            }
        }
        die('---end---');
    }
    /**
     * @param $comment
     * @return string
     */
    protected function saveItem($comment)
    {
        if (!$comment ) return '';
        $model = AuthItem::findOne(['name' => $comment]);
        // Dump::dump($model);
        if (empty($model)) {
            $model = new AuthItem();
            $model->name = StringHelper::byteSubstr($comment, 0, 60);
            $model->type = $this->type;
            if (!$model->save()) {
                Dump::dump($model->getErrors());
            }
            echo " save item '{$comment}' item succeed ...\n ";
        }
        else {
            echo " the item '{$comment}' is existed ... \n ";
        }
    }
    protected function saveItemChild($parent, $child)
    {
        if (!$parent || !$child) return '';
        $model = AuthItemChild::findOne(['parent' => $parent, 'child' => $child]);
        // Dump::dump($model);
        if (empty($model)) {
            $model = new AuthItemChild();
            $model->parent = StringHelper::byteSubstr($parent, 0, 60);
            // $model->pid = $pid;
            $model->child = StringHelper::byteSubstr($child, 0, 60);
            $model->auth_type = $this->type;
            if (!$model->save()) {
                Dump::dump($model->getErrors());
            }
            echo " build '{$parent} --> {$child}' item child succeed ...\n ";
        }
        else {
            echo " the item_child '{$parent} --> {$child}' is existed ... \n ";
        }
        return $model;
    }
    /**
     * Get list of application routes
     * @return array
     */
    public function getAppAllRoutes()
    {
        $result = [];
        $this->getRouteRecrusive(Yii::$app, $result);
        return $result;
    }

    /**
     * Get route(s) recrusive
     * @param \yii\base\Module $module
     * @param array $result
     */
    private function getRouteRecrusive($module, &$result)
    {
        $token = "Get Route of '" . get_class($module) . "' with id '" . $module->uniqueId . "'";
        Yii::beginProfile($token, __METHOD__);
        try {
            foreach ($module->getModules() as $id => $child) {
                if (($child = $module->getModule($id)) !== null) {
                    $this->getRouteRecrusive($child, $result);
                }
            }
            foreach ($module->controllerMap as $id => $type) {
                $this->getControllerActions($type, $id, $module, $result);
            }
            $namespace = trim($module->controllerNamespace, '\\') . '\\';
            // todo recursive
            $result[$module->uniqueId] = [
                'uniqueId' => $module->uniqueId,
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
                        $result[$module->uniqueId]['controllers'][$prefix . $id] = [
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
            $result[$module->uniqueId]['controllers'][$id]['actions']['*'] = '/' . $controller->uniqueId . '/*';
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
        $token = "Get actions of controller '" . $controller->uniqueId . "'";
        Yii::beginProfile($token, __METHOD__);
        try {
            $prefix = '/' . $controller->uniqueId . '/';
            foreach ($controller->actions() as $id => $value) {
                // $result[] = $prefix . $id;
            }
            $class = new \ReflectionClass($controller);
            foreach ($class->getMethods() as $method) {
                $name = $method->getName();
                if ($method->isPublic() && !$method->isStatic() && strpos($name, 'action') === 0 && $name !== 'actions') {
                    $name = strtolower(preg_replace('/(?<![A-Z])[A-Z]/', ' \0', substr($name, 6)));
                    $result[$module->uniqueId]['controllers'][$id]['actions'][trim($name)] = $prefix . ltrim(str_replace(' ', '-', $name), '-');
                }
            }
        } catch (\Exception $exc) {
            Yii::error($exc->getMessage(), __METHOD__);
        }
        Yii::endProfile($token, __METHOD__);
    }
    /**
     * Ivalidate cache
     */
    protected function invalidate()
    {
        if (Configs::instance()->cache !== null) {
            TagDependency::invalidate(Configs::instance()->cache, self::CACHE_TAG);
        }
    }


    public function actionDelete()
    {

    }

}
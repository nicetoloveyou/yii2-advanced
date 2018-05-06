<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/21 0021
 * Time: 23:21
 */

namespace backend\controllers;
use backend\models\AuthItemChild;
use backend\models\AuthItem;
use yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use helpers\Helper;
use helpers\Dump;


class ArraytestController extends yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
//            'access' => [
//                'class' => AccessControl::className(),
//                'rules' => [
//                    [
//                        'actions' => ['index'],
//                        'allow' => true,
//                        'roles' => ['@', '?'],
//                    ],
//                ],
//            ]
        ];
    }

    public function actionIndex()
    {
        $array = [
            999=>  ['id' => 999, 'pid' => 0, 'label' => 'Dong'],
            ['id' => 100, 'pid' => 0, 'label' => 'Dong'],

           1=> ['id' => 1, 'pid' => 0, 'label' => 'china'],
            ['id' => 2, 'pid' => 0, 'label' => 'us'],
            ['id' => 3, 'pid' => 0, 'label' => 'as'],
            ['id' => 4, 'pid' => 0, 'label' => 'kr'],

           5=> ['id' => 5, 'pid' => 1, 'label' => 'chengdu'],
            ['id' => 7, 'pid' => 1, 'label' => 'Chongqing'],
            ['id' => 8, 'pid' => 2, 'label' => 'lal'],
            ['id' => 9, 'pid' => 3, 'label' => 'xini'],
            ['id' => 10, 'pid' => 4, 'label' => 'shouer'],

            //'id' => 100,
            //'pid' => 1111
        ];
//        $tree = Helper::toTree($array);
//        $value = ArrayHelper::getValue($array, function($array){
//            // return ($array['pid'] === 1111) ? $array['pid'] : '';
//            $arr = [];
//            foreach($array as $key=>$val){
//                if($key % 2 == 0) $arr[$key] = $val;
//            }
//            return $arr;
//        });

//        ArrayHelper::remove($array, '1111');

//        $array = ArrayHelper::index($array, function($element){
//            return $element['label'];
//        });

//        $array = ArrayHelper::index($array, null, 'pid');

//        $array = ArrayHelper::index($array, 'label', [function($element){
//            return $element['pid'];
//        }, 'id']);

//        $array = ArrayHelper::getColumn($array, function($element){
//            return $element['pid'] . '--' . $element['id'];
//        });

//        $array = ArrayHelper::map($array, 'id', 'label', 'pid');

//        ArrayHelper::multisort($array, 'pid', SORT_DESC);

//        $isAssoc = ArrayHelper::isAssociative($array);

//        $subArray = ['id' => 1, 'pid' => 10, 'label' => 'china'];
//
//        $isIn = ArrayHelper::isIn($subArray, $array);

//        $left = ArrayHelper::filter($array, ['!pid']);

//        $tree = \helpers\Helper_Array::array2tree($array, 'id', 'pid');

//        $class = new \ReflectionClass('backend\models\Menu');
//        $comment = $class->getDocComment();
//        $comment = Helper::DocParser($comment);
//        //$comment = str_replace(['*', '/', '\\'], '', $comment);
//        //$comment = nl2br($comment);
//        //$arr = explode('<br />', $comment);
//
//        var_dump($comment);

//        $child = '/admin/role/update';
//
//        //$ItemChild = AuthItemChild::find()->asArray()->all();
//
//        $ItemChild = [
//            ['parent' => '系统管理', 'child' => '角色管理'],
//            ['parent' => '角色管理', 'child' => '角色列表'],
//            ['parent' => '角色列表', 'child' => '角色添加'],
//            ['parent' => '角色添加', 'child' => '/admin/role/update'],
//        ];

//        $parents = Helper::searchParents($ItemChild, '/admin/role/update');
//
//        $res = Helper::reserveArray($parents);
//
//        Dump::dump($parents);
//
//        Dump::dump($res);


    }

    public function actionModel()
    {
        $model = AuthItem::find()->where(['name' => 'Admin'])->with('authAssignments')->asArray()->one();

        // 获取所有children
        // Dump::dump(count($model->getAuthItemChildren()->asArray()->all()));

        // Dump::dump(($model->getChildren()->asArray()->all()));

        Dump::dump($model);

    }


    public function actionString()
    {
        $string = '*admin*';

        $startWith = StringHelper::startsWith($string, '*');
        $endWith = StringHelper::startsWith($string, '*');

        var_dump($startWith);

        var_dump($endWith);
    }
















}
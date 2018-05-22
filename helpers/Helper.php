<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/22 0022
 * Time: 00:21
 */

namespace helpers;
use yii;
use helpers\DocParser;
use yii\helpers\VarDumper;
use yii\helpers\ArrayHelper;

class Helper
{
    private static $parse;

    public static function instanceParser()
    {
        if(self::$parse == null) {
            self::$parse = new DocParser();
        }

        return self::$parse;
    }

    public static function DocParser($doc)
    {
        if (!$doc) return '';

        return self::instanceParser()->parse($doc);
    }

    /**
     * @param $object
     * @param string $key
     * @return string
     */
    public static function getComment($object, $key = 'description')
    {
        $comment = $object->getDocComment();
        $comment = Helper::DocParser($comment);

        return ($comment[$key]) ?? ($comment['long_description'] ?? '');
    }


    /**
     * 生成树形结构：数组必须带有索引主键
     *
     * @param array $items
     * @return array
     */
    public static function toTree($items, $pid = 'pid', $id = 'id', $children = 'children'){
        // convert into index array by id
        $items = ArrayHelper::index($items, $id);
        // make tree
        $tree = array();
        foreach($items as $item){
            if(isset($items[$item[$pid]])){
                $items[$item[$pid]][$children][] = &$items[$item[$id]];
            }else{
                $tree[] = &$items[$item[$id]];
            }
        }
        return $tree;
    }

    /**
     * 对数组建立父子关系：便于生成树形结构
     *
     * $items = [
     *  ['parent' => 'admin', 'child' => 'view'],
     *  ['parent' => 'admin', 'child' => 'update'],
     *  ['parent' => 'view', 'child' => '查看'],
     *  ['parent' => 'update', 'child' => '修改'],
     * ]
     *
     *  转换之后：
     *
     *  $items = [
     *  ['id' => 1, 'label' => 'admin', 'pid' => 0]
     *  ['id' => 5, 'label' => 'view', 'pid' => 1]
     *  ['id' => 6, 'label' => 'update', 'pid' => 1]
     * ]
     *
     *
     * @param $items
     * @param bool $toTree
     * @return array
     */
    public static function buildParentChild($items, $toTree = false)
    {
        // get columns
        $columns = array_merge(
            ArrayHelper::getColumn($items, 'parent')
            // ArrayHelper::getColumn($items, 'child')
        );
        $columns = array_unique($columns);
        // the prefect way is : to merge
        // assign id to columns
        $newItems = [];
        foreach($columns as $key => $col){
            // let the key from 1
            $newItems[$col] = ['id' => $key+1, 'label' => $col];
        }
        // reset parent
        foreach($newItems as $key=>$item) {
            $parent = self::searchParent($items, $item['label']);
            if ($parent) {
                $pid = $newItems[$parent]['id'];
            }
            else {
                $pid = 0;
            }
            $item['pid'] = $pid;
            $newItems[$key] = $item;
        }
        // Dump::dump($newItems);
        // to tree
        if ($toTree) return Helper::toTree($newItems);

        return $newItems;
    }

    /**
     * 二维数组生成树形菜单结构：适合从数据库中取出具有父子关系的item
     *
     * $items = [
     *  ['parent' => 'admin', 'child' => 'view'],
     *  ['parent' => 'admin', 'child' => 'update'],
     *  ['parent' => 'view', 'child' => '查看'],
     *  ['parent' => 'update', 'child' => '修改'],
     * ]
     *
     * @param $items
     * @return array
     */
    public static function makeTree($items)
    {
        $columns = ArrayHelper::getColumn($items, 'parent');
        $columns = array_unique($columns);

        $newItems = [];
        foreach ($columns as $key=>$col) {
            $parent = Helper::searchParent($items, $col);
            if ($parent) {
                $item = ['id' => $col, 'pid' => $parent, 'label' => $col];
                // add item to parent children
                $newItems[$parent]['children'][] = $item;
            }
            // else $newItems[] = $col;
        }

        return $newItems;
    }

    /**
     * 从二维数组中查找 parent
     *
     * @param $array
     * @param $value
     * @return string
     */
    public static function searchParent($array, $value, $child = 'child')
    {
        foreach($array as $val){
            if ($val[$child] == $value) return $val['parent'];
        }

        return '';
    }

    /**
     * 查找祖宗
     *
     * @param array $ItemChild
     * @param string $child
     * @return array
     */
    public static function searchParents(array $ItemChild, string $value)
    {
        static $parents = [];
        $parent = self::searchParent($ItemChild, $value);
        if($parent != '') {
            $parents[] = $parent;
            self::searchParents($ItemChild, $parent);

            //$parents[$parent] = self::searchParents($ItemChild, $parent);
        }

        return $parents;
    }

    public static function reserveArray($array)
    {
        static $newArray = [];
        foreach($array as $key=>$val)
        {
            if(is_array($val) && !empty($val)) {
                //array_unshift($newArray, $val);
                $keys = array_keys($val);
                $parent = !empty($keys[0]) ? $keys[0] : '';
                $newArray[$key] = ['parent' => $parent, 'child' => $key];

                self::reserveArray($val);
            }
            else {
                $newArray[$key] = ['parent' => '', 'child' => $key];
            }
        }

        return array_reverse($newArray);
    }

    public static function reverse($arr)
    {
        $temp = [];
        end($arr);
        while (($value = current($arr)) != null) {
            $temp[key($arr)] = $value;
            prev($arr);
        }
        return $temp;
    }


    // @see http://www.jb51.net/article/104454.htm
    function dirList($dir)
    {
        $files = [];
        if(is_dir($dir)){
            if(($dh=opendir($dir)) !== false){
                while(($rd=readdir($dh)) !== false){
                    if($rd != '..' && $rd != '.'){
                        if(is_dir($dir."/".$rd)){
                            $files[$rd] = dirlist($dir."/".$rd);
                        }
                        else{
                            $files[] = $rd;
                        }
                    }
                }
            }
            closedir($dh);
        }
        return $files;
    }






}
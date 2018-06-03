<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/3 0003
 * Time: 22:36
 */

namespace backend\components;


use yii\base\Model;

class SearchModel extends Model
{

    public $query;

    public $where = '';

    public $orderBy;

    public $groupBy;

    public $limit;

    public function rules()
    {
        return [];
    }

}
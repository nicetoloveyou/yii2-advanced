<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/29 0029
 * Time: 23:10
 */

namespace backend\models;




use yii\base\Model;

class TotalForm extends Model
{
    public $platform_id;

    public $platform_id_value;


    public function rules()
    {
        return [
            ['platform_id', 'array'],
            [['platform_id_value'], 'integer'],
            [['platform_id_value'], 'in', 'range' => function($model, $attribute){
                //return [1, 2, 3];
                return array_keys($model->platform_id);
            }]
        ];
    }




}
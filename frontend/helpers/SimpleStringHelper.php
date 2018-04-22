<?php
namespace app\helpers;

use yii\helpers\StringHelper;
use yii\helpers\VarDumper;
/**
 *
 * @author Administrator
 *        
 */
class SimpleStringHelper extends StringHelper
{
    
    /**
     * implode array to string
     * @return multitype:string
     */
    public static function toImplode($glue, $array)
    {
        $string = 'LoginForm';
        $arr = explode('_', self::humpToLine($string));
        var_dump($arr);
        
        exit;
        $fields = [
            [
                'name' => 'username', 
                'type' => 'input', 
                'rule' => 'string', 
                'required' => true, 
                'max_length' => 20,
                'min' => 6,
                'placeholder' => 'please enter your username ... ',
            ],
            [
                'name' => 'email', 
                'type' => 'input', 
                'rule' => 'email', 
                'required' => true, 
                'max_length' => 30,
                'min_length' => 10,
                'placeholder' => 'please enter your email ... ',
            ],
        ];
        
        $rules = [];
        foreach($fields as $field)
        {
            if(!$field['rule'] || !$field['name']) continue;
            
            if($field['required'] === true)
            {
                $rule = [$field['name'], 'required', 'message' => ((!$field['placeholder']) ? '' : $field['placeholder'])];
                $rules[] = self::reserveArraySourceCode($rule);
            }
            if($field['rule'])
            {
                $rule = [$field['name'], $field['rule']];
                if(!empty($field['min_length']))
                {
                    $rule['min'] = $field['min_length'];
                }
                if(!empty($field['max_length']))
                {
                    $rule['max'] = $field['max_length'];
                }
                if(!empty($field['min']))
                {
                    $rule['min'] = $field['min'];
                }
                if(!empty($field['max']))
                {
                    $rule['max'] = $field['max'];
                }
                $rules[] = self::reserveArraySourceCode($rule);
            }
        }
        if(!empty($rules)) $rules = "\n            " . implode(",\n            ", $rules) . ",\n        ";
        fwrite(fopen(dirname(__FILE__). '/data.txt', 'w'), $rules);
        var_dump($rules);
        
        exit;
        return $array;
    }
    
    public static function humpToLine($str){
        $str = preg_replace_callback('/([A-Z]{1})/',function($matches){
            return '_'.strtolower($matches[0]);
        }, $str);
        return trim($str, '_');
    }
    
    /**
     * Reserve array data be source code:
     * for example: 
     *      $arr = ['a', 'b', 'message'=> 'something ...'];
     * So then   
     *      $string = SimpleStringHelper::reserveArraySourceCode($arr), while you print out $string, it will be same as $arr 
     * @param array $array
     * @return string
     */
    public static function reserveArraySourceCode(array $array)
    {
        if(!is_array($array) || (count($array)) < 2) return '';
        $temp = [];
        foreach($array as $key=>$val)
        {
            if(is_string($key))
            {
                $temp[$key] = "'{$key}' => '{$val}'";
            }
            else
            {
                if($val) $temp[$key] = "'{$val}'";
            }
        }
        return ( !empty($temp) ? " [" . implode(", ", $temp) . "] " : '' );
    }
    
    
    
}

?>
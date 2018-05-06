<?php
namespace app\models;

use yii;
use yii\redis\ActiveRecord;

/**
 * redis model 
 *
 * @author Administrator
 *        
 */
class Customer extends ActiveRecord
{
    
    /**
     * @var UploadedFile
     */
    public $imageFile;


    /**
     * 主键 默认为 id
     *
     * @return array|string[]
     */
    public static function primaryKey()
    {
        return ['id'];
    }
    
    /**
     * 验证规则
     * @see \yii\base\Model::rules()
     */
    public function rules()
    {
        return [
            [['id', 'name', 'phone', 'age'], 'required'], //必填参数
            [['age'], 'integer', 'min' => 16, 'max' => 30],
            ['age', 'default', 'value' => 20], //默认值
            [['id', 'name', 'phone', 'age'], 'trim'], //过滤空格
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'], //上传文件
            [['phone'], 'validatePhone']
        ];
    }
    
    public function uploadImageFile()
    {
        if ($this->validate()) {
            $this->imageFile->saveAs('upload/' . $this->imageFile->baseName . '.' . $this->imageFile->extension);
            // 多文件 
            /**
             * foreach ($this->imageFiles as $file) {
                    $file->saveAs('uploads/' . $file->baseName . '.' . $file->extension);
                }
             */
            return true;
        } else {
            var_dump($this->errors);exit;
        }
    }
    
    /**
     * 自定义验证：电话号码验证
     * @param  $attribute
     * @param  $params
     */
    public function validatePhone($attribute, $params)
    {
        if(strlen($this->$attribute) < 11){
            $this->addError($attribute, '电话号码格式错误');
        }
    }
    
    /**
     * 模型对应记录的属性列表
     *
     * @return array
     */
    public function attributes()
    {
        return ['id', 'name', 'age', 'phone', 'status', 'created_at', 'updated_at', 'imageFile'];
    }
    
    
//     /**
//      * 定义和其它模型的关系
//      *
//      * @return \yii\db\ActiveQueryInterface
//      */
//     public function getOrders()
//     {
//         return $this->hasMany(Order::className(), ['customer_id' => 'id']);
//     }

    
    
    
}

?>
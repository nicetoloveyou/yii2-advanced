<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "admin_log".
 *
 * @property integer $id
 * @property string $route
 * @property string $url
 * @property string $user_agent
 * @property string $gets
 * @property string $posts
 * @property integer $admin_id
 * @property string $admin_email
 * @property string $ip
 * @property integer $created_at
 * @property integer $updated_at
 */
class AdminLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['route', 'url', 'user_agent', 'posts', 'admin_id', 'admin_email', 'ip', 'created_at', 'updated_at'], 'required'],
            [['gets', 'posts'], 'string'],
            [['admin_id', 'created_at', 'updated_at'], 'integer'],
            [['route', 'url', 'user_agent', 'admin_email', 'ip'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'route' => 'Route',
            'url' => 'Url',
            'user_agent' => 'User Agent',
            'gets' => 'Gets',
            'posts' => 'Posts',
            'admin_id' => 'Admin ID',
            'admin_email' => 'Admin Email',
            'ip' => 'Ip',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

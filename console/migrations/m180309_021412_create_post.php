<?php

use yii\db\Migration;

class m180309_021412_create_post extends Migration
{
    public function safeUp()
    {

    }

    public function safeDown()
    {
        echo "m180309_021412_create_post cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        $this->createTable('{{%posts}}', [
            'id' => $this->primaryKey(),
            'content' => $this->string(32),
            'user_id' => $this->integer()
        ], $tableOptions);
    }

    public function down()
    {
        echo "m180309_021412_create_post cannot be reverted.\n";

        return false;
    }
    
}

<?php

namespace console\controllers;

use yii;
use yii\helpers\Console;
use yii\db\SchemaBuilderTrait;
use yii\db\Migration;

class AutoController extends \yii\console\Controller
{


    public function actionIndex()
    {
        // return $this->render('index');
        echo 'Welcome auto console ';
    }
    
     public function actionBackup($path, $deepth = 1)
     {
         echo $path .'----'. $deepth . "\n";
         echo Console::moveCursorUp();
         echo $path .'----'. $deepth . "\n";
         echo $path .'----'. $deepth . "\n";
         echo Console::renderColoredString("%yHello%g");
         echo Console::prompt('Please input some thing here ...');
         echo $string = Console::stdin();
         echo Console::confirm('You sure ?');
         Console::stdout($string);
         // echo Console::error('error: You can not find it here .');
     }

    /**
     * create table
     */
     public function actionCreateTable()
     {
         $migration = new Migration();
         $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

         $result = Yii::$app->db->createCommand()
             ->createTable('{{customer}}', [
                 'id' => $migration->primaryKey(),
                 'username' => $migration->string(32),
                 'age' => $migration->integer(2)
             ], $tableOptions)
             ->execute();

         var_dump($result);exit;
     }












}

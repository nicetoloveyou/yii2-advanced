<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/29 0029
 * Time: 23:08
 */

namespace backend\controllers;
use Faker\Provider\File;
use yii;
use yii\web\Controller;
use yii\helpers\FileHelper;
use helpers\Dump;

class FiletestController extends yii\web\Controller
{

    public function actionIndex()
    {
        $dir = dirname(__FILE__) . '/';
        $path = FileHelper::normalizePath($dir, '\\');
        //$file = __FILE__;
        //$fileType = FileHelper::getMimeType($_SERVER['SCRIPT_FILENAME']);
        $result = '';

        Dump::dump($result);

    }


























}
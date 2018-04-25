<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/14 0014
 * Time: 19:34
 */

namespace helpers;
use yii;
use helpers\DocParser;
use yii\helpers\VarDumper;

class Dump
{

    public static function dump($var, $depth = 1000, $highlight = true)
    {
        VarDumper::dump($var, $depth, $highlight);

        // die();
    }



















}
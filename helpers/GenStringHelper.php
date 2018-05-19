<?php
/**
 * Created by PhpStorm.
 * User: chrispaul
 * Date: 2018/5/18
 * Time: 16:06
 */

namespace app\helpers;

/**
 * 字符串生成：随机、按规则等
 *
 * Class GenStringHelper
 *
 * @package app\helpers
 */

class GenStringHelper
{

    /**
     * 随机生成6密码
     *
     * @return bool|string
     */
    public static function generatePassword()
    {
        $str = str_shuffle(substr(md5(time()), 0, 6));

        return $str;
    }














}

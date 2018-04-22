<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/23 0023
 * Time: 03:51
 */

namespace helpers;


class Helper_Common
{

    /**
     * 简单的日志功能
     */
    public static function log($str, $file = 'common') {
        if (!$file || !$str) {
            return false;
        }
        $file .= '_' . date('Y-m-d');
        $file = Yii::app()->basePath . "/runtime/{$file}.log";
        touch($file);
        return file_put_contents($file, date('Ymd H:i:s') . ' ' . $str . "\n", FILE_APPEND);
    }

    /**
     * 检测TCP地址是否可到达
     *
     * @return boolean
     * @param string $ip
     * @param int $port
     * @author wushoulin@haowan123.com
     */
    public static function socketCheck($ip, $port) {
        if (function_exists('fsockopen')) {
            $fp = @fsockopen($ip, $port, $errno, $errmsg, 1);
            $result = !$fp ? false : true;
            if ($fp)
                fclose($fp);
            return $result;
        }else {
            throw new CException('function fsockopen not find');
        }
    }


    //字符串截取
    static public function subStr(&$_object,$_field,$_length,$_encoding) {
        if ($_object) {
            if (is_array($_object)) {
                foreach ($_object as $key=> $_value) {
                    if (mb_strlen($_value[$_field],$_encoding) > $_length) {
                        $_object[$key][$_field ]= mb_substr($_object[$key][$_field ],0,$_length,$_encoding).'......';
                    }
                }
                return $_object;
            }else {
                if (mb_strlen($_object,$_encoding) > $_length) {
                    return mb_substr($_object,0,$_length,$_encoding).'...';
                } else {
                    return $_object;
                }
            }
        }

    }

    /**
     * 字符串截取，支持中文和其他编码
     * static
     * access public
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
     * return string
     */
    public static function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
        if(function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif(function_exists('iconv_substr')) {
            $slice = iconv_substr($str,$start,$length,$charset);
            if(false === $slice) {
                $slice = '';
            }
        }else{
            $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("",array_slice($match[0], $start, $length));
        }
        return $suffix ? $slice.'...' : $slice;
    }

    /**
     * 检查密码强度，最高得4分，一般要求最低2分
     * @param string $str
     * @return int
     */
    public static function PasswordStrongLevel($str) {
        if (strlen($str) < 6) {
            return 0; //长度小于6直接毙掉
        }
        $score = 0;
        if (preg_match("/[0-9]+/", $str)) {
            $score ++; //包含数字
        }
        if (preg_match("/[a-z]+/", $str)) {
            $score ++; //包含小写字母
        }
        if (preg_match("/[A-Z]+/", $str)) {
            $score ++; //包含大写字母
        }
        if (preg_match("/[_|\-|+|=|*|!|@|#|$|%|^|&|(|)]+/", $str)) {
            $score ++; //包含特殊字符
        }
        return $score;
    }
}
<?php

/**
 * 开发调试工具
 */
class Debuger extends CApplicationComponent {

    /**
     * 输出调试变量并终止程序
     * @param type $var
     */
    public static function DumpEnd($var) {
        CVarDumper::dump($var, 10, true);
        Yii::app()->end();
    }

    /**
     * 输出调试变量到HTML注释
     * @param type $var
     */
    public static function DumpHide($var) {
        echo '<!--';
        print_r($var);
        echo '-->';
    }

    /**
     * 开始记录Profile信息
     */
    public static function BeginProfile() {
        Yii::beginProfile('DebugerBlock');
    }

    /**
     * 结束记录Profile信息并输出到浏览器控制台
     */
    public static function EndProfile($showInFireBug = true) {
        Yii::endProfile('DebugerBlock');
        $logs = Yii::getLogger()->getLogs('profile');
        $begin = $end = 0;
        foreach ($logs as $key => $log) {
            if ($log[0] == 'begin:DebugerBlock') {
                $begin = $key + 1;
                continue;
            }
            if ($log[0] == 'end:DebugerBlock') {
                $end = $key;
                break;
            }
        }
        $profile = array_slice($logs, $begin, $end - $begin);
        if ($showInFireBug) {
            $logRoute = new CWebLogRoute();
            $logRoute->showInFireBug = true;
            $logRoute->ignoreAjaxInFireBug = false;
            $logRoute->processLogs($profile);
        } else {
            Debuger::DumpEnd($profile);
        }
    }

}

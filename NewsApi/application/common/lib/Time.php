<?php
namespace app\common\lib;
/**
 * 时间
 * Class Time
 * @package app\common\lib
 */
class Time {
    /**
     * 获取13位数时间戳
     * @return int
     */
    public static function get12TimeStamp(){
        
        list($t1, $t2) = explode(' ', microtime());
        return $t2 . ceil($t1 * 1000);
    }

}
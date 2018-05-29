<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19
 * Time: 16:38
 */
namespace app\common\lib;
use app\common\lib\Aes;
use think\Cache;

/**
 * Iauth
 * Class IAuth
 */
class IAuth{

    /**
     * 设置密码
     * @param string $data
     * @return string
     */
    public static function setPassword($data){
        return md5($data.config('app.password_pre_halt'));
    }

    /**
     * 生成每次请求的sign
     * @param array $data
     * @return string
     */
    public static function setSign($data=[]){
        
        // 1.按字段排序
        ksort($data);
        //2.拼接字符串数据 &
        $string = http_build_query($data);
        //3.通过aes加密
        $string = (new Aes())->encrypt($string);

         return $string;
    }

    /**
     * 检查sign是否正常
     * @param array $data
     * @param $data
     * @return bool
     */
    public static function checkSignPass($data){

        $str = (new Aes())->decrypt($data['sign']);
        //echo $str;exit;did=12345dg&time=1514441786728&version=1
        
        if(empty($str)){
            return false;
        }

        parse_str($str, $arr);
       
        if (!is_array($arr) || empty($arr['did']) || $arr['did'] != $data['did'] ){
            return false;
        }

       //time 329
        if (!config('app_debug')){
            //echo   time() - ceil($arr['time'] / 1000);exit;
            //echo  config('app.app_sign_time');exit;
            if ((time() - ceil($arr['time'] / 1000)) > config('app.app_sign_time')){

                return false;
            }
            //echo  Cache::get($data['sign']);exit;
            //唯一性判定
            if (Cache::get($data['sign'])){
                return false;
            }
        }

        return true;
    }

    /**
     * 设置登录的token  唯一性
     * @param string $phone
     * @return string
     */
    public static function setAppLoginToken($phone=''){
        $str = md5(uniqid(md5(microtime(true)), true));
        $str = sha1($str.$phone);
        return $str;
    }
}
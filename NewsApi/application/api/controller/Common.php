<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 14:01
 */
namespace app\api\controller;

use app\common\lib\exception\ApiException;
use app\common\lib\IAuth;
use app\common\lib\Time;
use think\Cache;
use think\Controller;
use app\common\lib\Aes;

/**
 * API模块 公共控制器
 * Class Common
 * @package app\api\controller
 */
class Common extends Controller{

    /**
     * headers 信息
     * @var string
     */
    public  $headers = '';
    /**
     * 初始化的方法
     */
    public function _initialize()
    {
        $this->checkRequestAuth();
        //$this->testAes();
        // 生成sign专用
    }

    public function  checkRequestAuth(){
        $headers = request()->header();
        // body
        //$body = request()->param();
       
        //基础参数校验
        if (empty($headers['sign'])){
            throw new ApiException('sign不存在', 400);
        }

        if (!in_array($headers['app_type'], config('app.apptypes'))){
            throw new ApiException('app_type不合法', 400);
        }

        if (!IAuth::checkSignPass($headers)){
            throw new ApiException('授权码sign失败', 401);
        }

        $this->headers = $headers;

        Cache::set($headers['sign'], 1, config('app.app_sign_cache_time'));


    }

    public function testAes(){
        //$str = "id=1&ms=45&username=singwa";
        //echo (new Aes())->encrypt($str);exit;$aes_str = '6dDiaoQrSC2tPepBYWGFh8ri8FNeKXBwRFKbn3hv8qA=';
        //echo (new Aes())->decrypt($aes_str);exit;
        $data = [
            'did' =>'12345dg',
            'version'=>1,
            'time' => Time::get12TimeStamp(),
        ];
        //halt($data);exit;
        //echo IAuth::setSign($data);exit;
        //$str = 'col9j6cqegAKiiey3IrXWvj6T8Q5kTAAH0tlsojzndQIVnKDb7Rin03dOqY2qLWP';
        //$str = 'col9j6cqegAKiiey3IrXWuS/h2yb32fuh/6SPlR0qn4IVnKDb7Rin03dOqY2qLWP';
        $str = 'col9j6cqegAKiiey3IrXWgVPSCV0/QJPlwWs8BY51H8IVnKDb7Rin03dOqY2qLWP';
        echo (new Aes())->decrypt($str);exit;
    }

    protected function getDealNews($news = []){
        if (empty($news)){
            return [];
        }

        $cats = config('cat.lists');
        foreach ($news as $key =>$new){
            $news[$key]['catname'] = $cats[$new['catid']] ?
                $cats[$new['catid']] : '-';
        }
        return $news;
    }
}
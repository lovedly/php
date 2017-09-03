<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
function status($status){
    if ($status == 1){
        $str ="<span class='label label-success radius'>正常</span>";
    }elseif ($status == 0){
        $str ="<span class='label label-danger radius'>待审</span>";
    }else{
        $str ="<span class='label label-danger radius'>删除</span>";
    }
    return $str;
}

/**
 * @param $url
 * @param number $type 0 get 1 post
 * @param array $data
 */
function doCurl($url, $type=0,$data=[]){
    // 1. 初始化
    $ch = curl_init();
    // 2. 设置选项，包括URL
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,0);
    
    if($type == 1){
        //post
        curl_setopt($con, CURLOPT_POSTFIELDS, $data);
        curl_setopt($con, CURLOPT_POST,1);
    }
    
    //执行并且获取内容
    $output = curl_exec($ch);
    
    //释放curl句柄
    curl_close($ch);
    return $output;
}


//商户入驻申请的文案
function bisRegister($status){
    if($status == 1){
        $str ="入驻申请成功";
    }elseif($status ==0){
        $str ="待审核,审核后平台方会发送邮寄通知,请关注邮件";
    }elseif($status ==2){
        $str ="非常抱歉,您提交的材料不符合条件,请重新提交";
    }else{
       $str = "该申请已经被删除"; 
    }
    return $str;
}

/**
 * 通用的分页样式
 * @param  $obj
 * @return string
 */
function pagination($obj){
    if(!$obj){
        return '';
    }
    //优化的方案
    $params = request()->param();
    return '<div class="cl pd-5 bg-1 bk-gray mt-20 tp5-o2o">'.$obj->appends($params)->render().'</div>';
}

function getSeCityName($path){
    if (empty($path)){
        return '';
    }
    if(preg_match('/,/',$path)){
        $cityPath = explode(',',$path);
        $cityId = $cityPath[1];
    }else {
        $cityId = $path;
    }
    $city = model('City')->get($cityId);
    return $city->name;
}



function getSeCategoryName($path){
    if (empty($path)){
        return '';
    }
    if(preg_match('/,/',$path)){
        $categoryPath = explode(',',$path);
        $categoryId = $categoryPath[1];
    }else {
        $categoryId = $path;
    }
    $category = model('Category')->get($categoryId);
    return $category->name;
}


function ismain($is_main){
    if($is_main == 1){
        $str ="总店";
    }elseif($is_main ==0){
        $str ="分店";
    }else{
        $str = "其他";
    }
    return $str;
}



function countLocation($ids){
    if(!$ids){
        return 1;
    }
    if(preg_match('/./',$ids)){
        $arr = explode('.',$ids);
        return count($arr);
    }
}

//设置订单号
function setOrderSn(){
    list($t1,$t2) =explode(' ',microtime());
    $t3 = explode('.',$t1*10000);
    return $t2.$t3[0].(rand(10000, 99999));
}
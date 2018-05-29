<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/1
 * Time: 16:42
 */
return[
    'password_pre_halt' =>'#api_5',     //密码加密盐
    'aeskey' =>'sgg45747ss223455' ,    // aes 密匙 服务端和客户端必须保持一致
    'apptypes' =>[
        'ios',
        'android',
    ],
    'app_sign_time' => 60000,  //sign失效时间  秒
    'app_sign_cache_time' => 20,  //sign  缓存失效时间
    'login_time_out_day'=>7000,   //登陆token失效时间
];
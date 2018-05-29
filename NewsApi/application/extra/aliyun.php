<?php
/**
 * 阿里云相关配置
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/8
 * Time: 16:46
 */
return [
    'product' => 'Dysmsapi',   // 短信API产品名，唯一
    'domain' => 'dysmsapi.aliyuncs.com',   // 短信API产品域名，唯一
    'region' => 'cn-hangzhou',   // 暂时不支持多Region，唯一
    'endPointName' => 'cn-hangzhou',   // 服务结点，唯一
    //'accessKeyId' => '',   // AccessKeyId
    //'accessKeySecret' => '',   // AccessKeySecret
    'signName' => '',   // 签名名称
    'templateCode' => 'SMS_127080007',   // 短信模板Code
    'valid_time' => '30000000',   // 验证码有效时间，单位为秒
];
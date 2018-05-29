<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18
 * Time: 20:39
 */
namespace app\common\validate;
use think\Validate;

class AdminUser extends Validate{

    protected $rule =[
        'username' => 'require|max:20',
        'password' => 'require|max:20',
    ];
}
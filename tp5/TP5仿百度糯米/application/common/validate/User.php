<?php
namespace app\common\validate;
use think\Validate;
class User extends Validate{
    protected  $rule = [
        ['username','require','用户名不能为空!'],
        ['email','require|email','邮箱不能为空!|请填写正确的email格式'],
        ['password','require','密码不能为空!'],
        ['repassword','require','确认密码不能为空!'],
        ['verifyCode','require','验证码不能为空!'],
        
    ];
    
    /*
     * 场景设置
     */
    protected $scene = [
        'register' =>['username','email','password','repassword','verifyCode'],  
        'login' =>['username','password'],
        
    ];
}
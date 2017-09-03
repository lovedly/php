<?php
namespace app\common\validate;
use think\Validate;
class BisAccount extends Validate{
    protected  $rule = [
        ['username','require','用户名不能为空'], 
        ['password','require','密码不能为空!'],   
    ];
    
    /*
     * 场景设置
     */
    protected $scene = [
        'add' =>['username','password'],
        'login' =>['username','password']
    ];
}
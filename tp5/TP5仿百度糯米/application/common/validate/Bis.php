<?php
namespace app\common\validate;
use think\Validate;
class Bis extends Validate{
    protected  $rule = [
        'name'=>'require',
        'email'=>'require|email',
        'logo'=>'require',
        'city_id'=>'require',
        'bank_info'=>'require',
        'bank_name'=>'require',
        'bank_user' =>'require',
        'faren'=>'require',
        'faren_tel'=>'require',  
        'id'=>'require|number',
        ['status','number|in:-1,0,1,2','状态必须是数字|状态范围不合法'],
        
    ];
    
    /*
     * 场景设置
     */
    protected $scene = [
        'add' =>['name','email','logo','city_id','bank_info','bank_name','bank_user','faren','faren_tel'],
        'status' =>['id','status'],   //状态
        
    ];
}
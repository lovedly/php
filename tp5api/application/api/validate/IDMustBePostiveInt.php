<?php
namespace app\api\validate;

use think\Validate;

class IDMustBePostiveInt extends BaseValidate
{
    protected $rule = [
        'id' =>'require|isPostiveInteger'
    ];
    
    protected $message = [
        'id' => 'id必须是正整数'
    ];
  
}
<?php
namespace app\api\validate;

use think\Validate;

class Count extends BaseValidate
{
    protected $rule = [
        'count' =>'isPostiveInteger|between:1,15'  //这里不能有空格
    ];
    
    protected $message = [
        'ids' => 'ids参数必须是以逗号分隔的多个正整数'
    ];
}
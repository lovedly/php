<?php
namespace app\api\validate;

use think\Validate;

class IDCollection extends BaseValidate
{
    protected $rule = [
        'ids' =>'require|checkIDs'
    ];
    
    protected $message = [
        'ids' => 'ids参数必须是以逗号分隔的多个正整数'
    ];
    
    protected function checkIDs($value){
        $values = explode(',',$value);
        if (empty($values)){
            return false;
        }
        foreach ($values as $id){
           if (!$this->isPostiveInteger($id)){
               return false;
           }
        }
        return true;
    }
}
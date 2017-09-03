<?php
namespace app\common\model;

use think\Model;

class User extends BaseModel
{
    public function add($data= []){
      //如果提交的非数组
      if(!is_array($data)){
          exception('传递的数据不是数组');
      }
      
      $data['status'] = 1;
      return $this->data($data)->allowField(true)
        ->save();
    }
    
    public function getUserByUsername($usernmae){
        if(!$usernmae){
            exception('用户名不合法');
        }
        $data = ['username' => $usernmae];
        return $this->where($data)->find();
    }
}
<?php
namespace app\common\model;

use think\Model;

class BisAccount extends BaseModel
{
    public function updataById($data, $id){
        //allowField 过滤data数组中非数据表的数据
        return $this->allowField(true)->save($data,['id'=>$id]);
    }
}
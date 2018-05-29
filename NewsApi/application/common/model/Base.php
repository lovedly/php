<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18
 * Time: 20:47
 */
namespace app\common\model;
use think\Model;

class Base extends Model{

    protected $autoWriteTimestamp =true;

    /**
     * 新增
     * @param $data
     * @return mixed
     */
    public function add($data){
        if (!is_array($data)){
            exception('数据不合法');
        }
        $this->allowField(true)->save($data);
        return $this->id;
    }

}
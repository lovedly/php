<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18
 * Time: 20:47
 */
namespace app\common\model;
use think\Model;

class Version extends Base {
    /**
     * 通过apptype获取最后一条版本内容
     * @param string $appType
     */
    public function getLastVersionByAppType($appType = ''){
        $data =[
            'status' => config('code.status_normal'),
            'app_type' =>$appType,
        ];
        $order = [
            'id' => 'desc',
        ];

        $result = $this->where($data)
            ->order($order)
            ->find();
        return $result;
    }
}
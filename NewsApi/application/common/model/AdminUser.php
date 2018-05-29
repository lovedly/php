<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18
 * Time: 20:47
 */
namespace app\common\model;
use think\Model;

class AdminUser extends Base {

    /**
     * 管理员列表分页
     * @param array $data
     * @return \think\Paginator
     */
    public function getAdminUser($data=[]){
        $data['status'] =[
            'neq' , -1,
        ];
        $order = [
            'id' => 'desc',
        ];

        $result = $this->where($data)
            ->order($order)
            ->paginate();
        //echo $this->getLastSql();
        return $result;

    }
}
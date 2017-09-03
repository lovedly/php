<?php
namespace app\common\model;

use think\Model;

class BisLocation extends BaseModel
{
    //商户中心-门店列表获取
    public function getNormalLocationByBisId($bisId){
        $order = [
            'id'=> 'des',
        ];
         
        $data = [
            'bis_id' =>$bisId,
            'status' =>1,
        ];
         
        $result = $this->where($data)
        ->order($order)
        ->paginate();
        return $result;
    }
    
    public function getNormalLocationsInId($ids){
        $data =[
            'id'=>['in', $ids],
            'status'=>1,
        ];
        return $result = $this->where($data)
            ->select();
    }

}
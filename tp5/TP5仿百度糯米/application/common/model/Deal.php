<?php
namespace app\common\model;

use think\Model;

class Deal extends BaseModel
{
    public function getNormalDeals($data=[]){
        $data['status'] =1;
        $order = [
           'id'=> 'desc', 
        ];
        $result = $this->where($data)
        ->order($order)
        ->paginate();
       // echo $this->getlastSql();
        return $result;
    }
    
    public function getApplyDealsByBisId($bisId){
         $data = [
            'bis_id' =>$bisId,
            'status' =>0,
        ];
        $order = [
            'id'=> 'desc',
        ];
        $result = $this->where($data)
        ->order($order)
        ->paginate();
        // echo $this->getlastSql();
        return $result;
    }
    
    /**
     * 获取所有商户的申请数据
     * @param  $data
     */
    public function getApllyDeals($data=[]){
        $data['status'] =0;
        $order = [
            'id'=> 'desc',
        ];
        $result = $this->where($data)
        ->order($order)
        ->paginate();
        // echo $this->getlastSql();
        return $result;
    }
    
    /**
     * 根据分类以及城市来获取商品数据
     * @param  $id 分类
     * @param  $cityId 城市 
     * @param number $limit 条数
     */
    public function getNormalDealByCategoryCityId($id,$cityId,$limit=10){
        $data = [
            'end_time' =>['gt',time()],
            'category_id'=>$id,
            'city_id'=>$cityId,
            'status'=>1,
        ];
        $order = [
            'listorder'=> 'desc',
            'id'=> 'desc',
        ];
        $result =  $this->where($data)
        ->order($order);
        if($limit){
            $result->limit($limit);
        }
        return $result->select();
    }
    
    public function getDealByConditions($data=[],$orders){
        
        if (!empty($orders['order_sales'])){
            $order['buy_count']= 'desc';
        }
        if (!empty($orders['order_price'])){
            $order['current_price']= 'desc';
        }
        if (!empty($orders['order_time'])){
            $order['create_time']= 'desc';
        }
        
        $order['id'] ='desc';
        $datas[] = "end_time >".time();
        
        if(!empty($data['se_category_id'])){
            $datas[] = " find_in_set(".$data['se_category_id'].", se_category_id)";
        }
        if (!empty($data['category_id'])){
            $datas[] ='category_id = '.$data['category_id'];
        }
        if (!empty($data['city_id'])){
            $datas[] ='city_id = '.$data['city_id'];
        }
        $datas[] = 'status=1';
        
        $result = $this->where(implode(' AND ', $datas))
                ->order($order)
                ->paginate();
        //echo $this->getLastSql();exit;
        //SELECT * FROM `o2o_deal` WHERE ( end_time >1504060283 AND find_in_set(8, se_category_id) AND city_id = 4 AND status=1 ) ORDER BY `id` desc LIMIT 0,15
        return $result;
    }
    
    public function updataBuyCountById($id,$buycount){
        return $this->where(['id'=>$id])->setInc('buy_count',$buycount);
    }
}
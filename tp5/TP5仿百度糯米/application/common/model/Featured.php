<?php
namespace app\common\model;

use think\Model;

class Featured extends BaseModel
{
    /**
     * 根据类型获取列表数据
     * @param  $type
     */
    public function getFeaturedsByType($type){
        $data = [
            'status' =>['neq',-1],
            'type' =>$type,
        ];
        
        $order =[
            'id' => 'desc',
        ];
        
        $result =  $this->where($data)
            ->order($order)
            ->paginate();
        return $result;
        } 
        
        /**
         * 获取首页大图和广告数据
         * @param  $type
         * @param  $limit
         */
        public function getToppicBytype($type,$limit=1){
            $data = [
                'status' =>['eq',1],
                'type' =>$type,
            ];
            
            $order =[
                'id' => 'desc',
            ];
            
            $result =  $this->where($data)
            ->order($order);
            if($limit){
                $result->limit($limit);
            }            
            return $result->select();
        }
        
}
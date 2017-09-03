<?php
namespace app\common\model;

use think\Model;

class Category extends Model
{
    protected $autoWriteTimestamp = true;
    public function add($data){
        $data['status'] = 1;
        //$data['create_time'] =time();
        return $this->save($data);
    }
    
    public function getNormalFirstCategory(){
        $data = [
            'status' =>1,
            'parent_id' =>0,
        ];
        
        $order =[   
            'id' => 'desc',         
        ];
        
        return $this->where($data)
            ->order($order)
            ->select();
    }
    
    public function getFirstCategorys($parentId = 0){
        $data = [
            'parent_id' =>$parentId,
            'status' =>['neq',-1],           
        ];

        $order =[
            'listorder' =>'desc',
            'id' => 'desc',
        ];
        
        $result =  $this->where($data)
            ->order($order)
            ->paginate();
      //echo $this->getLastSql();
      //SELECT * FROM `o2o_category` WHERE `parent_id` = 0 AND `status` <> -1 ORDER BY `id` desc
       return $result; 
    }
    
    public function  getNormalCategoryByParentId($parentId=0){
        $data = [
            'status' =>1,
            'parent_id' =>$parentId,
        ];
    
        $order =[
            
            'id' => 'desc',
        ];
    
        return $this->where($data)
        ->order($order)
        ->select();
    }
    
    public function getNormalRecommendCategoryByParentId($id=0,$limit=5){
        $data = [
            'parent_id' =>$id,
            'status' =>1,            
        ];
        
        $order =[
            'listorder' =>'desc',
            'id' => 'desc',
        ];
        
        $result =  $this->where($data)
            ->order($order);
        if($limit){
            $result->limit($limit);
        }
        return $result->select();
    }
    
     public function getNormalCategoryParentId($ids){
        $data = [
            'parent_id' =>['in',implode(',', $ids)],
            'status' =>1,
        ];
        
        $order =[
            'listorder' =>'desc',
            'id' => 'desc',
        ];
        
        $result =  $this->where($data)
        ->order($order)
        ->select();
        
        return $result;
    } 
}
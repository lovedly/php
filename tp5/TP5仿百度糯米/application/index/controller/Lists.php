<?php
namespace app\index\controller;
use think\Controller;

class Lists extends Base
{
    public function index()
    {      
        $firstCatIds = [];
        //首先需要一级栏目
        $categorys =model("Category")->getNormalCategoryByParentId();
        foreach ($categorys as $category){
            $firstCatIds[] = $category->id;
        }
        /* print_r($firstCatIds);exit;
        Array ( [0] => 11 [1] => 7 [2] => 6 [3] => 4 [4] => 2 [5] => 1 ) */
        $id = input('id',0, 'intval');
        $data = [];
        //id =0 一级分类 2级分类
        if(in_array($id,$firstCatIds)){   //一级分类
            //to do
            $categoryParentId = $id;
            $data['category_id'] = $id;
        }elseif ($id){    //2级分类
            //获取2级分类数据
            $category = model('Category')->get($id);
            if(!$category || $category->status != 1){
                $this->error('数据不合法');
            }
            $categoryParentId =$category->parent_id;
            $data['se_category_id'] =$id;
        }else{  //0
            $categoryParentId = 0;
        }
        
        $sedcategorys = [];
        //获取父类下的所有子分类
        if($categoryParentId){
           $sedcategorys =model('Category')->getNormalCategoryByParentId($categoryParentId);
        }
        $orders =[];
        //排序数据获取的逻辑
        $order_sales =input('order_sales','');
        $order_price =input('order_price','');
        $order_time =input('order_time','');
        if(!empty($order_sales)){
            $orderflag ='order_sales';
            $orders['order_sales'] = $order_sales;
        }elseif(!empty($order_price)){
            $orderflag ='order_price';
            $orders['order_price'] = $order_price;
        }elseif(!empty($order_time)){
            $orderflag ='order_time';
            $orders['order_time'] = $order_time;
        }else{
            $orderflag= '';
        }
        $data['city_id'] = $this->city->id;
        
        //根据上面条件来查询商品列表数据
        $deals =model('Deal')->getDealByConditions($data, $orders);
        
        return $this->fetch('',[
            'categorys'=>$categorys,
            'sedcategorys'=>$sedcategorys,
            'id'=>$id,
            'categoryParentId'=>$categoryParentId,
            'orderflag'=>$orderflag,
            'deals'=>$deals,
        ]);   
    }
}

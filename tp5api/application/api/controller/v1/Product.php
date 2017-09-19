<?php
namespace app\api\controller\v1;

use app\api\validate\Count;
use app\api\validate\IDMustBePostiveInt ;
use app\api\model\Product as ProductModel;
use app\lib\exception\ProductException;

class Product
{
    public function getRecent($count=15){
        (new Count())->gocheck();     
        $products = ProductModel::getMostRecent($count);
        if($products->isEmpty()){
            throw new ProductException();
        }
        $products =$products->hidden(['summary']); //数据集临时隐藏
        return $products;
    }
    
    public function getAllInCategory($id){
        (new IDMustBePostiveInt())->gocheck();
        $products = ProductModel::getProductsByCategoryID($id);
        if($products->isEmpty()){
            throw new ProductException();
        }
        $products =$products->hidden(['summary']);
        return $products;
    }
    
}
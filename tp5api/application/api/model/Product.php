<?php
namespace app\api\model;
use think\Model;

class Product extends BaseModel
{
    protected $hidden = ['delete_time','create_time',
        'pivot','from','update_time','category_id'];
    
    public function getMainImgUrlAttr($value,$data)
    {
        return $this->prefixImgUrl($value,$data);
    }
    
    public static function getMostRecent($count){
         $products =self::limit($count)
            ->order('create_time desc')
            ->select();
         return $products;         
    }
    
    public static function getProductsByCategoryID($categoryID){
        $products = self::where('category_id','=',$categoryID)
            ->select();
        return $products;
    }
}
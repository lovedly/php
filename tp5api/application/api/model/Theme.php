<?php
namespace app\api\model;
use think\Model;

class Theme extends BaseModel
{
    protected $hidden = ['update_time','delete_time',
                        'topic_img_id','head_img_id'];
    public function topicImg()
    {
        //如果有外键则用belongsTo否则hasone
        return $this->belongsTo('Image','topic_img_id','id');
        
    }
    
    public function headImg()
    {
        return $this->belongsTo('Image','head_img_id','id');
    }
    
    public function products(){
        return $this->belongsToMany('Product','theme_product',
            'product_id','theme_id');
    }
    
    public static function getThemeWithProducts($id)
    {
        //或者 $theme =self::with('products',topicImg,headImg')
        $theme =self::with(['products','topicImg','headImg'])
            ->find($id);
        return $theme;
    }
    
}
<?php
namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    //读取器   
        protected function prefixImgUrl($value,$data){
        $finaUrl = $value;
        if($data['from'] ==1){
            $finaUrl =  config('setting.img_prefix').$value;
        }
        return $finaUrl;
    }
}
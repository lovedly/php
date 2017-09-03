<?php
namespace app\common\validate;
use think\Validate;
class BisLocation extends Validate{
    protected  $rule = [
        ['name','require','店名不能为空!'],
        ['logo','require','缩略图不能为空!'],
        ['city_id','require','所属城市不能为空!'],
        'tel'=>'require|number',
        'contact'=>'require',
        'category_id'=>'require',
        'api_address'=>'require',
        'open_time'=>'require',
        'content'=>'require',  
        
        
    ];
    
    /*
     * 场景设置
     */
    protected $scene = [
        'add' =>['tel','contact','category_id','address','open_time','content'],
        'bisadd'=>['name','logo','city_id','tel','contact','category_id','address','open_time','content']
    ];
}
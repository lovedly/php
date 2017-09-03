<?php
namespace app\common\validate;
use think\Validate;
class Deal extends Validate{
    protected  $rule = [
        ['name','require','商品名不能为空!'],
        ['city_id','require','城市不能为空!'],
        ['category_id','require','分类不能为空!'],
        ['location_ids','require','支持门店不能为空!'],
        ['image','require','缩略图不能为空!'],
        ['location_ids','require','支持门店不能为空!'],
        ['start_time','require','团购开始时间不能为空!'],
        ['end_time','require','团购结束时间不能为空!'],
        ['total_count','require','库存数不能为空!'],
        ['origin_price','require','原价不能为空!'],
        ['current_price','require','团购价不能为空!'],
        ['coupons_begin_time','require','消费券生效时间不能为空!'],
        ['coupons_end_time','require','消费券结束时间不能为空!'],
        ['description','require','团购描述不能为空!'], 
        ['notes','require','购买须知不能为空!'],
    ];
    
    /*
     * 场景设置
     */
    protected $scene = [
        'add' =>['name','city_id','category_id','location_ids','image','location_ids','start_time','end_time','total_count','origin_price','current_price','coupons_begin_time','coupons_end_time','description','notes'],  
    ];
}
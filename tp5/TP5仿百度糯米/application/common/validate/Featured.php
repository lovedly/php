<?php
namespace app\common\validate;
use think\Validate;
class Featured extends Validate{
    protected  $rule = [
        ['title','require','标题不能为空!'],
        ['image','require','推荐图不能为空!'],
        ['type','require','推荐位不能为空!'],
        ['url','require','url不能为空!'],
        ['description','require','描述不能为空!'],
        
    ];
    
    /*
     * 场景设置
     */
    protected $scene = [
        'add' =>['title','image','type','url','description'],  
    ];
}
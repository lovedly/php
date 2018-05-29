<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 14:01
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\exception\ApiException;
use think\Controller;
class Cat extends Common{
    /**
     * 栏目接口
     */
    public function read(){
        $cats = config('cat.lists');
        $result[] = [
            'catid' =>0,
            'catname'=>'首页',
        ];
        foreach ($cats as $catid =>$catname){
            $result[] =[
                'catid' =>$catid,
                'catname'=>$catname,
            ];
        }
        return show(config('code.success'), 'Ok', $result, 200);
    }

}
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
class Rank extends Common{
    /**
     * 获取排行榜数据列表
     */
    public function index(){
        try{
            $ranknews = model('News')->getRankNormalNews();
        }catch (\Exception $e){
            return new ApiException('error', 400);
        }
        $ranknews = $this->getDealNews($ranknews);
        return show(config('code.success'), 'Ok', $ranknews, 200);
    }

}
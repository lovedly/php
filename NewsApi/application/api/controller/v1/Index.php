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
class Index extends Common{
    /**
     * 获取首页接口
     * 1.头图 4-6
     * 2.推荐位列表 默认40条
     */
    public function index(){
        $heads = model('News')->getIndexHeadNormalNews();
        $heads = $this->getDealNews($heads);

        $positions = model('News')->getPositionNormalNews();
        $positions = $this->getDealNews($positions);

        $result = [
            'heads' => $heads,
            'positions' =>$positions,
        ];
        return show(config('code.success'), 'Ok', $result, 200);
    }

    /**
     * 客户端初始化接口
     * 1.检测APP是否需要升级
     */
    public function init(){
        //app_type 去ent_version 查询
        $appType = $this->headers['app_type'];
        $version = model('Version')->getLastVersionByAppType($appType);
        if (empty($version)){
            return new ApiException('error', 400);
        }
        if ($version->version >$appType){
            $version->is_update = 1;
        }else{
            $version->is_update = 0;  //0不更新  1需要更新
        }

        //记录用户基本信息  用于统计
        $actives = [
            'version' =>$this->headers['version'],
            'app_type'=>$this->headers['app_type'],
            'did'     =>$this->headers['did'],
            'version_code'=>$this->headers['version_code'],
            'model'     =>$this->headers['model'],
        ];
        try{
            model('AppActive')->add($actives);
        }catch (\Exception $e){
            //return new ApiException('error', 400);
        }
        return show(config('code.success'), 'Ok', $version, 200);
    }

}
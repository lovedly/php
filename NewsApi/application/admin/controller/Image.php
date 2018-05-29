<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 14:01
 */
namespace app\admin\controller;

use think\Controller;
use think\Request;

/**
 * 后台图片上传相关逻辑
 * Class Image
 * @package app\admin\controller
 */
class Image extends Base{

    public function index(){
        
        return $this->fetch();
    }

    /**
     * 图片上传
     */
    public function upload(){
        $file = Request::instance()->file('file');
        $info = $file->move('upload');
        if ($info && $info->getPathname()){
            $data = [
                'status' => 1,
                'msg'    =>'OK',
                'data'   =>'/'.$info->getPathname(),
            ];
            echo json_encode($data);exit;
        }
        echo json_encode(['status' => 0, 'msg'=>'上传失败']);
        
    }

    /**
     * 七牛图片上传
     */
    public function upload1(){
        // 捕获异常
        try{
            // 返回qiniu上的文件名
            $image = Upload::image();
        }catch(\Exception $e){
            echo json_encode(['status'=>0,'message'=>$e->getMessage()]);
        }
        // 返回给uploadify插件状态
        if($image){
            $data = [
                'status' => 1,
                'message' => 'OK',
                'data' => config('qiniu.image_url').'/'.$image,
            ];
            echo json_encode($data);exit;
        }else{
            echo json_encode(['status'=>0,'message'=>'上传失败']);
        }
    }
}
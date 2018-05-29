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
class News extends Common{

    public function index(){
        $data = input('get.');
        //validate
      
        $whereData['status'] = config('code.status_normal');
        if (!empty($data['catid'])){
            $whereData['catid'] =input('get.catid', 0 ,'intval');
        }
        if (!empty($data['title'])){
            $whereData['title'] =['like','%'.$data['title'].'%'];
        }
        try{
            $news = model('News')->getNewsByCondition($whereData);
        }catch (\Exception $e){
            return new ApiException('error', 400);
        }
        $result = $this->getDealNews($news);
        return show(config('code.success'), 'Ok', $result, 200);
    }

    public function read(){
        $id = input('param.id', 0 ,'intval');
        if (empty($id)){
            return new ApiException('id is not', 404);
        }
        try{
            $news = model('News')->get($id);
        }catch (\Exception $e){
            return new ApiException('error', 400);
        }
        if (empty($news) || $news->status !=config('code.status_normal')){
            return new ApiException('新闻不存在', 404);
        }
        try{
            model('News')->where(['id' => $id])->setInc('read_count');
        }catch (\Exception $e){
            return new ApiException('error', 400);
        }
        $cats  =config('cat.lists');
        $news->catname = $cats[$news->catid];
        return show(config('code.success'), 'Ok', $news, 200);
    }

}
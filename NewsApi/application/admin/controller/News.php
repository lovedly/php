<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 14:01
 */
namespace app\admin\controller;

use think\Controller;

class News extends Base{

    public function index(){
        $data = input('get.');
        $whereData = [];
        if (!empty($data['catid'])){
            $whereData['catid'] =intval($data['catid']);
        }

        if (!empty($data['start_time'])  && !empty($data['end_time'])  && $data['end_time'] >$data['start_time']){
            $whereData['update_time'] = [
                'gt', $data['start_time'],
                'lt', $data['end_time'],
            ];
        }

        if (!empty($data['title'])){
            $whereData['title'] =['like','%'.$data['title'].'%'];
        }


        $news = model('News')->getNewsByCondition($whereData);


        return $this->fetch('',[
            'news' =>$news,
            'cats' =>config('cat.lists'),
            'start_time'=>empty($data['start_time']) ? '' : $data['start_time'],
            'end_time'=>empty($data['end_time']) ? '' : $data['end_time'],
            'title'=>empty($data['title']) ? '' : $data['title'],
            'catid'=>empty($data['catid']) ? '' : $data['catid'],
        ]);
    }

    public function add(){
        if (request()->isPost()){
            
            $data = input('post.');
            //validate
            try{
                $id = model('News')->add($data);
            }catch (\Exception $e){
                return $this->result('', 0 , '新增失败');
            }
            if ($id){
                return $this->result(['jump_url' =>url('news/index')], 1, 'ok');
            }else{
                return $this->result('', 0 , '新增失败');
            }
        }else{
            return $this->fetch('',[
                'cats' =>config('cat.lists')
            ]);
        }

    }

}
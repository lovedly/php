<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 14:01
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\Aes;
use app\common\lib\exception\ApiException;
use app\common\lib\IAuth;
use think\Controller;
class Upvote extends AuthBase{
    /**
     * 新闻点赞功能
     */
    public function save(){
        $id = input('post.id' , 0 , 'intval');
        if (empty($id)){
            return show(config('code.error'), 'id不存在', [], 404);
        }

        //判定这个id的新闻文章是否存在
        try{
            $news = model('News')->get(['id'=>$id]);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),[],500);
        }
        if (!$news || $news->status !=config('code.status_normal')){
            return show(config('code.error'),'新闻不存在',[],404);
        }

        //查询是否存在点赞
        $data = [
            'user_id' => $this->user->id,
            'news_id' => $id,
        ];


        try{
            $userNews = model('UserNews')->get($data);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),[],500);
        }
        if ($userNews){
            return show(config('code.error'), '已经点赞,不能再次点赞', [], 401);
        }

        try{
            $userNewsId = model('UserNews')->add($data);
            if ($userNewsId){
                model('News')->where(['id' =>$id])->setInc('upvote_count');
                return show(config('code.success'),'Ok',[],202);
            }else{
                return show(config('code.error'),'内部错误,点赞失败',[],500);
            }
        }catch (\Exception $e){
            return show(config('code.error'),'内部错误,点赞失败',[],500);
        }
    }

    public function delete(){
        $id = input('delete.id', 0, 'intval');
        if (empty($id)){
            return show(config('code.error'),'id不存在',[],404);
        }
        //判定这个id的新闻文章是否存在
        try{
            $news = model('News')->get(['id'=>$id]);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),[],500);
        }
        if (!$news || $news->status !=config('code.status_normal')){
            return show(config('code.error'),'新闻不存在',[],404);
        }

        //判定是否存在点赞
        $data = [
            'user_id' =>$this->user->id,
            'news_id' =>$id,
        ];
        try{
            $userNews = model('UserNews')->get($data);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),[],500);
        }
        if (empty($userNews)){
            return show(config('code.error'),'您没有点赞,无法取消',[],401);
        }

        try{
            $userNewsId = model('UserNews')
                ->where($data)
                ->delete();
            if ($userNewsId){
                model('News')->where(['id' =>$id])->setDec('upvote_count');
                return show(config('code.success'),'Ok',[],202);
            }else{
                return show(config('code.error'),'取消失败',[],500);
            }
        }catch (\Exception $e){
            return show(config('code.error'),'内部错误,取消点赞失败',[],500);
        }
    }

    /**
     * 查看文章是否被该用户点赞
     */
    public function read(){
        $id = input('param.id', 0, 'intval');
        if (empty($id)){
            return show(config('code.error'),'id不存在',[],404);
        }
        //判定这个id的新闻文章是否存在
        try{
            $news = model('News')->get(['id'=>$id]);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),[],500);
        }
        if (!$news || $news->status !=config('code.status_normal')){
            return show(config('code.error'),'新闻不存在',[],404);
        }
        //判定是否存在点赞
        $data = [
            'user_id' =>$this->user->id,
            'news_id' =>$id,
        ];
        try{
            $userNews = model('UserNews')->get($data);
        }catch (\Exception $e){
            return show(config('code.error'),$e->getMessage(),[],500);
        }
        if ($userNews){
            return show(config('code.success'),'OK',['isUpvote' =>1],200);
        }else{
            return show(config('code.success'),'OK',['isUpvote' =>0],200);
        }

    }



}
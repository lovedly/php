<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2
 * Time: 21:31
 */
namespace app\api\controller\v1;

use app\common\lib\Aes;
use app\common\lib\Alidayu;
use app\common\lib\exception\ApiException;
use app\api\controller\Common;
use app\common\lib\IAuth;
use think\Controller;

class Comment extends AuthBase {
    /**
     * 评论 -  回复功能开发
     */
    public function save(){
        $data = input('post.',[]);
        //print_r($data);exit;
         //validate
        //news_id
        $id = $data['news_id'];
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

        $data['user_id'] =$this->user->id;

        try{
            $CommentId = model('Comment')->add($data);
            if ($CommentId){
                //model('News')->where(['id' =>$id])->setInc('upvote_count');
                return show(config('code.success'),'Ok',[],202);
            }else{
                return show(config('code.error'),'评论失败',[],500);
            }
        }catch (\Exception $e){
            return show(config('code.error'),'内部错误,评论失败',[],500);
        }
    }

    /**
     *评论列表
     */
    public function read(){
        //select * from ent_comment as a join ent_user as b on a.user_id =b.id  and  a.news_id = 7;
        //print_r(input('param.'));exit;
        $newsId = input('param.id',0,'intval');
        //halt($newsId);
        if (empty($newsId)){
            return show(config('code.error'),'id不存在',[],404);
        }
        $param['news_id']  = $newsId;
        $count = model('Comment')->getCountByCondition($param);
        //halt($count);
        $comments = model('Comment')->getListsByCondition($param);
        //print_r($comments);exit;
        if ($comments){
            foreach ($comments as $comment){
                $userIds[] = $comment['user_id'];
                if ($comment['to_user_id']){
                    $userIds[] = $comment['to_user_id'];
                }
            }
            $userIds = array_unique($userIds);
            //print_r($userIds);exit;
        }
        //halt($userIds);
        $userIds = model('User')->getUsersByUserId($userIds);
        //print_r($userIds);exit;
        if (empty($userIds)){
            $userIdNames = [];
        }else{
            foreach ($userIds as $userId){
                $userIdNames[$userId->id] = $userId;
            }
        }
        //print_r($userIdNames);exit;

        $resultDatas = [];
        foreach ($comments as $comment){
            $resultDatas[] =[
                'id' =>$comment->id,
                'user_id'=>$comment->user_id,
                'content'=>$comment->content,
                'to_user_id'=>$comment->to_user_id,
                'create_time'=>$comment->create_time,
                'parent_id'=>$comment->parent_id,
                'username' =>!empty($userIdNames[$comment->user_id]) ? $userIdNames[$comment->user_id]->username : '',
                'tousername' =>!empty($userIdNames[$comment->to_user_id]) ? $userIdNames[$comment->to_user_id]->username : '',
                'image' =>!empty($userIdNames[$comment->user_id]) ? $userIdNames[$comment->user_id]->image : '',
            ];
        }

        $result = [
            'total' =>$count,
            'list' =>$resultDatas,
        ];

        return show(config('code.success'),'Ok',$result,200);
    }
}
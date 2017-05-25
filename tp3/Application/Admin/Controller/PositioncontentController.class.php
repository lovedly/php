<?php
/**
 * 后台Index相关
 */
namespace Admin\Controller;
use Think\Controller;

class PositioncontentController extends CommonController { 
    public function index(){
        $positions = D("Position")->getNormalPositions();
        /* print_r($positions);exit;
        [id] => 2
        [name] => 首页大图
        [status] => 1
        [description] => 展示首页大图
        [create_time] => 1455634715
        [update_time] => 0
        [id] => 3
        [name] => 小图推荐
        [status] => 1
        [description] => 小图推荐位
        [create_time] => 1456665873
        [update_time] => 0 */
        //获取推荐位内容
        $data['status']=array('neq',-1);
        if($_GET['title']){
            $data['title'] = trim($_GET['title']);
            $this->assign('title',$data['title']); /* 展示到搜索框中 */
        }
        $data['position_id'] = $_GET['position_id'] ? intval($_GET['position_id']):$positions[0]['id'];
        /* 分页 */
    /*     $page =$_REQUEST['p']? $_REQUEST['p']:1;
        $pageSize = 2;
        $conds['status'] = array('eq',1);
        
        $news = D("News")->getNews($conds,$page,$pageSize);
        $count = D("News")->getNewsCount($conds);
        
        $res = new \Think\Page($count,$pageSize);
        
        $pageres = $res->show(); */
        
        $contents = D("PositionContent")->select($data);
        $this->assign('contents',$contents);
        
        $this->assign('positions',$positions);
        $this->assign('positionId',$data['position_id']);
    	$this->display();
    }
    
    public function add(){
        if($_POST){
            if (!isset($_POST['position_id']) || !$_POST['position_id']){
                return show(0,'推荐位不能为空');
            }
            if (!isset($_POST['title']) || !$_POST['title']){
                return show(0,'标题不能为空');
            }
            if (!isset($_POST['url']) && !$_POST['news_id']){
                return show(0,'地址和文章id不能同时为空');
            }
            if (!isset($_POST['thumb']) || !$_POST['thumb']){
                if ($_POST['news_id']){
                    $res = D("News")->find($_POST['news_id']);
                    if ($res && is_array($res)){
                        $_POST['thumb'] = $res['thumb'];
                    }
                }else{
                    return show(0,'图片不能为空');
                }
            }
            //如果是修改操作
            if($_POST['id']){
                return $this->save($_POST);
            }
            try{
                $id = D("PositionContent")->insert($_POST);
                if($id){
                    return show(1,'新增成功');
                }
                return show(0,'新增失败');
            }catch(Exception $e) {
                return show(0,$e->getMessage());
            }
            }else{
                $positions = D("Position")->getNormalPositions();
            
                $this->assign('positions',$positions);
                $this->display();
            } 
        
    }
    
    public function edit(){
        $id = $_GET['id'];
        if (!$id){
            //执行跳转
            $this->redirect('/admin.php?c=positioncontent');
        }
        $position = D("PositionContent")->find($id);
        $positions = D("Position")->getNormalPositions();
        
        $this->assign('positions',$positions);
        $this->assign('vo',$position);
        $this->display();
    }
    
    public function save($data){
        $id = $data['id'];
        unset ($data['id']);
        
        try{
            $resid = D("PositionContent")->updateById($id,$data);
            if($resid ===false){
                return show(0,'更新失败');
            }
            return show(1,'更新成功');
        }catch(Exception $e) {
            return show(0,$e->getMessage());
        }
        
    }
    
    public function setStatus(){
        $data =array(
          'id'=>intval($_POST['id']),
          'status'=>intval($_POST['status']),
        );
        return parent::setStatus($data,'PositionContent');
    }
    
    public function listorder(){
        return parent::listorder("PositionContent");
    }
}
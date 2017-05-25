<?php
namespace Admin\Controller;
use Think\controller;

class ContentController extends CommonController{
    public function index(){
        $conds = array();
        $title=$_GET['title'];
        if($title){
            $conds['title'] = $title;
        }
        if($_GET['catid']){
            $conds['catid'] = intval($_GET['catid']);
        }
        /* 分页 */
        $page =$_REQUEST['p']? $_REQUEST['p']:1;
        $pageSize = 5;
        $conds['status'] = array('neq',-1);
        
        $news = D("News")->getNews($conds,$page,$pageSize);
        $count = D("News")->getNewsCount($conds);
        
        $res = new \Think\Page($count,$pageSize);
        
        $pageres = $res->show();
        /*print_r($news);exit;
        [news_id] => 24
        [catid] => 3
        [title] => 中超-汪嵩世界波制胜 富力客场1-0力擒泰达
        [small_title] => 中超-汪嵩世界波制胜 富力客场1-0力擒泰达
        [title_font_color] =>
        [thumb] => /upload/2016/03/13/56e51fc82b13a.png
        [keywords] => 中超 汪嵩世界波  富力客场 1-0力擒泰达
        [description] => 中超-汪嵩世界波制胜 富力客场1-0力擒泰达
        [posids] =>
        [listorder] => 19
        [status] => 1
        [copyfrom] => 0
        [username] => admin
        [create_time] => 1457856460
        [update_time] => 0
        [count] => 39 */
        
        /* 推送到推荐位,获取推荐位 */
        $positions  =D("Position")->getNormalPositions();
       /*  print_r($positions);exit;
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
        
        $this->assign('pageres',$pageres);
        $this->assign('news',$news);
        $this->assign('webSiteMenu',D("Menu")->getBarMenus());
        $this->assign('catId',$conds['catid']);
        $this->assign('title',$conds['title']);   /* 搜索字段保留 */
        $this->assign('positions',$positions);
        $this->display();
    }
    public function add(){
        if ($_POST){
            if(!isset($_POST['title']) || !$_POST['title']) {
                return show(0,'标题不存在');
            }
            if(!isset($_POST['small_title']) || !$_POST['small_title']) {
                return show(0,'短标题不存在');
            }
            if(!isset($_POST['catid']) || !$_POST['catid']) {
                return show(0,'文章栏目不存在');
            }
            if(!isset($_POST['keywords']) || !$_POST['keywords']) {
                return show(0,'关键字不存在');
            }
            if(!isset($_POST['content']) || !$_POST['content']) {
                return show(0,'content不存在');
            } 
            
            //如果是修改操作
            if($_POST['news_id']){
                return $this->save($_POST);
            }
            $newsId = D("News")->insert($_POST);
            if($newsId){
                $newsContentData['content'] = $_POST['content'];
                $newsContentData['news_id'] = $newsId;
                $cId = D("NewsContent")->insert($newsContentData);
                if($cId){
                    return show(1,'新增成功');
                }else{
                    return show(1,'主表插入成功,副表插入失败');
                }
            }else{
                    return show(0,'新增失败');
            }
        }else{
            $webSiteMenu = D("Menu")->getBarMenus();
           /*  print_r($webSiteMenu);exit;
    [0] => Array(
            [menu_id] => 4
            [name] => 科技
            [parentid] => 0
            [m] => home
            [c] => cat 
            [f] => index
            [data] => 
            [listorder] => 17
            [status] => 1
            [type] => 0)
    [1] => Array
    ([menu_id] => 3
            [name] => 体育
            [parentid] => 0
            [m] => home
            [c] => cat
            [f] => index
            [data] => 
            [listorder] => 8
            [status] => 1
            [type] => 0)

    [2] => Array(
            [menu_id] => 5
            [name] => 汽车
            [parentid] => 0
            [m] => home
            [c] => cat
            [f] => index
            [data] => 
            [listorder] => 7
            [status] => 1
            [type] => 0   ) */

            $titleFontColor = C("TITLE_FONT_COLOR");
            $copyFrom =C("COPY_FROM");
            $this->assign('webSiteMenu',$webSiteMenu);
            $this->assign('titleFontColor',$titleFontColor);
            $this->assign('copyFrom',$copyFrom);
            $this->display();    
        }
    }
    public function edit(){
        $newsId = $_GET['id'];
        if(!$newsId){
            //执行跳转
            $this->redirect('/admin.php?c=content');    
        }
        $news = D("News")->find($newsId);
        if(!$news){
            $this->redirect('/admin.php?c=content');  
        }
        $newsContent=D("NewsContent")->find($newsId);
        if($newsContent){
            $news['content'] = $newsContent['content'];
        }
       /*  print_r($news);exit;
        [news_id] => 18
        [catid] => 3
        [title] => 你好ssss
        [small_title] => 你好
        [title_font_color] => #ed568b
        [thumb] => /upload/2016/03/06/56dbdc015e662.JPG
        [keywords] => 你好
        [description] => 你好sssss  ss
        [posids] =>
        [listorder] => 2
        [status] => 1
        [copyfrom] => 3
        [username] => admin
        [create_time] => 1455756999
        [update_time] => 0
        [count] => 1
        [content] => &lt;p&gt;
        你好 */
        
        $titleFontColor = C("TITLE_FONT_COLOR");
        $copyFrom =C("COPY_FROM");
        $webSiteMenu = D("Menu")->getBarMenus();
        $this->assign("webSiteMenu",$webSiteMenu);
        $this->assign('titleFontColor',$titleFontColor);
        $this->assign('copyFrom',$copyFrom);
        $this->assign('news',$news);
        $this->display();
    }
    public function save($data){
        $newsId = $data['news_id'];
        unset($data['news_id']);
        try{
            $id = D("News")->updateById($newsId,$data);
            $newsContentData['content'] = $data['content'];
            $condId = D("NewsContent")->updateNewsById($newsId,$newsContentData);
           
            if($id === false || $condId === false){
                return show(0,'更新失败');
            }
            return show(1,'更新成功');
       }catch (Exception $e){
            return show(0,$e->getMessage());
       }
    
    }
    public function setStatus(){
        $data = array(
            'id' => intval($_POST['id']),
            'status' => intval($_POST['status']),
        );
        return parent::setStatus($data,'News');
    }
    public function listorder(){
        return parent::listorder("News");
    }
    
    public function push(){
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        $positionId = intval($_POST['position_id']);
        
        $newsId = $_POST['push'];
        /* print_r($newsId);exit;
        Array ( [0] => 21 [1] => 41 ) */
        if(!$newsId || !is_array($newsId)){
            return show(0,'请选择推荐的文章ID进行推荐');
        }
        if(!$positionId){
            return show(0,'没有选择推荐位');
        }
        try{
            $news = D("News")->getNewsByNewsIdIn($newsId);
            
            if(!$news){
                return show(0,',没有相关内容');
            }
            foreach($news as $new){
                $data = array(
                    'position_id'=>$positionId,
                    'title'=>$new['title'],
                    'thumb'=>$new['thumb'],
                    'news_id'=>$new['news_id'],
                    'status'=>1,
                    'create_time'=>$new['create_time'],   
                );  
                $position = D("PositionContent")->insert($data);
            }
        }catch (Exception $e){
            return show(0,$e->getMessage());
        }
        return show(1,'推荐成功',array('jump_url'=>$jumpUrl));
    }
}
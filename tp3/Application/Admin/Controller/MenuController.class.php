<?php
/* 后台菜单 */
namespace Admin\Controller;
use Think\controller;

class MenuController extends CommonController{
    public function index(){
         $navs = D("Menu")->getAdminMenus();
        /*print_r($navs);exit;
            [menu_id] => 9
            [name] => 推荐位管理
            [parentid] => 0
            [m] => admin
            [c] => position
            [f] => index
            [data] =>
            [listorder] => 6
            [status] => 1
            [type] => 1 */   
        $data =array();
        if(isset($_REQUEST['type']) &&in_array($_REQUEST['type'],array(0,1))){ 
            $data['type'] = intval($_REQUEST['type']);
            $this->assign('type',$data['type']);
        }else{
            $this->assign('type',-1);
        }
        /**
        * 分页操作
        */
        $page = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $pageSize = $_REQUEST['pageSize'] ? $_REQUEST['pageSize'] : 3;
        /* 菜单数据总数 */
        $menus= D("Menu")->getMenus($data,$page,$pageSize);
        $menusCount = D("Menu")->getMenusCount($data);
        
        $res = new \Think\Page($menusCount,$pageSize);
        $pageRes = $res->show();
 
        
        /* print_r($menus);exit; 主要数据
        [menu_id] => 17
        [name] => 菜单
        [parentid] => 0
        [m] => 模块
        [c] => 控制器
        [f] => 方法
        [data] =>
        [listorder] => 0
        [status] => 1
        [type] => 1 */
        $this->assign('pageRes',$pageRes);
        $this->assign('menus',$menus);
        
        $this->display();
    }
    public function add(){
        if ($_POST){
            if(!isset($_POST['name']) || !$_POST['name']) {
                return show(0,'菜单名不能为空');
            }
            if(!isset($_POST['m']) || !$_POST['m']) {
                return show(0,'模块名不能为空');
            }
            if(!isset($_POST['c']) || !$_POST['c']) {
                return show(0,'控制器不能为空');
            }
            if(!isset($_POST['f']) || !$_POST['f']) {
                return show(0,'方法名不能为空');
            }
            
            /* 如果是编辑模式 */
            if($_POST['menu_id']){
                return $this->save($_POST);
            }
            $menuId = D("Menu")->insert($_POST);
            if ($menuId){
                return show(1,'新增成功',$menuId);
                /* {"status":1,"message":"\u65b0\u589e\u6210\u529f","data":"17"} */
            }
            return show(0,'新增失败',$menuId);
        }else{
            $this->display();
        }
    }
    
    public function edit(){
        $menuId = $_GET['id'];
        $menu = D("Menu")->find($menuId);
       /*  print_r($menu);exit;
        [menu_id] => 11
        [name] => 基本管理
        [parentid] => 0
        [m] => admin
        [c] => basic
        [f] => index
        [data] =>
        [listorder] => 2
        [status] => 1
        [type] => 1 */
        $this->assign('menu',$menu);
        $this->display();
    }
    public function save($data){
        $menuId = $data['menu_id'];
        
        unset($data['menu_id']);
        try{
            $id = D("Menu")->updateMenuById($menuId,$data);
            if ($id === fales){
                return show(0,'更新失败');
            }
            return show(1,'更新成功');
        }catch (Exception $e){
            return show(0,$e->getMessage());
        }       
    }
    /* 优化处理
    public function setStatus(){
        try{
            if($_POST){
                $id =$_POST['id'];
                $status = $_POST['status'];
                // 执行数据更新操作       
                $res = D("Menu")->updateStatusById($id,$status);
                
                if($res){
                    return show(1,'操作成功');
                }else{
                    return show(0,'操作失败 ');
                }
            }
        }catch(Excetion $e){
                return show(0,$e->getMessage());
        }
        return show(0,'没有提交的数据');
    } */
    
    
    public function setStatus(){
        $data = array(
            'id' =>intval($_POST['id']),
            'status'=> intval($_POST['status']),
        );
        return parent::setStatus($data,'Menu');
    }
    
   // public function listorder(){
        /* print_r($_POST);exit;
        [listorder] => Array(
            [11] => 2
            [10] => 4
            [9] => 6   ) */
       /*  $listorder = $_POST['listorder'];
        $errors = array();
        $jumpUrl = $_SERVER['HTTP_REFERER'];
        if($listorder){
            try{
                foreach($listorder as $menuId=>$v){
                    //执行更新
                    $id = D("Menu")->updateMenuListorderById($menuId,$v);
                    if($id === false){
                        $errors[] = $menuId;
                    }
                }
            }catch(Excetion $e){
                    return show(0,$e->getMessage(),array('jump_url'=>$jumpUrl));
            }
            if($errors){
                return show(0,'排序失败-'.implode(',',$errors),array('jump_url'=>$jumpUrl));
            }
            return show(1,'排序成功',array('jump_url'=>$jumpUrl));
        }
        return show(0,'排序数据失败',array('jump_url'=>$jumpUrl));
    } */
    public function listorder(){
        return parent::listorder("Menu");
    }
        
}
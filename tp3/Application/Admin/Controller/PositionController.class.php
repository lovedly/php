<?php
namespace Admin\Controller;
use Think\Controller;
class PositionController extends CommonController {
    
    public function index(){
        $data['status'] = array('neq',-1);
        $positions = D("Position")->select($data); 
        $this->assign('positions',$positions);
    	$this->display();
    }

   public function add(){
        if(IS_POST){
           if(!isset($_POST['name']) || !$_POST['name']){
               return show(0,'推荐位名称不能为空');
           }
           if($_POST['id']) {
               return $this->save($_POST);
           }
           try {
               $id =  D("Position")->insert($_POST);
               if($id){
                   return show(1,'新增成功');
               }
               return show(0,'新增失败',$id);
           }catch(Exception $e) {
               return show(0, $e->getMessage());
           } 
           return show(0,'新增失败',$newsId);
      }else{ 
           $this->display();
      }
   }
   
   public function edit(){
       $id  = $_GET['id'];
       $position = D("Position")->find($id);
       $this->assign('vo',$position); 
       $this->display();
   }
   
    public function save($data) {
       $id = $data['id'];
       unset($data['id']);
       try {
           $id = D("Position")->updateById($id,$data);
           if($id === false) {
               return show(0,'更新失败');
           }
           return show(1,'更新成功');
       }catch (Exception $e) {
           return show(0,$e->getMessage());
       }
   } 
   
   
   public function setStatus(){
       $data = array(
          'id' =>intval($_POST['id']),
          'status' =>intval($_POST['status']),       
       );
       return parent::setStatus($data,'Position');
   }
    
}
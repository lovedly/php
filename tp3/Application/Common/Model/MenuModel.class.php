<?php
namespace Common\Model;
use Think\Model;

class MenuModel extends  Model {
    private $_db = '';
    public function __construct() {
        $this->_db = M('menu');
    }
    
    public function insert($data=array()){
       if(!$data || !is_array($data)){
           return 0;
       }
       return $this->_db->add($data);
    }
    
    public function getMenus($data,$page,$pagesize=10){
        $data['status'] = array('neq',-1);
        $offset = ($page - 1) * $pagesize;
        $list = $this->_db->where($data)->order('listorder desc,menu_id desc')->limit($offset,$pagesize)
        ->select();
        return $list;
    }
    
    public function getMenusCount($data=array()){
        $data['status'] = array('neq',-1);
        return $this->_db->where($data)->count();
    }
    public function find($id){
        if(!$id || !is_numeric($id)){
            return array();
        }
        return $this->_db->where('menu_id='.$id)->find();
    }
    public function updateMenuById($id,$data){
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!$data || !is_array($data)){
            throw_exception('更新的数据不合法');
        }
        return $this->_db->where('menu_id='.$id)->save($data);
    }
    public function updateStatusById($id,$status){
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        if(!$id || !is_numeric($status)){
            throw_exception('状态不正常');
        }
        $data['status'] = $status;
        return $this->_db->where('menu_id='.$id)->save($data);
    }
    public function updateListorderById($id,$listorder){
        if(!$id || !is_numeric($id)){
            throw_exception('ID不合法');
        }
        $data =array(
          'listorder' =>intval($listorder), 
        );
        return $this->_db->where('menu_id='.$id)->save($data);
    }
    
    public function getAdminMenus(){
        $data =array(
           'status'=> array('eq',1),
           'type' =>1,
        );
        return $this->_db->where($data)->order('listorder desc,menu_id desc')->select();
    }
    /**
     * 获取前台数据
     */
    public function getBarMenus(){
        $data =array(
           'status' =>1,
            'type' =>0,
        );
        $res =  $this->_db->where($data)
        ->order('listorder desc,menu_id desc')
        ->select();
       /*  print_r($res);exit;
        [menu_id] => 4
        [name] => 科技
        [parentid] => 0
        [m] => home
        [c] => cat
        [f] => index
        [data] =>
        [listorder] => 17
        [status] => 1
        [type] => 0 */
        return $res;
    }
}
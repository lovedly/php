<?php
namespace Common\Model;
use Think\Model;

class PositionModel extends Model{
    private $_db='';
    public function  __construct(){
        $this->_db = M("position");
    }
   
    public function select($data=array()){
        $condition = $data;
        $list = $this->_db->where($condition)->order('id')->select();
        return $list;  
    }
    
    public function find($id) {
        if(!$id || !is_numeric($id)){
            return array();
        }
        $data = $this->_db->where('id='.$id)->find();
        return $data;
    }
    
    public function insert($res=array()) {
        if(!$res || !is_array($res)) {
            return 0;
           
        }
        $res['create_time'] = time();
        return $this->_db->add($res);
    }
    
    public function updateById($id, $data) {
    
        if(!$id || !is_numeric($id)) {
            throw_exception("ID不合法1");
        }
        if(!$data || !is_array($data)) {
            throw_exception('更新的数据不合法');
        }
        return  $this->_db->where('id='.$id)->save($data); // 根据条件更新记录
    }
    
    public function updateStatusById($id, $status) {
        if(!is_numeric($status)) {
            throw_exception("status不能为非数字");
        }
        if(!$id || !is_numeric($id)) {
            throw_exception("ID不合法2");
        }
        $data['status'] = $status;
        return  $this->_db->where('id='.$id)->save($data); // 根据条件更新记录
    
    }
    
    public function getNormalPositions(){
        $conditions  =array('status'=>1);
        $list  =$this->_db->where($conditions)->order('id')->select();
        return $list;
    }
    
    public function getCount($data=array()){
        $conditions = $data;
        return $list = $this->_db->where($conditions)->count();
    }

}
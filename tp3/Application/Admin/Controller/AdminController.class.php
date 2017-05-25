<?php
/**
 * 后台Index相关
 */
namespace Admin\Controller;
use Think\Controller;
use Think\Exception;

class AdminController extends CommonController {


    public function index() {
        $admins = D('Admin')->getAdmins();
        $this->assign('admins', $admins);
        $this->display();
    }

    public function add() {

        // 保存数据
        if(IS_POST) {

            if(!isset($_POST['username']) || !$_POST['username']) {
                return show(0, '用户名不能为空');
            }
            if(!isset($_POST['password']) || !$_POST['password']) {
                return show(0, '密码不能为空');
            }
            $_POST['password'] = getMd5Password($_POST['password']);
            // 判定用户名是否存在
            $admin = D("Admin")->getAdminByUsername($_POST['username']);
            if($admin && $admin['status']!=-1) {
                return show(0,'该用户存在');
            }

            // 新增
            $id = D("Admin")->insert($_POST);
            if(!$id) {
                return show(0, '新增失败');
            }
            return show(1, '新增成功');
        }
        $this->display();
    }

    public function setStatus() {
         $data = array(
            'id'=>intval($_POST['id']),
            'status' => intval($_POST['status']),
        );
        return parent::setStatus($data,'Admin'); 
       /*  或者
        $data = array(
            'admin_id'=>intval($_POST['id']),
            'status' => intval($_POST['status']),
        );
        return parent::setStatus($_POST,'Admin'); 
                    分析:获取到的id值到最后插入数据库语句UPDATE `cms_admin` SET `status`='0' WHERE ( admin_id=8 )
                    所以这里的id或者admin_id都不影响最后的结果,只是传入的值必须为id,$_post数据有id值,和$data值一样            
        */

        
    }

    public function personal() {
        $res = $this->getLoginUser();
        
        $user = D("Admin")->getAdminByAdminId($res['admin_id']);
        
        $this->assign('vo',$user);
        $this->display();
    }

    public function save() {
        $user = $this->getLoginUser();
        if(!$user) {
            return show(0,'用户不存在');
        }

        $data['realname'] = $_POST['realname'];
        $data['email'] = $_POST['email'];

        try {
            $id = D("Admin")->updateByAdminId($user['admin_id'], $data);
            if($id === false) {
                return show(0, '配置失败');
            }
            return show(1, '配置成功');
        }catch(Exception $e) {
            return show(0, $e->getMessage());
        }
    }

}
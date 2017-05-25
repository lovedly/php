<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * use Common\Model 这块可以不需要使用，框架默认会加载里面的内容
 */
class LoginController extends Controller {

    public function index(){
            if (session('adminUser')){
            redirect('/admin.php?c=index');
        } 
    	return $this->display();
    }
    
    public function check(){
        $username = $_POST['username'];
        $password = $_POST['password'];
        if (!trim($username)){
            return show(0,'用户名不能为空');    
        }
        if (!trim($password)){
            return show(0,'密码不能为空');
        }
        
        $ret = D('Admin')->getAdminByUsername($username);
        
       /*  print_r($ret);exit;
        Array
        (
            [admin_id] => 1
            [username] => admin
            [password] => d099d126030d3207ba102efa8e60630a
            [lastloginip] => 0
            [lastlogintime] => 1488783947
            [email] => 123456@126.com
            [realname] => huan
            [status] => 1
            ) */
        if(!$ret || $ret['status'] !=1){
            return show(0,'该用户不存在');
        }
        if ($ret['password'] != getMd5Password($password)){
            return show(0,'密码错误');
        }
        
        D("Admin")->updateByAdminId($ret['admin_id'],array('lastlogintime'=>time()));
        session('adminUser',$ret);
        return show(1,'登陆成功');   
    }
    
    public function loginout(){
        session('adminUser',null);
        redirect('/admin.php?c=login');
    }

}
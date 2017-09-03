<?php
namespace app\bis\controller;
use think\Controller;
class Base extends Controller
{ 
    public $account;
    public function _initialize(){
        //检测用户是否登陆
        $lsLogin = $this->isLogin();
        if (!$lsLogin){
            return $this->redirect(url('login/index'));
        }
    }
    
    //判定是否登陆
    public function isLogin(){
        //获取session
        $user = $this->getLoginUser();
        if($user && $user->id){
                return true;
        }
        return false;
    
    }
    
    //获取session值
    public function getLoginUser(){
        if(!$this->account) {
            $this->account = session('bisAccount', '', 'bis');
        }
        return $this->account;
        
    }
}

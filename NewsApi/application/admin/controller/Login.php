<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 14:01
 */
namespace app\admin\controller;

use app\common\lib\IAuth;
use think\Controller;

class Login extends Base{
    public function _initialize(){
        
    }
    public function index(){
        $islogin = $this->isLogin();
        if ($islogin){
            return $this->redirect('index/index');
        }
        return $this->fetch();
    }

    /**
     * 登录业务
     */
    public function check(){
        if (request()->isPost()){
            $data =input('post.');
			
            if(!captcha_check($data['code'])){
                $this->error('验证码不正确！');
            }
            $validate = validate('AdminUser');
            if (!$validate->check($data)){
                $this->error($validate->getError());
            }
            try{
                $user = model('AdminUser')->get(['username' => $data['username']]);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }
            
            if (!$user || $user->status !=config('code.status_normal')){
                $this->error('用户不存在！');
            }
            //密码校验
            if (IAuth::setPassword($data['password']) != $user['password']){
                $this->error('密码错误！');
            }
            //登录后更新数据库 时间和ip
            $udata = [
                'last_login_time'=>time(),
                'last_login_ip' =>request()->ip(),
            ];

            try{
                model('AdminUser')->save($udata,['id' => $user->id]);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }

            //session
            session(config('admin.session_user'),$user,config('admin.session_user_scope'));
            $this->success('登录成功','index/index');
        }else{
            $this->error('请求不合法！');
        }
    }

    public function logout(){
        session(null,config('admin.session_user_scope'));
        $this->redirect('login/index');
    }

}
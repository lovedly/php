<?php
namespace app\index\controller;
use think\Controller;

class User extends Controller
{
    public function login()
    {
        //获取session
        $user =session('o2o_user','','o2o');
        if($user && $user->id){
            $this->redirect(url('index/index'));
        }
        return $this->fetch();   
    }
    
    public function register()
    {
        if(request()->ispost()){
            $data = input('post.');
            //print_r($data); [verifyCode] => sssss
            if(!captcha_check($data['verifyCode'])){
                //验证失败
                $this->error('验证码错误');     
            }
                //严格校验
            $validate = validate('User');
            if (!$validate->scene('register')->check($data)){
                $this->error($validate->getError());
            }    
            if ($data['password'] != $data['repassword']){
                $this->error('两次输入密码不一致');
            }
            // 自动生成 密码的加盐字符串
            $data['code'] =mt_rand(100, 10000);
            $data['password'] = md5($data['password'].$data['code']);
           
            try{
                $res = model("User")->add($data);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }
            if($res){
                $this->success('注册成功',url('user/login'));
            }else{
                $this->error('注册失败');    
            }
       }else{
                return $this->fetch();
            }
          //SQLSTATE[23000]: Integrity constraint violation: 1062 Duplicate entry 'admin' for key 'username'
    }
    
    public function logincheck(){
        if(!request()->ispost()){
            $this->error('提交不合法');
        }
        $data =input('post.');
        //校验
        $validate = validate('User');
        if (!$validate->scene('login')->check($data)){
            $this->error($validate->getError());
        }
        try{
            $user = model("User")->getUserByUsername($data['username']);
        }catch (\Exception $e){
                $this->error($e->getMessage());
        }
        if(!$user || $user->status != 1){
            $this->error('该用户不存在');
        }
        //判断密码是否正确
        if(md5($data['password'].$user->code) != $user->password){
            $this->error('密码不正确');
        }
        
        //登陆成功
        model('User')->updateById(['last_login_time' =>time()],$user->id);
        
        //用户信息记录到session
        session('o2o_user',$user,'o2o');
        $this->success('登陆成功',url('index/index'));
    }
    
    public function logout(){
        session(null,'o2o');
        $this->redirect(url('user/login'));
    }
}

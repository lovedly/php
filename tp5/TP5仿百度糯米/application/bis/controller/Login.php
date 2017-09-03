<?php
namespace app\bis\controller;
use think\Controller;
class Login extends Controller
{
    public function index()
    {
        if (request()->isPost()){
            //登陆
            //获取数据
            $data = input('post.');
            //通过用户名获取信息
            //严格判定
            $validate = validate('BisAccount');
            if (!$validate->scene('login')->check($data)){
                $this->error($validate->getError());
            }
            $ret = model('BisAccount')->get(['username'=>$data['username']]);
            
            if(!$ret || $ret->status != 1){
                $this->error('该用户不存在或者未被审核通过!');
            }
            
            if($ret->password != md5($data['password'].$ret->code)){
                $this->error('密码错误,请重新输入!');
            }
            
            model('BisAccount')->updataById(['last_login_time'=>time()],$ret->id);
            //保存用户信息,bis是作用域
            session('bisAccount', $ret , 'bis');
            return $this->success('登陆成功',url('index/index'));
            
            
        }else{
            //获取session
            $account = session('bisAccount','','bis');
           /*  print_r($account);exit;
            [data:protected] => Array
            (
                [id] => 11
                [username] => admin
                [password] => 768687f766f49bf97f28a9517441cb6a
                [code] => 927
                [bis_id] => 22
                [last_login_ip] =>
                [last_login_time] => 1497425014
                [is_main] => 1
                [listorder] => 0
                [status] => 1
                [create_time] => 1497406349
                [update_time] => 1497425014
                ) */
            if($account && $account->id){
                return $this->redirect(url('index/index'));
            }
            return $this->fetch();
        }   
    }
    
    public function logout(){
        //清除session
        session(null,'bis');
        //跳出
        $this->redirect('login/index');
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 14:01
 */
namespace app\admin\controller;

use think\Controller;

/**
 * 后台基础类库
 * Class Base
 * @package app\admin\controller
 */
class Base extends Controller{
    /**
     * 定义model
     * @var string
     */
    public $model = '';
    /**
     * 初始化的方法
     */
    public function _initialize(){
        $islogin = $this->isLogin();
        if (!$islogin){
            return $this->redirect('login/index');
        }

    }

    public function isLogin(){
        //获取session
        $user = session(config('admin.session_user'),'',config('admin.session_user_scope'));
        if ($user && $user->id){
            return true;
        }

        return false;
    }

    public function delete($id = 0){
        if (!intval($id)){
            return $this->result('', 0, 'ID不合法');
        }
        //如果表和控制器文件名一样
        //$model = $this->model ? $this->model : request()->controller();
        $model = request()->controller();
        if ($model == 'Admin'){
            $model ='AdminUser';
        }
        try{
            $res = model($model)->save(['status' => -1], ['id' => $id]);
        }catch (\Exception $e){
            return $this->result('', 0 , $e->getMessage());
        }
        if ($res){
            return $this->result(['jump_url' =>$_SERVER['HTTP_REFERER']], '1' ,'OK');
        }
        return $this->result('', '10', '$model');
    }

    /**
     * 通用化修改状态
     */
    public function status(){
        $data = input('param.');
        //dump($data);
        //验证

        //tp5 validate
        //通过id去库中查询记录是否存在
        //model('news')->get($data['id']);

        //如果表和控制器文件名一样,news
        //如果不一样需要定义model  例如 admin 定义 $this->model = 'adminUser'
        //$model = $this->model ? $this->model : request()->controller();
        $model = request()->controller();
        //
        if($model == 'Admin'){
            $model = 'AdminUser';
        }
        //echo $model;exit;
        try{
            $res = model($model)->save(['status' =>$data['status']],['id' =>$data['id']]);
        }catch (\Exception $e){
            return $this->result('', 0,$e->getMessage());
        }
        if ($res){
            return $this->result(['jump_url' =>$_SERVER['HTTP_REFERER']], 1 ,'OK');

        }

        return $this->result('', 0 ,'修改失败');

    }

}
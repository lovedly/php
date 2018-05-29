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

class Admin extends Base{

    /**
     * 获取管理员列表
     * @return mixed
     */
    public function index(){
        
        try{
            $user = model('AdminUser')->getAdminUser();
        }catch (\Exception $e){
            $this->error($e->getMessage());
        }
        
        return $this->fetch('',[
            'user' => $user,
        ]);
    }

    public function add()
    {
        if (request()->isPost()) {
            $data = input('post.');
            $validate = validate('AdminUser');
            if (!$validate->check($data)){
                $this->error($validate->getError());
            }
            //密码加密
            $data['password'] = IAuth::setPassword($data['password']);
            $data['status']  = config('code.status_normal');

            try{
                $id = model('AdminUser')->add($data);
            }catch (\Exception $e){
                $this->error($e->getMessage());
            }

            if ($id){
                $this->success('id='.$id.'的用户新增成功');
            }else{
                $this->error('error');
            }

            
        }else {
            return $this->fetch();
        }

    }

   
}
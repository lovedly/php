<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 14:01
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\Aes;
use app\common\lib\Alidayu;
use app\common\lib\exception\ApiException;
use app\common\lib\IAuth;
use app\common\model\User;
use think\Controller;
class Login extends Common{
    public function save(){
        if (!request()->isPost()){
            return show(config('code.error'),'您没有权限', [], 403);
        }

        $param = input('param.');
        /*halt($param);
        ["phone"] => string(11) "13554239393"
        ["code"] => string(6) "700897"
        ["ver"] => string(2) "v1"*/
        if (empty($param['phone'])){
            return show(config('code.error'),'手机号码不合法', [], 404);
        }
        if (empty($param['code']) && empty($param['password'])){
            return show(config('code.error'),'手机验证码或者密码不合法', [], 404);
        }

        //validate 严格校验
        if(!empty($param['code'])) {
            $code = Alidayu::getInstance()->checkValidPhone($param['phone']);
            if ($code != $param['code']) {
                return show(config('code.error'), '手机验证码不对', [], 404);
            }
        }

        $token = IAuth::setAppLoginToken($param['phone']);
        $data = [
            'token' => $token,
            'time_out' => strtotime("+".config('app.login_time_out_day')." day"),

        ];

        //查询这个手机号是否存在
        $user = User::get(['phone' =>$param['phone']]);
        
        if ($user && $user->status == 1){
            if(!empty($param['password'])) {
                // 判定用户的密码 和 $param['password'] 加密之后
                if(IAuth::setPassword($param['password']) != $user->password) {
                    return show(config('code.error'), '密码不正确', [], 403);
                }
            }
            //更新的逻辑
            $id = model('User')->save($data,['phone' => $param['phone']]);
        }else{
            if(!empty($param['code'])) {
            //第一次登录
            $data['username'] = 'newsfans-'.$param['phone'];
            $data['status']= 1;
            $data['phone'] = $param['phone'];
            
            $id = model('User')->add($data);
        }else{
                return show(config('code.error'), '用户不存在', [], 403);
            }
        }
        $obj =new Aes();
        
        if ($id){
            $result = [
                'token' => $obj->encrypt($token."||".$id),
                //M8ITB+MhjmeqD1ZZchjkArNIxfVwEt/j7QOTAcv6CPO1GtACgEmaORfi1qkxoB2B
            ];
            return show(config('code.success'), 'ok', $result);
        }else{
            return show(config('code.error'), '登录失败', [], 403);
        }

    }

}
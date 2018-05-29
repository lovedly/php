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

/**客户端auth登录权限基础类库
 * Class AuthBase
 * @package app\api\controller\v1
 */

class AuthBase extends Common{
    /**
     * 登录用户基本信息
     * @var array
     */
    public $user= [];
    /**
     * 初始化
     */
    public function _initialize()
    {
        /*parent::_initialize();
        $islogin = $this->isLogin();
        halt($islogin);
        if(!$islogin){
            throw new ApiException('您没有登录', 401);
        }*/
        parent::_initialize();
        if(!$this->isLogin()) {
            throw new ApiException('您没有登录', 401);
        }
    }

    /**
     * 判断是否登录
     * return bool
     *
     */
    public function isLogin(){
        if(empty($this->headers['access_user_token'])){
            return false;
        }
        $obj =new Aes();
        $accessUserToken = $obj->decrypt($this->headers['access_user_token']);
        /*echo $accessUserToken;exit;
        0eb1ba44fa8f91c5fac49ddcccd72769415af9c8||1*/
        if(empty($accessUserToken)) {
            return false;
        }
        if(!preg_match('/||/', $accessUserToken)) {
            return false;
        }
        list($token, $id) = explode("||", $accessUserToken);
        $user = User::get(['token' => $token]);
        
        if(!$user || $user->status != 1) {
            return false;
        }
        // 判定时间是否过期
        if(time() > $user->time_out) {
            return false;
        }

        $this->user = $user;
        return true;
    }

}
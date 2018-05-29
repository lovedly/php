<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 14:01
 */
namespace app\api\controller\v1;

use app\api\controller\Common;
use app\common\lib\exception\ApiException;
use think\Controller;
use app\common\lib\Alidayu;

class Identify extends Common{
    /**
     * post
     * 设置短信验证码
     */
    public function save(){
        if (!request()->isPost()){
            return show(config('code.error'),'您提交的数据不合法', [], 403);
        }

        //校验数据
        $validate = validate('Identify');
        if (!$validate->check(input('post.'))){
            return show(config('code.error'), $validate->getError(), [], 403);
        }

        $id = input('param.id');

        if (Alidayu::getInstance()->sendSMS($id)){
            return show(config('code.success'), 'OK', [], 201);
        }else{
            return show(config('code.error'), 'error', [], 403);
        }
        
    }

}
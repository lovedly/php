<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 14:01
 */
namespace app\api\controller;

use app\common\lib\Alidayu;
use app\common\lib\exception\ApiException;
use think\Controller;

class Test extends Common{

    public function index(){
        return [
            'sgsg',
            'sgsgs',
        ];
    }

    public function update($id = 0){
        //echo $id;exit;
        halt(input('put.'));
        //return $id;
    }

    public function save(){
        $data = input('post.');
        //if ($data['mt'] != 1){
        //    throw new ApiException('提交的数据不合法', 400);
        //}
        //return input('post.');
        return show(1, 'Ok', input('post.'), 201);
        // return show(1, 'Ok', (new Aes())->encrypt(json_encode(input('post.'))), 201);
    }


    public  function sendSms() {
        //Alidayu::getInstance()->sendSMS("");
    }


}
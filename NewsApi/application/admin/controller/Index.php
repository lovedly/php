<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/6
 * Time: 14:01
 */
namespace app\admin\controller;

use think\Controller;

class Index extends Base{

    public function index(){
        
        return $this->fetch();
    }


    public function welcome(){
        return 'hello';
        
    }


}
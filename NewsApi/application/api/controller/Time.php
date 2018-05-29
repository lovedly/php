<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2
 * Time: 21:31
 */
namespace app\api\controller;



use think\Controller;

class Time extends Controller{

    public function index(){
        return show(1,'OK',time());
    }
    
}
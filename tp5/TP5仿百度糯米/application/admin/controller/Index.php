<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Controller
{
    public function index()
    {
        return $this->fetch();    
    }
	
	public function test(){
	    //\Map::getLngLat('江西省南昌市经济开发区英雄大道901号');
	    //{"status":0,"result":{"location":{"lng":115.86527987993137,"lat":28.787932657860116},"precise":1,"confidence":80,"level":"道路"}}
		return '成功';
	}
	public function map(){
	    return \Map::staticimage('北京昌平沙河地铁');
	}
	
	public function welcome(){
	   //\phpmailer\Email::send('1041801637@qq.com','tp5','成功!');
	  //return '发送邮件成功';
	    return "欢迎来到o2o主后台首页!";
	}
}

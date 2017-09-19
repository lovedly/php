<?php
namespace app\sample\controller;

use think\Request;
class Test
{
    public function hello()
    {
        $all = input('get.');
        var_dump($all);
        /* $id = Request::instance()->param('id');
        $name = Request::instance()->param('name');
        $age = Request::instance()->param('age');
        echo $id;
        echo '|';
        echo $name;
        echo '|';
        echo $age; */
        //return "hello,world";
    }
         
}
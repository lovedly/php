<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

    use think\Route;
    //Route::rule('路由表达式','路由地址','请求类型','路由参数(数组)','变量规则(数组)');
    Route::get('api/:version/banner/:id', 'api/:version.Banner/getBanner');
    
    Route::get('api/:version/theme', 'api/:version.Theme/getSimpeList');
    Route::get('api/:version/theme/:id', 'api/:version.Theme/getComplexOne');  
    
    Route::get('api/:version/product/recent', 'api/:version.Product/getRecent');
    Route::get('api/:version/product/by_category', 'api/:version.Product/getAllInCategory');
    
    Route::get('api/:version/category/all', 'api/:version.Category/getAllCategories');
    
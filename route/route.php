<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------


Route::group('/admin', function () {
    Route::get('/captcha','admin/Index/captcha'); //验证码表单
    Route::get('/code','admin/Comm/captcha'); //生成验证码
    Route::get('/redis','admin/Index/redis'); //Redis
    Route::get('/querylist','admin/Index/querylist'); //queryList爬虫
    Route::get('/cache','admin/Index/cache'); //数据库缓存
});

Route::group('/',function(){
    Route::rule('/','index/Index/index');
});


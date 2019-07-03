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
    Route::get('/jwt', 'admin/Index/jwt'); //JWT
});

//【ElasticSearch】
Route::group('/es', function() {
    Route::get('/add','admin/Es/addDoc'); //ElasticSearch新增创建文档
    Route::get('/get','admin/Es/getDoc'); //ElasticSearch获取单条文档
    Route::get('/delete','admin/Es/delDoc'); //ElasticSearch删除单条文档
    Route::get('/search','admin/Es/searchDoc'); //ElasticSearch高级查询
    Route::get('/update','admin/Es/updateDoc'); //ElasticSearch高级查询
});

//【PHP设计模式】
Route::group('/pattern', function() {
    Route::get('/single', 'admin/Index/singlePattern'); //单例模式

});

Route::group('/',function(){
    Route::rule('/','index/Index/index');
});


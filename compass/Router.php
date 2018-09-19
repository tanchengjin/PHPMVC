<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 18865477815@163.com
 */
use compass\cores\Route;
Route::get('index','index/Index/index');
Route::get('search','index/Search/index');
Route::get('article/:id','index/Article/index');
Route::post('post/:id/:power','test/Index/test');
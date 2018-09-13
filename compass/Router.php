<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 18865477815@163.com
 */
use compass\cores\Route;
Route::get('test','index/Index/index');
Route::post('post/:id/:power','test/Index/test');
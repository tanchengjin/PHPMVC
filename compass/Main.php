<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass;


use compass\cores\App;
use compass\cores\Apps;
use compass\cores\Config;
use compass\cores\Route;

class Main
{

    public static function run(){
        //启动路由
        App::get('route')->run();
    }
}
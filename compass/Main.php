<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass;


use compass\cores\App;
use compass\cores\Apps;
use compass\cores\Smarty;
class Main
{

    public static function run(){
        //设置公用侧边栏头部变量
        $_SESSION['blog']['category']=App::get('db')->connect()->table('blog_cate')->select();
        //设置公用侧边栏右侧变量,热门点击
        $_SESSION['blog']['sidebar']['hot']=App::get('db')->connect()->table('blog_article')->order('click desc')->limit(9)->select();
        //设置公用侧边栏右侧变量,热门点击
        $_SESSION['blog']['sidebar']['new']=App::get('db')->connect()->table('blog_article')->order('id desc')->limit(9)->select();

//        dd($_SESSION['blog']['category']);
        //启动路由
        App::get('route')->run();
    }
}
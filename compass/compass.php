<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

$start=current_time();
require(ROOT_PATH.'/compass/constants.php');
require(ROOT_PATH.'/compass/cores/Loader.php');
require(COMPASS.'/function.php');
require(COMPASS.'/cores/Log.php');
require COMPASS."/cores/Config.php";
require COMPASS."/cores/App.php";

spl_autoload_register('\compass\cores\Loader::autoload');
//App容器依赖注入
\compass\cores\App::run();

//载入smarty模板引擎
require 'cores/template/drive/smarty/Smarty.class.php';
//载入路由
require COMPASS."/Router.php";
//判断是否开启调试模式
$config=new \compass\cores\Config();
$debug=$config['config']['debug'];
ini_set('display_errors',$debug);
//运行框架
\compass\Main::run();



$end=current_time();
$runtime='runtime[框架运行时间]'.(number_format($end-$start,3)*1000).'ms[毫秒]';
$log=new \compass\cores\Log();
$log->write($runtime,'systemInfo');
function current_time(){
    list($usec,$use)=explode(' ',microtime());
    return (float)$usec+(float)$use;
}
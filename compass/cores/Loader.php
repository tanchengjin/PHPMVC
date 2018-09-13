<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 18865477815@163.com
 */

namespace compass\cores;


class Loader
{
    public static $fileMapper=[];
    //自动加载类
    public static function autoload($fileName){
        if(isset($fileMapper[$fileName])){
            return;
        }
        //如果存在app命名空间,将自动转换为application
        if(strstr($fileName,'app')){
        $fileName=str_replace('app','application',$fileName);
        }
        //获取文件完整路径
        $class=str_replace('\\','/',ROOT_PATH.'/'.$fileName.'.php');
        if(is_file($class)){
            require_once $class;
            //读取配置文件如果开启,将载入的类名写入日志中
            if(App::get('config')['config']['log']['systemInfo']){
                App::get('log')->write('Loading: '.$fileName,'systemInfo');
            }
            self::$fileMapper[$fileName]=$fileName;
        }else{
            die(var_dump(App::get('language')['file_not_exists']).' '.$class);
        }
    }
}
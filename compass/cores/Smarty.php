<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores;


class Smarty
{
    private static $smarty;
    public function __construct(){
        self::$smarty=new \Smarty();
        //获取模板配置文件
        $configs=new Config();
        $object=$configs['smarty'][0];
        $func=$configs['smarty'][1];
        //设置成员方法
        foreach($func as $k=>$v){
            self::$smarty->$k($v);
        }
        //设置成员属性
        foreach($object as $k=>$v){
            self::$smarty->$k=$v;
        }
    }
    public function assign($variable=array()){
        if(is_array($variable)){
            foreach ($variable as $k=>$v){
                self::$smarty->assign($k,$v);
            }
        }
    }
    public function display($fileName=''){
        self::$smarty->display($fileName);
    }
}
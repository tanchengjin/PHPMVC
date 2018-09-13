<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */
#基础配置信息
return array(
    //调试模式
    'debug'=>true,
    //默认模块
    'default_module'=>'index',
    //默认控制器
    'default_controller'=>'Index',
    //默认方法
    'default_action'=>'index',
    //默认语言
    'language'=>'zh-cn',
    //模板引擎是否开启,false则不使用模板引擎,如不开启则在模板中使用原生PHP语法
    'html_engine'=>'smarty',

    'log'=>[
        //是否记录框架运行时信息
        'systemInfo'=>false,
        #日志记录驱动,与compass\core\log\drive驱动下小写文件夹名,首字母大写类名
        'driver'=>'file',
        #日志存放路径
        'storage'=>ROOT_PATH.'/buffer/log',
        //文件内容格式
        'content'=>[
            //是否添加时间戳,true|false
            'timestamp'=>true,
            //文件内容格式,text|json
            'format'=>'text',
        ],
    ],
);
<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */
return array(
    //用于设置smart成员变量
    0=>array(
        //缓存开关,开发模式慎用
        'caching'=>false,
        //缓存目录
        'cache_dir'=>ROOT_PATH.'/buffer/template',
        //缓存时间
        'cache_lifetime'=>60*60*24,
        //左侧模板定界符
        'left_delimiter'=>'{',
        //右侧模板定界符
        'right_delimiter'=>'}',
    ),
    //用于设置smart成员方法
    1=>array(
        //模板缓存存放目录
        'setCompileDir'=>ROOT_PATH.'/buffer/template',
    ),
);
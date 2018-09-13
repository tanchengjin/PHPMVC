<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */
#App容器所需服务,依赖注入
return array(
    'service'=>[
        //服务提供者
        'test'=>\compass\Test::class,
        'config'=>\compass\cores\Config::class,
        'language'=>\compass\cores\Language::class,
        'log'=>\compass\cores\Log::class,
        'route'=>\compass\cores\Route::class,
        'db'=>\compass\cores\Db::class,
    ],
);
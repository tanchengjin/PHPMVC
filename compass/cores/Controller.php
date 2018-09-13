<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores;


class Controller
{

    public function fetch($filePath='',$variable=array()){
        if(is_array($variable)){
            $view=new View();
            if(!empty($variable)){
                $view->assign($variable);
            }
            $view->display($filePath);
        }

    }
}
<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace app\index\controller;


use compass\cores\Controller;
use model\Article;

class Index extends Controller
{
    public function index(){
        $article=new Article();
        $article->index();
        return $this->fetch('',[
            'variable'=>'compass',
        ]);
    }
}
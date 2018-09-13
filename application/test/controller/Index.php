<?php
/**
 * Created by PhpStorm.
 * User: TanChengjin
 * Date: 2018/9/13
 * Time: 13:03
 */

namespace app\test\controller;


use compass\cores\Controller;

class Index extends Controller
{
    public function test(){
        dd($_POST);
        $this->fetch('',[
            'a'=>'hello,world',
        ]);
    }
}
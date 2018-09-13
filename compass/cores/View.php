<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores;


class View
{
    private $assign=array();
    //用于存储模板视图文件
    private $viewFile;
    public function assign($name='',$value=''){
        if(is_array($name) && !empty($name)){
            $this->assign=$name;
        }
        if(!empty($name) && !empty($value)){
            $this->assign[$name]=$value;
        }else{
            return false;
        }
    }
    public function display($filePath=''){
        if(empty($filePath)){
            $request=new Route();
            //获取当前模块下当前控制器下的当前方法的模板文件
            $filePath=APP."/{$request->module}/view/{$request->controller}/{$request->action}.html";
        }
        if(!isset($this->viewFile)){
            if(!is_file($filePath)){
                //视图文件是否存在
                throw new \Exception($GLOBALS['lang']['view_not_exists'].$filePath);
            }
            //获取模板引擎
            $engine=new Config();
            $engine=$engine['config']['html_engine'];
            if($engine){
                if(strtolower($engine) == 'smarty'){
                    $smarty=new Smarty();
                    $smarty->assign($this->assign);
                    $smarty->display($filePath);
                }
            }else{
                //默认视图渲染方法
                extract($this->assign);
                include $filePath;
            }
        }
    }
}
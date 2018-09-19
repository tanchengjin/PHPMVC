<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 349508017@qq.com
 */

namespace compass\cores;


class Route
{
    //默认模块名
    public $module=null;
    //默认控制器名
    public $controller=null;
    //默认方法名
    public $action=null;
    private $configs=null;
    private $config=null;
    //路由规则,get,post,put...
    public static $rules=array();
    public function __construct(){
        $this->allocateUrl();
    }
    //启动路由
    public function run(){
        #模块
        $module=str_replace('\\','/',APP.'/'.$this->module);
        #获取控制器所在路径
        $controller_file=$module.'/'.'controller/'.$this->controller.'.php';
        #模块是否存在
        if(!is_dir($module)){
            var_dump(App::get('language')['module_not_exists']);die;
        }
        #控制器是否存在
        if(is_file($controller_file)){
            $controller_file= 'app\\'.$this->module.'\\controller\\'.$this->controller;
            $controller=new $controller_file();
        }else{
            var_dump(App::get('language')['ctrl_not_exists'].':'.$controller_file);die;
        }
        #获取方法名
        $action=$this->action;
        #检测方法是否存在
        if(method_exists($controller,$this->action)){
            #调用控制器对应方法
            $controller->$action();
        }else{
            var_dump(App::get('language')['action_not_exists']);die;
        }
    }
    /**
     * 设置相对应的模块,控制器,方法
     * 默认先去寻找路由,然后查找模块,控制器,方法
     */
    private function allocateUrl(){
#加载配置文件
        if(class_exists('\compass\cores\Config')){
            $this->configs=new Config();
            #获取基础配置文件
            $this->config=$this->configs['config'];
        }
        if(isset($_SERVER['REQUEST_URI']) && $_SERVER['REQUEST_URI'] !== '/'){
            //获取url地址栏模块,控制器,方法名
            $urls=$this->checkVirtualDomainName();
            //将控制器方法组合成url来判断路由中是否已经注册
            $strUrl=implode('/',$urls);
            //检测是否存在?p=1参数复制
            if(strpos($strUrl,'?')){
                $strUrl=explode('?',$strUrl);
                $strLen=count($strUrl)-1;
                //将?p=1参数转为为get参数
                for ($i=1;$i<=$strLen;$i++){
                    $params=explode('=',$strUrl[$i]);
                    $key=$params[0];
                    $val=$params[1];
                    unset($strUrl[$i]);
                }
                //数组转换为字符串
                $strUrl=$strUrl[0];
            }
            //判断是否为get提交
            if($_SERVER['REQUEST_METHOD'] == 'GET'){
                //路由已注册,并不存在参数
                if(isset(self::$rules['get'][$strUrl])){
                    $urls=explode('/',self::$rules['get'][$strUrl]['route']);
                }else{
                    //路由没有找到,判断是否为有参路由
                    //获取地址栏参数,并拆分成数组
                    $variable=explode('/',$strUrl);
                    //判断是否为有参路由
                    if(isset(self::$rules['get'][$variable[0]]['route'])) {
                        //路由中存在参数
                        $urls = $this->setUrlParam($variable,'get');
                    }
                }
            }elseif($_SERVER['REQUEST_METHOD'] == 'POST'){
                //post提交
                //如果路由已经注册则跳转到所定义的路由中
                if(!empty(self::$rules['post'][$strUrl])){
                    $urls=explode('/',self::$rules['post'][$strUrl]['route']);
                }else{
                    //路由没有找到,判断是否为有参路由
                    //获取地址栏参数,并拆分成数组
                    $variable=explode('/',$strUrl);
                    //判断是否为有参路由
                    if(isset(self::$rules['post'][$variable[0]]['route'])) {
                        //路由中存在参数
                        $urls = $this->setUrlParam($variable,'post');
                    }
                }
            }
            #根据url地址栏查找相应模块,控制器,方法
            if(isset($urls[0]) && !empty($urls[0])){
                $this->module=$urls[0];
                unset($urls[0]);
            }else{
                $this->module=$this->config['default_module'];
            }
            if(isset($urls[1])){
                $this->controller=ucfirst($urls[1]);
                unset($urls[1]);
            }else{
                $this->controller=$this->config['default_controller'];
            }
            if(isset($urls[2])){
                #解决pathinfo出现/index?id=1格式
                if(strstr($urls[2],'?')){
                    $res=explode('?',$urls[2]);
                    $this->action=$res[0];
                }else{
                    $this->action=$urls[2];
                }
                unset($urls[2]);
            }else{
                $this->action=$this->config['default_action'];
            }
            #将url多余部分转换为get参数
            $this->setRouterParam($urls);

        }else{
            #获取配置文件相应模块,控制器,方法
            $this->module=$this->config['default_module'];
            $this->controller=$this->config['default_controller'];
            $this->action=$this->config['default_action'];
        }
    }

    /**
     * 设置有参路由的参数
     * @param $variable 地址栏路由地址
     * @param $modle get或则post或则put....
     * @return array
     */
    private function setUrlParam($variable,$modle){
        $urls=self::$rules[$modle][$variable[0]]['route'];
        if(isset(self::$rules[$modle][$variable[0]]['params'])){
            $params=self::$rules[$modle][$variable[0]]['params'];
        }
        $variableLen=count($variable)-1;
        //如果地址栏值大于路由所定义的变量则忽略多余的变量
        for($i=1;$i<=$variableLen;$i++){
            //将路由参数与值进行组合匹配
            if(isset($params[$i-1])){
                $urls.='/'.$params[$i-1].'/'.$variable[$i];
            }
        }
        //返回将url地址转换为数组的数据
         return explode('/',$urls);
        }
    /**
     * 将url多余参数转换为get参数
     * @param $urls array 删除模块,控制器,方法后的多余参数
     */
    private function setRouterParam($urls){
        //post提交
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            $urlCount=count($urls)+3;
            $i=3;
            while($i<$urlCount){
                #判断url地址栏key是否有对应的value
                if(isset($urls[$i+1])){
                    $_POST[$urls[$i]]=$urls[$i+1];
                }
                $i+=2;
            }
        }elseif($_SERVER['REQUEST_METHOD'] == 'GET'){
            //get提交
            $urlCount=count($urls)+3;
            $i=3;
            while($i<$urlCount){
                #判断url地址栏key是否有对应的value
                if(isset($urls[$i+1])){
                    $_GET[$urls[$i]]=$urls[$i+1];
                }
                $i+=2;
            }
        }
    }
    /**
     * 检查是否是通过虚拟域名访问
     * 如果不是通过虚拟域名访问,则截取掉框架路径外的路径
     * @return array 模块,控制器,方法
     */
    private function checkVirtualDomainName(){
        $urlLen=strlen($_SERVER['SCRIPT_NAME']);
        if($urlLen > 10){
            //查询index.php入口文件首次出现的位置
            $n=strpos($_SERVER['SCRIPT_NAME'],"index.php");
            //如果不是通过虚拟域名访问,则截取掉框架路径外的路径
            return explode('/',substr($_SERVER['REQUEST_URI'],$n));
        }else{
            return explode('/',trim($_SERVER['REQUEST_URI'],'/'));
        }
    }
    //设置get路由
    public static function get($rule,$route){
        self::setRule($rule,$route,'get');
    }
    public static function post($rule,$route){
        self::setRule($rule,$route,'post');
    }
    //设置路由
    private static function setRule($rule,$route,$method){
        if(is_string($route)){
            if($sta=strpos($rule,':')){
                $strLen=strLen($rule)-1;
                //获取参数
                $param=explode('/',str_replace(':','',substr($rule,$sta,$strLen)));
                //获取去掉参数后的路由
                $rule=trim(substr($rule,0,$sta),'/');
                self::$rules[$method][$rule]['route']=$route;
                self::$rules[$method][$rule]['params']=$param;
            }else{
                self::$rules[$method][$rule]['route']=$route;
            }
        }
    }
}
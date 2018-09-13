<?php
/**
 * Created by PhpStorm.
 * User: TanChengjin
 * Date: 2018/9/8
 * Time: 20:24
 */

namespace compass\cores;
class App{
    /**
     * @var array 用于保存实例化之前的数据
     */
    private static $service=array();
    /**
     *
     * @var array 用于保存实例化之后的数据
     */
    public static $container=array();

    /**
     * 根据app配置文件进行依赖注入
     * @param null $path app配置文件路径
     */
    public static function run($path=null){
        if(!is_null($path)){

        }
        $config=new Config();
        $services=$config['app'];
        //将依赖遍历绑定到容器中
        foreach($services['service'] as $k=>$v){
            self::set($k,$v);
        }
    }
    //获取容器中的对象
    public static function get($service,$params=array()){
        //如果存在于容器中则直接返回对象
        if(isset(self::$container[$service])){
            return self::$container[$service];
        }
        //如果没有注册则显示错误
        if(!isset(self::$service[$service])){
            die(var_dump('service not registered: '.$service));
        }
        //服务已经注册,获取注册的服务
        $provider=self::$service[$service];
        $obj=null;
        //如果为匿名函数则直接调用回调函数
        if($provider instanceof \Closure){
            $obj=call_user_func_array($provider,$params);
        }elseif(is_string($provider)){
            if(empty($params)){
                $obj=new $provider();
            }else{
                //需要传递构造参数,则启动反射
                $ref=new \ReflectionClass($provider);
                $obj=$ref->newInstance($params);
            }
        }
        //将实例对象放入容器并返回
        return self::$container[$service]=$obj;
    }
    //绑定对象
    public static function set($service,$provider){
        //如果服务已经注册,则卸载掉已注册的服务
        self::remove($service);
        self::register($service,$provider);
    }
    //获取容器中的对象
    public function make($service,$params=array()){
        return self::get($service,$params);
    }
    //绑定对象
    public function bind($service,$provider){
            self::set($service,$provider);
    }
    public static function remove($service){
        if(!self::find($service)){
            //卸载掉服务与实例化的对象
            unset(self::$service[$service],self::$container[$service]);
        }
    }

    public static function find($service){
        //已经实例化返回instance
        if(isset(self::$container[$service])){
            return 'instance';
        }
        //已经注册但未实例化返回service
        if(isset(self::$service[$service])){
            return 'service';
        }
        //没有注册也没有实例化返回false
        return false;
    }
    private static function register($service,$provider){
        //不是匿名函数,并且是一个对象直接放入到容器中
        if(!($provider instanceof \Closure) && is_object($provider)){
            self::$container[$service]=$provider;
        }else{
            //进行服务注册
            self::$service[$service]=$provider;
        }
    }
}
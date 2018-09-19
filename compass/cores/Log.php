<?php
/**
 * Created by PhpStorm.
 * User: TanChengjin
 * Date: 2018/9/9
 * Time: 16:45
 */

namespace compass\cores;
require_once COMPASS."/cores/log/driver/file/File.php";
use compass\cores\log\ALog;

class Log
{
    private static $log;

    public function __construct()
    {
        //获取日志配置文件驱动
        $config=App::get('config')['config']['log'];
        $driver="\compass\cores\log\driver\\".strtolower($config['driver'])."\\".ucfirst($config['driver']);
        //加载配置文件中的驱动
        $this->setLog(new $driver());
    }

    /**
     * @param mixed $log
     */
    public function setLog(ALog $log)
    {
        self::$log = $log;
    }

    /**
     * 将日志写入文件
     * @param $content
     * @param string $pattern
     */
    public function write($content,$pattern='log'){
        self::$log->write($content,$pattern);
    }
    public static function w($content,$pattern='log'){
        //获取日志配置文件驱动
        $config=new Config();
        $config=$config['config']['log'];
        $driver="\compass\cores\log\driver\\".strtolower($config['driver'])."\\".ucfirst($config['driver']);
        //加载配置文件中的驱动
        self::$log=new $driver();
        self::$log->write($content,$pattern);
    }

    /**
     * 将文件写入内存
     */
    public function memory($content,$pattern='log'){
        self::$log->memory($content,$pattern);
    }

    /**
     * 将内存中的数据写入到文件中
     * @param $path String
     */
    public function save($path=null,$pattern='log'){
        if(!is_null($path)){

        }
        self::$log->save($path,$pattern='log');
    }

}
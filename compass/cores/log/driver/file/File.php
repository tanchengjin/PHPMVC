<?php
/**
 * Created by PhpStorm.
 * User: TanChengjin
 * Date: 2018/9/9
 * Time: 16:44
 */

namespace compass\cores\log\driver\file;
require_once COMPASS.'/cores/log/ALog.php';
use compass\cores\log\ALog;
class File extends ALog
{
    //用于保存在内存中
    private static $memory=array();

    /**
     * @return array
     */
    public static function getMemory()
    {
        return self::$memory;
    }

    public function __construct()
    {
        parent::__construct();
    }

    function write($content, $pattern = 'log',$path=null)
    {
        if(!is_array($content)){
            $data=$this->generateFileContentFormat($content);
        }
        $data=$content;
        if(!is_null($path)){
            self::$configs['storage']=$path;
        }
        //生成以月为格式的日志文件夹以日为格式的日志文件
        $path=$this->generateFolder(self::$configs['storage'],$pattern);
        file_put_contents($path,$data."\n",FILE_APPEND);
    }

    /**
     * 将内存中的日志写入到文件中
     * @param string $path 存储路径
     * @param string $pattern 保存类型
     */
    function save($path=null,$pattern='memroy')
    {
        $this->write(self::$memory,$pattern);
    }

    /**
     * 将日志放入到内存中
     * @param $content
     * @param string $pattern
     */
    function memory($content, $pattern = 'log')
    {
        self::$memory[]=$content;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: TanChengjin
 * Date: 2018/9/9
 * Time: 16:30
 */

namespace compass\cores\log;
use compass\cores\App;

abstract class ALog
{
    //用于保存configs文件夹中的config.php配置文件中的log数组配置
    //confgis.php优先级大于类内部配置
    public static $configs=[
        #日志记录驱动
        'drive'=>'file',
        #日志存放路径
        'storage'=>ROOT_PATH.'/buffer/log',
        //文件内容格式
        'content'=>[
            //是否添加时间戳,true|false
            'timestamp'=>true,
            //文件内容格式,text|json|array
            'format'=>'text',
        ],
    ];
    public function __construct()
    {
        //加载日志配置文件
        self::$configs=App::get('config')['config']['log'];
    }

    //将日志写入到文件中
    abstract function write($content,$pattern='log');
    //将内存中的文件保存硬盘
    abstract function save();
    //将日志保存到内存中
    abstract function memory($content,$pattern='log');

    /**
     * 检测要存放的日志的路径是否存在
     * 并生成以月为格式的日志文件夹,以日为格式的日志文件
     * @param $path String 日志存放路径 文件夹
     * @param $pattern String 日志类型
     * @return String 返回生成路径格式后的字符串
     */
    protected function generateFolder($path,$pattern){
        $path=$path.'/'.date("Ym");
        if(!is_dir($path)){
            //递归创建文件夹
            if(!@mkdir($path,0700,true)){
                die(var_dump(App::get('language')['create_folder_error']));
            }
        }
        return $path.'/'.date('d')."{$pattern}.log";
    }

    /**
     * 生成文件内容格式
     * @param $content String 内容
     * @return String
     */
    protected function generateFileContentFormat($content){
        $format=self::$configs['content'];
        $text='';
        if($format['timestamp'] == true){
            $text.=time().'|'.date("Y-m-d H:i:s");
        }
        if($format['format'] == 'json'){
            $content=json_encode($content);
            return $text.='|'.$content;
        }elseif($format['format'] == 'array'){
            //在数组中写入时间戳
            if(!empty($text)){
                $arr['log']['content']=$text;
            }
            //在数组中写入内容
            $arr['log']['content']=$content;
            return $arr;
        }
        return $text.'|'.$content;
    }
}
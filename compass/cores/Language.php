<?php
/**
 * Created by PhpStorm.
 * User: TanChengjin
 * Date: 2018/9/10
 * Time: 10:43
 */

namespace compass\cores;


class Language implements \ArrayAccess
{
    public $configs=array();
    public function __construct()
    {
        $config=new Config(COMPASS.'/language/');
        $this->configs=$config[APP::get('config')['config']['language']];
    }

    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    public function offsetGet($offset)
    {
        if(!isset($this->configs[$offset])){
            $this->configs=new Config(COMPASS.'/language/'.APP::get('config')['config']['language']);
        }
        return $this->configs[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->configs[$offset]=$value;
    }

    public function offsetUnset($offset)
    {
        unset($this->configs[$offset]);
    }
}
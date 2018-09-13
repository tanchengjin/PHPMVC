<?php
/**
 * Copyright: Copyright (c) 2018 https://www.xingchenw.cn All rights reserved.
 * Author Tan 18865477815@163.com
 */

namespace compass\cores;


class Config implements \ArrayAccess
{
    //用于存储已经包含的配置文件
    private $container=array();
    //配置文件的路径
    private $path=COMPASS.'/configs';

    public function __construct($path=null)
    {
        if(!is_null($path)){
            $this->setPath($path);
        }
    }

    /**
     * @return array
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param array $path
     * @return $this;
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    /**
     * 判断是否存在
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * 获取
     * @param mixed $offset
     * @return mixed|void
     */
    public function offsetGet($offset)
    {
        if(!isset($this->container[$offset])){
            //加载配置文件
            $configure=$this->path.'/'.$offset.'.php';
            $this->container[$offset]=require $configure;
        }
        return $this->container[$offset];
    }

    /**
     * 设置
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if(is_null($offset)){
            $this->container[]=$value;
        }else{
            $this->container[$offset]=$value;
        }
    }

    /**
     * 卸载
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }
}
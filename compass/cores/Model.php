<?php
/**
 * Created by PhpStorm.
 * User: TanChengjin
 * Date: 2018/9/13
 * Time: 18:04
 */

namespace compass\cores;


class Model
{
    protected static $data;
    protected $prefix='blog_';
    protected $table;
    protected $db;
    public function __construct()
    {
        //获取子类的类名
        $child=explode('\\',get_called_class())[1];
            if(!is_null($this->prefix)){
                //获取数据表名
                $this->table=$this->prefix.strtolower($child);
            }else{
                $this->table=strtolower($child);
            }
        $this->db=App::get('db')->connect()->table($this->table);
    }
    public function __get($name)
    {
        if(isset(self::$data[$name])){
            return self::$data[$name];
        }
    }
    public function __set($name, $value)
    {
        if(isset(self::$data[$name])){
            self::$data[$name]=$value;
        }
    }

    protected function get($id){
        $sql="SELECT * FROM {$this->table} WHERE id={$id}";
        self::$data=App::get('db')->connect()->query($sql)[0];
        return self::$data;
    }
    protected function getAll(){
        $sql="SELECT * FROM {$this->table}";
        self::$data=$this->db->query($sql);
        return self::$data;
    }
    //保存
    protected function save(){
        if(count(self::$data) == count(self::$data,1)){
            //一维数组
            return $this->db->update(self::$data,self::$data['id']);
        }else{
            //二维数组
//            foreach (self::$data as $k=>$v){
//                $this->db->update($v,$v['id'],'id');
//            }
        }
    }
    //模型插入
    protected function insert($param=array()){
        return $this->db->insert($param);
    }
}
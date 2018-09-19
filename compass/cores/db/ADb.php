<?php
/**
 * Created by PhpStorm.
 * User: TanChengjin
 * Date: 2018/9/10
 * Time: 19:57
 */

namespace compass\cores\db;


use compass\cores\App;

abstract class ADb
{
    protected $configs=array();

    protected $where;
    protected $order;
    protected $field;
    protected $table;
    protected $limit;
    //将配置文件中的参数加载到configs变量中
    public function __construct()
    {
        $this->configs=App::get('config')['database'];
    }
    abstract function connect($dbname=null,$host=null,$username=null,$password=null,$port=3306,$charset='utf8');
    abstract function query($sql);
    abstract function close();

    /**
     * 设置表名
     */
    public function Table($table)
    {
        $this->table=$table;
        return $this;
    }
    /**
     * where 查询条件
     */
    public function where($where){
        if(empty($this->where)){
            $this->where=' WHERE '.$where;
        }else{
            $this->where.=' AND WHERE '.$this->where;
        }
        return $this;
    }
    //获取某个字段
    public function field($field){
        if(is_array($field)){
            $this->field=implode(',',$field);
        }else {
            $this->field = " {$field} ";
        }
        return $this;
    }
    //排序
    public function order($order){
        $this->order=" ORDER BY {$order}";
        return $this;
    }
    //获取条数数据
    public function limit($start,$end=null){
        if(!is_null($start) && !is_null($end)){
            $this->limit=" limit ".$start.','.$end;
        }else{
            $this->limit=" limit ".$start;
        }
        return $this;
    }
    public function insert($params=array()){
        if(empty($this->table)){
            die(var_dump("table name don't empty"));
        }
        $sql="INSERT INTO ".$this->table."(%s) VALUES(%s)";
        if(!is_array($params) && !empty($params)){
            die('param must is array and can not be empty');
        }
        $column='';
        $value='';
        foreach ($params as $k=>$v){
            if(empty($column)){
                $column=$k;
            }else{
                $column.=','.$k;
            }

            if(empty($value)){
                $value="'".$v."'";
            }else{
                $value.=",'".$v."'";
            }

        }
        $content=sprintf($sql,$column,$value);
        return $this->query($content);
    }

    /**
     * 删除方法
     * @param $id String|Array 数据库id索引可接收Id 1,或者Id[1,2,3]
     * @param $index String 默认索引为id
     */
    public function delete($id=null,$index='id'){
        $sql="DELETE FROM {$this->table}";
        //如果通过Id传递
        if(!is_null($id)){
            if(is_string($id) || is_integer($id)){
                //判断id字符串中是否存在逗号
                if(strpos($id,',')){
                    //id参数不含有逗号
                    $sql.=" where {$index} in({$id})";
                }else {
                    $sql .= " where {$index}={$id}";
                }
            }elseif(is_array($id)){
                //将数据id拆分成字符串并且以逗号分隔
                $ids=implode(',',$id);
                $sql.=" where {$index} in({$ids})";
            }
        }else{
            //如果不通过Id传递则通过where语句进行拼接
            $sql.=" {$this->where}";
        }
        return $this->query($sql);
    }

    public function update($data,$id=null,$index='id'){
        if(is_array($data)){
            $sql="UPDATE {$this->table} SET ";
            $set='';
            foreach($data as $key=>$val){
                if(empty($set)){
                    $set.=$key."='".$val."'";
                }else{
                    $set.=",".$key."='".$val."'";
                }
            }
            if(!empty($this->where)){
                $set.=$this->where;
            }elseif(!is_null($id)){
                $set.=" WHERE {$index}='{$id}'";
            }
        }
//        dd($sql.$set);
        return $this->query($sql.$set);

    }
    public function select(){
        //拼接查询语句
        $sql='SELECT ';
        if(!empty($this->field)){
            $sql.=$this->field;
        }else{
            $sql.=' *';
        }
        if(!empty($this->table)){
            $sql.=" FROM ".$this->table;
        }else{
            die(var_dump("table name don't empty"));
        }
        if(!empty($this->where)){
            $sql.=$this->where;
        }
        if(!empty($this->order)){
            $sql.=$this->order;
        }
        if(!empty($this->limit)){
            $sql.=$this->limit;
        }
        return $this->query($sql);
    }

}
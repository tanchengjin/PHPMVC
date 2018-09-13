<?php
/**
 * Created by PhpStorm.
 * User: TanChengjin
 * Date: 2018/9/10
 * Time: 17:49
 */

namespace compass\cores\db\driver\pdo;


use compass\cores\db\ADb;

class Pdo extends ADb
{
    private $db;
    public function __construct()
    {
        parent::__construct();
    }

    function connect($dbname = null, $host = null, $username = null, $password = null,$port=3306,$charset='utf8')
    {
        if(!is_null($dbname) && !is_null($host) && !is_null($username) && !is_null($password) && !is_null($port)) {
            try {
                if($this->configs['drive'] == 'mysql'){
                    $dsn = "mysql:dbname={$dbname};host={$host};port={$this->configs['port']};charset={$this->configs['charset']}";
                }
                $this->db = new \PDO($dsn, $this->configs['username'], $this->configs['password']);
            } catch (\PDOException $e) {
                die($e->getMessage());
            }
        }
        else{
            try{
                if($this->configs['drive'] == 'mysql') {
                    $dsn = "mysql:dbname={$this->configs['dbName']};host={$this->configs['host']};port={$this->configs['port']};charset={$this->configs['charset']}";
                }
                $this->db=new \PDO($dsn,$this->configs['username'],$this->configs['password']);
            }catch(\PDOException $e){
                die($e->getMessage());
            }
        }
    }

    /**
     * 执行查询
     * @param $sql
     * @return mixed 返回查询的数据如果数据为空则返回影响的行数
     */
    function query($sql)
    {
        $result=$this->db->query($sql);
        //查询失败输出错误信息
        if(!$result){
            list($num,$num2,$msg)=$this->db->errorInfo();
            die(var_dump($num.' '.$num2.' '.$msg));
        }else{
            //获取查询的结果
            $data=$result->fetchAll(2);
            if(empty($data)){
                //返回影响的行数
                return $result->rowCount();
            }else{
                //返回查询到的数据
                return $data;
            }
        }
    }

    function close()
    {
        // TODO: Implement close() method.
    }
}
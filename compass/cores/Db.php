<?php
/**
 * Created by PhpStorm.
 * User: TanChengjin
 * Date: 2018/9/10
 * Time: 17:41
 */

namespace compass\cores;


use compass\cores\db\ADb;

class Db
{
    private $configs=array();
    private $db;
    public function __construct()
    {
        $this->configs=App::get('config')['database'];
        $n='compass\cores\db\driver\\'.strtolower($this->configs['type']).'\\'.ucfirst($this->configs['type']);
        $this->setDb(new $n());
    }

    /**
     * @param mixed $db
     */
    public function setDb(ADb $db)
    {
        $this->db = $db;
    }

    public function connect($dbname = null, $host = '127.0.0.1', $username = 'root', $password = '',$port=3630,$charset='utf8'){
        $this->db->connect($dbname, $host, $username, $password,$port=3306,$charset='utf8');;
        return $this->db;
    }
}
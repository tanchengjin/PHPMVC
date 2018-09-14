<?php
/**
 * Created by PhpStorm.
 * User: TanChengjin
 * Date: 2018/9/13
 * Time: 17:42
 */

namespace model;
use compass\cores\Model;

class Article extends Model
{
    protected $prefix='blog_';
    protected $table='test';
    public function index(){
        $this->delete(10846);
    }
}
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
        $this->insert([
            'title'=>'1',
            'content'=>'1',
            'textdesc'=>'1',
            'author'=>'1',
            'cateid'=>3
        ]);
    }
}
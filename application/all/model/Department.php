<?php


namespace app\all\model;


use think\Db;
use think\Exception;

class Department extends BaseModel
{
    protected $hidden = [
        'id', 'create_time', 'delete_time'
    ];

}
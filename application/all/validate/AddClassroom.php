<?php


namespace app\all\validate;


class AddClassroom extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty'
    ];

    protected $message = [
        'name' => '教室名称不能为空'
    ];
}
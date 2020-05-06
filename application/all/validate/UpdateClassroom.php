<?php


namespace app\all\validate;


class UpdateClassroom extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'name' => 'require|isNotEmpty'
    ];

    protected $message = [
        'id' => '请正确输入查询id',
        'name' => '请正确输入宿舍名称'
    ];
}
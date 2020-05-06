<?php


namespace app\all\validate;


class DepartmentChange extends BaseValidate
{
    protected $rule = [
        'num' => 'require|isPositiveInteger',
        'name' => 'require|isNotEmpty',
        'year' => 'isPositiveInteger',
        'delay' => 'isPositiveInteger',
    ];

    protected $message = [
        'num' => '编号必须是正整数',
        'name' => '请正确填写名称',
        'year' => '学年必须是正整数',
        'delay' => '延期年限必须是正整数',
    ];
}
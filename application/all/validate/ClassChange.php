<?php


namespace app\all\validate;


class ClassChange extends BaseValidate
{
    protected $rule = [
        'num' => 'require|isPositiveInteger',
        'name' => 'require|isNotEmpty',
        'id' => 'isPositiveInteger'
    ];

    protected $message = [
        'num' => '教职工编号必须是正整数',
        'name' => '名称不能为空',
        'id' => '班级id必须是正整数'
    ];
}
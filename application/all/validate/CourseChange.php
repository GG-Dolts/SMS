<?php


namespace app\all\validate;


class CourseChange extends BaseValidate
{
    protected $rule = [
        'num' => 'require|isPositiveInteger',
        'name' => 'require|isNotEmpty',
        'term' => 'require|isPositiveInteger',
        'id' => 'isPositiveInteger'
    ];

    protected $message = [
        'num' => '教职工编号必须是正整数',
        'name' => '名称不能为空',
        'term' => '请正确选择学期',
        'id' => '课程id必须是正整数'
    ];
}
<?php


namespace app\all\validate;


class CourseAndClassRoom extends BaseValidate
{
    protected $rule = [
        'coid' => 'require|isPositiveInteger',
        'classroom' => 'require|isNotEmpty',
        'week' => 'isPositiveInteger',
        'start_time' => 'isNotEmpty',
        'end_time' => 'isNotEmpty'
    ];

    protected $message = [
        'coid' => '课程编号必须是正整数',
        'classroom' => '请选择课室',
        'week' => '请选择周几',
        'start_time' => '请选择开始时间',
        'end_time' => '请选择结束时间'
    ];
}
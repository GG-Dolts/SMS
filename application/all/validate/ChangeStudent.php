<?php


namespace app\all\validate;


class ChangeStudent extends BaseValidate
{
    protected $rule = [
        'num' => 'require|isPositiveInteger',
    ];


    protected $message = [
        'num' => '编号必须且为正整数',
    ];
}
<?php


namespace app\all\validate;


class ChangeEmployee extends BaseValidate
{
    protected $rule = [
        'num' => 'require|isPositiveInteger',
    ];


    protected $message = [
        'num' => '编号必须且为正整数',
    ];
}
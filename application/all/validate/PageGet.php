<?php


namespace app\all\validate;


class PageGet extends BaseValidate
{
    protected $rule = [
        'page' => 'isPositiveInteger',
        'size' =>'isPositiveInteger'
    ];

    protected $message = [
        'page' => '页码必须是正整数',
        'size' => '单页数量必须是正整数'
    ];
}
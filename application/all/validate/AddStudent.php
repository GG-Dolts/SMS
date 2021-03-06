<?php


namespace app\all\validate;


class AddStudent extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'password' => 'require|isNotEmpty',
        'gender' => 'require|isNotEmpty',
        'identity' => 'require|isIdentity',
        'department' => 'require|isNotEmpty',
        'email' => 'email',
        'from' => 'isNotEmpty'
    ];


    protected $message = [
        'name' => '请正确填写姓名',
        'password' => '请正确填写初始密码',
        'gender' => '请选择性别',
        'identity' => '请正确填写身份证',
        'department' => '请选择院系',
        'email' => '请正确填写邮箱',
        'from' => '请正确填写籍贯'
    ];
}
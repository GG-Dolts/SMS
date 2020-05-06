<?php


namespace app\all\validate;


class LoginValidate extends BaseValidate
{
    protected $rule = [
        'num' => 'require|isPositiveInteger',
        'password' => 'require|passwordVal'
    ];

    protected $message = [
        'num' => '编号必须是一串正整数数字',
        'password' => '密码长度在5-20之间'
    ];

    protected function passwordVal($value)
    {
        $length = strlen($value);
        if( $length > 5 && $length < 20 ){
            return true;
        }
        return false;
    }
}
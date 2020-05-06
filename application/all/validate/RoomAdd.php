<?php


namespace app\all\validate;


class RoomAdd extends BaseValidate
{
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'gender' => 'require|isNotEmpty'
    ];

    protected $message = [
        'name' => '宿舍名称不能为空',
        'gender' => '性别不能为空'
    ];
}
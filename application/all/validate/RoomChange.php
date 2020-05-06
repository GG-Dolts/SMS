<?php


namespace app\all\validate;


class RoomChange extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'name' => 'require|isNotEmpty',
        'gender' => 'isNotEmpty'
    ];

    protected $message = [
        'id' => 'id必须是正整数',
        'name' => '宿舍名称不能为空',
        'gender' => '性别不能为空'
    ];
}
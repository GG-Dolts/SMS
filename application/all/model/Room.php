<?php


namespace app\all\model;


class Room extends BaseModel
{
    protected $hidden = ['create_time', 'update_time', 'delete_time'];

    public function getGenderAttr($value)
    {
        return $value == 1 ? "男" : "女";
    }

    public function setGenderAttr($value)
    {
        return ($value == "男" or $value == "1") ? 1 : 0;
    }
}
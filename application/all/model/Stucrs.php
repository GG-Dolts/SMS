<?php


namespace app\all\model;


class Stucrs extends BaseModel
{
    public function getStatusAttr($value)
    {
        if ($value == 0)
            $result = "未通过";
        elseif ($value == 1)
            $result = "通过";
        else
            $result = "在读";
        return $result;
    }

    public function setStatusAttr($value)
    {
        if ($value == "未通过")
            $result = 0;
        elseif ($value == "通过")
            $result = 1;
        else
            $result = 2;
        return $result;
    }
}
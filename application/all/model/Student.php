<?php


namespace app\all\model;


use app\lib\enum\StudentStatus;

class Student extends BaseModel
{
    protected $hidden = [
        'id', 'did', 'cid', 'rid', 'scope', 'create_time', 'update_time', 'delete_time'
    ];

    //课程关联
    public function course()
    {
        return $this->belongsToMany('Course', 'stucrs', 'coid', 'sid');
    }

    //班级关联
    public function clazz()
    {
        return $this->belongsTo('Clazz', 'cid', 'id');
    }

    //宿舍关联
    public function room()
    {
        return $this->belongsTo('Room', 'rid', 'id');
    }

    //院系关联
    public function department()
    {
        return $this->belongsTo('Department', 'did', 'id');
    }

    public function getGenderAttr($value)
    {
        return $value == 1 ? "男" : "女";
    }

    public function setGenderAttr($value)
    {
        return ($value == "男" or $value == "1") ? 1 : 0;
    }

    public function getStatusAttr($value)
    {
        if ($value == 0)
            $result = "毕业";
        elseif ($value == 1)
            $result = "在读";
        else
            $result = "参军";
        return $result;
    }

    public function setStatusAttr($value)
    {
        if ($value == "毕业" or $value == StudentStatus::Graduate)
            $result = 0;
        elseif ($value == "在读" or $value == StudentStatus::AtSchool)
            $result = 1;
        else
            $result = 9;
        return $result;
    }

    public static function getAll($page=1, $size=10)
    {
        $paging = self::with(['course','clazz','room','department'])->paginate($size,false,['page'=>$page]);
        $paging->visible(['course.name']);
        $paging->visible(['room.name']);
        $paging->visible(['department.name', 'department.num']);
        return $paging;
    }

}
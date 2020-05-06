<?php


namespace app\all\model;


class Classroom extends BaseModel
{
    protected $hidden = ['create_time', 'update_time', 'delete_time'];
    protected $visible = ['pivot.week','pivot.start_time','pivot.end_time'];

    public static function getClassroomByName($name){
        $classroom = self::find($name);
        return $classroom;
    }

}
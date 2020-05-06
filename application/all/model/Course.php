<?php


namespace app\all\model;



class Course extends BaseModel
{
    protected $hidden = ['create_time', 'update_time', 'delete_time'];
    protected $visible = ['pivot.score'];

    public function teacher()
    {
        return $this->belongsTo('Employee', 'teacher', 'id');
    }

    public function room()
    {
        return $this->belongsToMany('Classroom', 'crscrm', 'crid', 'coid');
    }

    public static function getAll($page=1, $size=10)
    {
        $paging = self::with(['teacher','room'])->paginate($size, false, ['page' => $page]);
        $paging->visible(['teacher.num','teacher.name','teacher.gender']);
        return $paging;
    }
}
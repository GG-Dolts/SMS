<?php


namespace app\all\model;


class Clazz extends BaseModel
{
    protected $hidden = ['create_time', 'update_time', 'delete_time'];

    public function leader()
    {
        return $this->belongsTo('Employee', 'leader', 'id');
    }

    public static function getAll($page=1, $size=10)
    {
        $paging = self::with('leader')->paginate($size, false, ['page' => $page]);
        $paging->visible(['leader.num','leader.name','leader.gender']);
        return $paging;
    }
}
<?php


namespace app\all\model;


use think\Model;
use think\model\concern\SoftDelete;

class BaseModel extends Model
{
    protected $autoWriteTimestamp = true;
    use SoftDelete;

    public static function getAll($page=1, $size=10)
    {
        $paging = self::paginate($size, false, ['page' => $page]);
        return $paging;
    }

}
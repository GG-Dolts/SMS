<?php


namespace app\all\controller;

use app\all\model\Classroom as ClassroomModel;
use app\all\validate\AddClassroom;
use app\all\validate\IDCollection;
use app\all\validate\IDMustBePositiveInt;
use app\all\validate\PageGet;
use app\all\validate\UpdateClassroom;
use app\lib\exception\ClassroomException;
use app\lib\exception\SuccessMessage;

class Classroom extends BaseController
{
    protected $beforeActionList = [
        'checkManageScope' => ['only' => 'getAllClassroom, add, deleteByID, deleteAll, updateByID, search'],
    ];

    public function getAllClassroom($page=1, $size=5)
    {
        (new PageGet())->goCheck();
        $all = ClassroomModel::getAll($page, $size);
        if($all->isEmpty()){
            return [
                'current_page' => $all->currentPage(),
                'data' => ''
            ];
        }
        $data = $all->toArray();
        return $data;
    }

    public function search($q, $page=1, $size=5)
    {
        if(is_numeric($q)){
            $classroom = ClassroomModel::where('id','like', '%'.$q.'%')
                ->paginate($size, false, ['page' => $page]);
        }elseif(is_string($q)){
            $classroom = ClassroomModel::where('name','like', '%'.$q.'%')
                ->paginate($size, false, ['page' => $page]);
        }
        if($classroom->isEmpty()){
            return ['msg' => '无搜索结果'];
        }
        $data = $classroom->toArray();
        return $data;
    }

    public function add($name)
    {
        (new AddClassroom())->goCheck();
        $classroom = new ClassroomModel();
        $result = $classroom->where('name', $name)->find();
        if($result){
            throw new ClassroomException([
                'message' => '该教室已存在，不可重复添加',
                'errorCode' => 300002
            ]);
        }
        $classroom->name = $name;
        $result = $classroom->save();
        if($result){
            return new SuccessMessage();
        }
    }

    public function deleteAll($ids="")
    {
        (new IDCollection())->goCheck();
        $ids = explode(",",$ids);
        $result = ClassroomModel::destroy($ids);
        if($result){
            return new SuccessMessage();
        }
    }

    public function deleteByID($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $result = ClassroomModel::destroy($id);
        if($result){
            return new SuccessMessage();
        }
    }

    public function updateByID($id, $name)
    {
        (new UpdateClassroom())->goCheck();
        $classroom = ClassroomModel::find($id);
        $isHave = ClassroomModel::where('name', $name)->find();
        if($classroom && empty($isHave)){
            $classroom->name = $name;
            $result = $classroom->save();

        }else{
            throw new ClassroomException([
                'message' => '教室信息更新失败',
                'errorCode' => 300003
            ]);
        }
        if($result){
            return new SuccessMessage();
        }
    }
}
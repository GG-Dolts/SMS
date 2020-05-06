<?php


namespace app\all\controller;

use app\all\model\Room as RoomModel;
use app\all\validate\IDCollection;
use app\all\validate\IDMustBePositiveInt;
use app\all\validate\PageGet;
use app\all\validate\RoomAdd;
use app\all\validate\RoomChange;
use app\lib\exception\RoomException;
use app\lib\exception\SuccessMessage;

class Room extends BaseController
{
    protected $beforeActionList = [
        'checkManageScope' => ['only' => 'getAllRoom, add, deleteByID, deleteAll, update, search, getRoomByGender'],
    ];

    public function getAllRoom($page=1, $size=5)
    {
        (new PageGet())->goCheck();
        $all = RoomModel::getAll($page, $size);
        if($all->isEmpty()){
            return [
                'current_page' => $all->currentPage(),
                'data' => ''
            ];
        }
        $data = $all->toArray();
        return $data;
    }

    public function getRoomByGender($gender)
    {
        if(!is_numeric($gender))
            $gender = $gender == "男" ? 1 : 0;
        $room = RoomModel::all(['gender' => $gender]);
        if(!$room){
            return new RoomException(['message' => '获取宿舍失败']);
        }
        return $room;
    }

    public function search($q, $page=1, $size=5)
    {
        if(is_numeric($q)){
            $room = RoomModel::where('id','like', '%'.$q.'%')
                ->paginate($size, false, ['page' => $page]);
        }elseif(is_string($q)){
            $room = RoomModel::where('name','like', '%'.$q.'%')
                ->paginate($size, false, ['page' => $page]);
        }
        if($room->isEmpty()){
            return ['msg' => '无搜索结果'];
        }
        $data = $room->toArray();
        return $data;
    }

    public function add()
    {
        (new RoomAdd())->goCheck();
        $args = request()->param();
        $name = $args['name'];
        $gender = $args['gender'];
        $room = new RoomModel();
        $result = $room->where('name', $name)->find();
        if($result){
            throw new RoomException([
                'message' => '该宿舍已存在，不可重复添加',
                'errorCode' => 300002
            ]);
        }
        $room->name = $name;
        $room->gender = $gender;

        $result = $room->save();
        if($result){
            return new SuccessMessage();
        }
    }

    public function deleteByID($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $result = RoomModel::destroy($id);
        if($result){
            return new SuccessMessage();
        }
    }

    public function deleteAll($ids="")
    {
        (new IDCollection())->goCheck();
        $ids = explode(",",$ids);
        $result = RoomModel::destroy($ids);
        if($result){
            return new SuccessMessage();
        }
    }

    public function update()
    {
        (new RoomChange())->goCheck();
        $args = request()->param();
        $id = $args['id'];
        $name = $args['name'];
        $room = RoomModel::find($id);
        $isHave = RoomModel::where('name', $name)->find();
        if($room && empty($isHave)){
            $room->name = $name;
            if(array_key_exists('gender', $args))
                $room->gender = $args['gender'];
            $result = $room->save();

        }else{
            throw new RoomException([
                'message' => '宿舍信息更新失败',
                'errorCode' => 300003
            ]);
        }
        if($result){
            return new SuccessMessage();
        }
    }
}
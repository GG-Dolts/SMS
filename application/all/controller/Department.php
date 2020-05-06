<?php


namespace app\all\controller;

use app\all\model\Department as DepartmentModel;
use app\all\service\Department as DepartmentService;
use app\all\service\Image as ImageService;
use app\all\validate\DepartmentChange;
use app\all\validate\IDCollection;
use app\all\validate\IDMustBePositiveInt;
use app\all\validate\PageGet;
use app\lib\exception\DepartmentException;
use app\lib\exception\SuccessMessage;

class Department extends BaseController
{
    protected $beforeActionList = [
        'checkManageScope' => ['only' => 'getAllDepartment, add, deleteByNum, deleteAll, update, search'],
    ];


    public function getAllDepartment($page=1, $size=5)
    {
        (new PageGet())->goCheck();
        $all = DepartmentModel::getAll($page, $size);
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
        //$value = preg_match('/^\d+$/', $q);
        if(is_numeric($q)){
            $department = DepartmentModel::where('num','like', '%'.$q.'%')
                ->paginate($size, false, ['page' => $page]);
        }elseif(is_string($q)){
            $department = DepartmentModel::where('name','like', '%'.$q.'%')
                ->whereOr('introduce', 'like', '%'.$q.'%')
                ->paginate($size, false, ['page' => $page]);
        }
        if($department->isEmpty()){
            return ['msg' => '无搜索结果'];
        }
        $data = $department->toArray();
        return $data;
    }

    public function add()
    {
        (new DepartmentChange())->goCheck();
        $args = request()->param();
        $name = $args['name'];
        $num = $args['num'];
        $department = DepartmentModel::where('name', $name)
            ->whereOr('num', $num)->find();
        if($department){
            throw new DepartmentException([
                'message' => '该院系已存在，不可重复添加',
                'errorCode' => 300002
            ]);
        }

        $result = DepartmentService::addDepartment($args);

        if($result){
            return new SuccessMessage();
        }
    }

    public function deleteByNum($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $result = DepartmentModel::destroy(['num'=>$id]);
        if($result){
            return new SuccessMessage();
        }
    }

    public function deleteAll($ids="")
    {
        (new IDCollection())->goCheck();
        $ids = explode(",",$ids);
        $result = DepartmentModel::destroy($ids);
        if($result){
            return new SuccessMessage();
        }
    }

    public function update()
    {
        (new DepartmentChange())->goCheck();
        $args = request()->param();
        $department = DepartmentModel::where('num', $args['num'])->find();
        $isHave = DepartmentModel::where('name', $args['name'])->find();
        if($department && (empty($isHave) or $department->id == $isHave->id)){
            $result = DepartmentService::changeDepartment($department, $args);
        }else{
            throw new DepartmentException([
                'message' => '院系信息更新失败',
                'errorCode' => 300003
            ]);
        }
        if($result){
            return new SuccessMessage();
        }
    }
}
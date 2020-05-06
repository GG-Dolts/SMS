<?php


namespace app\all\controller;

use app\all\model\Clazz as ClassModel;
use app\all\model\Employee as EmployeeModel;
use app\all\model\Department as DepartmentModel;
use app\all\service\Token as TokenService;
use app\all\validate\IDCollection;
use app\all\validate\IDMustBePositiveInt;
use app\all\validate\PageGet;
use app\lib\exception\SuccessMessage;

class Clazz extends BaseController
{
    protected $beforeActionList = [
        'checkManageScope' => ['only' => 'getAllClass, deleteByID, deleteAll, search, getClassByDepartment'],
        'checkTeacherScope' => ['only' => 'getClassByEmployee, add, update']
    ];

    public function getAllClass($page=1, $size=5)
    {
        (new PageGet())->goCheck();
        $all = ClassModel::getAll($page, $size);
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
            $class = ClassModel::with('leader')->where('id','like', '%'.$q.'%')
                ->paginate($size, false, ['page' => $page]);
        }elseif(is_string($q)){
            $where[] = ['name', 'like','%'.$q.'%'];
            $class = ClassModel::hasWhere('leader', $where)->relation('leader')
                ->paginate($size, false, ['page' => $page]);
            if($class->isEmpty()){
                $class = ClassModel::with('leader')->where($where)
                    ->paginate($size, false, ['page' => $page]);
            }
        }
        if($class->isEmpty()){
            return ['msg' => '无搜索结果'];
        }
        $class->visible(['leader.num','leader.name','leader.gender']);
        $data = $class->toArray();
        return $data;
    }

    public function getClassByEmployee($page=1, $size=5)
    {
        $uid = TokenService::getCurrentUid();
        $employee = EmployeeModel::get(['num' => $uid]);
        $classes = ClassModel::where('leader', $employee->id)
            ->paginate($size, false, ['page' => $page]);
        if($classes->isEmpty()){
            return [
                'data' => '',
            ];
        }
        $data = $classes->toArray();
        return $data;
    }


    public function getClassByDepartment($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $department = DepartmentModel::get(['num' => $id]);
        $did = $department->id;
        $employees = EmployeeModel::where('did', '=', $did)->select();
        $leaders = [];
        foreach ($employees as $value){
            array_push($leaders, $value->id);
        }
        $class = ClassModel::where('leader', 'in', $leaders)->select();
        if($class->isEmpty()){
            return [
                'msg' => '未查询到相关班级'
            ];
        }
        return $class;
    }

    public function add()
    {
        $uid = TokenService::getCurrentUid();
        $employee = EmployeeModel::get(['num' => $uid]);
        $args = request()->post();
        $class = new ClassModel();
        $class->name = $args['name'];
        $class->leader = $employee->id;
        $result = $class->save();
        if($result){
            return new SuccessMessage();
        }
    }

    public function update()
    {
        $args = request()->post();
        $class = ClassModel::get($args['id']);
        $class->name = $args['name'];
        $result = $class->save();
        if($result){
            return new SuccessMessage();
        }
    }

    public function deleteByID($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $result = ClassModel::destroy($id);
        if($result){
            return new SuccessMessage();
        }
    }

    public function deleteAll($ids="")
    {
        (new IDCollection())->goCheck();
        $ids = explode(",",$ids);
        $result = ClassModel::destroy($ids);
        if($result){
            return new SuccessMessage();
        }
    }

}
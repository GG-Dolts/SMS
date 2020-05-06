<?php


namespace app\all\controller;

use app\all\model\Employee as EmployeeModel;
use app\all\model\Clazz as ClassModel;
use app\all\model\Course as CourseModel;
use app\all\service\Employee as EmployeeService;
use app\all\service\Token as TokenService;
use app\all\validate\AddEmployee;
use app\all\validate\ChangeEmployee;
use app\all\validate\ClassChange;
use app\all\validate\CourseChange;
use app\all\validate\IDCollection;
use app\all\validate\IDMustBePositiveInt;
use app\all\validate\PageGet;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Employee extends BaseController
{
    protected $beforeActionList = [
        'checkManageScope' => ['only' => 'getAllEmployee, add, deleteByNum, deleteAll, search'],
        'checkTeacherScope' => ['only' => 'getDetailByNum, update, addClass, getCourse, addCourse, getMyInfo'],
    ];

    public function getAllEmployee($page=1, $size=10)
    {
        (new PageGet())->goCheck();
        $all = EmployeeModel::getAll($page, $size);
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
            $employee = EmployeeModel::with(['belongs','clazz','course'])->where('num','like', '%'.$q.'%')
                ->paginate($size, false, ['page' => $page]);
        }elseif(is_string($q)){
            $where[] = ['name', 'like','%'.$q.'%'];
            $employee = EmployeeModel::hasWhere('belongs', $where)->relation('belongs,clazz,course')
                ->paginate($size, false, ['page' => $page]);
            if($employee->isEmpty()){
                $employee = EmployeeModel::with(['belongs','clazz','course'])->where($where)
                    ->paginate($size, false, ['page' => $page]);
            }
        }
        if($employee->isEmpty()){
            return ['msg' => '无搜索结果'];
        }
        $employee->visible(['belongs.name']);
        $employee->hidden(['clazz.leader']);
        $employee->hidden(['course.teacher']);
        $data = $employee->toArray();
        return $data;
    }

    public function getMyInfo()
    {
        $uid = TokenService::getCurrentUid();
        $employee = EmployeeModel::with(['belongs','clazz','course'])->get(['num' => $uid]);
        if(!$employee){
            throw new UserException([
                'message' => '该教职工不存在',
                'errorCode' => 10002
            ]);
        }
        $employee->visible(['belongs.name']);
        $employee->hidden(['clazz.leader']);
        $employee->hidden(['course.teacher']);
        return $employee;
    }

    public function getDetailByNum($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $employee = EmployeeModel::with(['belongs','clazz','course'])->get(['num' => $id]);
        if(!$employee){
            throw new UserException([
                'message' => '该教职工不存在',
                'errorCode' => 10002
            ]);
        }
        $employee->visible(['belongs.name']);
        $employee->hidden(['clazz.leader']);
        $employee->hidden(['course.teacher']);
        return $employee;
    }

    public function add()
    {
        (new AddEmployee())->goCheck();
        $args = request()->param();
        $identity = $args['identity'];
        $employee = EmployeeModel::where('identity', $identity)->find();

        if($employee){
            throw new UserException([
                'message' => '该教职工已存在，不可重复添加',
                'errorCode' => 10003
            ]);
        }
        $result = EmployeeService::addEmployee($args);
        if($result){
            return new SuccessMessage();
        }
    }

    public function deleteByNum($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $result = EmployeeModel::destroy(['num'=>$id]);
        if($result){
            return new SuccessMessage();
        }
    }

    public function deleteAll($ids="")
    {
        (new IDCollection())->goCheck();
        $ids = explode(",",$ids);
        $result = EmployeeModel::destroy($ids);
        if($result){
            return new SuccessMessage();
        }
    }

    public function update()
    {
        (new ChangeEmployee())->goCheck();
        $args = request()->param();
        $employee = EmployeeModel::where('num', $args['num'])->find();
        $isHave = key_exists('identity', $args) ? EmployeeModel::where('identity', $args['identity'])->find() : null;
        if($employee && (empty($isHave) or $employee->id == $isHave->id)){
            $result = EmployeeService::changeEmployee($employee, $args);

        }else{
            throw new UserException([
                'message' => '教职工信息更新失败',
                'errorCode' => 10004
            ]);
        }
        if($result){
            return new SuccessMessage();
        }
    }

    public function getClass($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $args = request()->param();
        $employee = EmployeeModel::with('clazz')->get(['num' => $id]);
        $employee->visible(['clazz', 'num', 'name']);
        return $employee;
    }

    public function addClass( )
    {
        (new ClassChange())->goCheck();
        $args = request()->param();
        $employee = EmployeeModel::get(['num' => $args['num']]);
        $id = $employee->id;
        $class = new ClassModel();
        $class->name = $args['name'];
        $class->leader = $id;
        $result = $class->save();
        if($result){
            return new SuccessMessage();
        }
    }

    public function changeClass()
    {
        (new ClassChange())->goCheck();
        $args = request()->param();
        $employee = EmployeeModel::get(['num' => $args['num']]);
        $class = ClassModel::get($args['id']);
        $id = $employee->id;
        $class->name = $args['name'];
        $class->leader = $id;
        $result = $class->save();
        if($result){
            return new SuccessMessage();
        }
    }

    public function getCourse($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $employee = EmployeeModel::with('course')->get(['num' => $id]);
        $employee->visible(['course', 'num', 'name']);
        return $employee;
    }

    public function addCourse( )
    {
        (new CourseChange())->goCheck();
        $args = request()->param();
        $employee = EmployeeModel::get(['num' => $args['num']]);

        $id = $employee->id;
        $course = new CourseModel();
        $course->name = $args['name'];
        $course->teacher = $id;
        $course->term = $args['term'];
        $result = $course->save();
        if($result){
            return new SuccessMessage();
        }
    }

    public function changeCourse()
    {
        (new CourseChange())->goCheck();
        $args = request()->param();
        $employee = EmployeeModel::get(['num' => $args['num']]);
        $course = CourseModel::get($args['id']);
        $id = $employee->id;
        $course->name = $args['name'];
        $course->teacher = $id;
        $course->term = $args['term'];
        $result = $course->save();
        if($result){
            return new SuccessMessage();
        }
    }

}
<?php


namespace app\all\controller;

use app\all\model\Student as StudentModel;
use app\all\model\Employee as EmployeeModel;
use app\all\model\Course as CourseModel;
use app\all\model\Stucrs as StucrsModel;
use app\all\service\Student as StudentService;
use app\all\service\Token as TokenService;
use app\all\validate\AddStudent;
use app\all\validate\ChangeStudent;
use app\all\validate\IDCollection;
use app\all\validate\IDMustBePositiveInt;
use app\all\validate\PageGet;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Student extends BaseController
{
    protected $beforeActionList = [
        'checkManageScope' => ['only' => 'getAllStudent, getDetailByNum, add, deleteByNum, deleteAll'],
        'checkTeacherScope' => ['only' => 'changeClass, changeRoom, changeCourse, search, getDetailByNum'],
        'checkStudentScope' => ['only' => 'getMyInfo, getMyCourse']
    ];

    public function getAllStudent($page=1, $size=10)
    {
        (new PageGet())->goCheck();
        $all = StudentModel::getAll($page, $size);
        if($all->isEmpty()){
            return [
                'current_page' => $all->currentPage(),
                'data' => ''
            ];
        }
        $data = $all->toArray();
        return $data;
    }

    public function getStudentByEmployee($page=1, $size=5)
    {
        $uid = TokenService::getCurrentUid();
        $employee = EmployeeModel::get(['num' => $uid]);
        $courses = CourseModel::where('teacher', $employee->id)->select();
        $coids = [];
        foreach ($courses as $value) {
            array_push($coids, $value['id']);
        }
        $stucrs = StucrsModel::where('coid', 'in', $coids)->select();
        $sids = [];
        foreach ($stucrs as $value) {
            array_push($sids, $value['sid']);
        }
        $students = StudentModel::with(['course','clazz','room','department'])->where('id', 'in', $sids)
            ->paginate($size, false, ['page' => $page]);
        if($students->isEmpty()){
            return [
                'current_page' => $students->currentPage(),
                'data' => ''
            ];
        }
        $data = $students->toArray();
        return $data;
    }

    public function search($q, $page=1, $size=5)
    {
        if(is_numeric($q)){
            $student = StudentModel::with(['course','clazz','room','department'])->where('num','like', '%'.$q.'%')
                ->paginate($size, false, ['page' => $page]);
        }elseif(is_string($q)){
            $where[] = ['name', 'like','%'.$q.'%'];
            $student = StudentModel::hasWhere('clazz', $where)->relation('course,clazz,room,department')
                ->paginate($size, false, ['page' => $page]);
            if($student->isEmpty()){
                $student = StudentModel::hasWhere('room', $where)->relation('course,clazz,room,department')
                    ->paginate($size, false, ['page' => $page]);
            }
            if($student->isEmpty()){
                $student = StudentModel::hasWhere('department', $where)->relation('course,clazz,room,department')
                    ->paginate($size, false, ['page' => $page]);
            }
            if($student->isEmpty()){
                $student = StudentModel::with(['course','clazz','room','department'])->where($where)
                    ->paginate($size, false, ['page' => $page]);
            }
        }
        if($student->isEmpty()){
            return ['msg' => '无搜索结果'];
        }
        $student->visible(['course.name']);
        $student->visible(['room.name']);
        $student->visible(['department.name', 'department.num']);
        $data = $student->toArray();
        return $data;
    }

    public function getMyInfo()
    {
        $uid = TokenService::getCurrentUid();
        $student = StudentModel::with(['course','clazz','room','department'])->get(['num' => $uid]);
        if(!$student){
            throw new UserException([
                'message' => '该学生不存在',
                'errorCode' => 10102
            ]);
        }
        $student->visible(['course.name']);
        $student->visible(['room.name']);
        $student->visible(['department.name']);
        return $student;
    }

    public function getDetailByNum($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $student = StudentModel::with(['course','clazz','room','department'])->get(['num' => $id]);
        if(!$student){
            throw new UserException([
               'message' => '该学生不存在',
                'errorCode' => 10102
            ]);
        }
        $student->visible(['course.name']);
        $student->visible(['room.name']);
        $student->visible(['department.name']);
        return $student;
    }

    public function add()
    {
        (new AddStudent())->goCheck();
        $args = request()->param();
        $identity = $args['identity'];
        $student = StudentModel::where('identity', $identity)->find();
        if($student){
            throw new UserException([
                'message' => '该学生已存在，不可重复添加',
                'errorCode' => 10103
            ]);
        }
        $result = StudentService::addStudent($args);
        if($result){
            return new SuccessMessage();
        }
    }

    public function deleteByNum($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $result = StudentModel::destroy(['num'=>$id]);
        dump($result);
        if($result){
            return new SuccessMessage();
        }
    }

    public function deleteAll($ids="")
    {
        (new IDCollection())->goCheck();
        $ids = explode(",",$ids);
        $result = StudentModel::destroy($ids);
        if($result){
            return new SuccessMessage();
        }
    }

    public function update()
    {
        (new ChangeStudent())->goCheck();
        $args = request()->param();
        $student = StudentModel::where('num', $args['num'])->find();
        $isHave = key_exists('identity', $args) ? StudentModel::where('identity', $args['identity'])->find() : null;
        if($student && (empty($isHave) or $student->id == $isHave->id)){
            $result = StudentService::changeStudent($student, $args);

        }else{
            throw new UserException([
                'message' => '学生信息更新失败',
                'errorCode' => 10104
            ]);
        }
        if($result){
            return new SuccessMessage();
        }
    }

    public function changeClass($num, $id)
    {
        $student = StudentModel::get(['num' => $num]);
        $student->cid = $id;
        $result = $student->save();
        if($result){
            return new SuccessMessage();
        }
    }

    public function changeRoom($num, $id)
    {
        $student = StudentModel::get(['num' => $num]);
        $student->rid = $id;
        $result = $student->save();
        if($result){
            return new SuccessMessage();
        }
    }

    public function getMyCourse()
    {
        $uid = TokenService::getCurrentUid();
        $student = StudentModel::with('course')->get(['num' => $uid]);
        $student->visible(['course', 'num', 'name']);
        return $student;
    }

    public function getCourse($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $student = StudentModel::with('course')->get(['num' => $id]);
        $student->visible(['course', 'num', 'name']);
        return $student;
    }

    public function addCourse($s, $c)
    {
        $student = StudentModel::get(['num' => $s]);
        $course = CourseModel::get($c);
        $stucrs = StucrsModel::where('sid', $student->id)
            ->where('coid', $course->id)->find();
        if($stucrs){
            throw new UserException([
                'message' => '该课程已在学生课程表中',
                'errorCode' => 10105
            ]);
        }
        $stucrs = new StucrsModel(['sid' => $student->id, 'coid' => $course->id]);
        $result = $stucrs->save();
        if($result){
            return new SuccessMessage();
        }
    }

    public function changeCourse()
    {
        $args = request()->param();
        $student = StudentModel::get(['num' => $args['num']]);
        $stucrs = StucrsModel::where('sid', $student->id)
            ->where('coid', $args['id'])->find();
        $stucrs->score = $args['score'];
        $result = $stucrs->save();
        if($result){
            return new SuccessMessage();
        }
    }

}
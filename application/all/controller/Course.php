<?php


namespace app\all\controller;

use app\all\model\Course as CourseModel;
use app\all\model\Employee as EmployeeModel;
use app\all\model\Classroom as ClassroomModel;
use app\all\model\Crscrm as CrscrmModel;
use app\all\service\Token as TokenService;
use app\all\validate\CourseAndClassRoom;
use app\all\validate\IDCollection;
use app\all\validate\IDMustBePositiveInt;
use app\all\validate\PageGet;
use app\lib\exception\CourseException;
use app\lib\exception\SuccessMessage;

class Course extends BaseController
{
    protected $beforeActionList = [
        'checkManageScope' => ['only' => 'getAllCourse, getCourseByID,  deleteByID, deleteAll, search'],
        'checkTeacherScope' => ['only' => 'addTimeAndRoom, getCourseByEmployee']
    ];

    public function getAllCourse($page=1, $size=5)
    {
        (new PageGet())->goCheck();
        $all = CourseModel::getAll($page, $size);
        if($all->isEmpty()){
            return [
                'data' => '',
            ];
        }
        $data = $all->toArray();
        return $data;
    }

    public function getCourseByEmployee($page=1, $size=5)
    {
        $uid = TokenService::getCurrentUid();
        $employee = EmployeeModel::get(['num' => $uid]);
        $courses = CourseModel::with('room')->where('teacher', $employee->id)
            ->paginate($size, false, ['page' => $page]);
        if($courses->isEmpty()){
            return [
                'data' => '',
            ];
        }
        $data = $courses->toArray();
        return $data;
    }

    public function addCourseByEmployee( )
    {
        $args = request()->param();
        $uid = TokenService::getCurrentUid();
        $employee = EmployeeModel::get(['num' => $uid]);
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

    public function getCourse()
    {
        $all = CourseModel::All();
        if(!$all){
            return [
                'data' => '',
            ];
        }
        return $all;
    }

    public function search($q, $page=1, $size=5)
    {
        if(is_numeric($q)){
            $course = CourseModel::with(['teacher','room'])->where('id','like', '%'.$q.'%')
                ->paginate($size, false, ['page' => $page]);
        }elseif(is_string($q)){
            $where[] = ['name', 'like','%'.$q.'%'];
            $course = CourseModel::hasWhere('teacher', $where)->relation('teacher,room')
                ->paginate($size, false, ['page' => $page]);
            if($course->isEmpty()){
                $course = CourseModel::with(['teacher','room'])->where($where)
                    ->paginate($size, false, ['page' => $page]);
            }
        }
        if($course->isEmpty()){
            return ['msg' => '无搜索结果'];
        }
        $course->visible(['teacher.num','teacher.name','teacher.gender']);
        $data = $course->toArray();
        return $data;
    }

    public function getCourseByID($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $course = CourseModel::with(['teacher','room'])->get($id);
        if(!$course){
            throw new CourseException([
               'message' => '课程信息获取失败',
               'errorCode' => 50002
            ]);
        }
        $course = $course->visible(['teacher.num','teacher.name','teacher.gender']);
        return $course;
    }

    /**
     * 添加/更新课程的上课时间和课室
     */
    public function addTimeAndRoom()
    {
        (new CourseAndClassRoom)->goCheck();
        $args = request()->param();
        $crscrm = new CrscrmModel();
        $classroom = ClassroomModel::get(['name' => $args['classroom']]);
        if(!$classroom){
            return [
                'msg' => '添加课程信息失败,课室不存在'
            ];
        }
        $result = $crscrm->save([
            'coid' => $args['coid'],
            'crid' => $classroom->id,
            'week' => $args['week'],
            'start_time' => $args['start_time'],
            'end_time' => $args['end_time'],
        ]);
        return [
            'msg' => '添加课程信息成功'
        ];
    }

    public function deleteTimeAndRoom()
    {
        (new CourseAndClassRoom)->goCheck();
        $args = request()->param();
        $classroom = ClassroomModel::get(['name' => $args['classroom']]);
        $crscrm = CrscrmModel::where('coid', $args['coid'])
            ->where('crid', $classroom->id)
            ->where('week', $args['week'])
            ->where('start_time', $args['start_time'])
            ->find();
        if(!$crscrm){
            throw new CourseException([
                'message' => '删除课程信息失败',
                'errorCode' => 50003
            ]);
        }
        $result = CrscrmModel::destroy($crscrm->id);
        if($result){
            return new SuccessMessage();
        }
    }

    public function deleteByID($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $result = CourseModel::destroy($id);
        if($result){
            return new SuccessMessage();
        }
    }

    public function deleteAll($ids="")
    {
        (new IDCollection())->goCheck();
        $ids = explode(",",$ids);
        $result = CourseModel::destroy($ids);
        if($result){
            return new SuccessMessage();
        }
    }


}
<?php


namespace app\all\service;

use app\all\model\Student as StudentModel;
use app\all\model\Department as DepartmentModel;
use app\all\service\Image as ImageService;
use app\lib\enum\StudentStatus;
use think\Db;
use think\Exception;

class Student
{
    public static function addStudent($args)
    {
        Db::startTrans();
        try{
            $student = new StudentModel();

            $department = DepartmentModel::where('name',$args['department'])->find();
            $year = date('y', time());
            $students = $student->where('did', $department->id)
                ->where('year', $year)
                ->order('num desc')->select();
            $last = count($students) == 0 ? 0 :  $students[0]['num'];
            $dnum = $department->num;
            $num = self::generateNum($year, $dnum, $last);

            $student->num = $num;
            $student->did = $department->id;
            $student->year = $year;
            $student->name = $args['name'];
            $student->password = $args['password'];
            $student->gender = $args['gender'];
            $student->identity = $args['identity'];
            $student->email = array_key_exists('email', $args) ? $args['email'] : null;
            $student->from = array_key_exists('from', $args) ? $args['from'] : null;
            $student->status = StudentStatus::AtSchool;
            $student->save();
            Db::commit();
            ImageService::save('image', $student);
            return true;
        }catch (Exception $e){
            Db::rollback();
            throw $e;
        }

    }

    public static function changeStudent($student,$args)
    {
        Db::startTrans();
        try{
            if(array_key_exists('department', $args)) {
                $department = DepartmentModel::where('name', $args['department'])->find();
                $student->did = $department->id;
            }
            foreach ($args as $key=>$value){
                $student->$key = $value!=null ? $value : $student->$key;
            }
            $student->save();
            Db::commit();

            return true;
        }catch (Exception $e){
            Db::rollback();
            throw $e;
        }

    }

    /**
     * @param $year 添加的年份，例如：20
     * @param $dnum 院系编号
     * @param $last 同期同院系的最后一个编号
     * @return int|string 编号
     */
    private static function generateNum($year, $dnum, $last)
    {
        if($last != 0){
            $num = intval($last)+1;
        }else{
            $num = $year.$dnum.'10001';
        }
        return $num;
    }
}
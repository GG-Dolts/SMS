<?php


namespace app\all\service;

use app\all\model\Employee as EmployeeModel;
use app\all\model\Department as DepartmentModel;
use app\all\service\Image as ImageService;
use app\lib\enum\EmployeeStatus;
use think\Db;
use think\Exception;

class Employee
{
    public static function addEmployee($args)
    {
        Db::startTrans();
        try{
            $employee = new EmployeeModel();

            $department = DepartmentModel::where('name',$args['department'])->find();
            $employees = $employee->where('did', $department->id)
                ->order('num desc')->select();
            $last = count($employees) == 0 ? 0 :  $employees[0]['num'];
            $dnum = $department->num;
            $num = self::generateNum($dnum, $last);

            $employee->num = $num;
            $employee->did = $department->id;
            $employee->name = $args['name'];
            $employee->password = $args['password'];
            $employee->title = $args['title'];
            $employee->gender = $args['gender'];
            $employee->education = $args['education'];
            $employee->identity = $args['identity'];
            $employee->email = array_key_exists('email', $args) ? $args['email'] : null;
            $employee->from = array_key_exists('from', $args) ? $args['from'] : null;
            $employee->address = array_key_exists('address', $args) ? $args['address'] : null;
            $employee->scope = array_key_exists('scope', $args) ? $args['scope'] : 16;
            $employee->status = EmployeeStatus::OnTheJob;
            $employee->save();
            Db::commit();
            ImageService::save('image', $employee);
            return true;
        }catch (Exception $e){
            Db::rollback();
            throw $e;
        }
    }

    public static function changeEmployee($employee, $args)
    {
        Db::startTrans();
        try{
            if(array_key_exists('department', $args)) {
                $department = DepartmentModel::where('name', $args['department'])->find();
                $employee->did = $department->id;
            }
            foreach ($args as $key=>$value){
                $employee->$key = $value!=null ? $value : $employee->$key;
            }
            $employee->save();
            Db::commit();

            return true;
        }catch (Exception $e){
            Db::rollback();
            throw $e;
        }
    }

    private static function generateNum($dnum, $last)
    {
        if($last != 0){
            $num = intval($last)+1;
        }else{
            $num = $dnum.'10001';
        }
        return $num;
    }

}
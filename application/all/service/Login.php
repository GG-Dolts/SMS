<?php


namespace app\all\service;

use app\all\model\Employee as EmployeeModel;
use app\all\model\Student as StudentModel;
use app\lib\exception\UserException;

class Login
{
    public function login($num, $password){
        $length = strlen($num);
        if($length == 8){
            $user = $this->employeeLogin($num, $password);
        }elseif($length == 10){
            $user = $this->studentLogin($num, $password);
        }else{
            throw new UserException([
                'message' => '用户不存在'
            ]);
        }
        return $user;
    }

    public function employeeLogin($num, $password){
        $employee = EmployeeModel::where('num',$num)
            ->where('password',$password)
            ->find();
        if(!$employee){
            throw new UserException([
                'message' => '用户不存在或密码错误'
            ]);
        }
        return $employee;
    }

    public function studentLogin($num, $password){
        $student = StudentModel::where('num',$num)
            ->where('password',$password)
            ->find();
        if(!$student){
            throw new UserException([
                'message' => '用户不存在或密码错误'
            ]);
        }
        return $student;
    }
}
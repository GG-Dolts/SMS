<?php


namespace app\all\model;


use app\lib\enum\EmployeeStatus;
use app\lib\exception\UserException;

class Employee extends BaseModel
{
    protected $hidden = [
        'id', 'did', 'create_time', 'update_time', 'delete_time'
    ];

    public function belongs()
    {
        return $this->belongsTo('Department', 'did', 'id');
    }

    public function clazz()
    {
        return $this->hasMany('Clazz', 'leader', 'id');
    }

    public function course()
    {
        return $this->hasMany('Course', 'teacher', 'id');
    }

    public function getGenderAttr($value)
    {
        return $value == 1 ? "男" : "女";
    }

    public function setGenderAttr($value)
    {
        return ($value == "男" || $value == "1") ? 1 : 0;
    }

    public function getAddressAttr($value)
    {
        $result = $value;
        if (empty($value))
            $result = "未知";
        return $result;
    }

    public function getStatusAttr($value)
    {
        if ($value == 1)
            $result = "在岗";
        elseif ($value == 0)
            $result = "离职";
        elseif ($value == 2)
            $result = "产假";
        elseif ($value == 3)
            $result = "实习";
        else
            $result = "无效";
        return $result;
    }

    public function setStatusAttr($value)
    {
        if ($value == "在岗" or $value == EmployeeStatus::OnTheJob)
            $result = 1;
        elseif ($value == "离职" or $value == EmployeeStatus::QuitOffice)
            $result = 0;
        elseif ($value == "产假" or $value == EmployeeStatus::Vacation)
            $result = 2;
        elseif ($value == "实习" or $value == EmployeeStatus::Internship)
            $result = 3;
        else
            $result = 9;
        return $result;
    }

    /*
    public function setPasswordAttr($value)
    {
        if(!$value)
            $value = md5($value);
        return $value;
    }*/

    public static function checkUserLogin($num, $pswByUser)
    {
        $psw = md5($pswByUser);
        $user = self::find($num);
        if($psw !== $user->password){
            throw new UserException([
                'message'=>'密码错误'
            ]);
        }
        return $user;
    }

    public static function getAll($page=1, $size=10)
    {
        $paging = self::with(['belongs','clazz','course'])->paginate($size,false,['page'=>$page]);
        $paging->visible(['belongs.name']);
        $paging->hidden(['clazz.leader']);
        $paging->hidden(['course.teacher']);
        return $paging;
    }
}
<?php


namespace app\all\service;

use app\all\model\Department as DepartmentModel;
use app\all\service\Image as ImageService;
use think\Db;

class Department
{
    public static function addDepartment($args)
    {
        Db::startTrans();
        try {
            $department = new DepartmentModel();
            $department->num = $args['num'];
            $department->name = $args['name'];
            $department->year = array_key_exists('year', $args) ? $args['year'] : 4;
            $department->delay = array_key_exists('delay', $args) ? $args['delay'] : 2;
            $department->introduce = array_key_exists('delay', $args) ? $args['introduce'] : '';

            $department->save();
            Db::commit();
            ImageService::save('image', $department);
            return true;
        }
        catch (Exception $e) {
            Db::rollback();
            return false;
        }

    }

    public static function changeDepartment($department, $args)
    {
        Db::startTrans();
        try {

            foreach ($args as $key=>$value){
                $department->$key = $value!=null ? $value : $department->$key;
            }

            $department->save();
            Db::commit();
            return true;
        }
        catch (Exception $e) {
            Db::rollback();
            return false;
        }

    }

    public static function saveImage()
    {

    }
}
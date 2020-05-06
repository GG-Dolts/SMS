<?php


namespace app\all\service;


use app\lib\exception\UserException;

class Image
{
    public static function save($inputName, $obj, $path='static/image/'){
        $file = request()->file($inputName);
        $name = $file->getInfo('name');
        $suffix = explode('.',$name)[1];
        $after = self::generateName($name);
        $info = $file->validate(['size'=>5120000,'ext'=>'jpg,png,gif'])->rule('md5')
            ->move('../public/pages/'.$path,$after.".$suffix");
        if($info){
            $obj->image = $path.$after.".$suffix";
            $obj->save();
        }else{
            throw new UserException([
                'message' => '图片上传失败',
                'errorCode' => '10008'
            ]);
        }
    }

    public static function generateName($originName){
        $time = time();
        $union = $time.$originName;
        $after = md5($union);
        return $after;
    }


}

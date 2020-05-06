<?php


namespace app\all\service;


use app\lib\enum\UserAuth;
use app\lib\exception\ForbiddenException;
use app\lib\exception\TokenException;
use think\Exception;

class Token
{
    public static function saveTokenToCache($user){
        $key = self::generateToken();
        $expire = config('settings.expire');

        $cacheValue = self::prepareCachedValue($user);
        $value = json_encode($cacheValue);

        $request = cache($key, $value, $expire);
        cookie('token', $key, $expire);
        if(!$request){
            throw new TokenException();
        }
        return ["token"=>$key, "expire"=>$expire];
    }

    private static function prepareCachedValue($user)
    {
        $cachedValue = [];
        $cachedValue['num'] = $user['num'];
        $cachedValue['scope'] = $user['scope'];
        return $cachedValue;
    }

    public static function generateToken(){
        $rand = self::generateRandStr(16);
        $timestamp = time();
        $salt = config('security.token_salt');
        return md5($rand.$timestamp.$salt);
    }

    public static function generateRandStr($length, $isUpper=null){
        $origin = 'QWERTYUIOPASDFGHJKLZXCVBNM0123456789qazwsxedcrfvtgbyhnujmikolp';
        $max = strlen($origin) - 1;
        $length = $length or 10;
        $result = '';

        for($i = 0; $i < $length; $i++){
            $result .= $origin[rand(0, $max)];
        }

        if($isUpper){
            $result = strtoupper($result);
        }
        elseif(is_bool($isUpper)){
            $result = strtolower($result);
        }
        return $result;
    }

    public static function getCurrentTokenVar($key)
    {
        //$token = request()->header('token');
        $token = cookie('token');
        $vars = cache($token);
        if (!$vars) {
            throw new TokenException();
        } else {
            if (!is_array($vars)) {
                $vars = json_decode($vars, true);
            }
            if (array_key_exists($key, $vars)) {
                return $vars[$key];
            } else {
                throw new Exception('尝试获取的Token变量不存在');
            }
        }
    }

    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('num');
        return $uid;
    }

    public static function needManageScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope > UserAuth::Manager) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

    public static function needTeacherScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope >= UserAuth::Manager) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

    public static function needStudentScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope == UserAuth::Student) {
                return true;
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }
}


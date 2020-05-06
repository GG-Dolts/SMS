<?php


namespace app\all\controller;


use app\all\validate\LoginValidate;
use app\all\service\Login as LoginService;
use app\all\service\Token as TokenService;
use app\lib\enum\UserAuth;

class Index extends BaseController
{
    public function login(){
        if(request()->isPost()){
            (new LoginValidate())->goCheck();
            $args = request()->post();
            $num = $args['num'];
            $password = $args['password'];

            $user = (new LoginService())->login($num, $password);
            $token = TokenService::saveTokenToCache($user);
            $token['scope'] = $user['scope'];

            if($user->scope == UserAuth::Super){
                return $token;
            }elseif($user->scope == UserAuth::Manager){
                return $token;
            }elseif($user->scope == UserAuth::Student){
                return $token;
            }
        }

        return null;
    }

}
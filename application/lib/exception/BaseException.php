<?php

namespace app\lib\exception;

use think\Exception;
use Throwable;

class BaseException extends Exception
{
    //HTTP 状态码
    public $code = 400;
    //错误信息
    public $message = '参数错误';
    //错误码
    public $errorCode = 10000;

    public function __construct($params = [])
    {
        if(!is_array($params)){
            return ;
        }else{
            if(key_exists('code', $params)){
                $this->code = $params['code'];
            }
            if(key_exists('message', $params)){
                $this->message = $params['message'];
            }
            if(key_exists('errorCode', $params)){
                $this->errorCode = $params['errorCode'];
            }
        }

    }

}
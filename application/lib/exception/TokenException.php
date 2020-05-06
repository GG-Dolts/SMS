<?php


namespace app\lib\exception;


class TokenException extends BaseException
{
    public $code = 404;
    public $message = '获取Token失败';
    public $errorCode = 20001;
}
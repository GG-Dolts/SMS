<?php


namespace app\lib\exception;


class ParameterException extends BaseException
{
    public $code = 400;
    public $message = '参数错误';
    public $errorCode = 10003;
}

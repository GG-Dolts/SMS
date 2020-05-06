<?php


namespace app\lib\exception;


class UserException extends BaseException
{
    public $code = 400;
    public $message = '该用户不存在';
    public $errorCode = 10001;
}
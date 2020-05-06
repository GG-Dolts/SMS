<?php


namespace app\lib\exception;


class ClassException extends BaseException
{
    public $code = 404;
    public $message = '该班级不存在';
    public $errorCode = 30001;
}
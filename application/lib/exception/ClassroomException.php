<?php


namespace app\lib\exception;


class ClassroomException extends BaseException
{
    public $code = 404;
    public $message = '该教室不存在';
    public $errorCode = 40001;
}
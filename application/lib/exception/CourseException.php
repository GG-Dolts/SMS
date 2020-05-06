<?php


namespace app\lib\exception;


class CourseException extends BaseException
{
    public $code = 400;
    public $message = '该课程不存在';
    public $errorCode = 50001;
}
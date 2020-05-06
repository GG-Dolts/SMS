<?php


namespace app\lib\exception;


class DepartmentException extends BaseException
{
    public $code = 400;
    public $message = '该院系不存在';
    public $errorCode = 70001;
}
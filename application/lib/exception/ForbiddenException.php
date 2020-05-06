<?php


namespace app\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $message = '权限不够';
    public $errorCode = 10002;
}
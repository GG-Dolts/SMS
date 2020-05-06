<?php


namespace app\lib\exception;


class RoomException extends BaseException
{
    public $code = 400;
    public $message = '该宿舍不存在';
    public $errorCode = 60001;
}
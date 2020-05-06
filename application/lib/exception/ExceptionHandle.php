<?php


namespace app\lib\exception;


use Exception;
use think\exception\Handle;

class ExceptionHandle extends Handle
{
    public $code = null;
    public $message = null;
    public $errorCode = null;

    public function render(Exception $e)
    {
        if($e instanceof BaseException){
            $this->code = $e->code;
            $this->message = $e->message;
            $this->errorCode = $e->errorCode;
        }else{
            if(config('app.app_debug')){
                return parent::render($e);
            }
            $this->code = 500;
            $this->message = '系统内部错误';
            $this->errorCode = 999;
        }

        $result = [
            'message' => $this->message,
            'error_code' => $this->errorCode,
            'url' => request()->url()
        ];

        return json($result, $this->code);
    }
}
<?php

namespace app\all\controller;

use app\all\service\Token;
use think\Controller;

class BaseController extends Controller
{
    protected function checkManageScope()
    {
        Token::needManageScope();
    }

    protected function checkTeacherScope()
    {
        Token::needTeacherScope();
    }

    protected function checkStudentScope()
    {
        Token::needStudentScope();
    }
}
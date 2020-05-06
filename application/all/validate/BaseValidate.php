<?php

namespace app\all\validate;


use app\lib\exception\ParameterException;
use app\lib\exception\UserException;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck(){
        $params = request()->param();
        $result = self::check($params);
        if(!$result){
            throw new UserException([
                'message' => $this->getError()
            ]);
        }
        return true;
    }

    protected function isPositiveInteger($value)
    {
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){
            return true;
        }
        return false;
    }

    protected function isNotEmpty($value)
    {
        if(empty($value)){
            return false;
        }
        return true;
    }

    protected function isIdentity($value)
    {
        if($this->isNotEmpty($value)){
            $pattern = '/^[1-6]\d{5}(19|20|21)\d{2}((0[1-9])|(1[0-2]))(([0-2][1-9])|10|20|30|31)\d{3}[0-9Xx]$/';
            preg_match($pattern, $value, $match);
            if(!empty($match)){
                return true;
            }

        }
        return false;
    }

    /**
     * 数据源合法性判断，以免恶意替换外键、主键
     * @param $array
     * @return array
     */
    public function isLegalData($array)
    {
        foreach (array_keys($array) as $key) {
            $rule = '/^.*id.*$/';
            $result = preg_match($rule, $key);
            if($result){
                throw new ParameterException([
                    'message' => '参数中包含有非法字段'
                ]);
            }
        }
    }
}
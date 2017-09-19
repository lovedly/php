<?php
namespace app\api\validate;

use think\Validate;
use think\Exception;
use think\Request;
use app\lib\exception\ParameterException;

class BaseValidate extends Validate
{
    public function gocheck()
    {
        //获取http传入的参数
        //对参数进行校验
        $request =Request::instance();
        $params = $request->param();
        
        $result =$this->batch()->check($params);
        if (!$result){
            $e = new ParameterException([
                'msg' => $this->error,
                
            ]);
            
            throw $e;
        }else {
            return true;
        }
        
    }
    
    protected  function isPostiveInteger($value,$rule='',
        $data='',$field='')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        else {
            return false;
        }
    }
}
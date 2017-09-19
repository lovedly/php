<?php
namespace app\lib\exception;

use think\Exception;
use think\exception\Handle;
use think\Request;
use think\Log;
use think\exception\HttpException;


class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;
    //需要返回客户端当前请求的URL路径
    
    public function render(\Exception $e) 
    {
        if($e instanceof  BaseException){
            //如果是自定义的异常
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }else {
            if(config('app_debug')){
                // 调试状态下需要显示TP默认的异常页面，因为TP的默认页面
                // 很容易看出问题
                return parent::render($e);
            } 
            $this->code = 500;
            $this->msg = '服务器内部错误,不想告诉你';
            $this->errorCode = 999;
            $this->recordErrorLog($e);
        } 
        $request =Request::instance();
        
        $result = [
            'msg' => $this->msg,
            'error_code'=>$this->errorCode,
            'request_url' =>$request->url(),
        ];
        return json($result,$this->code);
        
    }
    
    private function recordErrorLog(\Exception $e){
        Log::init([
            'type'=>'File',
            'path'=>LOG_PATH,
            'level'=>['error'],
        ]);
        Log::record($e->getMessage(),'error');
    }
}
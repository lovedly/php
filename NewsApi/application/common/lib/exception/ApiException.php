<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2
 * Time: 22:51
 */
namespace app\common\lib\exception;
use think\Exception;
use Throwable;

class ApiException extends Exception{

    public $message = '';
    public $httpCode = 500;
    public $code = 0;
    /**
     * ApiException constructor.
     * @param string $message
     * @param int $httpCode
     * @param int $code
     */
    public function __construct($message = "", $httpCode = 0,$code = 0){
        $this->message = $message;
        $this->httpCode = $httpCode;
        $this->code = $code;
    }

}
<?php
namespace app\index\controller;

use app\common\lib\ali\Alidayu;

class Index
{
    public function index()
    {
        return 'ok';
    }

    public function sms(){
        try{
            Alidayu::getInstance()->sendSMS(13554239393);
            
        }catch (\Exception $e){

        }
    }
}

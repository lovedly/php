<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/2
 * Time: 21:31
 */
namespace app\api\controller\v1;

use app\common\lib\Aes;
use app\common\lib\exception\ApiException;
use app\api\controller\Common;
use app\common\lib\Upload;
use think\Controller;

class Image extends Controller{
    public function save(){
        $image = Upload::image();
        if ($image){
            return show(config('code.success'),'Ok',config('qiniu.image_url').'/'.$image);

        }

    }
    

}
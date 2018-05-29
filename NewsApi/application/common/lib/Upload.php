<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/21
 * Time: 10:42
 */
namespace app\common\lib;
// 引入鉴权类
use Qiniu\Auth;
// 引入上传类
use Qiniu\Storage\UploadManager;
//如果不是使用composer的话就需要require VENDOR_PATH.'qiniu/php-sdk-7.2/autoload.php';
/**
 * 七牛图片上传基础类库
 */
class Upload
{

    public static function image(){
        if(empty($_FILES['file']['tmp_name'])){
            exception('你提交的图片数据不合法',404);
        }
        // 图片的本地路径
        $file = $_FILES['file']['tmp_name'];
        // 换取上传图片的后缀
        // $ext = explode('.',$_FILES['file']['name'])[1];
        $pathinfo = pathinfo($_FILES['file']['name']);
        $ext = $pathinfo['extension'];
        $config = config('qiniu');
        // 构建一个鉴权对象
        $auth = new Auth($config['ak'],$config['sk']);
        // 生成上传的token
        $token = $auth->uploadToken($config['bucket']);
        // 上传到七牛后保存的文件名
        $key = date('Y').'/'.date('m').'/'.substr(md5($file),0,5).date('YmdHis').mt_rand(0,9999).'.'.$ext;

        // 初始化UploadManager类
        $uploadMgr = new UploadManager();
        list($ret,$err) = $uploadMgr->putFile($token,$key,$file);
        if($err !== null){
            return null;
        }else{
            return $key;
        }
    }
}  
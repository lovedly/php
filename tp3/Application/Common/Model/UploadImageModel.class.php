<?php
namespace Common\Model;
use Think\Model;

/**
 * 上传图片类 
 */
class UploadImageModel extends Model {
    private $_uploadObj = '';
    private $_uploadImageData = '';

    const UPLOAD = 'upload';

    public function __construct() {
        $this->_uploadObj = new  \Think\Upload();

        $this->_uploadObj->rootPath = './'.self::UPLOAD.'/';
        $this->_uploadObj->subName = date(Y) . '/' . date(m) .'/' . date(d);
    }

    public function upload() {
        $res = $this->_uploadObj->upload();

        if($res) {
            return '/' .self::UPLOAD . '/' . $res['imgFile']['savepath'] . $res['imgFile']['savename'];
        }else{
            return false;
        }
    }

    public function imageUpload() {
        $res = $this->_uploadObj->upload();
        /* print_r($res);exit;
        [name] => 6.JPG
        [type] => application/octet-stream
        [size] => 73240
        [key] => file
        [ext] => JPG
        [md5] => 0778e806dc70028594e9438b65083dd3
        [sha1] => 42dcbd0c173fdb4aebc1bcafa87cd5169d2211b0
        [savename] => 58db6d3299244.JPG
        [savepath] => 2017/03/29/ */
        if($res) {
            return '/' .self::UPLOAD . '/' . $res['file']['savepath'] . $res['file']['savename'];
        }else{
            return false;
        }
    }
}

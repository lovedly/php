<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/18
 * Time: 20:39
 */
namespace app\common\validate;
use think\Validate;

class Identify extends Validate{

    protected $rule =[
        'id' => 'require|number|length:11',

    ];
}
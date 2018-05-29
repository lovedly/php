<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19
 * Time: 16:38
 */
namespace app\common\lib\ali;
use app\common\lib\Aes;
use think\Cache;
use Aliyun\Core\Config;
use Aliyun\Core\Profile\DefaultProfile;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
require_once EXTEND_PATH.'ali/vendor/autoload.php';

// 加载区域结点配置
Config::load();

class Alidayu{
    /**
     * 短信验证码单例
     * Created by
     * User:
     * Date:
     * Time:
     */

    public $acsClient = '';
    /**
     * 静态变量保存全局实例
     */
    private static $_instance = null;

    /**
     * 私有构造方法
     */
    private function __construct(){}

    /**
     * 获取单例实例
     */
    public static function getInstance(){
        if(empty(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 发送短信验证码
     * $phone ：手机号码
     */
    public function sendSMS($phone) {
        // 短信API产品名
        $product = config('aliyun.product');

        // 短信API产品域名
        $domain = config('aliyun.domain');

        // 暂时不支持多Region
        $region = config('aliyun.region');

        // 服务结点
        $endPointName = config('aliyun.endPointName');

        // 初始化用户Profile实例
        $profile = DefaultProfile::getProfile($region, config('aliyun.accessKeyId'), config('aliyun.accessKeySecret'));

        // 增加服务结点
        DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

        // 初始化AcsClient用于发起请求
        $this->acsClient = new DefaultAcsClient($profile);

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();

        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($phone);

        // 必填，设置签名名称
        $request->setSignName(config('aliyun.signName'));

        // 必填，设置模板CODE
        $request->setTemplateCode(config('aliyun.templateCode'));

        //随机数
        $random = rand(10000,999999);

        //模板参数数组
        $send_info = [
            'code' => $random,

        ];
        // 可选，设置模板参数

        $request->setTemplateParam(json_encode($send_info));

        // 发起访问请求
        $acsResponse = $this->acsClient->getAcsResponse($request);

        // 打印请求结果
        if(!$acsResponse->Code == "OK"){
            return false;
        }
        //设置手机号码有效期为 5 分钟
        Cache::set($phone,$random,config('aliyun.valid_time'));

        return true;
    }

    /**
     * 检查短信验证码是否失效
     * @param $phone
     * @return 返回验证码
     */
    public static function checkValidPhone($phone) {
        if(!$phone){
            return false;
        }
        return Cache::get($phone);
    }
}
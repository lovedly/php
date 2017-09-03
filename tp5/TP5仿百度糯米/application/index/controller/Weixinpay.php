<?php
namespace app\index\controller;
use think\Controller;
use wxpay\database\WxPayUnifiedOrder;
use wxpay\database\WxPayResults;
use wxpay\NativePay;
use wxpay\WxPayConfig;
use wxpay\WxPayApi;
use wxpay\WxPayNotify;
use wxpay\PayNotifyCallBack;

class Weixinpay extends controller
{
    public function notify(){
        //测试
        $weixinData =file_get_contents("php://input");
        /* $weixinData = '<xml><appid><![CDATA[wx2421b1c4370ec43b]]></appid>
  <attach><![CDATA[支付测试]]></attach>
  <bank_type><![CDATA[CFT]]></bank_type>
  <fee_type><![CDATA[CNY]]></fee_type>
  <is_subscribe><![CDATA[Y]]></is_subscribe>
  <mch_id><![CDATA[10000100]]></mch_id>
  <nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
  <openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>
  <out_trade_no><![CDATA[1409811653]]></out_trade_no>
  <result_code><![CDATA[SUCCESS]]></result_code>
  <return_code><![CDATA[SUCCESS]]></return_code>
  <sign><![CDATA[B552ED6B279343CB493C5DD0D78AB241]]></sign>
  <sub_mch_id><![CDATA[10000100]]></sub_mch_id>
  <time_end><![CDATA[20140903131540]]></time_end>
  <total_fee>1</total_fee>
  <trade_type><![CDATA[JSAPI]]></trade_type>
  <transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id>
 </xml>'; */
        
     $weixinData= ' 
   array(17) {
  ["appid"] => "wx2421b1c4370ec43b"
  ["attach"] => "支付测试"
  ["bank_type"] => "CFT"
  ["fee_type"] => "CNY"
  ["is_subscribe"] =>  "Y"
  ["mch_id"] =>  "10000100"
  ["nonce_str"] =>  "5d2b6c2a8db53831f7eda20af46e531c"
  ["openid"] =>  "oUpF8uMEb4qRXf22hE3X68TekukE"
  ["out_trade_no"] =>  "1409811653"
  ["result_code"] =>  "SUCCESS"
  ["return_code"] => "SUCCESS"
  ["sign"] =>  "B552ED6B279343CB493C5DD0D78AB241"
  ["sub_mch_id"] =>  "10000100"
  ["time_end"] =>  "20140903131540"
  ["total_fee"] =>  "1"
  ["trade_type"] =>  "JSAPI"
  ["transaction_id"] =>  "1004400740201409030005092168"
}';
     
     print_r($weixinData['return_code']);exit;
        try {
            $resultObj = new WxPayResults();
            $weixinData =$resultObj->Init($weixinData);
            return $resultObj->toXml();
        }catch (\Exception $e){
            $resultObj->setData('return_code','FAIL');
            $resultObj->setData('return_msg','error');
        }
        
        if($weixinData['return_code'] === 'FAIL' || ['result_code'] !== 'SUCCESS'){
            $resultObj->setData('return_code','FAIL');
            $resultObj->setData('return_msg','error');
            return $resultObj->toXml();
        }
       // 根据订单号查询订单数据
       $ouTradeNo = $weixinData['out_trade_no'];
       $order =model('Order')->get(['out_trade_no'=>$ouTradeNo]);
       if (!$order || $order->pay_status=1){
           $resultObj->setData('return_code','SUCCESS');
           $resultObj->setData('return_msg','Ok');
           return $resultObj->toXml();
       }
        //更新表 订单表 商品表
       
        try {
            $orderRes = model('Order')->updataOrderByOuTradeNo($ouTradeNo,$weixinData);
       
             model('Deal')->updataBuyCountById($order->id,$order->deal_count); 
        
            //消费券生成
            $conpons =[
                'sn' =>$ouTradeNo,
                'password' =>rand(10000,99999),
                'user_id'=>$order->user_id,
                'deal_id'=>$order->deal_id,
                'order_id'=>$order->id,
            ];
            model('Conpons')->add($conpons);
            
            //发送邮件 
        }catch (\Exception $e){
           // 说明没有更新 告诉微信服务器 我们还需要回调
           return false;
       }
       $resultObj->setData('return_code','SUCCESS');
       $resultObj->setData('return_msg','Ok');
       return $resultObj->toXml();
    }
    
    public function wxpayQCode($id){
        $notify =new  NativePay();
        $input = new WxPayUnifiedOrder();
        $input->setBody("支付0.01元");
        $input->setAttach("支付0.01元");
        $input->setOutTradeNo(WxPayConfig::MCHID.date("YmdHis"));
        $input->setTotalFee("1");
        $input->setTimeStart(date("YmdHis"));
        $input->setTimeExpire(date("YmdHis", time() + 600));
        $input->setGoodsTag("QRCode");
        $input->setNotifyUrl("/index.php/index/weixinpay/notify");
        $input->setTradeType("NATIVE");
        $input->setProductId($id);
        $result = $notify->getPayUrl($input);
        if (empty($result["code_url"])){
            $url = '';
        }else{
            $url = $result["code_url"];
        }
        return '<img alt="扫码支付" src="/weixin/example/qrcode.php?data='.urlencode().'" style="width:300px;height:300px;"/>';
	   
    }
}

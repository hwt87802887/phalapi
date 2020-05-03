<?php
/*
 * +----------------------------------------------------------------------
 * | 支付宝接口
 * +----------------------------------------------------------------------
 */
require_once("Lib/AopClient.php");
class Alipay_Lite {

    protected $alipay_config =array(

        //商户的私钥（后缀是.pen）文件相对路径
        'private_key_path' => '\\Library\\Alipay\key\\rsa_private_key.pem',

        //支付宝公钥（后缀是.pen）文件相对路径
        'ali_public_key_path' => '\\Library\\Alipay\\key\\alipay_public_key.pem',

    );

    public function __construct(){
        $this->alipay_config['private_key_path'] = dirname(dirname(dirname(__FILE__))).$this->alipay_config['private_key_path'];
        $this->alipay_config['ali_public_key_path'] = dirname(dirname(dirname(__FILE__))).$this->alipay_config['ali_public_key_path'];
    }

    public function getAlipay($con,$notify_url){
        //公共参数
        $Client = new AopClient();//实例化支付宝sdk里面的AopClient类,下单时需要的操作,都在这个类里面
        $param['app_id'] = '2016071501620914';
        $param['method'] = 'alipay.trade.app.pay';//接口名称，固定值
        $param['charset'] = 'utf-8';//请求使用的编码格式
        $param['sign_type'] = 'RSA';//商户生成签名字符串所使用的签名算法类型
        $param['timestamp'] = date("Y-m-d Hi:i:s");//发送请求的时间
        $param['version'] = '1.0';//调用的接口版本，固定为：1.0
        $param['notify_url'] = $notify_url;
        $param['biz_content'] = $con;//业务请求参数的集合,长度不限,json格式，即前面一步得到的

        $paramStr = $Client->getSignContent($param);//组装请求签名参数
        $sign = $Client->alonersaSign($paramStr, $this->alipay_config['private_key_path'], 'RSA2', true);//生成签名
        $param['sign'] = $sign;
        $str = $Client->getSignContentUrlencode($param);//最终请求参数
        return $str;
    }


}

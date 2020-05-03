<?php
/**
 *  Lite.php
 *  视图控制器
 *
 *  Created by SteveAK on 04/27/16
 *  Copyright (c) 2016 SteveAK. All rights reserved.
 *  Contact email(aer_c@qq.com) or qq(7579476)
 */

class Sms_Lite {

    protected $accountSid = 'aaf98f894c9d994b014ca65c03bf068b';
    protected $accountToken = 'e6d2dd5e72204154bdba00e33eb743e8';
    protected $appId = '8aaf070857418a5801574c0337b4089c';
    protected $serverIP='app.cloopen.com';
    protected $serverPort='8883';
    protected $softVersion='2013-12-26';
    protected $tempId = '124144';

    /**
     * 发送短信
     * @param  string $tel  电话号码
     */
    public function sendSMS($tel,$datas) {

        require_once('SDK/CCPRestSDK.php');
        // 初始化REST SDK
        $rest = new REST($this->serverIP,$this->serverPort,$this->softVersion);
        $rest->setAccount($this->accountSid,$this->accountToken);
        $rest->setAppId($this->appId);
        $tishi = '';
        // 发送模板短信
        $tishi.="Sending TemplateSMS to $tel <br/>";
        $result = $rest->sendTemplateSMS($tel,$datas,$this->tempId);
        if($result == NULL ) {
            $tishi.="result error!";
            return $tishi;
        }
        if($result->statusCode!=0) {
            $tishi.="error code :" . $result->statusCode . "<br>";
            $tishi.="error msg :" . $result->statusMsg . "<br>";
            return $tishi;
            //TODO 添加错误处理逻辑
        }else{
            $tishi.="Sendind TemplateSMS success!<br/>";
            // 获取返回信息
            $smsmessage = $result->TemplateSMS;
            $tishi.="dateCreated:".$smsmessage->dateCreated."<br/>";
            $tishi.="smsMessageSid:".$smsmessage->smsMessageSid."<br/>";
            $tishi="1";
            return $tishi;
            //TODO 添加成功处理逻辑
        }
    }

    /**
     * 获取随机数字验证码
     * @return  array 返回数组
     */
    public function getRand(){
        $a = rand(0,9);
        $b = rand(0,9);
        $c = rand(0,9);
        $d = rand(0,9);
        $e = rand(0,9);
        $yzm = $a.$b.$c.$d.$e;
        return array($yzm,'10');
    }

}
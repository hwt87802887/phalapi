<?php

class Domain_Pay {
    
    public function  getRecharge($token){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] >0 ) {
            return $auth;
        }
        $model = new Model_Pay();
        return $model -> getRecharge();
    }

    //小说-支付宝
    public function  getAlipay($token,$recharge_id,$sdtoken){
       $rs = $this->getOrder($token,$recharge_id,'alipay',$sdtoken);
        if($rs['code'] > 0){
            return $rs;
        }
        $data['code']=$rs['code'];
        $data['msg']=$rs['msg'];


        //构造业务请求参数的集合(订单信息)
        $content = array();
        $content['subject'] = $rs['subject'];
        $content['out_trade_no'] = $rs['orderid'];
        //$content['timeout_express'] = "该笔订单允许的最晚付款时间";
        $content['total_amount'] = $rs['price'];
        $content['product_code'] = "QUICK_MSECURITY_PAY";//销售产品码,固定值
        $con = json_encode($content);//$content是biz_content的值,将之转化成json字符串

        $notify_url=DI()->config->get('sys.xsapp.siteurl')."Public/pay/aliwap/notify.php";

        DI()->alipay = new Alipay_Lite();
        $orderinfo = DI()->alipay->getAlipay($con,$notify_url);
        $data['list']['orderinfo']=$orderinfo;
        $data['list']['orderid']=$rs['orderid'];
        return $data;

    }

    //拼团-支付宝
    public function  getAlipay_Pt($token,$id,$type,$infoid,$beizhu,$adrid){
        $rs = $this->getOrder_Pt($token,$id,'alipay',$type,$infoid,$beizhu,$adrid);
        if($rs['code'] > 0){
            return $rs;
        }
        $data['code']=$rs['code'];
        $data['msg']=$rs['msg'];

        //构造业务请求参数的集合(订单信息)
        $content = array();
        $content['subject'] = $rs['subject'];
        $content['out_trade_no'] = $rs['orderid'];
        //$content['timeout_express'] = "该笔订单允许的最晚付款时间";
        $content['total_amount'] = $rs['price'];
        $content['product_code'] = "QUICK_MSECURITY_PAY";//销售产品码,固定值
        $con = json_encode($content);//$content是biz_content的值,将之转化成json字符串

        $notify_url=DI()->config->get('sys.xsapp.siteurl')."Public/pay/aliwap_pt/notify.php";

        DI()->alipay = new Alipay_Lite();
        $orderinfo = DI()->alipay->getAlipay($con,$notify_url);
        $data['list']['orderinfo']=$orderinfo;
        $data['list']['orderid']=$rs['orderid'];
        return $data;

    }

    //小说-微信
    public function  getWeixinpay($token,$recharge_id,$sdtoken){
        $rs = $this->getOrder($token,$recharge_id,'weixin',$sdtoken);
        if($rs['code'] > 0){
            return $rs;
        }

        DI()->wxpay = new Wxpay_Lite();
        $response =  DI()->wxpay->getPrePayOrder($rs['subject'], $rs['orderid'], $rs['price']*100);

        //print_r($response);
        //print_r("---拿到prepayId再次签名----");
        $x =  DI()->wxpay->getOrder($response['prepay_id']);
        $x['orderid'] = $rs['orderid'];
        return array('code'=>0,'msg'=>'订单创建成功','list'=>$x);

    }

    //拼团-微信
    public function  getWeixinpay_Pt($token,$id,$type,$infoid,$beizhu,$adrid){
        $rs = $this->getOrder_Pt($token,$id,'weixin',$type,$infoid,$beizhu,$adrid);
        if($rs['code'] > 0){
            return $rs;
        }

        DI()->wxpay = new Wxpay_Lite();
        $response =  DI()->wxpay->getPrePayOrder($rs['subject'], $rs['orderid'], $rs['price']*100,2);

        //print_r($response);
        //print_r("---拿到prepayId再次签名----");
        $x =  DI()->wxpay->getOrder($response['prepay_id']);
        $x['orderid'] = $rs['orderid'];
        return array('code'=>0,'msg'=>'订单创建成功','list'=>$x);

    }
    
    //未支付回调-小说
    public function getUnPay($token,$orderid){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $model = new Model_Pay();
        $order = $model->getOrderByOrderId($orderid);
        if(!$order){
            return array('code'=>1,'msg'=>'订单号不存在','list'=>array());
        }
        return $model->getUnPay($auth['uid'],$order);
    }

    //订单重新支付
    public function getRePay($token,$orderid,$ordertype){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $model = new Model_Pay();
        $order = $model->getOrderByOrderId($orderid);
        if(!$orderid){
            return array('code'=>1,'msg'=>'订单号不存在','list'=>array());
        }

        if($order['zt']>0){
            return array('code'=>3,'msg'=>'订单已支付，请不要重复支付','list'=>array());
        }
        if(($order['addtime']+(7*86400)) < time()){
            return array('code'=>1,'msg'=>'订单已失效','list'=>array());
        }

        $paytype = $order['paytype'];
        if($ordertype && $ordertype!="alipay" && $ordertype!="weixin") {
            return array('code'=>1,'msg'=>'支付类型错误','list'=>array());
        }
        if($ordertype && $paytype!=$ordertype){
            $paytype = $ordertype;
            $model->getUpdatePay($orderid,array('paytype'=>$paytype));
        }
        if($paytype=="alipay"){

            //构造业务请求参数的集合(订单信息)
            $content = array();
            $content['subject'] = $order['title'];
            $content['out_trade_no'] = $order['orderid'];
            //$content['timeout_express'] = "该笔订单允许的最晚付款时间";
            $content['total_amount'] = $order['balance'];
            $content['product_code'] = "QUICK_MSECURITY_PAY";//销售产品码,固定值
            $con = json_encode($content);//$content是biz_content的值,将之转化成json字符串

            $notify_url=DI()->config->get('sys.xsapp.siteurl')."Public/pay/aliwap/notify.php";

            DI()->alipay = new Alipay_Lite();
            $orderinfo = DI()->alipay->getAlipay($con,$notify_url);
            $data['orderinfo']=$orderinfo;
            $data['orderid']=$order['orderid'];
            $data['paytype']=$paytype;
            return array('code'=>0,'msg'=>'订单重新支付','list'=>$data);

        }elseif($paytype=="weixin"){

            DI()->wxpay = new Wxpay_Lite();
            $response =  DI()->wxpay->getPrePayOrder($order['title'], $order['orderid'], $order['balance']*100);
            if(!$response['prepay_id'] && $response['return_code']=="SUCCESS"){
                return array('code'=>1,'msg'=>$response['err_code_des'],'list'=>array());
            }
            //print_r($response);
            //print_r("---拿到prepayId再次签名----");
            $x =  DI()->wxpay->getOrder($response['prepay_id']);
            $x['orderid'] = $order['orderid'];
            $x['paytype']=$paytype;
            return array('code'=>0,'msg'=>'订单重新支付','list'=>$x);

        }else{
            return array('code'=>1,'msg'=>'订单错误!','list'=>array());
        }
    }

    //订单重新支付-拼团
    public function getRePay_Pt($token,$orderid,$ordertype){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $model = new Model_Pay();
        $order = $model->getOrderByOrderId_Pt($orderid);
        if(!$orderid){
            return array('code'=>1,'msg'=>'订单号不存在','list'=>array());
        }

        if($order['fk']>0){
            return array('code'=>3,'msg'=>'订单已支付，请不要重复支付','list'=>array());
        }
        if(($order['time']+(7*86400)) < time()){
            return array('code'=>1,'msg'=>'订单已失效','list'=>array());
        }

        $paytype = $order['paytype'];
        if($ordertype && $ordertype!="alipay" && $ordertype!="weixin") {
            return array('code'=>1,'msg'=>'支付类型错误','list'=>array());
        }
        if($ordertype && $paytype!=$ordertype){
            $paytype = $ordertype;
            $model->getUpdatePay_Pt($orderid,array('paytype'=>$paytype));
        }
        if($paytype=="alipay"){

            //构造业务请求参数的集合(订单信息)
            $content = array();
            $content['subject'] = $order['title'];
            $content['out_trade_no'] = $order['orderid'];
            //$content['timeout_express'] = "该笔订单允许的最晚付款时间";
            $content['total_amount'] = $order['jiage'];
            $content['product_code'] = "QUICK_MSECURITY_PAY";//销售产品码,固定值
            $con = json_encode($content);//$content是biz_content的值,将之转化成json字符串

            $notify_url=DI()->config->get('sys.xsapp.siteurl')."Public/pay/aliwap_pt/notify.php";

            DI()->alipay = new Alipay_Lite();
            $orderinfo = DI()->alipay->getAlipay($con,$notify_url);
            $data['orderinfo']=$orderinfo;
            $data['orderid']=$order['orderid'];
            $data['paytype']=$paytype;
            return array('code'=>0,'msg'=>'订单重新支付','list'=>$data);

        }elseif($paytype=="weixin"){

            DI()->wxpay = new Wxpay_Lite();
            $response =  DI()->wxpay->getPrePayOrder($order['title'], $order['orderid'], $order['jiage']*100,2);
            if(!$response['prepay_id'] && $response['return_code']=="SUCCESS"){
                return array('code'=>1,'msg'=>$response['err_code_des'],'list'=>array());
            }
            //print_r($response);
            //print_r("---拿到prepayId再次签名----");
            $x =  DI()->wxpay->getOrder($response['prepay_id']);
            $x['orderid'] = $order['orderid'];
            $x['paytype']=$paytype;
            return array('code'=>0,'msg'=>'订单重新支付','list'=>$x);


        }else{
            return array('code'=>1,'msg'=>'订单错误!','list'=>array());
        }
    }

    public function getOrder($token,$recharge_id,$paytype,$sdtoken){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $recharge_id = intval($recharge_id);
        if(!$recharge_id){
            return array('code' => 1, 'msg' => '缺少必要的参数');
        }
        $model = new Model_Pay();
        $rech = $model->getRechargeById($recharge_id);
        if(!$rech){
            return array('code' => 1, 'msg' => '未找到您要充值的类型');
        }
        $time = time();

        $qudao=$auth['qudao']?$auth['qudao']:"";

        $sdid=0;
        if($sdtoken) {
            $sdinfo = authcode(base64_decode_s($sdtoken), 'DECODE');
            if ($sdinfo) {
                list($sdid, $qudao) = explode("\t", $sdinfo);
            }
        }


        $orderid = str_replace(".","",microtime(true)).getRandNum(8);
        if($rech['is_vip']){
            $title="开通年费VIP会员";
        }else{
            $song = $rech['giving']?"送".($rech['giving']/100)."元":"";
            $title="充值".$rech['price'].($song?"送".$song:"");
        }
        $balance = number_format($rech['price'],2);
        $yuedubi = ($rech['price']*100)+$rech['giving'];
        $data = array('uid'=>$auth['uid'],'orderid'=>$orderid,'title'=>$title,'yuedubi'=>$yuedubi,'balance'=>$balance,'is_vip'=>$rech['is_vip'],'paytype'=>$paytype,'addtime'=>$time,'sdid'=>$sdid,'qudao'=>$qudao);
        if($model->AddOrder($data)){
            return array('code' => 0,'msg'=>'订单创建成功', 'orderid' => $orderid,'price'=>$balance,'subject'=>$title);
        }else{
            return array('code' => 1, 'msg' => '订单创建失败');
        }
    }

    public function getOrder_Pt($token,$id,$paytype,$type,$infoid,$beizhu,$adrid){
        $id = intval($id);$adrid = intval($adrid);
        if(!$id || !$adrid){
            return array('code' => 1, 'msg' => '缺少必要的参数');
        }

        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $qudao = $auth['qudao']?$auth['qudao']:"";

        $adr = $user->getAddress($auth['uid'],$adrid,0,1,1);
        if(!$adr){
            return array('code' => 3, 'msg' => '收件相关信息不存在');
        }
        $address = $adr['province'].$adr['city'].$adr['area'].$adr['address'];

        $model = new Model_Pay();
        $rs = $model->getPinTuanById($id);
        if(!$rs){
            return array('code' => 1, 'msg' => '未找到相关拼团信息');
        }

        $type = intval($type);
        if($type<1 || $type>2){
            return array('code' => 1, 'msg' => 'type参数范围[1,2]');
        }

        //拼团
        if($type==2) {
            $seconds = DI()->config->get("fuli.fuli.seconds");
            if ($rs['cstarttime'] > date("Y-m-d H:i:s")) {
                return array('code' => 1, 'msg' => '活动还没有开始');
            }
            if ($rs['cendtime'] <= date("Y-m-d H:i:s")) {
                return array('code' => 1, 'msg' => '活动已结束,等待抽奖');
            }
            if ((strtotime($rs['cendtime']) + $seconds) <= time()) {
                return array('code' => 1, 'msg' => '活动已抽奖');
            }
        }


        $info = $model->getPinTuanSku($id);
        if($info){
            if(!$beizhu){
                return array('code' => 3, 'msg' => '请选择属性值');
            }
            $b = explode(";",$beizhu);
            $i=0;
            foreach ($b as $item){
                if($item){
                    $i++;
                    $it = explode(":",$item);
                    foreach ($info as $v){
                        if($it[0] !=$v['name'] || !in_array($it[1],$v['list'])){
                            return array('code' => 3, 'msg' => '非法属性值');
                        }
                    }
                }
            }

            if($i==0){
                return array('code' => 3, 'msg' => '非法属性值');
            }

        }else{
            $beizhu="";
        }

        $time = time();
        $orderid = "pt".str_replace(".","",microtime(true)).getRandNum(8);
        $endtime = $time + ($rs['xiaoshi']*60*60);
        //直接购买
        if($type==1){
            $price = number_format($rs['yuanjia'],2);
            $ptdata = array('uid'=>$auth['uid'],'id'=>$rs['id'],'tnum'=>1,'cstarttime'=>strtotime($rs['cstarttime']),'cendtime'=>strtotime($rs['cendtime']),'time'=>$time,'endtime'=>$endtime,'lx'=>0,'qudao'=>$qudao);
            $pid = $model->AddPinTuan($ptdata);
            if($pid){
                $ptinfodata = array('pid'=>$pid,'orderid'=>$orderid,'tuid'=>$auth['uid'],'uid'=>$auth['uid'],'id'=>$rs['id'],'title'=>$rs['title'],'jiage'=>$price,'num'=>1,'beizhu'=>$beizhu,'name'=>$adr['name'],'tel'=>$adr['tel'],'address'=>$address,'paytype'=>$paytype,'time'=>$time,'qudao'=>$qudao);
                $info_id = $model->AddPinTuanInfo($ptinfodata);
                if(!$info_id){
                    $model->DelPinTuan($pid);
                    return array('code' => 1, 'msg' => '订单创建失败');
                }
            }else{
                return array('code' => 1, 'msg' => '订单创建失败');
            }
        }else{
            $pt = new Model_Pt();
            //参团
            if($infoid) {

                    $ptinfo = $pt->getPtInfo($infoid);
                    if ($ptinfo) {

                        if ($ptinfo['tuid'] == $auth['uid']) {
                            return array('code' => 3, 'msg' => '您是团长，不能参团');
                        }
                        //必须参加团长开的团
                        if ($ptinfo['tuid'] != $ptinfo['uid']) {
                            return array('code' => 3, 'msg' => '团长信息不存在');
                        }
                        
                    } else {
                        return array('code' => 3, 'msg' => '您要参的团不存在');
                    }

                    if ($pt->CheckPtInfoByUid($auth['uid'],$ptinfo['pid'])) {
                        return array('code' => 3, 'msg' => '您已参加此团，不要重复参团');
                    }

                    $p = $pt->getPt($ptinfo['pid']);
                    if ($p['tnum'] <= $p['num']) {
                        return array('code' => 4, 'msg' => '来晚了，团已满');
                    }

                    $u = $user->getuserInfo($auth['uid']);
                    if($u['is_new']==0){
                        return array('code' => 3, 'msg' => '对不起，您只能开团，无法参团');
                    }

                    $tuid = $ptinfo['tuid'];
                    $pid = $ptinfo['pid'];
            }else {
                
                //开团
                $ck = $pt->CheckPtByUid($auth['uid']);
                if($ck){
                    return array('code' => 3, 'msg' => '您有未完成的拼团，成团后可开新团');
                }
                $tuid = $auth['uid'];
                $ptdata = array('uid' => $auth['uid'],'id'=>$rs['id'], 'tnum' => $rs['tnum'], 'cstarttime'=>strtotime($rs['cstarttime']),'cendtime'=>strtotime($rs['cendtime']), 'time' => $time, 'endtime' => $endtime, 'lx' => 1,'qudao'=>$qudao);
                $pid = $model->AddPinTuan($ptdata);
            }

                $price = number_format($rs['price'],2);
                if($pid){
                    $ptinfodata = array('pid'=>$pid,'orderid'=>$orderid,'tuid'=>$tuid,'uid'=>$auth['uid'],'id'=>$rs['id'],'title'=>$rs['title'],'jiage'=>$price,'num'=>1,'beizhu'=>$beizhu,'name'=>$adr['name'],'tel'=>$adr['tel'],'address'=>$address,'paytype'=>$paytype,'time'=>$time,'qudao'=>$qudao);
                    $info_id = $model->AddPinTuanInfo($ptinfodata);
                    if(!$info_id){
                        $model->DelPinTuan($pid);
                        return array('code' => 1, 'msg' => '订单创建失败');
                    }
                }else{
                    return array('code' => 1, 'msg' => '订单创建失败');
                }
        }

        return array('code' => 0,'msg'=>'订单创建成功', 'orderid' => $orderid,'price'=>$price,'subject'=>$rs['title']);

    }

}

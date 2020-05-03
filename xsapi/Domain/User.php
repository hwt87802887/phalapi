<?php

class Domain_User {

    protected $num = 10;
    //QQ登录
    public function  getqqLogin($unionid,$openid,$param,$devicecode,$qudao){
        $qudao = $qudao?$qudao:"";
       $param = urldecode($param);
       $data = json_decode($param,true);
        if(!$data || empty($data)  || !is_array($data)){
            return array('code'=>1,'msg'=>'缺少必要的参数');
        }
        //$avatar = $data['figureurl_qq_2']?$data['figureurl_qq_2']:$data['figureurl_qq_1'];
        if(($data['nickname']=='' || $data['gender']=='') && $data['ret']!=0){
            return array('code'=>1,'msg'=>'缺少必要的参数');
        }
        $model = new Model_User();
        $list = $model->getqqLogin($unionid,$openid,$data,$devicecode,$qudao);
        if(!$list['token']){
            return array('code'=>1,'msg'=>"账号已关停，无法登陆!");
        }else{
            return array('code'=>0,'list'=>$list);
        }


    }

    //微信登录
    public function  getwxLogin($unionid,$param,$devicecode,$qudao){
        $qudao = $qudao?$qudao:"";
        $data = json_decode(stripslashes($param),true);
        if(!$data || empty($data)  || !is_array($data)){
            return array('code'=>1,'msg'=>'缺少必要的参数');
        }

        if(($data['openid']=='' || $data['nickname']=='') && $data['sex']!=0){
            return array('code'=>1,'msg'=>'缺少必要的参数');
        }
        $model = new Model_User();
        $list = $model->getwxLogin($unionid,$data,$devicecode,$qudao);
        if(!$list['token']){
            return array('code'=>1,'msg'=>"账号已关停，无法登陆!");
        }else{
            return array('code'=>0,'list'=>$list);
        }


    }

    //退出登录
    public function getLoginOut($token){
        $model = new Model_User();
        $auth = $model->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }else{
            $onlogin = $model->getLoginOut($auth['uid']);
            return array('code'=>0,'msg'=>'退出成功','list'=>array('token'=>''));
        }

    }

    //获取短信验证码
    public function getsmsCode($mobile)
    {
        if(!isMobile($mobile)){
            return array('code'=>1,'msg'=>'手机号码格式错误');
        }
        $model = new Model_User();
        $check = $model->getCodeByTel($mobile);
        if($check && $check['num']>=5 && date("Ymh",$check['time'])==date("Ymh")){
            return array('code'=>1,'msg'=>'已超过今日发送短信次数');
        }elseif($check && (time()-$check['time']<=60)){
            return array('code'=>1,'msg'=>'发送过快，请等待60s后重新发送');
        }

        //发送短信
        $send = getRandNum(4);
        $tishi = DI()->sms->sendSMS($mobile,array($send));

        if($tishi=="1") {
            $code = $model ->getCheckByTel($mobile,$send);
            return array('code'=>0,'msg'=>'短信发送成功');
        }else{
            return array('code'=>1,'msg'=>$tishi);
        }

    }

    //手机登录
    public function getPhoneLogin($mobile,$smscode,$devicecode,$fuid,$qudao){
        $devicecode=$devicecode?$devicecode:"";
        if(!isMobile($mobile)){
            return array('code'=>1,'msg'=>'手机号码格式错误');
        }
        $qudao = $qudao?$qudao:"";
        $model = new Model_User();
        $check = $model->getCodeByTel($mobile);
        if($check){
            if($check['zt']==1){
                return array('code'=>1,'msg'=>'验证码过期');
            }
            if(($check['time']+900)< time()){
                return array('code'=>1,'msg'=>'验证码过期');
            }
            if($check['yzm']!=$smscode){
                return array('code'=>1,'msg'=>'验证码错误');
            }
            
            //登录注册
            $list = $model->getPhoneLogin($mobile,$devicecode,$fuid,$qudao);
            if(!$list['token']){
                return array('code'=>1,'msg'=>"账号已关停，无法登陆!");
            }else{
                return array('code'=>0,'list'=>$list);
            }
            

        }else{
            return array('code'=>1,'msg'=>'验证码无效');
        }
    }

    //绑定手机号
    public function getBindPhone($token,$mobile,$smscode,$fuid){
        if(!isMobile($mobile)){
            return array('code'=>1,'msg'=>'手机号码格式错误');
        }
        if(!$smscode){
            return array('code'=>1,'msg'=>'验证码为空');
        }
        $model = new Model_User();
        $check = $model->getCodeByTel($mobile);
        if($check) {
            if($check['zt']==1){
                return array('code'=>1,'msg'=>'验证码过期');
            }
            if(($check['time']+900)< time()){
                return array('code'=>1,'msg'=>'验证码过期');
            }
            if($check['yzm']!=$smscode){
                return array('code'=>1,'msg'=>'验证码错误');
            }
            $user = $model->getUserByTel($mobile);
            if($user){
                return array('code'=>1,'msg'=>'该手机号码已绑定,请更换手机号');
            }

            $auth = $model->checkToken($token);
            if($auth['code']>0){
                return $auth;
            }
            $up = $model->getBindPhone($auth['uid'],$mobile,$fuid);
            if($up===false){
                return array('code'=>1,'msg'=>'手机号绑定失败');
            }else{
                return array('code'=>0,'msg'=>'成功绑定手机号');
            }
            
        }else{
            return array('code'=>1,'msg'=>'验证码无效');
        }
        
    }

    //解绑手机号
    public function getUnBindPhone($token,$mobile,$smscode){
        if(!isMobile($mobile)){
            return array('code'=>1,'msg'=>'手机号码格式错误');
        }
        if(!$smscode){
            return array('code'=>1,'msg'=>'验证码为空');
        }
        $model = new Model_User();
        $check = $model->getCodeByTel($mobile);
        if($check) {
            if($check['zt']==1){
                return array('code'=>1,'msg'=>'验证码过期');
            }
            if(($check['time']+900)< time()){
                return array('code'=>1,'msg'=>'验证码过期');
            }
            if($check['yzm']!=$smscode){
                return array('code'=>1,'msg'=>'验证码错误');
            }

            $auth = $model->checkToken($token);
            if($auth['code']>0){
                return $auth;
            }

            $user = $model->getuserInfo($auth['uid']);
            if($user && $user['mobile']!=$mobile){
                return array('code'=>1,'msg'=>'解绑数据不一致');
            }elseif(!$user){
                return array('code'=>1,'msg'=>'用户不存在或被封号');
            }


            $up = $model->getUnBindPhone($auth['uid']);
            if($up===false){
                return array('code'=>1,'msg'=>'手机解绑失败');
            }else{
                return array('code'=>0,'msg'=>'成功解绑手机号');
            }

        }else{
            return array('code'=>1,'msg'=>'验证码无效');
        }

    }

    //绑定支付宝信息
    public function getBindAlipay2($token,$param)
    {

        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }

        $param = urldecode($param);
        $data = json_decode($param, true);
        if (!$data || empty($data) || !is_array($data)) {
            return array('code' => 1, 'msg' => '缺少必要的参数');
        }

        if ($data['nick_name'] == '' || $data['user_id'] == '') {
            return array('code' => 1, 'msg' => '缺少必要的参数');
        }

        $u = $user->getuserInfo($auth['uid']);
        if(!$u['mobile']){
            return array('code'=>1,'msg'=>'请先绑定手机号');
        }

        $up = $user->getBindAlipay($auth['uid'],$data);
        if($up===false){
            return array('code'=>1,'msg'=>'支付宝绑定失败');
        }else{
            return array('code'=>0,'msg'=>'成功绑定支付宝');
        }
    }

    //授权请求参数
    public function getAlipayRequest($token){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }

        $u = $user->getuserInfo($auth['uid']);
        if(!$u['mobile']){
            return array('code'=>1,'msg'=>'请先绑定手机号');
        }

        DI()->alipay = new Alipay_Lite();
        $target_id = "app".str_replace(".","",microtime(true)).getRandNum(8);
        $list['param'] = DI()->alipay->getAccount($target_id);

        return array('code'=>0,'msg'=>'成功','list'=>$list);
    }

    //绑定支付宝信息
    public function getBindAlipay($token,$auth_code,$user_id)
    {
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }

        $u = $user->getuserInfo($auth['uid']);
        if(!$u['mobile']){
            return array('code'=>1,'msg'=>'请先绑定手机号');
        }

        if($auth_code=='' || $user_id==''){
            return array('code'=>1,'msg'=>'缺少必要参数');
        }

        DI()->alipay = new Alipay_Lite();
        $result =   DI()->alipay->getAuthToken($auth_code);
        $access_token = $result['access_token'];
        if(!$access_token){
            return array('code'=>1,'msg'=>$result['sub_msg']);
        }

        $userinfo = DI()->alipay->getUserInfo($access_token);
        if (!$userinfo['nick_name'] || !$userinfo['user_id']) {
            return array('code' => 1, 'msg' => $result['sub_msg']);
        }

        if($user_id!=$userinfo['user_id']){
            return array('code' => 1, 'msg' => '授权用户信息不一致');
        }

        $data['user_id'] = $userinfo['user_id'];
        $data['avatar'] = $userinfo['avatar'];
        $data['province'] = $userinfo['province']?$userinfo['province']:"";
        $data['city'] = $userinfo['city']?$userinfo['city']:"";;
        $data['nick_name'] = $userinfo['nick_name'];
        $data['is_student_certified'] = $userinfo['is_student_certified'];
        $data['user_type'] = $userinfo['user_type'];
        $data['user_status'] = $userinfo['user_status'];
        $data['is_certified'] = $userinfo['is_certified'];
        $data['gender'] = $userinfo['gender'];

        $up = $user->getBindAlipay($auth['uid'], $data);
        if ($up === false) {
            return array('code' => 1, 'msg' => '支付宝绑定失败');
        } else {
            return array('code' => 0, 'msg' => '成功绑定支付宝');
        }

    }

    //解绑支付宝信息
    public function getUnBindAlipay($token)
    {

        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        
        $up = $user->getUnBindAlipay($auth['uid']);
        if($up===false){
            return array('code'=>1,'msg'=>'支付宝解绑失败');
        }else{
            return array('code'=>0,'msg'=>'成功解绑支付宝');
        }
    }

    //获取用户信息
    public function getuserInfo($token){
        
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if($auth['code']>0){
            return $auth;
        }

        $list = $user->getuserInfo($auth['uid']);
        if(!empty($list)){
            return array('code'=>0,'list'=>$list);
        }else{
            return array('code'=>1,'msg'=>'获取用户信息失败');
        }

    }

    //修改用户基本信息
    public function getEditUserInfo($token,$nickname,$sex,$birth,$area)
    {
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] >0) {
            return $auth;
        }
        $sex = intval($sex);
        $nickname = trim($nickname);
        if (!$nickname && !$sex && !$birth && !$area) {
            return array('code' => 1, 'msg' => '缺少必要的参数');
        }
        $data = array();
        if($nickname && strlen($nickname)<2){
            return array('code' => 1, 'msg' => '昵称太短了');
        }elseif($nickname && strlen($nickname)>=2){
            $data['nickname']=$nickname;
        }

        if($sex ==1 || $sex ==2){
            $data['sex']=$sex;
        }
        if($birth && strtotime($birth)){
            $data['birth']=$birth;
        }elseif($birth && !strtotime($birth)){
            return array('code' => 1, 'msg' => '出生日期格式错误');
        }

        if($area){
            if(stripos($area,"-")>0){
                $qy = explode("-",$area);
                $province = $qy[0];$city=$qu="";
                if(count($qy)==2){
                    $city = $qy[1];
                }
                if(count($qy)==3){
                    $city = $qy[1];
                    $qu = $qy[2];
                }
                $data['province'] = $province;
                $data['city'] = $city;
                $data['area'] = $qu;
            }
        }



        $data['updatetime'] = time();
        
        $up = $user->getEditUserInfo($auth['uid'],$data);
        if($up===false){
            return array('code' => 1, 'msg' => '更新资料失败');
        }else{
            $param = array('service'=>"Asyn.Wszl",'uid'=>$auth['uid']);
            $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
            doSock($url,$param);

            return array('code' => 0, 'msg' => '成功更新资料');
        }
        
    }

    //我的充值记录
    public function getOrderLog($token,$id,$pagesize){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] >0) {
            return $auth;
        }
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $rs = $user->getOrderLog($auth['uid'],$id,$pagesize);
        return array('code'=>0,'msg'=>'成功','list'=>$rs);
    }

    //我的金币兑换阅读币记录
    public function getBalanceLog($token,$id,$pagesize){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] >0) {
            return $auth;
        }
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $rs = $user->getBalanceLog($auth['uid'],$id,$pagesize);
        return array('code'=>0,'msg'=>'成功','list'=>$rs);
    }

    //我的 章节购买记录
    public function getChapteBuyLog($token,$id,$pagesize){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $rs = $user->getChapteBuyLog($auth['uid'],$id,$pagesize);
        return array('code'=>0,'msg'=>'成功','list'=>$rs);
    }

    //我的 打赏记录
    public function getGiftLog($token,$id,$pagesize){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $rs = $user->getGiftLog($auth['uid'],$id,$pagesize);
        return array('code'=>0,'msg'=>'成功','list'=>$rs);
    }

    //建议反馈
    public  function getFeedBack($content,$category,$contact){
        $content = trim($content);$category = trim($category);
        if($content==''){
            return array('code' => 1, 'msg' => '反馈内容不能为空');
        }elseif(strlen($content)>250){
            return array('code' => 1, 'msg' => '反馈内容太长啦');
        }
        if($category==""){
            return array('code' => 1, 'msg' => '请选择反馈类别');
        }
        if(!isMobile($contact) && !isQQ($contact)){
            return array('code' => 1, 'msg' => '联系方式请填写手机号或QQ号');
        }
        $user = new Model_User();
        $r = $user->getFeedBack($content,$category,$contact);
        if($r){
            return array('code' => 0, 'msg' => '感谢您的反馈','list'=>array());
        }else{
            return array('code' => 1, 'msg' => '建议反馈失败');
        }

    }
    


    //获取收货地址
    public function GetAdrList($token,$isdefault,$id,$pagesize){
        $isdefault = intval($isdefault);
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $id = intval($id)?intval($id):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $rs = $user->getAddress($auth['uid'],0,$isdefault,$id,$pagesize);
        return array('code'=>0,'msg'=>'成功','list'=>$rs);
    }

    //设置默认地址
    public function SetDefault($token,$adrid){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $adrid = intval($adrid);
        $rs = $user->getAddress($auth['uid'],$adrid,0,1,$this->num);
        if(!$rs){
            return array('code'=>1,'msg'=>'地址不存在','list'=>array());
        }

        $param = array('service'=>"Asyn.SetDefault",'uid'=>$auth['uid'],'adrid'=>$adrid);
        $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
        doSock($url,$param);

        return array('code'=>0,'msg'=>'设置默认地址成功','list'=>array());
    }

    //删除收货地址
    public function SetAdrDel($token,$adrid){
        $user = new Model_User();
        $adrid = intval($adrid);
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        /*
        $data = urldecode($adrid);
        $data = json_decode($data,true);
        if(!$data || empty($data)  || !is_array($data)){
            return array('code'=>1,'msg'=>'缺少必要的参数');
        }
        */

        if(!$adrid){
            return array('code'=>1,'msg'=>'缺少必要的参数');
        }

        $param = array('service'=>"Asyn.SetAdrDel",'uid'=>$auth['uid'],'adrid'=>$adrid);
        $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
        doSock($url,$param);

        return array('code'=>0,'msg'=>'成功删除收货地址','list'=>array());
    }

    //地址详细
    public function GetAdrDetail($token,$adrid){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $adrid = intval($adrid);
        $rs = $user->getAddress($auth['uid'],$adrid,0,1,$this->num);
        return array('code'=>0,'msg'=>'成功','list'=>$rs);
    }

    //增加收货地址
    public function GetAddAdr($token,$name,$tel,$area,$address,$isdefault){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $name = trim($name);
        $isdefault = intval($isdefault);
        if (!$name && !$tel && !$area && !$address) {
            return array('code' => 1, 'msg' => '缺少必要的参数');
        }
        $data = array();
        $data['uid'] = $auth['uid'];
        if(strlen($name)<2){
            return array('code' => 1, 'msg' => '收件人姓名太短啦');
        }
        $data['name']=$name;

        if(!isMobile($tel)){
            if(!isTel($tel)){
                return array('code'=>1,'msg'=>'收件人电话格式错误');
            }
        }
        $data['tel']=$tel;

        if(stripos($area,"-")>0){
            $qy = explode("-",$area);
            $province = $qy[0];$city=$qu="";
            if(count($qy)==2){
                $city = $qy[1];
            }
            if(count($qy)==3){
                $city = $qy[1];
                $qu = $qy[2];
            }
            $data['province'] = $province;
            $data['city'] = $city;
            $data['area'] = $qu;
        }else{
            $data['province'] = $area;
        }

        if(strlen($address)<6){
            return array('code' => 1, 'msg' => '详细地址太短啦');
        }
        $data['address']=$address;
        $data['isdefault']=$isdefault;

        $param = array('service'=>"Asyn.AddAddress",'adrid'=>0,'param'=>urlencode(json_encode($data)));
        $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
        doSock($url,$param);

        return array('code' => 0, 'msg' => '成功添加收货地址','list'=>array());

    }

    //修改收货地址
    public function GetEditAdr($token,$adrid,$name,$tel,$area,$address,$isdefault){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }

        $dz = $user->getAddress($auth['uid'],intval($adrid),0,1,$this->num);
        if(!$dz){
            return array('code' => 1, 'msg' => '地址不存在');
        }

        $name = trim($name);
        $isdefault = intval($isdefault);
        if (!$name && !$tel && !$area && !$address) {
            return array('code' => 1, 'msg' => '缺少必要的参数');
        }
        $data = array();
        $data['uid'] = $auth['uid'];
        if(strlen($name)<2){
            return array('code' => 1, 'msg' => '收件人姓名太短啦');
        }
        $data['name']=$name;

        if(!isMobile($tel)){
            if(!isTel($tel)){
                return array('code'=>1,'msg'=>'收件人电话格式错误');
            }
        }
        $data['tel']=$tel;

        if(stripos($area,"-")>0){
            $qy = explode("-",$area);
            $province = $qy[0];$city=$qu="";
            if(count($qy)==2){
                $city = $qy[1];
            }
            if(count($qy)==3){
                $city = $qy[1];
                $qu = $qy[2];
            }
            $data['province'] = $province;
            $data['city'] = $city;
            $data['area'] = $qu;
        }else{
            $data['province'] = $area;
        }

        if(strlen($address)<6){
            return array('code' => 1, 'msg' => '详细地址太短啦');
        }
        $data['address']=$address;
        $data['isdefault']=$isdefault;



        $param = array('service'=>"Asyn.AddAddress",'adrid'=>intval($adrid),'param'=>urlencode(json_encode($data)));
        $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
        doSock($url,$param);

        return array('code' => 0, 'msg' => '成功修改收货地址','list'=>array());

    }

    //我的订单
    public function getOrderList($token,$type,$id,$pagesize)
    {
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $id = intval($id)?intval($id):1;
        $type = intval($type)?intval($type):1;
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $rs = $user->getOrderList($auth['uid'],$type,$id,$pagesize);
        return array('code'=>0,'msg'=>'成功','list'=>$rs);
    }

    //订单详情
    public function getOrderDetail($token,$infoid){
        $infoid = intval($infoid)?intval($infoid):0;
        if($infoid<=0){
            return array('code' => 1, 'msg' => '缺少必要参数');
        }
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $rs =  $user->getOrderDetail($auth['uid'],$infoid);
        if($rs){
            return array('code' => 0, 'msg' => '成功','list'=>$rs);
        }else{
            return array('code' => 1, 'msg' => '订单不存在','list'=>array());
        }
    }

    //订单收货
    public function getOrderReceive($token,$infoid,$type){
        $infoid = intval($infoid)?intval($infoid):0;
        $type = intval($type)?intval($type):0;
        if($infoid<=0){
            return array('code' => 1, 'msg' => '缺少必要参数');
        }

        if($type<1 || $type>2){
            return array('code' => 1, 'msg' => '缺少必要参数');
        }

        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $rs =  $user->getOrderReceive($auth['uid'],$infoid,$type);
        return array('code' => $rs['code'], 'msg' => $rs['msg'],'list'=>array());
    }

    //提现兑换
    public function getExchange($token){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $u = $user->getuserInfo($auth['uid']);
        $data['gold'] = $u['gold'];
        $data['money'] = $u['money'];
        $data['exmoney'] = 0;
        $dhjb = DI()->config->get("fuli.fuli.dhjb");
        if($data['gold']>0){
            $data['exmoney'] = $data['gold']/$dhjb;
            //$data['exmoney'] = floor($data['exmoney']*100)/100-floor($data['exmoney']*10)/10;
            $data['exmoney']=intval($data['exmoney'] * pow(10, 2))/ pow(10, 2);
        }
        $data['total'] = $data['money'] + $data['exmoney'];
        $data['exchange'] =array(
            '0'=>array('exid'=>1,'money'=>1,'saleprice'=>'售价：'.number_format($dhjb).'金币','isdefault'=>1),
            '1'=>array('exid'=>2,'money'=>5,'saleprice'=>'售价：'.number_format(5*$dhjb).'金币','isdefault'=>0),
            '2'=>array('exid'=>3,'money'=>10,'saleprice'=>'售价：'.number_format(10*$dhjb).'金币','isdefault'=>0),
            '3'=>array('exid'=>4,'money'=>30,'saleprice'=>'售价：'.number_format(30*$dhjb).'金币','isdefault'=>0),
            '4'=>array('exid'=>5,'money'=>50,'saleprice'=>'售价：'.number_format(50*$dhjb).'金币','isdefault'=>0),
            '5'=>array('exid'=>6,'money'=>100,'saleprice'=>'售价：'.number_format(100*$dhjb).'金币','isdefault'=>0),
            '6'=>array('exid'=>7,'money'=>500,'saleprice'=>'售价：'.number_format(500*$dhjb).'金币','isdefault'=>0)
        );
        return array('code'=>0,'msg'=>'成功','list'=>$data);
    }

    //立即提现
    public function getTixian($token,$exid){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $exid = intval($exid);
        if($exid<=0){
            return array('code' => 1, 'msg' => '缺少必要参数');
        }

        $u = $user->getuserInfo($auth['uid']);
        if(!$u['mobile']){
            return array('code' => 1, 'msg' => '请先绑定手机号');
        }

        $alipay = $user->getAlipayInfo($auth['uid']);
        if(!$alipay || $alipay['status']==2 ||($alipay && !$alipay['user_id'] && !$alipay['nick_name'])){
            return array('code' => 1, 'msg' => '请先绑定支付宝');
        }

        $dhjb = DI()->config->get("fuli.fuli.dhjb");
        $exchange = array(
            '0'=>array('exid'=>1,'money'=>1,'saleprice'=>'售价：'.number_format($dhjb).'金币','isdefault'=>1),
            '1'=>array('exid'=>2,'money'=>5,'saleprice'=>'售价：'.number_format(5*$dhjb).'金币','isdefault'=>0),
            '2'=>array('exid'=>3,'money'=>10,'saleprice'=>'售价：'.number_format(10*$dhjb).'金币','isdefault'=>0),
            '3'=>array('exid'=>4,'money'=>30,'saleprice'=>'售价：'.number_format(30*$dhjb).'金币','isdefault'=>0),
            '4'=>array('exid'=>5,'money'=>50,'saleprice'=>'售价：'.number_format(50*$dhjb).'金币','isdefault'=>0),
            '5'=>array('exid'=>6,'money'=>100,'saleprice'=>'售价：'.number_format(100*$dhjb).'金币','isdefault'=>0),
            '6'=>array('exid'=>7,'money'=>500,'saleprice'=>'售价：'.number_format(500*$dhjb).'金币','isdefault'=>0)
        );
        $exmoney = 0;
        foreach($exchange as $item){
            if($item['exid']==$exid){
                $exmoney = $item['money'];
            }
        }
        if($exmoney==0){
            return array('code' => 1, 'msg' => '未找到提现金额');
        }


        $money = 0;
        if($u['gold']>0){
            $dhje = $u['gold']/$dhjb;
            $money=intval($dhje * pow(10, 2))/ pow(10, 2);
        }
        $total = $money + $u['money'];
        if($total < $exmoney){
            return array('code' => 1, 'msg' => '账户余额不足');
        }

        if($exmoney>$u['money']){
            //账户余额不足时，计算需扣除金币
            $gold = ($exmoney-$u['money'])*$dhjb;
            $money2 = $u['money'];
        }elseif($exmoney <= $u['money']){
            $gold = 0;
            $money2 = $exmoney;
        }

        /*
        $param = array('service'=>"Asyn.Tixian",'uid'=>$auth['uid'],'gold'=>$gold,'money'=>$money2,'total'=>$exmoney);
        $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
        doSock($url,$param);*/
		$gold = explode(".",$gold);
		$gold = $gold[0];
        $r = $user->SetTixian($auth['uid'],$gold,$money2,$exmoney);

        if($r){
            return array('code' => 0, 'msg' => '提现成功，等待审核!');
        }else{
            return array('code' => 1, 'msg' => '提现失败!');
        }

    }

    public function getExchangeYdb($token)
    {
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $u = $user->getuserInfo($auth['uid']);
        $dhbl = DI()->config->get('fuli.fuli.dhydb');
        $dhjb = DI()->config->get('fuli.fuli.dhjb');
        $ydb = $dhbl * $dhjb;
        $data = array('gold' => $u['gold'], 'dhbl' => ($dhbl * 100), 'description' => '1.'.$dhjb.'金币可兑换'.$ydb.'阅读币'.PHP_EOL."2.兑换成功后，您可以在【我的】 - 【阅读币】内查看兑换记录".PHP_EOL."3.金币自由兑换阅读币，每天可兑换多次".PHP_EOL."4.".(($dhbl * 100))."起兑，最后兑换以实际扣除为准");
        return array('code'=>0,'msg'=>'成功','list'=>$data);
    }

    public function getTixianYdb($token,$gold){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }

        $u = $user->getuserInfo($auth['uid']);
        $dhbl = DI()->config->get('fuli.fuli.dhydb');
        if($dhbl * 100 >$gold){
            return array('code' => 0, 'msg' => '最少'.($dhbl * 100).'金币起兑');
        }
        if($gold > $u['gold']){
            return array('code' => 0, 'msg' => '金币余额不足');
        }
        $yuedubi = intval($gold * $dhbl);
        $shiji = $yuedubi / $dhbl;

        $param = array('service'=>"Asyn.Yuedubi",'uid'=>$auth['uid'],'gold'=>$shiji,'yuedubi'=>$yuedubi);
        $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
        doSock($url,$param);

        $r = $user->SetYuedubi($auth['uid'],$shiji,$yuedubi);

        if($r){
            return array('code' => 0, 'msg' => '兑换成功');
        }else{
            return array('code' => 1, 'msg' => '兑换失败!');
        }

    }

    public function getTixianRecord($token,$type){
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $type = intval($type);
        if(!in_array($type, array(1,2,3))){
            return array('code' => 1, 'msg' => '参数值错误');
        }
        $rs = $user->getTixianRecord($auth['uid'],$type);
        return array('code'=>0,'msg'=>'成功','list'=>$rs);
    }

    public function getFriend($token,$uid,$pagesize){
        $uid = intval($uid)?intval($uid):1;
        $pagesize = intval($pagesize)?intval($pagesize):$this->num;
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $rs = $user->getFriend($auth['uid'],$uid,$pagesize);
        return array('code'=>0,'msg'=>'成功','list'=>$rs);
    }

    public function getWallet($token,$type){
        $type = intval($type)?intval($type):1;
        if($type>2){
            $type = 1;
        }
        $user = new Model_User();
        $auth = $user->checkToken($token);
        if ($auth['code'] > 0) {
            return $auth;
        }
        $rs = $user->getWallet($auth['uid'],$type);
        return array('code'=>0,'msg'=>'成功','list'=>$rs);
    }

}

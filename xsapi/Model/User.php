<?php

class Model_User extends PhalApi_Model_NotORM {

    //QQ登录
    public  function getqqLogin($unionid,$openid,$param,$devicecode,$qudao){
        $token="";
        $Duetime=0;
        $is_new=0;
        $r = $this->getORM()->select("uid,username,avatar,sex,login_type,status,qudao,addtime")->where('username = ?',"qq_".$unionid)->limit(1) ->fetchOne();
        if($r){
            if($r['status']==0){
                $Duetime = time()+(15*86400);
                $string=$r['uid'].'[\t]'.$r['qudao'].'[\t]'.$r['login_type'].'[\t]'.$Duetime;
                $token =  base64_encode_s(authcode($string,"ENCODE"));
                $this->getORM()->where("uid = ?",$r['uid'])->update(array('onlogin'=>1,'updatetime'=>time()));//设置登录状态
                //24小时内都算新用户
                if(($r['addtime']+86400) > time()){
                    $is_new =1;
                }
            }
        }else{
            $openid = $param['openid']?$param['openid']:$openid;
            $username = "qq_".$unionid;
            $nickname = filterEmoji($param['nickname']);
            if(!$nickname){$nickname = "qq_".getRand(6);}
            $avatar= $param['figureurl_qq_2']?$param['figureurl_qq_2']:$param['figureurl_qq_1'];
            $sex = $param['gender']=='男'?1:2;
            $login_type="qq";
            $i=array('unionid'=>$unionid,'openid'=>$openid,'username'=>$username,'nickname'=>$nickname,'avatar'=>$avatar,'sex'=>$sex,'login_type'=>$login_type,'devicecode'=>$devicecode,'onlogin'=>1,'qudao'=>$qudao,'addtime'=>time());
            $insert_id = $this->getORM()->insert($i);
            if($insert_id){
                $Duetime = time()+(15*86400);
                $string=$insert_id.'[\t]'.$i['qudao'].'[\t]'.$i['login_type'].'[\t]'.$Duetime;
                $token =  base64_encode_s(authcode($string,"ENCODE"));
                $is_new =1;
            }

            $param = array('service'=>"Asyn.Xinren",'uid'=>$insert_id,'login_type'=>"qq");
            $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
            doSock($url,$param);

        }

        return array('token'=>$token,'exp'=>date("Y-m-d H:i:s",$Duetime),'is_new'=>$is_new);
    }

    //微信登录
    public  function getwxLogin($unionid,$param,$devicecode,$qudao){
        $token="";
        $Duetime=0;
        $is_new=0;
        $r = $this->getORM()->select("uid,username,avatar,sex,login_type,status,qudao,addtime")->where('username = ?',"wx_".$unionid)->limit(1)->fetchOne();
        if($r){
            if($r['status']==0){
                $Duetime = time()+(15*86400);
                $string=$r['uid'].'[\t]'.$r['qudao'].'[\t]'.$r['login_type'].'[\t]'.$Duetime;
                $token= base64_encode_s(authcode($string,"ENCODE"));
                $this->getORM()->where("uid = ?",$r['uid'])->update(array('onlogin'=>1,'updatetime'=>time()));//设置登录状态
                //24小时内都算新用户
                if(($r['addtime']+86400) > time()){
                    $is_new =1;
                }
            }

        }else{
            $openid = $param['openid'];
            $username = "wx_".$unionid;
            $nickname = filterEmoji($param['nickname']);
            if(!$nickname){$nickname = "wx_".getRand(6);}
            $avatar= $param['headimgurl'];
            $sex = $param['sex']?$param['sex']:2;
            $login_type="weixin";
            $i=array('unionid'=>$unionid,'openid'=>$openid,'username'=>$username,'nickname'=>$nickname,'avatar'=>$avatar,'sex'=>$sex,'login_type'=>$login_type,'devicecode'=>$devicecode,'onlogin'=>1,'qudao'=>$qudao,'addtime'=>time());
            $insert_id = $this->getORM()->insert($i);
            if($insert_id){
                $Duetime = time()+(15*86400);
                $string=$insert_id.'[\t]'.$i['qudao'].'[\t]'.$i['login_type'].'[\t]'.$Duetime;
                $token =  base64_encode_s(authcode($string,"ENCODE"));
                $is_new =1;
            }

            $param = array('service'=>"Asyn.Xinren",'uid'=>$insert_id,'login_type'=>"weixin");
            $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
            doSock($url,$param);

        }
        return array('token'=>$token,'exp'=>date("Y-m-d H:i:s",$Duetime),'is_new'=>$is_new);
    }

    //手机登录
    public function getPhoneLogin($mobile,$devicecode,$fuid,$qudao){
        $token="";
        $Duetime=0;
        $is_new=0;
        $r = $this->getORM()->select("uid,username,avatar,sex,login_type,status,qudao,addtime")->where("mobile = ?",$mobile)->limit(1)->fetch();
        if($r){
            $Duetime = time() + (15*86400);
            $string=$r['uid'].'[\t]'.$r['qudao'].'[\t]'.$r['login_type'].'[\t]'.$Duetime;
            $token =  base64_encode_s(authcode($string,"ENCODE"));
            $this->getORM()->where("uid = ?",$r['uid'])->update(array('onlogin'=>1,'updatetime'=>time()));
            //24小时内都算新用户
            if(($r['addtime']+86400) > time()){
                $is_new =1;
            }

        }else{
            //$nickname = substr_replace($mobile, '****', 3, 4);
            $nickname = $mobile;
            $data = array('username'=>$mobile,'nickname'=>$nickname,'mobile'=>$mobile,'login_type'=>'mobile','devicecode'=>$devicecode,'onlogin'=>1,'addtime'=>time(),'qudao'=>$qudao);
            $insert_id = $this->getORM()->insert($data);
            if($insert_id){
                $Duetime = time() + (15*86400);
                $string = $insert_id.'[\t]'.$qudao.'[\t]mobile[\t]'.$Duetime;
                $token =  base64_encode_s(authcode($string,"ENCODE"));
                $is_new =1;
            }

            $param = array('service'=>"Asyn.Xinren",'uid'=>$insert_id,'login_type'=>"mobile");
            $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
            doSock($url,$param);

            $fuid = intval($fuid);
            if($fuid>0) {
                $param = array('service' => "Asyn.YaoQing", 'fuid' => $fuid, 'uid' => $insert_id,'type'=>'mobile');
                $url = DI()->config->get('sys.xsapp.siteurl') . "Public/xsapi/index.php";
                doSock($url, $param);
            }

        }


        return array('token'=>$token,'exp'=>date("Y-m-d H:i:s",$Duetime),'is_new'=>$is_new);

    }
    
    //退出登录
    public function getLoginOut($uid){
        return $this->getORM()->where('uid = ?',$uid)->update(array('onlogin'=>0));
    }

    //获取用户信息
    public function getuserInfo($uid){
        $r = $this->getORM()->select("uid,nickname,avatar,birth,sex,mobile,province,city,area,login_type,money,balance,gold,integral,is_vip,year_surplus,onlogin,status,addtime")->where('uid = ?',$uid)->limit(1)->fetchOne();
        $data = array();
        if($r){
            if($r['status']==0){
                $r['year_surplus']="";
                if($r['is_vip'] && $r['year_surplus']>0 && $r['year_surplus']>time()){
                    $r['year_surplus'] = date("Y-m-d",$r['year_surplus']);
                }elseif($r['is_vip'] && $r['year_surplus']>0 && $r['year_surplus']<time()){
                    $this->getEditUserInfo($uid,array('is_vip'=>0,'year_surplus'=>0));
                    $r['is_vip'] = 0;
                }

                if(($r['addtime']+86400) > time()){
                    $r['is_new'] =1;
                }else{
                    $r['is_new'] =0;
                }
                $r['nickname'] = isMobile($r['nickname'])?substr_replace($r['nickname'], '****', 3, 4):$r['nickname'];
                unset($r['addtime']);
                $data = $r;
                $alipay = $this->getAlipayInfo($uid);
                $data['alipay_name'] = "";
                if($alipay['status']==1){
                    $data['alipay_name'] = $alipay['nick_name']?$alipay['nick_name']:"";
                }

            }

        }
        return $data;
    }

    //获取绑定支付宝信息
    public function getAlipayInfo($uid){
        return DI()->notorm->user_alipay->select("*")->where("uid",$uid)->limit(1)->fetch();
    }

    //修改用户基本资料
    public function getEditUserInfo($uid,$data){
        return $this->getORM()->where('uid = ?',$uid)->update($data);
    }

    //绑定手机号
    public function getBindPhone($uid,$mobile,$fuid){
       $rs = $this->getORM()->where('uid = ?',$uid)->update(array('mobile'=>$mobile));

        if($rs!==false) {
            $wf = DI()->notorm->welfare->select("bangdingshouji")->where('uid',$uid)->limit(1)->fetch();
            if(!$wf || $wf['bangdingshouji']=="0,0") {
                //更新任务状态
                $fuid = intval($fuid);
                $updata['bangdingshouji'] = "1," . DI()->config->get('fuli.fuli.bandphone');
                $param = array('service' => "Asyn.Welfare", 'uid' => $uid, 'fuid' => $fuid, 'param' => urlencode(json_encode($updata)));
                $url = DI()->config->get('sys.xsapp.siteurl') . "Public/xsapi/index.php";
                doSock($url, $param);
            }
        }
        return $rs;
    }

    //解绑手机号
    public function getUnBindPhone($uid){
        return $this->getORM()->where('uid = ?',$uid)->update(array('mobile'=>''));
    }

    //绑定支付宝
    public function getBindAlipay($uid,$data){
        $rs = DI()->notorm->user_alipay->select("*")->where("uid",$uid)->limit(1)->fetch();
        if($rs){
            $data = array_merge($data,array('status'=>1));
            $rs = DI()->notorm->user_alipay->where('uid = ?',$uid)->update($data);
        }else{
            $data = array_merge($data,array('uid'=>$uid,'status'=>1,'addtime'=>time()));
            $rs = DI()->notorm->user_alipay->insert($data);
        }
        return $rs;
    }

    //解绑手机号
    public function getUnBindAlipay($uid){
        return DI()->notorm->user_alipay->where('uid = ?',$uid)->update(array('status'=>2));
    }

    //根据手机号获取用户信息
    public function getUserByTel($mobile){
        return $this->getORM()->select('*')->where('mobile = ?',$mobile)->limit(1)->fetchOne();
    }



    //检查短信验证码
    public function  getCheckByTel($mobile,$yzm){
        $check = $this->getCodeByTel($mobile);
        if(!$check){
            $data = array('mobile'=>$mobile,'yzm'=>$yzm,'time'=>time(),'zt'=>0,'num'=>1);
            return DI()->notorm->telcode->insert($data);
        }else{
            //判断是否是同一天
            if((strtotime(date("Y-m-d 00:00:00",$check['time']))+86400) < time()){
                $setnum = 1;
            }else{
                $setnum = new NotORM_Literal('num+1');
            }
            $data = array('yzm'=>$yzm,'time'=>time(),'num'=> $setnum);
            return DI()->notorm->telcode->where('mobile = ?',$mobile)->update($data);
        }
    }

    public  function getCodeByTel($tel){
        return DI()->notorm->telcode->select('*')->where('mobile = ?',$tel)->fetch();
    }


    //验证用户信息
    public function checkToken($token){
        $authifo = authcode(base64_decode_s($token),'DECODE');
        if(!$authifo){
            return array('code'=>2,'msg'=>'登录失效了');
        }
        list($uid,$qudao,$login_type,$duetime) = explode('[\t]',$authifo);
        if(!$uid){
            return array('code'=>2,'msg'=>'登录失效');
        }
        if($duetime-600< time()){
            return array('code'=>2,'msg'=>'登录失效');
        }else{

            $uinfo = $this->getuserInfo($uid);
            if($uinfo['onlogin']==0){
                return array('code'=>2,'msg'=>'您还没有登录');
            }
            return array('code'=>0,'msg'=>'Token有效','uid'=>$uid);
        }
    }
    
    //充值记录
    public function getOrderLog($uid,$id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        return DI()->notorm->bookorder->select('id,title,balance,paytime')->where(" uid = $uid and zt =1 ".$add)->order('id desc')->limit($pagesize)->fetchAll();
    }

    //金币兑换阅读币记录
    public function getBalanceLog($uid,$id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        return DI()->notorm->balance->select('id,gold,balance,addtime')->where(" uid = $uid ".$add)->order('id desc')->limit($pagesize)->fetchAll();
    }


    //章节购买记录
    public function getChapteBuyLog($uid,$id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        return DI()->notorm->chapte_buy->select('id,book_name,chapte_name,price,paytime')->where(" uid = $uid ".$add)->order('id desc')->limit($pagesize)->fetchAll();
    }

    //打赏记录
    public function getGiftLog($uid,$id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        return DI()->notorm->book_reward->select('id,book_name,gift_name,gift_price,gift_time')->where(" uid = $uid ".$add)->order('id desc')->limit($pagesize)->fetchAll();
    }

    //收货地址列表/详情
    public function getAddress($uid,$adrid,$isdefault,$id,$pagesize){
        $add="";
        if($adrid>0){
            return DI()->notorm->address->select("*")->where("uid = ? and adrid = ?",$uid,$adrid)->limit(1)->fetch();
        }else{
            if($id>1){
                $add.= " and id < $id";
            }
            if($isdefault){
                $add.= " and isdefault = 1";
            }
            $rs = DI()->notorm->address->select("*")->where("uid = $uid".$add)->limit($pagesize)->fetchAll();
			
			if($isdefault && !$rs){
				$rs = DI()->notorm->address->select("*")->where("uid = ?",$uid)->limit(1)->fetch();
			}elseif($isdefault && $rs){
				$rs = $rs[0];
			}
			return $rs;
        }
    }

    //我的订单
    public function getOrderList($uid,$type,$id,$pagesize){

        $add = "";
        if($id>1){
            $add.= " and infoid < $id";
        }
        if($type==2){
            $add.=" and fk=0 ";
        }
        if($type==3){
            $add.=" and (fk=1 and zt=0) ";
        }
        if($type==4){
            $add.=" and (fk=1 and zj=1) ";
        }
        if($type==5){
            $add.=" and (fk=2 and zj=1) ";
        }

        $rs = DI()->notorm->pintuan_info->select("infoid,pid,uid as tzinfoid,orderid,id,title,tuid as titlepic,jiage as price,num,beizhu as sku,zt,zj,fk,paytype,time")->where(" uid = ".$uid.$add)->order("infoid desc")->limit($pagesize)->fetchAll();
        $seconds = DI()->config->get("fuli.fuli.seconds");
        $pt = new Model_Pt();
        foreach($rs as $k=>$v){
            $p =  DI()->notorm->pintuan->select("*")->where("pid",$v['pid'])->limit(1)->fetch();
            $r =  DI()->notorm->ecms_pintuan->select("titlepic")->where("id",$v['id'])->limit(1)->fetch();
            if($v['fk']==-1){
                $rs[$k]['status']=-1;
                $rs[$k]['desc']="交易已取消";
            }elseif($v['fk']==0){
                $rs[$k]['status']=0;
                $rs[$k]['desc']="待付款";
            }elseif($v['fk']==1){
                $rs[$k]['status']=1;
                $rs[$k]['desc']="已付款";
                if($p['lx']==0){
                    $rs[$k]['status']=1;
                    $rs[$k]['desc']="待发货";
                }else {
                    if ($v['zt'] == 0) {
                        $rs[$k]['desc'] = "去分享";
                    } elseif ($v['zt'] == 1) {
                        $rs[$k]['desc'] = "已成团，等待抽奖";
                    } elseif ($v['zt'] == 2) {
                        $rs[$k]['desc'] = "拼团失败";
                    }

                    if ($p['cendtime'] + $seconds < time()) {
                        if ($v['zj'] == 1) {
                            $rs[$k]['desc'] = "待发货";
                        } else {
                            $rs[$k]['desc'] = "待退款";
                        }
                    }
                }

            }elseif($v['fk']==2){
                $rs[$k]['status']=2;
                $rs[$k]['desc']="待收货";
            }elseif($v['fk']==3){
                $rs[$k]['status']=3;
                $rs[$k]['desc']="已收货";
            }elseif($v['fk']==4){
                $rs[$k]['status']=4;
                $rs[$k]['desc']="已退款";
            }elseif($v['fk']==5){
                $rs[$k]['status']=5;
                $rs[$k]['desc']="退款失败";
            }
            $rs[$k]['titlepic'] = $r['titlepic'];
            $rs[$k]['lx'] = $p['lx'];
            $tz = $pt->CheckTzInfoByPid($v['pid']);
            $rs[$k]['tzinfoid'] = $tz['infoid'];

            unset($rs[$k]['pid'],$rs[$k]['fk']);
        }

        return $rs;
    }

    //订单详情
    public function getOrderDetail($uid,$infoid){
        $rs = DI()->notorm->pintuan_info->select("infoid,pid,orderid,id,title,tuid as titlepic,jiage as price,num,beizhu as sku,zt,zj,fk,name,tel,address,time,paytype,paytime")->where(" uid = ? and infoid = ?",$uid,$infoid)->limit(1)->fetch();
        if($rs){
            $seconds = DI()->config->get("fuli.fuli.seconds");
            $p =  DI()->notorm->pintuan->select("*")->where("pid",$rs['pid'])->limit(1)->fetch();
            $r =  DI()->notorm->ecms_pintuan->select("titlepic")->where("id",$rs['id'])->limit(1)->fetch();
            if($rs['fk']==-1){
                $rs['status']=-1;
                $rs['desc']="交易已取消";
            }elseif($rs['fk']==0){
                $rs['status']=0;
                $rs['desc']="待付款";
            }elseif($rs['fk']==1){
                $rs['status']=1;
                $rs['desc']="已付款";
                if($p['lx']==0){
                    $rs['status']=1;
                    $rs['desc']="待发货";
                }else {
                    if ($rs['zt'] == 0) {
                        $rs['desc'] = "去分享";
                    } elseif ($rs['zt'] == 1) {
                        $rs['desc'] = "已成团，等待抽奖";
                    } elseif ($rs['zt'] == 2) {
                        $rs['desc'] = "拼团失败";
                    }

                    if ($p['cendtime'] + $seconds < time()) {
                        if ($rs['zj'] == 1) {
                            $rs['desc'] = "待发货";
                        } else {
                            $rs['desc'] = "待退款";
                        }
                    }
                }

            }elseif($rs['fk']==2){
                $rs['status']=2;
                $rs['desc']="待收货";
            }elseif($rs['fk']==3){
                $rs['status']=3;
                $rs['desc']="已收货";
            }elseif($rs['fk']==4){
                $rs['status']=4;
                $rs['desc']="已退款";
            }elseif($rs['fk']==5){
                $rs['status']=5;
                $rs['desc']="退款失败";
            }

            $pt = new Model_Pt();

            $tz = $pt->CheckTzInfoByPid($rs['pid']);
            $rs['tzinfoid'] = $tz['infoid'];
            $rs['lx'] = $p['lx'];
            $rs['titlepic'] = $r['titlepic'];
            $rs['tel'] = substr_replace($rs['tel'], '****', 3, 4);
            $tk = new Model_Tk();
            $like = $tk->getSearchLists($rs['title'],1,0,0,1,1,10);
            $rs['like'] = $like['list'];

            unset($rs['pid'],$rs['fk']);
        }
        return $rs;
    }

    //确认收货
    public function getOrderReceive($uid,$infoid,$type){
        $rs = DI()->notorm->pintuan_info->select("*")->where(" uid = ? and infoid = ?",$uid,$infoid)->limit(1)->fetch();
        if($rs){
            if($type==1){
                if($rs['fk']==0){
                    DI()->notorm->pintuan_info->where(" uid = ? and infoid = ?",$uid,$infoid)->update(array('fk'=>-1));
                    return array('code' => 0, 'msg' => '已取消订单');
                }else{
                    return array('code' => 1, 'msg' => '订单已付款，无法取消');
                }
            }else{
                if($rs['fk']==2){
                    DI()->notorm->pintuan_info->where(" uid = ? and infoid = ?",$uid,$infoid)->update(array('fk'=>3));
                    DI()->notorm->fahuo->where("infoid",$infoid)->update(array('shouhuotime'=>time()));
                    return array('code' => 0, 'msg' => '已成功收货');
                }elseif($rs['fk']==3){
                    return array('code' => 0, 'msg' => '您已收货，不要重复提交');
                }else{
                    return array('code' => 1, 'msg' => '收货失败');
                }
            }
        }else{
            return array('code' => 1, 'msg' => '订单不存在');
        }
    }

    //提现
    public function SetTixian($uid,$gold,$money,$total)
    {
        $rs = false;
        $alipay = $this->getAlipayInfo($uid);
        if ($alipay) {
            $user_id = $alipay['user_id'];
            $nick_name = $alipay['nick_name'];
            $time = time();
            $data = array(
                'orderid' => 't' . str_replace(".", "", microtime(true)) . getRandNum(8),
                'uid' => $uid,
                'user_id' => $user_id,
                'nick_name' => $nick_name,
                'money' => $total,
                'addtime' => $time,
            );
            DI()->notorm->tixian->insert($data);
			$uarr1 = array();
            if ($gold > 0) {
                $uarr1 = array('gold' => new NotORM_Literal('gold -' . $gold), 'txmoney' => new NotORM_Literal('txmoney +' . $total));
                $description = '系统金币换零钱提现';
                DI()->notorm->welfare_log->insert(array('uid' => $uid, 'type' => 2, 'cz' => '-', 'money' => $gold, 'title' => "金币换零钱", 'description' => $description, 'addtime' => $time));//记录福利
            }
			$uarr2 = array();
            if ($money > 0) {
                $uarr2 = array('money' => new NotORM_Literal('money -' . $money), 'txmoney' => new NotORM_Literal('txmoney +' . $total));
                $description = '系统零钱提现';
                DI()->notorm->welfare_log->insert(array('uid' => $uid, 'type' => 1, 'cz' => '-', 'money' => $money, 'title' => "零钱提现", 'description' => $description, 'addtime' => $time));//记录福利
            }
            $uarr = array_merge($uarr1, $uarr2);

            if ($uarr) {
                $rs = DI()->notorm->user->where('uid', $uid)->update($uarr);
            }
            return $rs;
        }
    }

    //提现记录
    public function getTixianRecord($uid,$type){
        $add="";
       if($type==2){
            $add=" and status =1";
        }elseif($type==3){
            $add=" and status = 0";
        }
        return DI()->notorm->tixian->select("id,nick_name,money,status,addtime")->where("uid=".$uid.$add)->order('id desc')->fetchall();

    }

    //金币兑换
    public function SetYuedubi($uid,$gold,$yuedubi){
        $data =array(
            'gold'=>new NotORM_Literal('gold - '.$gold),
            'balance'=>new NotORM_Literal('balance + '.$yuedubi),
        );
        $time = time();
        $insert_data = array(
            'uid'=>$uid,
            'gold'=>$gold,
            'balance'=>$yuedubi,
            'addtime'=>$time
        );
        $id = DI()->notorm->balance->insert($insert_data);
        if($id){
            $this->getORM()->user->where('uid',$uid)->update($data);
            $description ='金币兑换阅读币';
            DI()->notorm->welfare_log->insert(array('uid'=>$uid,'type'=>2,'cz'=>'-','money'=>$gold,'title'=>"金币兑换阅读币",'description'=>$description,'addtime'=>$time));//记录福利
            return true;
        }else{
            return false;
        }
    }

    //我的好友
    public function getFriend($fuid,$uid,$pagesize){
        $add="";
        if($uid>1){
            $add.= " and uid < $uid";
        }
        return $this->getORM()->select('uid,nickname,avatar,addtime')->where("fuid = ".$fuid.$add)->order("uid desc")->limit($pagesize)->fetchAll();
    }

    //我的钱包
    public function getWallet($uid,$type){
        $u = $this->getuserInfo($uid);
        $data['money'] = $u['money'];
        $data['gold'] = $u['gold'];
        //累积收益
        $data['total'] = (string)($u['txmoney'] +$u['money']);
        $time =strtotime(date("Y-m-d H:i:s"))-(86400*2);
        $data['record'] =DI()->notorm->welfare_log->select('id,title,description,money,cz,addtime')->where("uid = $uid and type = $type and addtime>".$time)->order("id desc")->fetchAll();

        return $data;
    }

    //建议反馈
    public function getFeedBack($content,$category,$contact){
        return DI()->notorm->feedback->insert(array('content'=>addslashes($content),'category'=>addslashes($category),'contact'=>addslashes($contact),'ftime'=>time()));
    }
    
    //试读查询
    public function getBookTrial($id){
        return DI()->notorm->book_trial->select("*")->where("id",$id)->limit(1)->fetch();
    }

    protected function getTableName($id) {
        return 'user';
    }
}

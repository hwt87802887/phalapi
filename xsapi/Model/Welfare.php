<?php

class Model_Welfare extends PhalApi_Model_NotORM {

    public  function getIndex($uid){

        //$weekarray=array(7,1,2,3,4,5,6);
        $rs= $this->getORM()->select('*')->where("uid",$uid)->limit(1)->fetch();
        if(!$rs){
            $signday="0";//未连续签到
            $status = "0";//今日未签到
        }else{
            $status = "1";
            $signday=$rs['day'];//连续签到天数
            $lasttime = date("Y-m-d 00:00:00",$rs['lasttime']);
            $time = date("Y-m-d 00:00:00");
            if($time != $lasttime){
                $status = "0";
            }
            //距离上次签到是否超过1天
            if(strtotime($time) - strtotime($lasttime) >86400){
                $signday = "0";
            }

        }
        $qiandao = DI()->config->get('fuli.fuli.qiandao');
        $qiandaodz = DI()->config->get('fuli.fuli.qiandaodz');

        $list = array();

        if($signday){
            //连续签到,明日获得金币
            if($signday<7){
                $gold= $qiandao+($qiandaodz*$signday);
                $start = $signday?$signday:1;
            }else{
                $day_yu = $signday % 7;
                if($day_yu==0){
                    $gold= $qiandao + $qiandaodz;
                    $start = 1;
                }else{
                    $gold= $qiandao+($qiandaodz*$day_yu);
                    $start = $day_yu;
                }
            }

        }else{
            $gold = $qiandao + $qiandaodz;
            $start = 1;
        }


        $k=1;
        for($i=$start-1;$i<7;$i++){
            if($k==1){
                $list[] = array('title'=>'今日','gold'=>$qiandao+($qiandaodz*$i));
            }else{
                $gold_d = $qiandao+($qiandaodz*$i);
                $list[] = array('title'=>$k.'天','gold'=>$gold_d);
            }
            $k++;
        }

        for($j=0;$j<=7-$k;$j++){
            $list[] = array('title'=>($k+$j).'天','gold'=>$qiandao+($qiandaodz*($j)));
        }


        //签到信息
        $sign = array('day'=>$signday,'gold'=>$gold,'status'=>$status,'desc'=>"已连续签到#day#天".PHP_EOL."明日签到领取#gold#金币",'list'=>$list);

        $welfare = array(
            'uid'=>$uid,
            'xinren'=>'1,'.DI()->config->get('fuli.fuli.newuser'),
            'wanshanziliao'=>'0,0',
            'bangdingshouji'=>'0,0',
            'tianjiashujia'=>'0,0',
            'meirifenxiang'=>'0,0',
            'yueduzixun'=>'0,0',
            'xiaoshuoshidu'=>'0,0',
            'addtime'=>time(),
            'lasttime'=>time(),
        );
        $wf = DI()->notorm->welfare->select("uid,xinren,wanshanziliao,bangdingshouji,tianjiashujia,meirifenxiang,wanshanziliao,yueduzixun,xiaoshuoshidu")->where("uid",$uid)->limit(1)->fetch();
        if(!$wf){
            DI()->notorm->welfare->insert($welfare);
        }else{
            $welfare = $wf;
        }

        $user = new Model_User();
        $u = $user->getuserInfo($uid);

        //新人红包
        $xinren = explode(",",$welfare['xinren']);
        $type = "xinren";
        $type_name = "新人红包";
        $type_desc = "新用户奖励：#gold#金币";
        $gold = DI()->config->get('fuli.fuli.newuser');
        $status = "1";
        $status_desc = "可领取";
        if($xinren[0]=="2"){
            $status = "2";
            $status_desc = "已领取";
        }
        $welfare_data[] = array('type'=>$type,'type_name'=>$type_name,'type_desc'=>$type_desc,'gold'=>$gold,'status'=>$status,'status_desc'=>$status_desc);

        $updata['xinren'] = $status.",".$gold;

        //完善资料
        $wanshanziliao = explode(",",$welfare['wanshanziliao']);
        $type = "wanshanziliao";
        $type_name = "完善资料";
        $type_desc = "奖励：#gold#金币";
        $gold = DI()->config->get('fuli.fuli.perfectzl');
        $status = "0";
        $status_desc = "去完成";
        if($wanshanziliao[0]=="0"){
            if($u['avatar']!='' && $u['birth']!='' && $u['sex']>0 && $u['province']!='' && $u['city']!=''){
                $status = "1";
                $status_desc = "可领取";

                $updata['wanshanziliao'] = $status.",".$gold;
            }
        }elseif($wanshanziliao[0]=="1"){
            $status = "1";
            $status_desc = "可领取";
        }elseif($wanshanziliao[0]=="2"){
            $status = "2";
            $status_desc = "已领取";
        }

        $welfare_data[] = array('type'=>$type,'type_name'=>$type_name,'type_desc'=>$type_desc,'gold'=>$gold,'status'=>$status,'status_desc'=>$status_desc);

        //绑定手机号
        $bangdingshouji = explode(",",$welfare['bangdingshouji']);
        $type = "bangdingshouji";
        $type_name = "绑定手机号";
        $type_desc = "奖励：#gold#金币";
        $gold = DI()->config->get('fuli.fuli.bandphone');
        $status = "0";
        $status_desc = "去完成";
        if($bangdingshouji[0]=="0"){
            if($u['mobile']!=''){
                $status = "1";
                $status_desc = "可领取";

                $updata['bangdingshouji'] = $status.",".$gold;
            }
        }elseif($bangdingshouji[0]=="1"){
            $status = "1";
            $status_desc = "可领取";
        }elseif($bangdingshouji[0]=="2"){
            $status = "2";
            $status_desc = "已领取";
        }


        $welfare_data[] = array('type'=>$type,'type_name'=>$type_name,'type_desc'=>$type_desc,'gold'=>$gold,'status'=>$status,'status_desc'=>$status_desc);

        //添加书架
        $tianjiashujia = explode(",",$welfare['tianjiashujia']);
        $type = "tianjiashujia";
        $type_name = "添加书架";
        $type_desc = "奖励：#gold#金币";
        $gold = DI()->config->get('fuli.fuli.addshujia');
        $status = "0";
        $status_desc = "去完成";
        if($tianjiashujia[0]=="0"){
            $bs = DI()->notorm->ecms_book_self->select("*")->where("uid",$uid)->limit(1)->fetch();
            if($bs){
                $status = "1";
                $status_desc = "可领取";

                $updata['tianjiashujia'] = $status.",".$gold;
            }
        }elseif($tianjiashujia[0]=="1"){
            $status = "1";
            $status_desc = "可领取";
        }elseif($tianjiashujia[0]=="2"){
            $status = "2";
            $status_desc = "已领取";
        }

        $welfare_data[] = array('type'=>$type,'type_name'=>$type_name,'type_desc'=>$type_desc,'gold'=>$gold,'status'=>$status,'status_desc'=>$status_desc);

        //每日分享
        $meirifenxiang = explode(",",$welfare['meirifenxiang']);
        $type = "meirifenxiang";
        $type_name = "每日分享";
        $type_desc = "奖励：#gold#金币";
        $gold = DI()->config->get('fuli.fuli.share');
        $status = "0";
        $status_desc = "去完成";
        if($meirifenxiang[0]=="0"){
            $bs = DI()->notorm->share->select("*")->where("uid",$uid)->limit(1)->fetch();
            if($bs && date("Ymd",$bs['lasttime'])==date("Ymd")){
                $status = "1";
                $status_desc = "可领取";

                $updata['meirifenxiang'] = $status.",".$gold;
            }
        }elseif($meirifenxiang[0]=="1"){
            $status = "1";
            $status_desc = "可领取";
        }elseif($meirifenxiang[0]=="2"){
            $status = "2";
            $status_desc = "已领取";
        }

        $welfare_data[] = array('type'=>$type,'type_name'=>$type_name,'type_desc'=>$type_desc,'gold'=>$gold,'status'=>$status,'status_desc'=>$status_desc);

        //阅读资讯
        $yueduzixun = explode(",",$welfare['yueduzixun']);
        $type = "yueduzixun";
        $type_name = "阅读资讯";
        $status="0";
        $type_desc="看资讯有钱赚";
        $gold = 0;
        $status_desc = "去完成";
        if($yueduzixun[0]=="1"){
            $status = "1";
            $status_desc = "可领取";
        }elseif($yueduzixun[0]=="2"){
            $status = "2";
            $status_desc = "已领取";
        }
        $welfare_data[] = array('type'=>$type,'type_name'=>$type_name,'type_desc'=>$type_desc,'gold'=>$gold,'status'=>$status,'status_desc'=>$status_desc);


            $szmoney = DI()->config->get('fuli.fuli.szmoney');
            $xiaoshuoshidu = explode(",", $welfare['xiaoshuoshidu']);
            $type = "xiaoshuoshidu";
            $type_name = "小说试读";
            $status = "0";
            $type_desc = "试读小说赚现金";
            $gold = $szmoney * 6;
            $status_desc = "去完成";
            $bkarr = array('book_id'=>"",'next_chapte'=>"",'sdtoken'=>"",'');
            if ($xiaoshuoshidu[0] == "1") {
                $status = "1";
                $type_desc = "奖励：#gold#元现金";
                $status_desc = "可领取";
            } elseif ($xiaoshuoshidu[0] == "2") {
                $status = "2";
                $type_desc = "奖励：#gold#元现金";
                $status_desc = "已领取";
            }
            $rs = DI()->notorm->book_trial->select("id,qudao,sdmiao,book_id,book_chapte")->where("readtime",strtotime(date("Y-m-d 00:00:00")))->order('id desc')->limit(1)->fetch();
            if($rs) {
                $book_chapte = json_decode($rs['book_chapte'],true);
                $next_chapte= $book_chapte[0];
                $cp = DI()->notorm->ecms_book_chapte->select('sort_id')->where('chapte_id',$next_chapte)->fetch();
                $sdtoken = base64_encode_s(authcode($rs['id'].'[\t]'.$rs['qudao'],'ENCODE'));
                $bkarr = array('book_id'=>$rs['book_id'],'next_chapte'=>$next_chapte,'sort_id'=>$cp['sort_id'],'sdtime'=>$rs['sdmiao'],'sdtoken'=>$sdtoken);
            }

            $welfare_data[] = array('type' => $type, 'type_name' => $type_name, 'type_desc' => $type_desc, 'gold' => $gold, 'status' => $status, 'status_desc' => $status_desc,'book'=>$bkarr);


        $param = array('service'=>"Asyn.Welfare",'uid'=>$uid,'param'=>urlencode(json_encode($updata)));
        $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
        doSock($url,$param);

        return array('sign'=>$sign,'welfare'=>$welfare_data);
    }

    public function getSign($uid){
        $rs= $this->getORM()->select('*')->where("uid",$uid)->limit(1)->fetch();
        $qiandao = DI()->config->get('fuli.fuli.qiandao');
        $qiandaodz = DI()->config->get('fuli.fuli.qiandaodz');
        if(!$rs){
            $signcount="1";
            $day="1";
            $gold = $qiandao;
        }else{

            $lasttime = date("Y-m-d 00:00:00",$rs['lasttime']);
            $time = date("Y-m-d 00:00:00");
            if($lasttime==$time){
                return array('code'=>1,'msg'=>'今日已签到','list'=>array());
            }

            $signcount = $rs['signcount']+1;
            $day = $rs['day']+1;
            //时间超过1天
            if(strtotime($time) - strtotime($lasttime) >86400){
                $day="1";
                $gold = $qiandao;
            }else{
                //连续签到
                if($day<=7){
                    $gold= $qiandao+($qiandaodz*($day-1));
                }else{
                    $day_yu = $day % 7;
                    $gold= $qiandao+($qiandaodz*($day_yu-1));
                }
            }
        }

        $param = array('service'=>"Asyn.Sign",'uid'=>$uid,'signcount'=>$signcount,'day'=>$day,'gold'=>$gold);
        $url = DI()->config->get('sys.xsapp.siteurl')."Public/xsapi/index.php";
        doSock($url,$param);

        $data= array('day'=>$signcount,'gold'=>$gold,'desc'=>"已连续签到#day#天".PHP_EOL."明日签到领取#gold#金币");
        return array('code'=>0,'msg'=>'签到成功','list'=>$data);
    }
    
    //领取福利
    public function getReceiveAward($uid,$type){
        $welfare = array(
            'uid'=>$uid,
            'xinren'=>'1,'.DI()->config->get('fuli.fuli.newuser'),
            'wanshanziliao'=>'0,0',
            'bangdingshouji'=>'0,0',
            'tianjiashujia'=>'0,0',
            'meirifenxiang'=>'0,0',
            'yueduzixun'=>'0,0',
            'xiaoshuoshidu'=>'0,0',
            'addtime'=>time(),
            'lasttime'=>time(),
        );

        $rs = DI()->notorm->welfare->select("*")->where('uid',$uid)->limit(1)->fetch();
        $gold=0;
        $up1 = array();$up2 = array();
        $user = new Model_User();
        $u = $user->getuserInfo($uid);
        if($type=="xinren"){
            $xinren = explode(",",$rs['xinren']);
            if($xinren[0]==1){
                $up1 = array('lasttime'=>time(),'xinren'=>"2,".$xinren[1]);
                $up2 = array('gold'=>new NotORM_Literal('gold + '.$xinren[1]));
                $gold = $xinren[1];
            }elseif($xinren[0]==2){
                return array('code'=>1,'msg'=>'已领取，请勿重复领取','list'=>array());
            }
        }elseif($type=="wanshanziliao"){

            $wanshanziliao = explode(",",$rs['wanshanziliao']);
            if($wanshanziliao[0]==1){
                $up1 = array('lasttime'=>time(),'wanshanziliao'=>"2,".$wanshanziliao[1]);
                $up2 = array('gold'=>new NotORM_Literal('gold + '.$wanshanziliao[1]));
                $gold = $wanshanziliao[1];
            }elseif($wanshanziliao[0]==2){
                return array('code'=>1,'msg'=>'已领取，请勿重复领取','list'=>array());
            }else{
                if($u['avatar']!='' && $u['birth']!='' && $u['sex']>0 && $u['province']!='' && $u['city']!=''){
                    $up1 = array('lasttime'=>time(),'wanshanziliao'=>"2,".$wanshanziliao[1]);
                    $up2 = array('gold'=>new NotORM_Literal('gold + '.$wanshanziliao[1]));
                    $gold = $wanshanziliao[1];

                }else{
                    return array('code'=>3,'msg'=>'尚未完成，去完善资料','list'=>array());
                }

            }

        }elseif($type=="bangdingshouji"){

            $bangdingshouji = explode(",",$rs['bangdingshouji']);
            if($bangdingshouji[0]==1){
                $up1 = array('lasttime'=>time(),'bangdingshouji'=>"2,".$bangdingshouji[1]);
                $up2 = array('gold'=>new NotORM_Literal('gold + '.$bangdingshouji[1]));
                $gold = $bangdingshouji[1];
            }elseif($bangdingshouji[0]==2){
                return array('code'=>1,'msg'=>'已领取，请勿重复领取','list'=>array());
            }else{
                if($u['mobile']!=''){
                    $up1 = array('lasttime'=>time(),'wanshanziliao'=>"2,".$bangdingshouji[1]);
                    $up2 = array('gold'=>new NotORM_Literal('gold + '.$bangdingshouji[1]));
                    $gold = $bangdingshouji[1];
                }else {
                    return array('code' => 3, 'msg' => '尚未完成，去绑定手机号', 'list' => array());
                }
            }

        }elseif($type=="tianjiashujia"){

            $tianjiashujia = explode(",",$rs['tianjiashujia']);
            if($tianjiashujia[0]==1){
                $up1 = array('lasttime'=>time(),'tianjiashujia'=>"2,".$tianjiashujia[1]);
                $up2 = array('gold'=>new NotORM_Literal('gold + '.$tianjiashujia[1]));
                $gold = $tianjiashujia[1];
            }elseif($tianjiashujia[0]==2){
                return array('code'=>1,'msg'=>'已领取，请勿重复领取','list'=>array());
            }else{
                $bs = DI()->notorm->ecms_book_self->select("*")->where("uid",$uid)->limit(1)->fetch();
                if($bs){
                    $up1 = array('lasttime'=>time(),'tianjiashujia'=>"2,".$tianjiashujia[1]);
                    $up2 = array('gold'=>new NotORM_Literal('gold + '.$tianjiashujia[1]));
                    $gold = $tianjiashujia[1];
                }else {
                    return array('code' => 3, 'msg' => '尚未完成，去添加书架', 'list' => array());
                }
            }

        }elseif($type=="meirifenxiang"){

            $meirifenxiang = explode(",",$rs['meirifenxiang']);
            if($meirifenxiang[0]==1){
                $up1 = array('lasttime'=>time(),'meirifenxiang'=>"2,".$meirifenxiang[1]);
                $up2 = array('gold'=>new NotORM_Literal('gold + '.$meirifenxiang[1]));
                $gold = $meirifenxiang[1];
            }elseif($meirifenxiang[0]==2){
                return array('code'=>1,'msg'=>'已领取，请勿重复领取','list'=>array());
            }else{
                $bs = DI()->notorm->share->select("*")->where("uid",$uid)->limit(1)->fetch();
                if($bs && date("Ymd",$bs['lasttime'])==date("Ymd")){
                    $up1 = array('lasttime'=>time(),'meirifenxiang'=>"2,".$meirifenxiang[1]);
                    $up2 = array('gold'=>new NotORM_Literal('gold + '.$meirifenxiang[1]));
                    $gold = $meirifenxiang[1];
                }else {
                    return array('code' => 3, 'msg' => '尚未完成，去分享书籍', 'list' => array());
                }
            }

        }elseif($type=="yueduzixun"){

            $yueduzixun = explode(",",$rs['yueduzixun']);
            if($yueduzixun[0]==1){
                $up1 = array('lasttime'=>time(),'yueduzixun'=>"2,".$yueduzixun[1]);
                $up2 = array('gold'=>new NotORM_Literal('gold + '.$yueduzixun[1]));
                $gold = $yueduzixun[1];
            }elseif($yueduzixun[0]==2){
                return array('code'=>1,'msg'=>'已领取，请勿重复领取','list'=>array());
            }else{
                return array('code'=>3,'msg'=>'尚未完成，去阅读资讯','list'=>array());
            }

        }elseif($type=="xiaoshuoshidu"){

            $xiaoshuoshidu = explode(",",$rs['xiaoshuoshidu']);
            if($xiaoshuoshidu[0]==1){
                $up1 = array('lasttime'=>time(),'xiaoshuoshidu'=>"2,".$xiaoshuoshidu[1]);
                $up2 = array('money'=>new NotORM_Literal('money + '.$xiaoshuoshidu[1]));
                $gold = $xiaoshuoshidu[1];
            }elseif($xiaoshuoshidu[0]==2){
                return array('code'=>1,'msg'=>'已领取，请勿重复领取','list'=>array());
            }else{
                return array('code'=>3,'msg'=>'尚未完成，去试读赚现金','list'=>array());
            }

        }else{
            return array('code'=>1,'msg'=>'领取失败','list'=>array());
        }

        if($up1){
            DI()->notorm->welfare->where("uid",$uid)->update($up1);
        }
        if($up2){
            DI()->notorm->user->where("uid",$uid)->update($up2);
        }


        $list = array('type' => $type, 'gold' => $gold, 'status' => 2, 'status_desc' => "已领取");
        return array('code'=>0,'msg'=>'领取成功','list'=>$list);
    }

    //分享回调
    public function getShare($uid){
        $rs= DI()->notorm->share->select('*')->where("uid",$uid)->limit(1)->fetch();
        $Asyn = true;
        $gold = DI()->config->get('fuli.fuli.share');
        if($rs){
            if(date("Ymd",$rs['lasttime'])==date("Ymd")){
                $Asyn = false;
                $code=1;$msg='今日已分享过啦';$data = array('gold'=>"0",'desc'=>'分享成功');
            }else {
                $code = 0;$msg = '分享成功';$data = array('gold' => $gold, 'desc' => '恭喜分享成功，获得#gold#金币');
                $param = array('service' => "Asyn.Share", 'uid' => $uid, 'type' => "update", 'gold' => $gold);
            }
        }else{
            $code=0;$msg='分享成功';$data = array('gold'=>$gold,'desc'=>'恭喜分享成功，获得#gold#金币');
            $param = array('service'=>"Asyn.Share",'uid'=>$uid,'type'=>"add",'gold'=>$gold);
        }


        if($Asyn==true) {
            $url = DI()->config->get('sys.xsapp.siteurl') . "Public/xsapi/index.php";
            doSock($url, $param);
        }
        return array('code'=>$code,'msg'=>$msg,'list'=>$data);
    }

    //阅读资讯回调
    public function getReadNews($uid,$info_id,$gold){
        //是否已读
        $h = DI()->notorm->info_history->select('*')->where("uid = ? and info_id=?",$uid,$info_id)->limit(1)->fetch();
        if($h){
            $code=1;$msg='资讯已阅读过啦';$data = array('gold'=>"0",'desc'=>'资讯已阅读过啦');
        }else{
            $code=0;$msg='成功';$data = array('gold'=>$gold,'desc'=>'阅读资讯，获得#gold#金币');

            $param = array('service'=>"Asyn.ReadNews",'uid'=>$uid,'info_id'=>$info_id,'gold'=>$gold);
            $url = DI()->config->get('sys.xsapp.siteurl') . "Public/xsapi/index.php";
            doSock($url, $param);
        }

        return array('code'=>$code,'msg'=>$msg,'list'=>$data);
    }
    
    //试读回调
    public function getReadBook($uid,$book_id,$chapte_id,$sdmoney){
        $sdmoney =$sdmoney?$sdmoney:DI()->config->get('fuli.fuli.sdmoney');
        $sdmoney2 = $sdmoney;
        //之前是否阅读过
        $sdc = DI()->notorm->book_trial_chapte->select('chapte_id')->where('uid = ? and chapte_id=?',$uid,$chapte_id)->limit(1)->fetch();
        if($sdc){
            $code=1;$msg='章节已试读过啦';$data = array('gold'=>"0",'desc'=>'章节已试读过啦');
        }else{
            $code=0;$msg='成功';$data = array('gold'=>$sdmoney,'desc'=>'阅读资讯，获得#gold#元现金');
        }

        if($code==0) {
            //试读历史
            $id = DI()->notorm->book_trial_chapte->insert(array('uid' => $uid, 'book_id' => $book_id, 'chapte_id' => $chapte_id, 'addtime' => time()));
            $rs = DI()->notorm->welfare->select('xiaoshuoshidu')->where('uid', $uid)->limit(1)->fetch();

            if (!$rs) {
                $welfare = array(
                    'uid' => $uid,
                    'xinren' => '1,' . DI()->config->get('fuli.fuli.newuser'),
                    'wanshanziliao' => '0,0',
                    'bangdingshouji' => '0,0',
                    'tianjiashujia' => '0,0',
                    'meirifenxiang' => '0,0',
                    'yueduzixun' => '0,0',
                    'xiaoshuoshidu' => '1,'.$sdmoney,
                    'addtime' => time(),
                    'lasttime' => time(),
                );
                DI()->notorm->welfare->insert($welfare);
            } else {
                $ydzx = explode(",", $rs['xiaoshuoshidu']);
                $status = 1;
                //未领取累加
                if ($ydzx[0] == 1) {
                    $sdmoney = bcadd(($ydzx[1] + $sdmoney), 2);
                }

                $updata['xiaoshuoshidu'] = $status . "," . $sdmoney;
                $asyn = new Model_Asyn();
                $asyn->SetWelfare($uid, $updata);
            }

            $description = '试读小说章节收益';
            DI()->notorm->welfare_log->insert(array('uid' => $uid, 'type' => 1, 'cz' => '+', 'money' => $sdmoney2, 'title' => "小说试读", 'description' => $description, 'addtime' => time()));//记录福利
            //DI()->notorm->user->where('uid',$uid)->update(array('money'=>new NotORM_Literal('money + '.$sdmoney)));
        }
        return array('code'=>$code,'msg'=>$msg,'list'=>$data);

    }
    
    //抽奖
    public function getLottery($uid){
        $rs= DI()->notorm->lottery->select('*')->where("uid",$uid)->limit(1)->fetch();
        $Asyn = true;
        $prize_arr = array(
            '0' => array('id'=>1,'type'=>'红包','rate'=>20),
            '1' => array('id'=>2,'type'=>'金币','rate'=>40),
            '2' => array('id'=>3,'type'=>'金币','rate'=>40)
        );

        foreach ($prize_arr as $key => $val) {
            $arr[$val['id']] = $val['rate'];
        }
        $randid = get_rand($arr);
        $fuli = $prize_arr[$randid-1]['id'];
        $type = $prize_arr[$randid-1]['type'];


        if($fuli==1){
            $min = DI()->config->get('fuli.fuli.hbmin')*100;
            $max = DI()->config->get('fuli.fuli.hbmax')*100;
            $gold = mt_rand($min,$max)/100;
        }else{
            $min = DI()->config->get('fuli.fuli.jbmin');
            $max = DI()->config->get('fuli.fuli.jbmax');
            $gold = mt_rand($min,$max);
            $fuli=2;
        }


        if($rs){
            if(date("Ymd",$rs['lasttime'])==date("Ymd")){
                $Asyn = false;
                $code=1;$msg='今日已抽过奖啦';$data = array('gold'=>"0",'type'=>"",'desc'=>'今日已抽过奖啦');
            }else {
                $code = 0;$msg = '成功';$data = array('gold' => $gold,'type'=>$type, 'desc' => '恭喜，获得#gold#'.$type);
                $param = array('service' => "Asyn.Lottery", 'uid' => $uid, 'type' => "update",'fuli'=>$fuli, 'gold' => $gold);
            }

        }else{
            $code=0;$msg='成功';$data = array('gold'=>$gold,'type'=>$type,'desc'=>'恭喜，获得#gold#'.$type);
            $param = array('service'=>"Asyn.Lottery",'uid'=>$uid,'type'=>"add",'fuli'=>$fuli,'gold'=>$gold);
        }


        if($Asyn==true) {
            $url = DI()->config->get('sys.xsapp.siteurl') . "Public/xsapi/index.php";
            doSock($url, $param);
        }
        return array('code'=>$code,'msg'=>$msg,'list'=>$data);
    }


    protected function getTableName($id) {
        return 'sign';
    }
}

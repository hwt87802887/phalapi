<?php

class Model_Pt extends PhalApi_Model_NotORM {

    public  function getIndexTuiJian(){
        //最近即将开始或已开始拼团
        $time = date("Y-m-d H:i:s");
        $data = array();
        $rs1 = $this->getORM()->select("id,title,keyboard as ftitle,titlepic,yuanjia,price,num,tnum,cstarttime,cendtime,description")->where("(cstarttime >'".$time."') or (cstarttime <='".$time."' and cendtime>'".$time."')")->order("id asc")->limit(1)->fetch();
        if($rs1){
            if($rs1['cstarttime'] >date("Y-m-d H:i:s")){
                $rs1['status']="0";
            }
            if($rs1['cstarttime'] <=date("Y-m-d H:i:s")){
                $rs1['status']="1";
            }
            $rs1['yuanjia'] = number_format($rs1['yuanjia'],2);
            $rs1['price'] = number_format($rs1['price'],2);
            $data[] = $rs1;
        }
        //最新等待开奖或已开奖
        $rs2 = $this->getORM()->select("id,title,keyboard as ftitle,titlepic,yuanjia,price,num,tnum,cstarttime,cendtime,description")->where("cendtime <='".$time."'")->order('id asc')->limit(1)->fetch();
        if($rs2){
            $seconds = DI()->config->get("fuli.fuli.seconds");
            if($rs2['cendtime'] >=date("Y-m-d H:i:s")){
                $rs2['status']="2";
            }
            if((strtotime($rs2['cendtime']) + $seconds) <=time()){
                $rs2['status']="3";
            }
            $rs2['yuanjia'] = number_format($rs2['yuanjia'],2);
            $rs2['price'] = number_format($rs2['price'],2);
            $rs2['cendtime'] = date("Y-m-d H:i:s",(strtotime($rs2['cendtime']) + $seconds));
            $data[] = $rs2;
        }
        return $data;
    }

    public function getLists($id,$pagesize){
        if($id>1){
            $add = " and id < $id";
        }
        $seconds = DI()->config->get("fuli.fuli.seconds");
        $rs = $this->getORM()->select("id,title,keyboard as ftitle,titlepic,yuanjia,price,num,tnum,cstarttime,cendtime,description")->where(" 1=1 ".$add)->order("id desc")->limit($pagesize)->fetchAll();
        foreach($rs as $key=>$item){
            if($item['cstarttime'] >date("Y-m-d H:i:s")){
                $rs[$key]['status']="0";
            }
            if($item['cstarttime'] <=date("Y-m-d H:i:s")){
                $rs[$key]['status']="1";
            }
            if($item['cendtime'] <=date("Y-m-d H:i:s")){
                $rs[$key]['status']="2";
            }
            if((strtotime($item['cendtime']) + $seconds) <=time()){
                $rs[$key]['status']="3";
            }
            $rs[$key]['yuanjia'] = number_format($item['yuanjia'],2);
            $rs[$key]['price'] = number_format($item['price'],2);
        }
        return $rs;
    }

    public function getDetail($id){
        $seconds = DI()->config->get("fuli.fuli.seconds");
        $rs = $this->getORM()->select("id,title,keyboard as ftitle,titlepic,yuanjia,price,num,tnum,cstarttime,cendtime,description")->where("id",$id)->limit(1)->fetch();
        if($rs){
            if($rs['cstarttime'] >date("Y-m-d H:i:s")){
                $rs['status']="0";
            }
            if($rs['cstarttime'] <=date("Y-m-d H:i:s")){
                $rs['status']="1";
            }
            if($rs['cendtime'] <=date("Y-m-d H:i:s")){
                $rs['status']="2";
            }
            if((strtotime($rs['cendtime']) + $seconds) <=time()){
                $rs['status']="3";
            }
            $rs['yuanjia'] = number_format($rs['yuanjia'],2);
            $rs['price'] = number_format($rs['price'],2);
            $d = DI()->notorm->ecms_pintuan_data_1->select("tuji,shaixuan")->where("id",$id)->limit(1)->fetch();
            $rs['tuji'] = $d['tuji']?explode("||||||",$d['tuji']):"";
            if($d['shaixuan']){
                $shaixuan = explode("||||||",$d['shaixuan']);
                $j=0;
                $shaixuan2 = array();
                foreach ($shaixuan as $k=>$dlist){
                    if($dlist!="::::::") {
                        $dlist = explode("::::::", $dlist);
                        if ($dlist[0]) {
                            $shaixuan2[$j] = array('name' => $dlist[0], 'list' => explode(" ", $dlist[1]));
                        }
                        $j++;
                    }
                }
                $rs['sku'] = $shaixuan2;
            }else{
                $rs['sku']=array();
            }

            $rs['tag'] = array('全场包邮','7天退换货','48小时发货','假一赔十');
            $rs['tag1'] = "支付开团并邀请 ".($rs['tnum']-1)." 人参团，人数不足自动退款";
            $st = date("m月d日 H:i",strtotime($rs['cstarttime']));
            $et = date("m月d日 H:i",strtotime($rs['cendtime']));
            $et2 = date("m月d日",strtotime($rs['cendtime']));
            $rs['game'] = "活动时间：".$st."~".$et."\r\n 1.活动结束后,从所有拼团成功的订单中随机抽取中奖者。\\r\\n 2.一等奖为“".$rs['title']."”（共".$rs['num']."名）；".$rs['ftitle']."。\\r\\n 3.中奖名单将于".$et2."公布，并于次日陆续发货。\\r\\n 4.本活动最终解释权归".(DI()->config->get('sys.xsapp.appname'))."所有。";
            $rs['contenturl'] = DI()->config->get("sys.xsapp.homeurl")."pt/content.php?id=".$id;

            return array('code'=>0,'msg'=>'成功','list'=>$rs);

        }else{
            return array('code'=>1,'msg'=>'未找到商品信息','list'=>array());
        }
    }

    public function getWinList($id){
        $seconds = DI()->config->get("fuli.fuli.seconds");
        $rs = $this->getORM()->select("id,title,titlepic,yuanjia,price,num,cstarttime,cendtime,description")->where("id",$id)->limit(1)->fetch();
        if($rs){
            if($rs['cstarttime'] >date("Y-m-d H:i:s")){
                $rs['status']="0";
            }
            if($rs['cstarttime'] <=date("Y-m-d H:i:s")){
                $rs['status']="1";
            }
            if($rs['cendtime'] <=date("Y-m-d H:i:s")){
                $rs['status']="2";
            }
            if((strtotime($rs['cendtime']) + $seconds) <=time()){
                $rs['status']="3";
            }
            $rs['yuanjia'] = number_format($rs['yuanjia'],2);
            $rs['price'] = number_format($rs['price'],2);
            $d = DI()->notorm->ecms_pintuan_data_1->select("tuji,shaixuan")->where("id",$id)->limit(1)->fetch();
            $rs['tuji'] = $d['tuji']?explode("||||||",$d['tuji']):"";
            if($d['shaixuan']){
                $shaixuan = explode("||||||",$d['shaixuan']);
                $j=0;
                $shaixuan2 = array();
                foreach ($shaixuan as $k=>$dlist){
                    if($dlist!="::::::") {
                        $dlist = explode("::::::", $dlist);
                        if ($dlist[0]) {
                            $shaixuan2[$j] = array('name' => $dlist[0], 'list' => explode(" ", $dlist[1]));
                        }
                        $j++;
                    }
                }
                $rs['sku'] = $shaixuan2;
            }else{
                $rs['sku']=array();
            }
            $rs['time'] = date("Y-m-d H:i:s",strtotime($rs['cendtime']) + $seconds);
            $rs['roster'] = $rs['status']=="3"?$this->getRoster($rs['id'],$rs['num']):array();
            $tk = new Model_Tk();
            $like = $tk->getSearchLists($rs['title'],1,0,0,1,1,10);
            $rs['like'] = $like['list'];

            unset($rs['cstarttime'],$rs['cendtime']);

            return array('code'=>0,'msg'=>'成功','list'=>$rs);

        }else{
            return array('code'=>1,'msg'=>'未找到商品信息','list'=>array());
        }
    }

    //获取中奖名单
    public function  getRoster($id,$num){
        $path = "roster/".$id.".txt";
        $md = @file_get_contents($path);
        if(!$md){
            $prefix = DI()->config->get('dbs.tables.__default__.prefix');
            $rs =  $this->getORM()->queryAll("SELECT nickname,avatar FROM ".$prefix."pt_user AS b1 JOIN (SELECT	ROUND(RAND() * ((SELECT MAX(id) FROM ".$prefix."pt_user)-(SELECT MIN(id) FROM ".$prefix."pt_user )) + (SELECT MIN(id) FROM ".$prefix."pt_user)) AS id) AS b2 WHERE  b1.id>=b2.id limit $num");
            file_put_contents($path,serialize($rs));
        }else{
            $rs = unserialize($md);
        }
        return $rs;
    }

    public function geUnfinished($id,$pid,$pagesize){
        $time = time();
        if($pid>1){
            $add = " and pid < $pid";
        }
        $rs = DI()->notorm->pintuan->select("pid,uid,tnum,num,endtime")->where("id = $id and tnum > num and num>0 and endtime >".$time.$add)->order('pid desc')->limit($pagesize)->fetchAll();
        $user = new Model_User();
        $data = $rs;
        foreach ($data as $key=>$item){
            $data[$key]['endtime'] = date("Y-m-d H:i:s",$item['endtime']);
            $u = $user->getuserInfo($item['uid']);
            $data[$key]['nickname'] = $u['nickname'];
            $data[$key]['avatar'] = $u['avatar'];
            $tz = $this->CheckTzInfoByPid($item['pid']);
            $data[$key]['tzinfoid'] = $tz['infoid'];
        }

        return array('code'=>0,'msg'=>'成功','list'=>$data);
    }

    //团长
    public function CheckTzInfoByPid($pid){
       return DI()->notorm->pintuan_info->select("*")->where("pid = $pid and tuid = uid")->limit(1)->fetch();
    }

    public function getPtDetail($infoid){
        $ptinfo = $this->getPtInfo($infoid);
        if(!$ptinfo){
            return array('code'=>1,'msg'=>'拼团信息不存在');
        }
        $data = array();
        $rs = $this->getORM()->select("id,title,tnum,num,keyboard,titlepic,yuanjia,price")->where("id",$ptinfo['id'])->limit(1)->fetch();
        if(!$rs){
            return array('code'=>1,'msg'=>'商品信息不存在');
        }
        $pt = $this->getPt($ptinfo['pid']);
        if(!$pt){
            return array('code'=>1,'msg'=>'团长信息不存在');
        }

        if($pt['lx']==0){
            return array('code'=>1,'msg'=>'直接购买无法参团');
        }

        $data['id'] = $rs["id"];
        $data['title'] = $rs["title"]."【限额".$rs['num']."名】".$rs['keyboard'];
        $data['sku'] = $ptinfo['beizhu'];
        $data['titlepic'] = $rs["titlepic"];
        $data['price'] = $ptinfo["jiage"];
        $data['tnum'] = $pt['tnum'];
        $data['num'] = $pt['tnum']-$pt['num'];
        $data['endtime'] = $pt['endtime'];
        $data['status'] = $pt['zt'];
        if($pt['zt']==0){
            //未到结束时间，过了活动时间；到了结束时间
            if(($pt['endtime']>time() && $pt['cendtime']< $pt['endtime']) || $pt['endtime'] < time()) {
                $data['status'] = 2;
            }
        }
        //参团人员信息
        $data['tuan'] = array();
        $t = DI()->notorm->pintuan_info->select('tuid,uid')->where("pid",$ptinfo['pid'])->order("infoid asc")->fetchall();
        foreach ($t as $item){
            $u = DI()->notorm->user->select('nickname,avatar')->where('uid',$item['uid'])->limit(1)->fetch();
            if($item['tuid']==$item['uid']){
                $tag="团长";
            }else{
                $tag="";
            }
            $data['tuan'][] = array('uid'=>$item['uid'],'nickname'=>$u['nuckname'],'avatar'=>$u['avatar'],'tag'=>$tag);
        }
        //猜你喜欢

        $tk = new Model_Tk();
        $like = $tk->getSearchLists($rs['title'],1,0,0,1,1,10);
        $data['like'] = $like['list'];

        $info = array(
            'title'=>$data['title'],
            'content'=>"还差".$data['num']."人成团拼".$rs["title"].$rs['keyboard'],
            'pic'=>$rs["titlepic"],
            'url'=>DI()->config->get('sys.xsapp.homeurl')."ptshare/?infoid=".$infoid
        );

        $data['share'][0]['weixin'] = array_merge(array('enable'=>"1"),$info);
        $data['share'][1]['timeline'] = array_merge(array('enable'=>"1"),$info);
        $data['share'][2]['qq'] = array_merge(array('enable'=>"1"),$info);
        $data['share'][3]['qzone'] = array_merge(array('enable'=>"1"),$info);
        $data['share'][4]['copy'] = array_merge(array('enable'=>"1"),$info);

        return array('code'=>0,'msg'=>'成功','list'=>$data);

    }


    //获取订单
    public function getCheckOrder($uid,$orderid){
        $rs =  DI()->notorm->pintuan_info->select("orderid,pid,title,jiage as price,beizhu as sku,fk as zt,paytype,paytime")->where('uid = ? and orderid=?',$uid,$orderid)->limit(1)->fetch();
        if($rs && $rs['paytime']){
            $rs['paytime'] = date("Y-m-d H:i:s",$rs['paytime']);
            $tz = $this->CheckTzInfoByPid($rs['pid']);
            $rs['tzinfoid'] = $tz['infoid'];
        }
        return $rs;
    }

    //团长表
    public function getPt($pid){
        return DI()->notorm->pintuan->select("*")->where("pid",$pid)->limit(1)->fetch();
    }

    //获取拼团详情
    public function getPtInfo($infoid){
        return DI()->notorm->pintuan_info->select("*")->where("infoid",$infoid)->limit(1)->fetch();
    }

    //未成团 未结束
    public function CheckPtByUid($uid){
        $time = time();
        return DI()->notorm->pintuan->select("*")->where("uid = $uid and lx=1 and tnum > num and num>0 and endtime >".$time)->limit(1)->fetch();

    }

    //判断用户是否参与此团
    public function CheckPtInfoByUid($uid,$pid){
        return DI()->notorm->pintuan_info->select("*")->where("uid = ? and pid = ?",$uid,$pid)->limit(1)->fetch();
    }

    //获取团长订单
    public function c($pid){
        return DI()->notorm->pintuan_info->select("*")->where("tuid = uid and pid = ?",$pid)->limit(1)->fetch();
    }


    protected function getTableName($id) {
        return 'ecms_pintuan';
    }
}

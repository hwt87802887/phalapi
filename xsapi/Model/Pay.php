<?php

class Model_Pay extends PhalApi_Model_NotORM {

    public function getRecharge(){
        $rs =  DI()->notorm->bookorder_set->select("*")->order('id asc')->fetchAll();
        foreach($rs as $key=>$item){
            $rs[$key]['yuedubi'] = (number_format($item['price'],2)*100)."";
            if($item['is_vip']){
                $rs[$key]['yuedubi']="0";
            }
        }
        return $rs;
    }
    
    public function AddOrder($data){
        return $this->getORM()->insert($data);
    }
    
    public function getOrderByOrderId($orderid){
        return DI()->notorm->bookorder->select("*")->where("orderid",$orderid)->limit(1)->fetch();
    }

    public function getUnPay($uid,$order){
        $user = new Model_User();
        $u = $user->getuserInfo($uid);
        if($u){
            $username = $u['nickname'];
            $time = time();
            $orderid = $order['orderid'];
            $order['balance'] = str_replace(".00","",$order['balance']);
            $description= '亲，您的阅读币订单还未完成支付，现充值'.$order['balance'].'元即送'.$order['yuedubi'].'阅读币，赶紧行动吧!';
            if($order['is_vip']){
                $description= '亲，您的阅读币订单还未完成支付，现充值'.$order['balance'].'元即可全年免费畅读VIP书籍，赶紧行动吧!';
            }
            $data = array(
                'ismember'=>1,
                'userid'=>$uid,
                'username'=>$username,
                'truetime'=>$time,
                'lastdotime'=>$time,
                'havehtml'=>1,
                'title'=>'阅读币订单未支付提醒',
                'newstime'=>$time,
                'channel'=>99,
                'description'=>$description,
                'orderid'=>$orderid
            );
            $rs = DI()->notorm->ecms_news->select('id,userid,orderid,newstime')->where('userid',$uid)->order('id desc')->limit(1)->fetch();
            if(!$rs){
                DI()->notorm->ecms_news->insert($data);
            }else{

                //30分钟内替换原有订单
                if ($time - $rs['newstime'] < 1800) {
                    DI()->notorm->ecms_news->where('id', $rs['id'])->update($data);
                } else {

                    $od = DI()->notorm->ecms_news->select('id,userid,orderid,newstime')->where('orderid',$orderid)->limit(1)->fetch();

                    if($od){
                        DI()->notorm->ecms_news->where('id', $od['id'])->update($data);
                    }else {
                        DI()->notorm->ecms_news->insert($data);
                    }

                }


            }
            return array('code'=>0,'msg'=>'记录成功','list'=>array());

        }else{
            return array('code'=>2,'msg'=>'用户不存在','list'=>array());
        }

    }
    
    

    public function getOrderByOrderId_Pt($orderid){
        return DI()->notorm->pintuan_info->select("*")->where("orderid",$orderid)->limit(1)->fetch();
    }

    public function  getRechargeById($id){
        return DI()->notorm->bookorder_set->select("*")->where("id",$id)->limit(1)->fetch();
    }

    public function getPinTuanById($id){
        return DI()->notorm->ecms_pintuan->select("id,title,yuanjia,price,tnum,num,cstarttime,cendtime,xiaoshi")->where("id",$id)->limit(1)->fetch();
    }

    public function  getPinTuanSku($id){
        $d =  DI()->notorm->ecms_pintuan_data_1->select("shaixuan")->where("id",$id)->limit(1)->fetch();
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
            return $shaixuan2;
        }else{
            return array();
        }
    }

    public function AddPinTuan($data){
        return DI()->notorm->pintuan->insert($data);
    }

    public function AddPinTuanInfo($data){
        return DI()->notorm->pintuan_info->insert($data);
    }

    public  function getUpdatePay($orderid,$data){
        return $this->getORM()->where('orderid',$orderid)->update($data);
    }
    public  function getUpdatePay_Pt($orderid,$data){
        return DI()->notorm->pintuan_info->where('orderid',$orderid)->update($data);
    }


    public function UpdatePinTuan($pid,$data,$isreturn=false){
        $rs = DI()->notorm->pintuan->where('pid',$pid)->update($data);
        if($isreturn){
            $model = new Model_Pt();
            $rs = $model->getPt($pid);
        }
        return $rs;
    }

    public function UpdatePinTuanInfo($pid,$data){
        return DI()->notorm->pintuan_info->where('pid',$pid)->update($data);
    }

    public function CancelOrder($uid,$id){
        return DI()->notorm->pintuan_info->where('uid = ? and id = ? and fk=0',$uid,$id)->update(array('fk'=>-1));
    }

    public function DelPinTuan($pid){
        return DI()->notorm->pintuan->where("pid",$pid)->delete();
    }

    protected function getTableName($id) {
        return 'bookorder';
    }
}

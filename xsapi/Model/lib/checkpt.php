<?php
date_default_timezone_set("Asia/Shanghai");	
require("../../../e/class/connect.php");
require("../../../e/class/db_sql.php");
require("../../../e/class/functions.php");
require("WxPay.Api.php");
require("../log.php");
$link=db_connect(); 
$empire=new mysqlquery();
ini_set("display_errors", "On");
error_reporting(E_ALL & ~E_NOTICE);

$fuli = include("/data/xsapi/Config/fuli.php");
$data = $fuli['fuli'];
$tuanzhang = intval($data['tuanzhang'])-1;
$cantuan = intval($data['cantuan'])-1;
$dhjb = intval($data['dhjb']);//退金币
$time = time();

//定时查询拼团是否成功
$sql = $empire->query("select * from {$dbtbpre}pintuan where lx=1 and zt=0 order by endtime asc");
$no=0;
while($p=$empire->fetch($sql)){
	
	if($p["endtime"]>$p["cendtime"]){
	   $p["endtime"] = $p["cendtime"];
	}
	if($p["tnum"]<=$p["num"]){
		//拼团成功处理
	 	$empire->query("update {$dbtbpre}pintuan set zt=1 where pid='$p[pid]' limit 1");
	 	$empire->query("update {$dbtbpre}pintuan_info set zt=1 where pid='$p[pid]' and fk=1");
	 	$no++;
	}elseif($p["tnum"]>$p["num"] && $p["endtime"]<$time){
	     //参团失败处理	
	   	 $empire->query("update {$dbtbpre}pintuan set zt=2 where pid='$p[pid]'  limit 1");
	 	 $empire->query("update {$dbtbpre}pintuan_info set zt=2 where pid='$p[pid]'  and fk=1");
	}
	
}


//拼团失败退款
$sql=$empire->query("select * from {$dbtbpre}pintuan_info where  (zt=2 or zj=2) and fk=1 limit 20");
while($r = $empire->fetch($sql)){
	//微信退款
	if($r['paytype']=="weixin"){
		if(isset($r["orderid"]) && $r["orderid"] != ""){
			$out_trade_no = $r["orderid"];
			$total_fee = $r["jiage"]*100;
			$refund_fee = $r["jiage"]*100;
			$input = new WxPayRefund();
			$input->SetOut_trade_no($out_trade_no);
			$input->SetTotal_fee($total_fee);
			$input->SetRefund_fee($refund_fee);
			$input->SetOut_refund_no(WxPayConfig::MCHID.date("YmdHis").mt_rand(1000,9999));
			$input->SetOp_user_id(WxPayConfig::MCHID);
			
			if($r['zt']==2){
				$input->SetRefund_desc("趣看看-未成团-退款");
			}elseif($r['zj']==2){
				$input->SetRefund_desc("趣看看-未中奖-退款");
			}
			
			//$input->SetRefund_account('REFUND_SOURCE_RECHARGE_FUNDS');
			$result = WxPayApi::refund($input,60);
			if($result){
				if(strtoupper($result["result_code"])=="SUCCESS"){
					$empire->query("update {$dbtbpre}pintuan_info set fk=4 where orderid='".$out_trade_no."'  and fk=1");
					//成团未中奖
					if($r['zj']){
						if($r['tuid']==$r['uid'] && $tuanzhang>0){
							$money = $tuanzhang * $r['jiage'];
						}
						if($r['tuid']!=$r['uid'] && $cantuan>0){
							$money = $cantuan * $r['jiage'];
						}
						
						if($money){
							$title="拼团退款";
							$description="成团未中奖退款";
							$empire->query("update {$dbtbpre}user set money=money+".$money." where uid=".$r['uid']);
							$empire->query("insert into {$dbtbpre}welfare_log(uid,type,money,cz,title,description,addtime)values('".$r['uid']."',1,'".$money."','+','".$title."','".$description."','".$time."')");
						}
					}
					
				}else{
					if($result['err_code_des']=="订单已全额退款"){
						$empire->query("update {$dbtbpre}pintuan_info set fk=4 where orderid='".$out_trade_no."' and fk=1");
					}else{
						$empire->query("update {$dbtbpre}pintuan_info set fk=5,msg='".$result['err_code_des']."' where orderid='".$out_trade_no."' and fk=1");
					}
				}
			}
		}
		
	}

	//支付宝退款
	if($r['paytype']=="alipay"){
		if(isset($r["orderid"]) && $r["orderid"] != ""){
			$arry["WIDTRout_trade_no"] = $r["orderid"];
			$arry["WIDTRrefund_amount"] = $r["jiage"];
			if($r['zt']==2){
				$arry["WIDTRrefund_reason"] = "趣看看-未成团-退款";
			}elseif($r['zj']==2){
				$arry["WIDTRrefund_reason"] = "趣看看-未中奖-退款";
			}
			
			$result = https_request("http://app.qqyou.com/open/alipay/pagepay/refundpt.php",$arry);
		
			if($result){
				$result = json_decode($result,true);
				if(strtoupper($result["msg"])=="SUCCESS"){
					$empire->query("update {$dbtbpre}pintuan_info set fk=4 where orderid='".$r["orderid"]."'  and fk=1");
					//成团未中奖
					if($r['zj']){
						if($r['tuid']==$r['uid'] && $tuanzhang>0){
							$money = $tuanzhang * $r['jiage'];
						}
						if($r['tuid']!=$r['uid'] && $cantuan>0){
							$money = $cantuan * $r['jiage'];
						}
						
						if($money){
							$title="拼团退款";
							$description="成团未中奖退款";
							$empire->query("update {$dbtbpre}user set money=money+".$money." where uid=".$r['uid']);
							$empire->query("insert into {$dbtbpre}welfare_log(uid,type,money,cz,title,description,addtime)values('".$r['uid']."',1,'".$money."','+','".$title."','".$description."','".$time."')");
						}
					}
					
				}else{
					$empire->query("update {$dbtbpre}pintuan_info set fk=5,msg='".$info['err_code_des']."' where orderid='".$out_trade_no."' and fk=1");
				}
			}
		}
	}
}


function https_request($url,$data=null){
        $curl = curl_init();
        curl_setopt($curl,CURLOPT_URL,$url);
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,FALSE);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,FALSE);
        if(!empty($data)){
            curl_setopt($curl,CURLOPT_POST,1);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        }
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,60);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
}

db_close();
$empire=null;
 echo "所有更新完毕<script>
						closewin();
						function closewin(){
							var browserName=navigator.appName;
							if(browserName=='Netscape'){
								var opened=window.open('about:blank','_self');
								opened.opener=null;
								opened.close();
							}else if(browserName=='Microsoft Internet Explorer'){
								window.opener=null;
								window.open('','_self');
								window.close();
							}
						}
						</script>";
				exit();

?>
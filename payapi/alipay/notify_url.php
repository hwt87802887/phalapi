<?php
require('../../e/class/connect.php');
require('../../e/class/db_sql.php');
require('../../e/class/functions.php');
require('../../e/class/gd.php');
$link=db_connect();                //创立一个连接
$empire=new mysqlquery();
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */

require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

//logResult($verify_result);
if($verify_result) {//验证成功
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//请在这里加上商户的业务逻辑程序代

	
	//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
    //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
	//商户订单号

	$out_trade_no = $_POST['out_trade_no'];
	
	$price = $_POST['price'];

	//支付宝交易号

	$trade_no = $_POST['trade_no'];
	$buyer_email = $_POST['buyer_email'];
	$buyer_id = $_POST['buyer_id'];
	//交易状态
	$trade_status = $_POST['trade_status'];


    if($_POST['trade_status'] == 'TRADE_FINISHED') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
		//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
		
		echo "success";		//请不要修改或删除
		$zhuangtai="交易完成";
		$cishu=1;

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }
    else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//付款完成后，支付宝系统发送该交易状态通知
		//请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
		
		echo "success";		//请不要修改或删除
		$zhuangtai="交易完成";
		$cishu=1;

        //调试用，写文本函数记录程序运行情况是否正常
        //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
    }

	//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
    
    $empire->query("update pw_alipay set zhanghao='$buyer_email',alipayid='$buyer_id',zhuangtai='$zhuangtai',cishu='$cishu' where did='$out_trade_no'");

	if($cishu==1)
	{
		$empire->query("update phome_dingdan set zt='0' where did='$out_trade_no' or didnum='$out_trade_no'");
		
			$r=$empire->fetch1("select jfdh,yhqid,yhprice from phome_dingdan where did='$out_trade_no' or didnum='$out_trade_no'");
			if($r[jfdh]>0)
			{
				$empire->query("update pw_memberdata set currency=currency-".$r[jfdh]." where uid='$winduid'");
				$ip=$_SERVER["REMOTE_ADDR"];
				
				$adddate=time();
				$descrip='[b]积分兑换[/b] 兑换成功。扣除积分：[b]银元[/b]，影响：-'.$r[jfdh].'。';
				$ctype='currency';
				$affect=-$r[jfdh];
				$logtype='duihuan';

				$empire->query("insert into pw_creditlog(uid,username,ctype,affect,adddate,logtype,ip,descrip) values('$winduid','$windid','$ctype','$affect','$adddate','$logtype','$ip','$descrip');");
			}
			
			if($r[yhqid]>0 && $r[yhprice]!=0)
			{
			  $empire->query("update phome_coupons_user set status=1 where id=".$r[yhqid]);	
			}
		
	}

    
  
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
else {
    //验证失败
    echo "fail";

    //调试用，写文本函数记录程序运行情况是否正常
    //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
}
?>
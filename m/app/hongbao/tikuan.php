<?php
require_once("./includes/config.php");
require_once('./pay/WxXianjinHelper.php');

session_start();
define('ROOT_PATH',dirname(__FILE__));
define('DS',DIRECTORY_SEPARATOR);
	function tikuan($openid,$amount,$DES){
		$commonUtil = new CommonUtil();
		$wxHongBaoHelper = new WxHongBaoHelper();
		$actioncode=0;//返回值
		$amount *= 100; //单位为分，承以100
		//=======================给客户转钱全过程
		$wxHongBaoHelper->setParameter("nonce_str",$commonUtil->create_noncestr());//随机字符串
		$wxHongBaoHelper->setParameter("mch_billno",MCHID.date('His').rand(10000,99999));//交易号
		$wxHongBaoHelper->setParameter("mch_id",MCHID);
		$wxHongBaoHelper->setParameter("wxappid",APPID);
		$wxHongBaoHelper->setParameter("nick_name", '平台');//提供方名称
		$wxHongBaoHelper->setParameter("send_name", '提现红包');//红包发送者名称
		$wxHongBaoHelper->setParameter("re_openid", $openid);	//收红包openid
		$wxHongBaoHelper->setParameter("total_amount", $amount);//付款金额，单位分
		$wxHongBaoHelper->setParameter("min_value", $amount);//最小红包金额，单位分
		$wxHongBaoHelper->setParameter("max_value", $amount);//最大红包金额，单位分
		$wxHongBaoHelper->setParameter("total_num", 1);//红包収放总人数
		$wxHongBaoHelper->setParameter("wishing",$DES);//红包祝福诧
		$wxHongBaoHelper->setParameter("client_ip", '127.0.0.1');//调用接口的机器 Ip 地址
		$wxHongBaoHelper->setParameter("act_name", '红包活动');//活劢名称
		$wxHongBaoHelper->setParameter("remark", '快来抢！');//备注信息
		$postXml = $wxHongBaoHelper->create_hongbao_xml();
		$url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/sendredpack';//接口，POST，需要证书
		$responseXml = $wxHongBaoHelper->curl_post_ssl($url,$postXml);//发送数据，并接收返回数据
		//echo $responseXml;
		$responseObj = simplexml_load_string($responseXml);//分解返回数据
		if( $responseObj->result_code=="SUCCESS"&&$responseObj->return_code=="SUCCESS")//付款成功，往红包记录表中插入一条数据
		{
			$actioncode=1;
			$msg['s']=1;
			$msg['r']="";
		}else{//返回数据不正常的时候
			$wxHongBaoHelper->create_file("log.txt","",$responseXml);//记录日志
			$msg['s']=0;
			$msg['r']=(string)$responseObj->return_msg;
		}
		return $msg;
		mysql_close();
	}
	$sss=tikuan('ogbVys7Wqx2uti1cg5lRLaRJYtbw',1,'提现');  //转账结果
/**	
if(isset($_POST['submit'])){
	$userid=$_POST['user_id'];	//用户ID	
	$sql = "SELECT * FROM `gz_user` WHERE user_id='$userid' LIMIT 1";
	$query = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($query);
	$mymoney = $row['mymoney'];	//用户余额
	$wechaid = $row['wecha_id'];	//用户wechaid
	if($_POST['postmoney']>$mymoney){
		echo "<script>alert('对不起，余额不足')</script>";
		echo "<script>history.go(-1)</script>";
		exit();
	}
	$sql = "SELECT * FROM `gz_userconfig` WHERE type='basic' LIMIT 1";
	$query = mysql_query($sql) or die(mysql_error());
	$row = mysql_fetch_array($query);
	$dixin360 = $row['dixin360'];	//最少提款额
	if($_POST['postmoney']<$dixin360){
		echo "<script>alert('".$dixin360."元以上才可以提款哦！')</script>";
		echo "<script>history.go(-1)</script>";
		exit();
	}
	if($mymoney<$dixin360&&$mymoney>0){
		echo "<script>alert('余额不足！')</script>";
		echo "<script>history.go(-1)</script>";
		exit();
	}
	$sss=tikuan($wechaid,$_POST['postmoney'],'多米商城提现');  //转账结果
	
	if($sss['s']==1){
		$dd['paytime'] = mktime();
		$dd['state'] = 1;
		//扣除用户余额
		$mymoney = $mymoney-$_POST['postmoney'];
		$sql = "UPDATE `gz_user` set `mymoney`=".$mymoney. " WHERE `user_id`='".$userid."'";
		$query = mysql_query($sql) or die(mysql_error());
		//更新财务记录
		$sql = "INSERT INTO `gz_user_money_change` set `time`='".time()."',`changedesc`='用户提现：".$_POST['postmoney']."元',`money`='-".$_POST['postmoney']."',`uid`=".$userid.",`type`='system'";
		$query = mysql_query($sql) or die(mysql_error());
		echo "<script>alert('提现成功！已入账，请查看钱包')</script>";
		echo "<script>history.go(-1)</script>";
	}else{
		echo "<script>alert('提现出错，请联系客服人员。错误：".$sss['r']."')</script>";
		echo "<script>history.go(-1)</script>";
		exit();
	}

}
**/
?>
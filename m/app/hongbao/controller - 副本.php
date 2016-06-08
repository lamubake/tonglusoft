<?php
require_once(SYS_PATH.'\m\app\hongbao\pay\WxXianjinHelper.php');

class HongbaoController extends Controller{
	//构造函数，自动新建对象
 	function  __construct() {
		/*
		*构造函数，自动新建session对象
		*/
		$this->js(array('jquery.json-1.3.js','user.js?v=v1'));
	}
	
	function checked_login(){
		$uid = $this->Session->read('User.uid');
		if(!($uid>0)){ $this->jump(ADMIN_URL.'user.php?act=login'); exit;}
		return $uid;
	}

	function index(){
		$uid = $this->checked_login();
		$rt = $this->personinfo($uid);
		if(!isset($_GET['cengji'])) exit('系统错误');
		$cengji = $_GET['cengji'];
		//要发货的ORDERID
		$sql = "SELECT `order_id` FROM `{$this->App->prefix()}goods_sn` WHERE pid ='{$uid}'  AND cengji='{$cengji}' AND `money`>0 AND `is_use`=1 ORDER BY `order_id` DESC";
		$ids = $this->App->findcol($sql);
		//将相同orderid的数组放在一起
		$sn = array();
		$i = 0;
		foreach($ids as $key=>$oid){
			$sql = "SELECT * FROM `{$this->App->prefix()}goods_sn` WHERE `order_id`='{$oid}' AND pid ='{$uid}' AND cengji='{$cengji}' ORDER BY `money` DESC";
			$sn[$i] = $this->App->find($sql);
			$i++;
		}
		
		if(!defined(NAVNAME)) define('NAVNAME', "会员中心");	
		$this->set('cengji',$cengji);
		$this->set('rt',$rt);
		$this->set('sn',$sn);
		$this->title("红包管理中心".' - '.$GLOBALS['LANG']['site_name']);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->set('mubanid',$GLOBALS['LANG']['mubanid']);		
		$this->template($mb.'/hongbao');
	}
	
	function fahuo(){
		$uid = $this->checked_login();
		if(!isset($_GET['oid'])) exit;
		$oid = $_GET['oid'];
		$result = $this->fahuohuo($oid);
		if($result[0]){
			echo "<script>alert('".$result[1]."')</script>";
			echo "<script>window.location.href='/m/user.php';</script>";
			exit;
		}else{
			$result = $this->fahuohuo($oid);	//再重新发一次
		}
		//再重新发一次
		if($result[0]){
			echo "<script>alert('".$result[1]."')</script>";
			echo "<script>window.location.href='/m/user.php';</script>";
			exit;
		}else{
			echo "<script>alert('".$result[1]."')</script>";
			echo "<script>history.go(-1)</script>";
			exit;
		}
		
	}
	
	function fahuohuo($oid){
		$uid = $this->checked_login();
		//判断合法性
		$sql = "SELECT `id` FROM `{$this->App->prefix()}goods_sn` WHERE `order_id`=".$oid." AND `pid`=".$uid;
		$a = $this->App->find($sql);
		if(!isset($a)||$a==''||empty($a)){
			return array(false,'无参数或错误');
			exit('无参数或错误');
		}
		$user = $this->App->findrow("SELECT `wecha_id`,`nickname`,`mymoney` FROM `{$this->App->prefix()}user` WHERE `user_id`=".$uid." LIMIT 1");
	
//发货通知
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_sn` WHERE `order_id`='".$oid."' AND `pid`=".$uid;
		$sn = $this->App->find($sql);
		//所有验证码连起来
		$snpass='';
		$money = 0;
		foreach($sn as $key=>$s){
			$snpass=$snpass.'\n'.$s['goods_pass'];
			if($s['money']>0){
				$money=$s['money'];		//订单佣金
				//$money = $money - $s['lmoney'];
				$snid = $s['id'];
			}
		}
//END------发货通知

//提款发红包
		$this->get_app_info();	//定义APPID等
		$DES=$GLOBALS['LANG']['site_name'];
		//如果money>200时  存到用户余额里
		$topm = 200;
		if($money>$user['mymoney']) return array(false,'系统错误，系统显示您的余额不足');
		if($money<=0) return array(false,$money.'系统错误，请联系管理员');
		
		if($money>$topm){
			$mymoney = $money - $topm;	//剩余的金额
			//开始提款 200
			$result = $this->tikuan($user['wecha_id'],$topm,$DES);
			if($result['s']==1){
				//开始发货通知
				$s_wecha_id = $this->App->findvar("SELECT `wecha_id` FROM `{$this->App->prefix()}user` WHERE `user_id`=".$sn[0]['uid']." LIMIT 1");
				$str = array('openid'=>$s_wecha_id,'appid'=>'','appsecret'=>'','snpass'=>$snpass,'p_name'=>$user['nickname']);
				$this->action('api','sendtxt',$str,'fahuo');
				//更改发货状态
				//$this->App->query("UPDATE `{$this->App->prefix()}goods_sn` SET `is_use`=2 WHERE `order_id`=".$oid." AND `pid`=".$uid);
				//更新余额及佣金总数
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `mymoney`=`mymoney`-$topm WHERE `user_id`=".$uid);
				//增加已提款
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `money_ucount`=`money_ucount`+$topm WHERE `user_id`=".$uid);
				//更新已经领的钱
				$this->App->query("UPDATE `{$this->App->prefix()}goods_sn` SET `money`=`money`-$topm,`lmoney`=`lmoney`+$topm WHERE `id`=".$snid);
				
				//更新到提款记录
				$this->App->query("INSERT `{$this->App->prefix()}user_money_change` SET `time`=".mktime().",`changedesc`='红包提现',`money`=-$topm,`uid`=$uid,`buyuid`=".$sn[0]['uid'].",`order_sn`='".$oid."',`order_id`=$oid");
				
				return array(true,'发货成功，请及时领取红包。\n由于微信限制，只能领取最高200元。1分钟后再发货一次');
			}else{
				return array(false,'提现出错，请重试，若还是失败请联系客服人员。错误：'.$result['r']);
			}
		}elseif($money<=$topm){
			$result = $this->tikuan($user['wecha_id'],$money,$DES);
			if($result['s']==1){
				//开始发货通知
				$s_wecha_id = $this->App->findvar("SELECT `wecha_id` FROM `{$this->App->prefix()}user` WHERE `user_id`=".$sn[0]['uid']." LIMIT 1");
				$str = array('openid'=>$s_wecha_id,'appid'=>'','appsecret'=>'','snpass'=>$snpass,'p_name'=>$user['nickname']);
				$this->action('api','sendtxt',$str,'fahuo');
				//更改发货状态
				$this->App->query("UPDATE `{$this->App->prefix()}goods_sn` SET `is_use`=2 WHERE `order_id`=".$oid." AND `pid`=".$uid);
				//更新余额及佣金总数
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `mymoney`=`mymoney`-$money WHERE `user_id`=".$uid);
				//增加已提款
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `money_ucount`=`money_ucount`+$money WHERE `user_id`=".$uid);
				//更新已经领的钱
				$this->App->query("UPDATE `{$this->App->prefix()}goods_sn` SET `money`=`money`-$money,`lmoney`=`lmoney`+$money WHERE `id`=".$snid);
				//更新到提款记录
				$this->App->query("INSERT `{$this->App->prefix()}user_money_change` SET `time`=".mktime().",`changedesc`='红包提现',`money`=-$money,`uid`=$uid,`buyuid`=".$sn[0]['uid'].",`order_sn`='".$oid."',`order_id`=$oid");
				return array(true,'发货成功，请及时领取红包。');
			}else{
				return array(true,'提现出错，请联系客服人员。错误：'.$result['r']);
			}
		}
		
	}
	
	//余额提款
/**	
	function yueti(){
		exit('余额提款功能已关闭');
		$uid = $this->checked_login();
		//判断合法性
		$user = $this->App->findrow("SELECT `mymoney`,`wecha_id` FROM `{$this->App->prefix()}user` WHERE `user_id`=".$uid);
		$mymoney = $user['mymoney'];
		$wecha_id = $user['wecha_id'];
		if(!isset($mymoney)||$mymoney==''||empty($mymoney)||$mymoney<=0){
			echo "<script>alert('余额不足，赶快推广起来吧！')</script>";
			echo "<script>window.location.href='/m/user.php';</script>";
			exit();
		}
//提款发红包
		$this->get_app_info();	//定义APPID等
		$DES="观复红包提现";
		//如果money>200时  存到用户余额里
		$topm = 200;
		if($mymoney>$topm){
			//开始提款 200
			$result = $this->tikuan($wecha_id,$topm,$DES);
			if($result['s']==1){
				//更新余额及佣金总数
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `mymoney`=`mymoney`-$topm WHERE `user_id`=".$uid);
				//增加已提款
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `money_ucount`=`money_ucount`+$topm WHERE `user_id`=".$uid);
				//更新到提款记录
				$this->App->query("INSERT `{$this->App->prefix()}user_money_change` SET `time`=".mktime().",`changedesc`='余额红包提现',`money`=-$topm,`uid`=$uid,`order_sn`='yueti',`order_id`=0");
				echo "<script>alert('发货成功，请及时领取红包。\n由于微信限制，只能领取最高200元。剩余的请在用户中心一分钟后领取。')</script>";
				echo "<script>window.location.href='/m/user.php';</script>";
			}else{
				echo "<script>alert('提现出错，请联系客服人员。错误：".$result['r']."')</script>";
				echo "<script>history.go(-1)</script>";
				exit();
			}
		}elseif($mymoney<=$topm){
			$result = $this->tikuan($user['wecha_id'],$mymoney,$DES);
			if($result['s']==1){
				//更新余额及佣金总数
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `mymoney`=0  WHERE `user_id`=".$uid);
				//增加已提款
				$this->App->query("UPDATE `{$this->App->prefix()}user` SET `money_ucount`=`money_ucount`+$mymoney WHERE `user_id`=".$uid);
				//更新到提款记录
				$this->App->query("INSERT `{$this->App->prefix()}user_money_change` SET `time`=".mktime().",`changedesc`='余额红包提现',`money`=-$mymoney,`uid`=$uid,`order_sn`='yueti',`order_id`=0");
				echo "<script>alert('提现成功，请及时领取红包。')</script>";
				echo "<script>window.location.href='/m/user.php';</script>";
			}else{
				echo "<script>alert('提现出错，请联系客服人员。错误：".$result['r']."')</script>";
				exit();
			}
		}
		
		
	}
**/
	function personinfo($uid){
		$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id ='{$uid}' AND active='1' LIMIT 1";
		$rt['userinfo'] = $this->App->findrow($sql);
		$rank = $this->Session->read('User.rank');		
		$wecha_id2 = $this->Session->read('User.wecha_id');
		$wecha_id_new = $wecha_id2;		
		$sql = "SELECT tb2.level_name FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_level` AS tb2 ON tb1.user_rank=tb2.lid WHERE tb1.user_id='$uid'";
		$rt['userinfo']['level_name'] = $this->App->findvar($sql);  //
		return $rt;
	}
	//获取APPID等信息
	function get_app_info(){
		$sql = "SELECT `pay_config` FROM `".$this->App->prefix()."payment` WHERE `pay_id`=4";
		$pay_config = $this->App->findvar($sql);
		$configr = unserialize($pay_config);
		$rt = array();
		$rt['MCHID'] = $configr['pay_no'];
		$rt['PARTNERKEY'] = $configr['pay_code'];
		$r = $this->action('common','_get_appid_appsecret');
		$rt['appid'] = $r['appid'];
		$rt['appsecret'] = $r['appsecret'];
		define('APPID',$rt['appid']);//APPID
		define('APPSECRET',$rt['appsecret']);//APPSECRET
		define('MCHID',$rt['MCHID']);//商户号
		define('PARTNERKEY',$rt['PARTNERKEY']);//秘钥
		define('ROOT_PATH',dirname(__FILE__));
		define('DS',DIRECTORY_SEPARATOR);
		return $rt;
	}
	
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
		$wxHongBaoHelper->setParameter("send_name", $DES);//红包发送者名称
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
	}	
	
	
}
?>
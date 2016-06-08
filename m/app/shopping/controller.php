<?php
class ShoppingController extends Controller{
    /*
     * @Photo Index
     * @param <type> $page
     * @param <type> $type
     */
 	function  __construct() {
		/*
		*构造函数
		*/
		$this->js(array('jquery.json-1.3.js','goods.js','user.js'));
		$this->css(array('comman.css'));
	}
	
	/*析构函数*/
	function  __destruct() {
        unset($rt);
    }
	////////////////////////////////////////////////////////////////////
	//一个商品对应多个收货地址
	function ajax_get_address($data=array()){
		$province = $data['province'];
		$city = $data['city'];
		$district = $data['district'];
		$resslist = $this->action('user','get_regions',1);  //获取省列表
		$dbress = array();
		if($province>0){
			$dbress['city'] = $this->action('user','get_regions',2,$province); 
		}
		if($city>0){
			$dbress['district'] = $this->action('user','get_regions',3,$city); 
		}
		$dbtype['province'] = $province;
		$dbtype['city'] = $city;
		$dbtype['district'] = $district;
		$this->set('dbtype',$dbtype);
		$this->set('dbress',$dbress);
		$this->set('resslist',$resslist);
		$this->set('goods_id', $data['gid']);
		echo $this->fetch('addressmore',true);
		exit;
	}
	
	function ajax_jisuanprice($data=array()){
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";//配置信息
		$rts = $this->App->findrow($sql);
		
		$gid = $data['gid'];
		$num = $data['num'];
		$goodslist = $this->Session->read('cart');
		$shop_price = $goodslist[$gid]['shop_price'];
		$pifa_price = $goodslist[$gid]['pifa_price'];
		
		$issubscribe = $this->Session->read('User.subscribe'); 
		$guanzhuoff = $rts['guanzhuoff'];
		$address3off = $rts['address3off'];
		$address2off = $rts['address2off'];
		if($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0){
			$pifa_price = ($guanzhuoff/100) * $pifa_price;
		}
		if($num >= 2 && $address2off < 101 && $address2off > 0){
			$pifa_price = ($address2off/100) * $pifa_price;
		}
		if($num >= 3 && $address3off < 101 && $address3off > 0){
			$pifa_price = ($address3off/100) * $pifa_price;
		}
		
		echo $pifa_price; exit;
	}
	
	//原始下单版本
	function confirm_daigou(){
		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL.'user.php?act=login',0,'请先登录！'); exit;}
		$order_sn = date('Y',mktime()).mktime();
		
		if(isset($_POST)&&!empty($_POST)){
			$totalprice = $_POST['totalprice'];
			if($totalprice < 0){
				$this->jump(ADMIN_URL,0,'非法提交');exit;
			}
			$pay_id = $_POST['pay_id'];
			$pay_name = $this->App->findvar("SELECT pay_name FROM `{$this->App->prefix()}payment` WHERE pay_id='$pay_id' LIMIT 1");
			$shipping_id = $_POST['shipping_id'];
			$shipping_name = $this->App->findvar("SELECT shipping_name FROM `{$this->App->prefix()}shipping` WHERE shipping_id='$shipping_id' LIMIT 1");
			$postscript = $_POST['postscript'];
			$orderdata['type']  = 3;
			
			$goodslist = $this->Session->read('cart');
			if(empty($goodslist)){
				$this->jump(ADMIN_URL,0,'购物车为空');exit;
			}
			//添加订单表
			$orderdata = array();
			$orderdata['pay_id'] = $pay_id;
			$orderdata['shipping_id'] = $shipping_id;
			$orderdata['pay_name'] = $pay_name;
			$orderdata['shipping_name'] = $shipping_name;
			$orderdata['order_sn'] = $order_sn;
			$orderdata['user_id'] = $uid;
			$parent_uid = $this->App->findvar("SELECT parent_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid='$uid' LIMIT 1");
			$orderdata['parent_uid'] = $parent_uid >0 ? $parent_uid : '0';
			$orderdata['postscript'] = $postscript;
			$orderdata['type']  = 3;
			$orderdata['goods_amount'] = $totalprice;
			$orderdata['order_amount'] = $totalprice;
			$orderdata['add_time'] = mktime();
			$this->App->insert('goods_order_info_daigou',$orderdata);
			$orderid = $this->App->iid();
			if($orderid > 0) foreach($goodslist as $row){
				$gid = $row['goods_id'];
				
				$consignees = $_POST['consignee'][$gid];
				$numbers = $_POST['goods_number'][$gid];
				$moblies = $_POST['moblie'][$gid];
				$provinces = $_POST['province'][$gid];
				$citys = $_POST['city'][$gid];
				$districts = $_POST['district'][$gid];
				$addresss = $_POST['address'][$gid];
				if(empty($consignees)) continue;
				
				
				//添加订单商品表
				$ds = array();
				$ds['order_id'] = $orderid;
				$ds['goods_id'] = $gid;
				$ds['brand_id'] = $row['brand_id'];
				$ds['goods_name'] = $row['goods_name'];
				$ds['goods_thumb'] = $row['goods_thumb'];
				$ds['goods_bianhao'] = $row['goods_bianhao'];
				$ds['goods_unit'] = $row['goods_unit'];
				$ds['goods_sn'] = $row['goods_sn'];
				$ds['market_price'] = $row['shop_price'];
				$ds['goods_price'] = $row['pifa_price'];
				if(!empty($row['spec'])) $ds['goods_attr'] = implode("、",$row['spec']);
				$this->App->insert('goods_order_daigou',$ds);
				$rec_id = $this->App->iid();
			
				//添加订单地址表
				if($rec_id > 0){
					foreach($consignees as $k=>$consignee){
						$dd = array();
						$dd['consignee'] = $consignee;
						$dd['goods_number'] = $numbers[$k];
						$dd['moblie'] = $moblies[$k];
						$dd['province'] = $provinces[$k];
						$dd['city'] = $citys[$k];
						$dd['district'] = $districts[$k];
						$dd['address'] = $addresss[$k];
						$dd['rec_id'] = $rec_id;
						$this->App->insert('goods_order_address',$dd);
					}
				}
				
			}
		}
		$this->Session->write('cart',null);
		$this->jump(ADMIN_URL.'mycart.php?type=pay&oid='.$orderid);exit;
		
		exit;
	}
	
	function pay(){
		$this->action('common','checkjump');
		if(!defined(NAVNAME)) define('NAVNAME', "在线支付");		 
		$oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
		if(!($oid > 0)){
			$this->jump(ADMIN_URL);exit;
		}
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE order_id='$oid' LIMIT 1";
		$orderinfo = $this->App->findrow($sql);
		if(empty($orderinfo)){
			$this->jump(ADMIN_URL);exit;
		}
		
		$sql = "SELECT tb1.*,SUM(tb2.goods_number) AS numbers FROM `{$this->App->prefix()}goods_order_daigou` AS tb1 LEFT JOIN `{$this->App->prefix()}goods_order_address` AS tb2 ON tb1.rec_id = tb2.rec_id WHERE tb1.order_id='$oid' GROUP BY tb2.rec_id";
		$ordergoods = $this->App->find($sql);

		$this->set('ordergoods',$ordergoods);
		$this->set('orderinfo',$orderinfo);
		$this->template('order_pay');
	}
	
	function pay2(){
		$this->action('common','checkjump');
		if(!defined(NAVNAME)) define('NAVNAME', "在线支付");		 
		$oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
		if(!($oid > 0)){
			$this->jump(ADMIN_URL);exit;
		}
		$sql = "SELECT tb1.*,tb2.region_name AS province,tb3.region_name AS city,tb4.region_name AS district FROM `{$this->App->prefix()}goods_order_info` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
		$sql .=" WHERE tb1.order_id='$oid'";	
		$rt['orderinfo'] = $this->App->findrow($sql);
		if(empty($rt['orderinfo'])){
			$this->jump(ADMIN_URL);exit;
		}
		
		$sql= "SELECT * FROM `{$this->App->prefix()}goods_order` WHERE order_id='$oid' ORDER BY goods_id";
		$rt['goodslist'] = $this->App->find($sql);
		
		//我的余额
		$uid = $this->Session->read('User.uid');
		if($uid > 0){
			$sql = "SELECT mymoney FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
			$rt['mymoney'] = $this->App->findvar($sql);
		}else{
			$rt['mymoney'] = 0;
		}
		
		//支付方式
		$sql = "SELECT * FROM `{$this->App->prefix()}payment` WHERE enabled='1'";
		$rt['paymentlist'] = $this->App->find($sql);
		
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/shopping_order_pay');
	}
	
	function ajax_update_payid($rt=array()){
		$payid = $rt['payid'];
		$oid = $rt['oid'];
		if($payid > 0 && $oid >0){
		$pay_name = $this->App->findvar("SELECT pay_name FROM `{$this->App->prefix()}payment` WHERE pay_id='$payid' LIMIT 1");
		$this->App->update('goods_order_info',array('pay_id'=>$payid,'pay_name'=>$pay_name),'order_id',$oid);
		}
	}
	//快速支付
	function fastpay(){
		$oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
		if(!($oid > 0)){
			$this->jump(ADMIN_URL,0,'意外错误');exit;
		}
		$uid = $this->Session->read('User.uid');
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE pay_status = '0' AND order_id='$oid' LIMIT 1";
		$rt = $this->App->findrow($sql);
		
		if(empty($rt)){
			$this->jump(ADMIN_URL,0,'非法支付提交！'); exit;
		}
		
		$rts['pay_id'] = $rt['pay_id'];
		$rts['order_sn'] = $rt['order_sn'];
		$rts['order_amount'] = $rt['order_amount'];
		$rts['logistics_fee'] = $rt['shipping_fee'];
		$userredd = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}systemconfig` WHERE type='basic' LIMIT 1");
		$rts['address'] = $userredd['company_url'];
			
		$this->_alipayment($rts);
		unset($rt);
		exit;
	}
	
	//快速支付
	function fastpay2(){
		$oid = isset($_GET['oid']) ? $_GET['oid'] : 0;
		if(!($oid > 0)){
			$this->jump(ADMIN_URL,0,'意外错误');exit;
		}
		$uid = $this->Session->read('User.uid');
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info` WHERE pay_status = '0' AND order_id='$oid' LIMIT 1";
		$rt = $this->App->findrow($sql);
		
		if(empty($rt)){
			$this->jump(ADMIN_URL,0,'非法支付提交！'); exit;
		}
		
		$rts['pay_id'] = $rt['pay_id'];
		$rts['order_sn'] = $rt['order_sn'];
		$rts['order_amount'] = $rt['order_amount'];
		$rts['logistics_fee'] = $rt['youfei'];
		$userredd = $this->App->findrow("SELECT * FROM `{$this->App->prefix()}systemconfig` WHERE type='basic' LIMIT 1");
		$rts['address'] = $userredd['company_url'];
			
		$this->_alipayment($rts);
		unset($rt);
		exit;
	}
	
	function ajax_remove_cargoods($data=array()){
		$gid = $data['gid'];
		$uid = $this->Session->read('User.uid');
		if(!empty($gid)){
			$cartlist = $this->Session->read('cart');
			if(isset($cartlist[$gid])){ $this->Session->write("cart.{$gid}",null);}
			
			$useradd = $this->Session->read('useradd'); 
			if(isset($useradd[$gid])){ $this->Session->write("useradd.{$gid}",null);}
		}
		//返回总价
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";//配置信息
		$rts = $this->App->findrow($sql);
		$sql = "SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
		$issubscribe = $this->App->findvar($sql); 
		$guanzhuoff = $rts['guanzhuoff'];
		$address3off = $rts['address3off'];
		$address2off = $rts['address2off'];
		$prices = 0;
		$cartlist = $this->Session->read('cart');
		
		$off = 1;
		if($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0){ //关注折扣
			$off = ($guanzhuoff/100);
		}
		$counts = 0;
		foreach($cartlist as $k=>$row){
			$counts += $row['number'];
		}
		if($issubscribe == '1' && $counts >= 2 && $address2off < 101 && $address2off > 0){
			$off = ($address2off/100) * $off; //相对关注再折扣
		}
		if($issubscribe == '1' && $counts >= 3 && $address3off < 101 && $address3off > 0){
			$off = ($address3off/100) * $off; //相对关注再折扣
		}
		
		foreach($cartlist as $k=>$row){
			$prices += format_price($row['pifa_price'] * $off)*$row['number'];
		}
		echo format_price($prices);
	}
	
	/////////////////////////////////////////////////////////////////////////
	
	function _get_payinfo($id=0){
		if($id=='4'){ //微信支付
			$rt = $this->App->findrow("SELECT `pay_config` FROM `".$this->App->prefix()."payment` WHERE `pay_id`='$id' LIMIT 1");
			
/*			$appid = $this->Session->read('User.appid');
			if(empty($appid)) $appid = isset($_COOKIE[CFGH.'USER']['APPID']) ? $_COOKIE[CFGH.'USER']['APPID'] : '';
			$appsecret = $this->Session->read('User.appsecret');
			if(empty($appsecret)) $appsecret = isset($_COOKIE[CFGH.'USER']['APPSECRET']) ? $_COOKIE[CFGH.'USER']['APPSECRET'] : '';
			if(empty($appid) || empty($appsecret)){
				$sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` WHERE id='1' LIMIT 1";
				$rts = $this->App->findrow($sql);
				$this->Session->write('User.appid',$rt['appid']);
				setcookie(CFGH.'USER[APPID]', $rt['appid'], mktime() + 3600*24);
				$this->Session->write('User.appsecret',$rt['appsecret']);
				setcookie(CFGH.'USER[APPSECRET]', $rt['appsecret'], mktime() + 3600*24);
			}else{
				$rts['appid'] = $appid;
				$rts['appsecret'] = $appsecret;
			}*/
			
			$sql = "SELECT appid,appsecret FROM `{$this->App->prefix()}wxuserset` ORDER BY id DESC LIMIT 1";
			$rts = $this->App->findrow($sql);
			$rt['appid'] = $rts['appid'];
			$rt['appsecret'] = $rts['appsecret'];
		}else{
			$rt = $this->App->findvar("SELECT `pay_config` FROM `".$this->App->prefix()."payment` WHERE `pay_id`='$id'");
		}
		return $rt;
	}
	
	//在线预约付款状态改变
	function baoming_pay_successs_tatus($order_sn=''){
		//改变状态
		$dd = array();
		$dd['pay_status'] = '1';
		$dd['pay_time'] = mktime();
		$this->App->update('cx_baoming_order',$dd,'order_sn',$order_sn);
		
		//开通分销
		$sql = "SELECT openfx_baoming FROM `{$this->App->prefix()}userconfig` WHERE type='basic' LIMIT 1";//用户配置信息
		$openfx_baoming = $this->App->findvar($sql);
		
		if($openfx_baoming=='1'){ 
			$uid = $this->App->findvar("SELECT user_id FROM `{$this->App->prefix()}cx_baoming_order` WHERE order_sn='$order_sn' LIMIT 1");
			if($uid > 0){
				$newrank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
				if($newrank=='1'){
					$this->App->update('user',array('user_rank'=>'12'),'user_id',$uid);
					
					$this->update_daili_tree($uid);//更新代理关系
				}
			}
		}
		exit;	
	}
	//分佣金
	function fenyongjin($uid,$p,$moeys,$order_amount,$cengji){
		//购买用户
		$ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
		$nickname = $ni['nickname'];
		$thismonth = date('Y-m-d',mktime());
		$thism = date('Y-m',mktime());
		$sql = "UPDATE `{$this->App->prefix()}user` SET `mymoney` = `mymoney`+$moeys WHERE user_id =".$p['user_id'];
		$this->App->query($sql);
		$this->App->insert('user_money_change',array('buyuid'=>$uid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$moeys,'changedesc'=>$cengji.'-收入'.$moeys.'元-返佣金','time'=>mktime(),'uid'=>$p['user_id']));
		//通知
		$str = array('openid'=>$p['wecha_id'],'appid'=>'','appsecret'=>'','money'=>$order_amount,'jibie'=>$cengji,'nickname'=>$nickname);
		$this->action('api','sendtxt',$str,'fenyongjin');
	}
	
	function kena($pid,$cengji){	//判断卡位
		$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$pid' LIMIT 1";
		$rank = $this->App->findvar($sql);
		//红包数量
		$sql = "SELECT count(order_id) FROM `{$this->App->prefix()}goods_sn` WHERE `pid` = '$pid' AND `cengji`=$cengji AND (`money`>0 OR `lmoney`>0)";
		$count = $this->App->findvar($sql);
		
		if($rank<13&&$count>3){
			return false;
		}
		if($rank<14&&$count>3&&$cengji==2){
			return false;
		}
		return true;
	}
	
	function quhao($uid,$order_sn,$order_id,$pid,$cengji,$money){	//用户ID、订单编号、订单ID、父级ID、父级层级、红包金额
			//判断卡位
			
			if(!$this->kena($pid,$cengji)){
				$user = $this->App->findrow("SELECT wecha_id,nickname FROM `{$this->App->prefix()}user` WHERE user_id='$pid' LIMIT 1");
				$str = array('openid'=>$user['wecha_id'],'appid'=>'','appsecret'=>'','nickname'=>$user['nickname'],'cengji'=>$cengji);
				$this->action('api','sendtxt',$str,'nomoney');
				$pid = 0;
			}
			
			//如果是虚拟卡变更状态
			if($uid > 0){
				//exit($uid.'<br />order_sn-'.$order_sn.'<br />order_id-'.$order_id.'<br />p-'.$pid.'<br />cengji-'.$cengji.'<br />money-'.$money);
				$this->App->update('goods_order_info',array('shipping_status'=>'5'),'order_sn',$order_sn);
				$gid = $this->App->findvar("SELECT goods_id FROM `{$this->App->prefix()}goods_order` WHERE order_id='$order_id' LIMIT 1");
				if($gid > 0){
					//各级别取到卡号
					$ids = $this->App->findrow("SELECT id,goods_pass,goods_sn FROM `{$this->App->prefix()}goods_sn` WHERE goods_id='$gid' AND is_use = '0' ORDER BY id ASC LIMIT 1");
					if(!empty($ids)){
						$id = $ids['id'];
						$pass = $ids['goods_pass'];
						$sn = $ids['goods_sn'];
						$this->App->update('goods_sn',array('is_use'=>'1','usetime'=>mktime(),'order_id'=>$order_id,'uid'=>$uid,'pid'=>$pid,'cengji'=>$cengji,'money'=>$money),'id',$id);
					}else{
						exit("错误，请尽快联系管理员，<font color=red>有可能没有货了。</font>");
					}
					if($pid==0){
						$user = $this->App->findrow("SELECT wecha_id,nickname FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
						$str = array('openid'=>$user['wecha_id'],'appid'=>'','appsecret'=>'','nickname'=>$user['nickname'],'goods_pass'=>$pass,'goods_sn'=>$sn);
						$this->action('api','sendtxt',$str,'payconfirm_vg');
					}
				}
			}
	}
	
	//支付成功改变支付状态
	function pay_successs_tatus2($rt=array()){
		set_time_limit(300); //最大运行时间
		
		$order_sn = $rt['order_sn'];
		$status = $rt['status'];
		
		if(empty($order_sn))exit;
		$order_sn = substr($order_sn,-14,14);

		//上三级返佣金
		
		$pay_status = $this->App->findvar("SELECT pay_status FROM `{$this->App->prefix()}goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
		$tt = "false";
		if($pay_status!='1'){
			//检查
			$sql = "SELECT cid FROM `{$this->App->prefix()}user_money_change` WHERE order_sn='$order_sn'"; //资金
			$cid = $this->App->findvar($sql);
			if($cid > 0){
				return true;
				exit;
			}else{
				$sql = "SELECT cid FROM `{$this->App->prefix()}user_point_change` WHERE order_sn='$order_sn'"; //积分
				$cid = $this->App->findvar($sql);
				if($cid > 0){
					return true;
					exit;
				}else{
					$tt = "true";
				}
			}
		}else{//已经支付了的
			return true;
			exit;
		}
		
		if($tt == 'true' && $status=='1' && !empty($order_sn)){
			$pu = $this->App->findrow("SELECT user_id,daili_uid,parent_uid,parent_uid2,parent_uid3,parent_uid4,goods_amount,order_amount,order_sn,pay_status,order_id FROM `{$this->App->prefix()}goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
			if(empty($pu)){ exit; }
			
			$user_id = isset($pu['user_id']) ? $pu['user_id'] : 0; //用户
			$user_wechat = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id=".$user_id);	//用户openid
			
			$daili_uid = isset($pu['daili_uid']) ? $pu['daili_uid'] : 0; //代理
			$moeys = isset($pu['order_amount']) ? $pu['order_amount'] : 0; //实际消费
			$order_amount = isset($pu['order_amount']) ? $pu['order_amount'] : 0; //实际消费
			
			$pay_status = isset($pu['pay_status']) ? $pu['pay_status'] : 0;
			
			$order_id = isset($pu['order_id']) ? $pu['order_id'] : 0;
			
			//改变销量
			$sql = "SELECT goods_id,goods_number FROM `{$this->App->prefix()}goods_order` WHERE order_id = '$order_id'";
			$rd = $this->App->find($sql);
			if(!empty($rd))foreach($rd as $rdd){
				$gid = $rdd['goods_id'];
				$number = $rdd['goods_number'];
				$sql = "UPDATE `{$this->App->prefix()}goods` SET `sale_count` = `sale_count`+'$number' , `goods_number` = `goods_number`- '$number' WHERE goods_id = '$gid' LIMIT 1";
				$this->App->query($sql);
			}
			unset($rd);
			
					
			$uid = $user_id;
			
			$parent_uid = $this->App->findvar("SELECT `parent_uid` FROM `{$this->App->prefix()}user_tuijian` WHERE `uid`=$uid");
			$parent_uid2 = $this->App->findvar("SELECT `parent_uid` FROM `{$this->App->prefix()}user_tuijian` WHERE `uid`=$parent_uid");
			$parent_uid3 = $this->App->findvar("SELECT `parent_uid` FROM `{$this->App->prefix()}user_tuijian` WHERE `uid`=$parent_uid2");
			if($parent_uid>0)	$p1 = $this->App->findrow("SELECT nickname,user_rank,wecha_id,user_id FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1");
			if($parent_uid2>0)	$p2 = $this->App->findrow("SELECT nickname,user_rank,wecha_id,user_id FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid2' LIMIT 1");
			if($parent_uid3>0)	$p3 = $this->App->findrow("SELECT nickname,user_rank,wecha_id,user_id FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1");
			//购买用户
			$ni = $this->App->findrow("SELECT nickname,user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
			$nickname = $ni['nickname'];
			
			$dd = array();
			$dd['order_status'] = '2';
			$dd['pay_status'] = '1';
			$dd['pay_time'] = mktime();
			$this->App->update('goods_order_info',$dd,'order_sn',$order_sn);
			//
			if($ni['user_rank']==1){
				$quid = $this->App->findvar("SELECT MAX(quid) FROM `{$this->App->prefix()}user` LIMIT 1");
				$this->App->update('user',array('quid'=>($quid+1)),'user_id',$uid);	
			}
							
			//自己升级------------自己升等级
			$user_rank = $ni['user_rank'];
			$rank_name = '';
			$record = array();
			$moeys = 0;
			switch($user_rank){
				case 1:
					//100元
					if($order_amount==100){
						//升级到分销商 12
						$this->App->update('user',array('user_rank'=>12),'user_id',$uid);	//更新自己等级
						$rank_name = '分销商';
						$moeys = 80;
						//一层父级返佣金
						if($parent_uid>0&&$p1['user_rank']>=12){
							$this->fenyongjin($uid,$p1,$moeys,$order_amount,'一级会员');
							$record['puid1_money'] = $moeys;
							$record['p_uid1'] = $parent_uid;
							//取号
							$this->quhao($uid,$order_sn,$order_id,$p1['user_id'],1,$moeys);
						}else{
							$this->quhao($uid,$order_sn,$order_id,0,0,$moeys);
						}
					}elseif($order_amount==500){	// 300元  升级到高级会员商 13
						$this->App->update('user',array('user_rank'=>13),'user_id',$uid);
						$rank_name = '高级会员商';
						$moeys = 80;
						//一层父级返佣金
						if($parent_uid>0&&$p1['user_rank']>=12){
							$this->fenyongjin($uid,$p1,$moeys,$order_amount,'一级会员');
							$record['puid1_money'] = $moeys;
							$record['p_uid1'] = $parent_uid;
							//取号
							$this->quhao($uid,$order_sn,$order_id,$p1['user_id'],1,$moeys);
						}else{
							$this->quhao($uid,$order_sn,$order_id,0,'0',$moeys);
						}
						$moeys = 320;
						//二层父级返佣金
						if($parent_uid2>0&&$p2['user_rank']>=13){
							$this->fenyongjin($uid,$p2,$moeys,$order_amount,'二级会员');
							$record['puid2_money'] = $moeys;
							$record['p_uid2'] = $parent_uid2;
							//取号
							$this->quhao($uid,$order_sn,$order_id,$p2['user_id'],2,$moeys);//一次
							$this->quhao($uid,$order_sn,$order_id,$p2['user_id'],2,0);//二次
						}else{
							$this->quhao($uid,$order_sn,$order_id,0,0,$moeys);
							$this->quhao($uid,$order_sn,$order_id,0,0,0);
						}
					}elseif($order_amount==1500){	//600元 升级到特级会员商 14
						$this->App->update('user',array('user_rank'=>14),'user_id',$uid);
						$rank_name = '特级会员商';
						$moeys = 80;
						//一层父级返佣金
						if($parent_uid>0&&$p1['user_rank']>=12){
							$this->fenyongjin($uid,$p1,$moeys,$order_amount,'一级会员');
							$record['puid1_money'] = $moeys;
							$record['p_uid1'] = $parent_uid;
							//取号
							$this->quhao($uid,$order_sn,$order_id,$p1['user_id'],1,$moeys);
						}else{
							$this->quhao($uid,$order_sn,$order_id,0,'0',$moeys);
						}
						$moeys = 320;
						//二层父级返佣金
						if($parent_uid2>0&&$p2['user_rank']>=13){
							$this->fenyongjin($uid,$p2,$moeys,$order_amount,'二级会员');
							$record['puid2_money'] = $moeys;
							$record['p_uid2'] = $parent_uid2;
							//取号
							$this->quhao($uid,$order_sn,$order_id,$p2['user_id'],2,$moeys);//一次
							$this->quhao($uid,$order_sn,$order_id,$p2['user_id'],2,0);//二次
						}else{
							$this->quhao($uid,$order_sn,$order_id,0,0,$moeys);
							$this->quhao($uid,$order_sn,$order_id,0,0,0);
						}
						$moeys = 800;
						//三层父级返佣金
						if($parent_uid3>0&&$p3['user_rank']>=14){
							$this->fenyongjin($uid,$p3,$moeys,$order_amount,'三级会员');
							$record['puid3_money'] = $moeys;
							$record['p_uid3'] = $parent_uid3;
							//取号
							$this->quhao($uid,$order_sn,$order_id,$p3['user_id'],3,$moeys);//一次
							$this->quhao($uid,$order_sn,$order_id,$p3['user_id'],3,0);//二次
							$this->quhao($uid,$order_sn,$order_id,$p3['user_id'],3,0);//3次
						}else{
							$this->quhao($uid,$order_sn,$order_id,0,0,$moeys);
							$this->quhao($uid,$order_sn,$order_id,0,0,0);
							$this->quhao($uid,$order_sn,$order_id,0,0,0);
						}
					}else{
						exit("错误，请联系管理员");
					}
					break;
				case 12:
					if($order_amount==400){	//升级到高级会员商 13
						$this->App->update('user',array('user_rank'=>13),'user_id',$uid);
						$rank_name = '高级会员商';
						$moeys = 320;
						//二层父级返佣金
						if($parent_uid2>0&&$p2['user_rank']>=13){
							$this->fenyongjin($uid,$p2,$moeys,$order_amount,'二级会员');
							$record['puid2_money'] = $moeys;
							$record['p_uid2'] = $parent_uid2;
							//取号
							$this->quhao($uid,$order_sn,$order_id,$p2['user_id'],2,$moeys);//一次
							$this->quhao($uid,$order_sn,$order_id,$p2['user_id'],2,0);//二次
						}else{
							$this->quhao($uid,$order_sn,$order_id,0,0,$moeys);
							$this->quhao($uid,$order_sn,$order_id,0,0,0);
						}
					}elseif($order_amount==1400){	//升级到特级会员商 14
						$this->App->update('user',array('user_rank'=>14),'user_id',$uid);
						$rank_name = '特级会员商';
						$moeys = 320;
						//二层父级返佣金
						if($parent_uid2>0&&$p2['user_rank']>=13){
							$this->fenyongjin($uid,$p2,$moeys,$order_amount,'二级会员');
							$record['puid2_money'] = $moeys;
							$record['p_uid2'] = $parent_uid2;
							//取号
							$this->quhao($uid,$order_sn,$order_id,$p2['user_id'],2,$moeys);//一次
							$this->quhao($uid,$order_sn,$order_id,$p2['user_id'],2,0);//二次
						}else{
							$this->quhao($uid,$order_sn,$order_id,0,0,$moeys);
							$this->quhao($uid,$order_sn,$order_id,0,0,0);
						}
						$moeys = 800;
						//三层父级返佣金
						if($parent_uid3>0&&$p3['user_rank']>=14){
							$this->fenyongjin($uid,$p3,$moeys,$order_amount,'三级会员');
							$record['puid3_money'] = $moeys;
							$record['p_uid3'] = $parent_uid3;
							//取号
							$this->quhao($uid,$order_sn,$order_id,$p3['user_id'],3,$moeys);//一次
							$this->quhao($uid,$order_sn,$order_id,$p3['user_id'],3,0);//二次
							$this->quhao($uid,$order_sn,$order_id,$p3['user_id'],3,0);//3次
						}else{
							$this->quhao($uid,$order_sn,$order_id,0,0,$moeys);
							$this->quhao($uid,$order_sn,$order_id,0,0,0);
							$this->quhao($uid,$order_sn,$order_id,0,0,0);
						}
					}else{
						exit("错误，请联系管理员");
					}
					break;
				case 13:
					if($order_amount==1000){	//升级到特级会员商 14
						$this->App->update('user',array('user_rank'=>14),'user_id',$uid);
						$rank_name = '特级会员商';
						$moeys = 800;
						//三层父级返佣金
						if($parent_uid3>0&&$p3['user_rank']>=14){
							$this->fenyongjin($uid,$p3,$moeys,$order_amount,'三级会员');
							$record['puid3_money'] = $moeys;
							$record['p_uid3'] = $parent_uid3;
							//取号
							$this->quhao($uid,$order_sn,$order_id,$p3['user_id'],3,$moeys);//一次
							$this->quhao($uid,$order_sn,$order_id,$p3['user_id'],3,0);//二次
							$this->quhao($uid,$order_sn,$order_id,$p3['user_id'],3,0);//3次
						}else{
							$this->quhao($uid,$order_sn,$order_id,0,0,$moeys);
							$this->quhao($uid,$order_sn,$order_id,0,0,0);
							$this->quhao($uid,$order_sn,$order_id,0,0,0);
						}
					}else{
						exit("错误，请联系管理员");
					}
					break;
				default:
					exit('错误，请联系管理员');
			}
			//最新等级
			//$new_user_rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
			//通知会员升级
			$this->action('api','sendtxt',array('openid'=>$user_wechat,'appid'=>'','appsecret'=>'','rank_name'=>$rank_name),'uplevel_self');

			//添加到资金记录表
			if(!empty($record)){
				$record['oid'] = $order_id;
				$record['uid'] = $uid;
				$record['date_y'] = date('Y',mktime());
				$record['date_m'] = date('Y-m',mktime());
				$record['date_d'] = date('Y-m-d',mktime());
				$this->App->insert('user_money_record',$record);
			}			
		
		}//end if
		
		return true;
		
	}//end function
	
	function return_daili_uid($uid=0,$k=0){
		if(!($uid > 0)){
			return 0;
		}
		$p = 0;
		$sql = "SELECT parent_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid = '$uid' LIMIT 1";
		$p = $this->App->findvar($sql);
		return $p;
	}
	
	function _firtuids($uid=0){
		$ut = array();
		$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
		$uids = $this->App->findcol($sql);
		if(!empty($uids))foreach($uids as $uid){
			$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
			if($ur!='1'){
				$ut[] = $uid;
			}else{
					/********************第二次*************************/
						$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
						$uids = $this->App->findcol($sql);
						if(!empty($uids))foreach($uids as $uid){
							$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
							if($ur!='1'){
								$ut[] = $uid;
							}else{
									/********************第三次*************************/
										$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
										$uids = $this->App->findcol($sql);
										if(!empty($uids))foreach($uids as $uid){
											$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
											if($ur!='1'){
												$ut[] = $uid;
											}else{
													/********************第四次*************************/
														$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
														$uids = $this->App->findcol($sql);
														if(!empty($uids))foreach($uids as $uid){
															$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
															if($ur!='1'){
																$ut[] = $uid;
															}else{
																	/********************第五次*************************/
																		$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
																		$uids = $this->App->findcol($sql);
																		if(!empty($uids))foreach($uids as $uid){
																			$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
																			if($ur!='1'){
																				$ut[] = $uid;
																			}else{
																					/********************第六次*************************/
																							$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
																							$uids = $this->App->findcol($sql);
																							if(!empty($uids))foreach($uids as $uid){
																								$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
																								if($ur!='1'){
																									$ut[] = $uid;
																								}else{
																										/********************第七次*************************/
																											$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
																											$uids = $this->App->findcol($sql);
																											if(!empty($uids))foreach($uids as $uid){
																												$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
																												if($ur!='1'){
																													$ut[] = $uid;
																												}else{
																														/********************第八次*************************/
																															$sql = "SELECT uid FROM `{$this->App->prefix()}user_tuijian` WHERE parent_uid='$uid'";
																															$uids = $this->App->findcol($sql);
																															if(!empty($uids))foreach($uids as $uid){
																																$ur = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id = '$uid'");
																																if($ur!='1'){
																																	$ut[] = $uid;
																																}else{
																																	break;
																																}
																																
																															}
																														/********************************************/
																												}
																												
																											}
																										/********************************************/
																								}
																								
																							}
																					/********************************************/
																			}
																			
																		}
																	/********************************************/
															}
															
														}
													/********************************************/
											}
											
										}
									/********************************************/
							}
							
						}
					/********************************************/
			}
			
		}
		
		return $ut;
	} //end function
	
	function update_daili_tree($uid=0){
		if($uid>0){
				$dd = array();
				$dd['uid'] = $uid;
				$dd['p1_uid'] = 0;
				$dd['p2_uid'] = 0;
				$dd['p3_uid'] = 0;
				
				$p1_uid = $this->return_daili_uid($uid); //最近分销
			
				$firtuids = array();
				if($p1_uid > 0 ){
					$dd['p1_uid'] = $p1_uid;
					$p2_uid = $this->return_daili_uid($p1_uid);
					
					if($p2_uid > 0 ){
						$dd['p2_uid'] = $p2_uid;
						$p3_uid = $this->return_daili_uid($p2_uid);
						
						if($p3_uid > 0 ){
							$dd['p3_uid'] = $p3_uid;
							/*$p4_uid = $this->return_daili_uid($p3_uid);
							if($p4_uid > 0){
								$dd['p4_uid'] = $p4_uid;
							}*/
						}
					}
				}
				
				//
				$sql = "SELECT id FROM `{$this->App->prefix()}user_tuijian_fx` WHERE uid='$uid' LIMIT 1";
				$id = $this->App->findvar($sql);
				
				if($id > 0){
					$this->App->update('user_tuijian_fx',$dd,'id',$id);
				}else{
					$this->App->insert('user_tuijian_fx',$dd);
				}
			
			//
			$firtuids = $this->_firtuids($uid); //当前开通用户的最近一层分销用户
			
			$aup = array();
			if(!empty($firtuids))foreach($firtuids as $u){ //
				$dds = array();
				$dds['uid'] = $u;
				$dds['p1_uid'] = $uid;
				$dds['p2_uid'] = $dd['p1_uid'];
				$dds['p3_uid'] = $dd['p2_uid'];
				
				$aup[] = $dds;
				
				$firtuids2 = $this->App->findcol("SELECT uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE p1_uid = '$u'");
				if(!empty($firtuids2))foreach($firtuids2 as $uu){ //
				
					$dds = array();
					$dds['uid'] = $uu;
					$dds['p1_uid'] = $u;
					$dds['p2_uid'] = $uid;
					$dds['p3_uid'] = $dd['p1_uid'];
					
					$aup[] = $dds;
					
					$firtuids3 = $this->App->findcol("SELECT uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE p1_uid = '$uu'");
					if(!empty($firtuids3))foreach($firtuids3 as $uuu){ //
						
						$dds = array();
						$dds['uid'] = $uuu;
						$dds['p1_uid'] = $uu;
						$dds['p2_uid'] = $u;
						$dds['p3_uid'] = $uid;
						
						$aup[] = $dds;
						
					}//end foreach
					unset($firtuids3);
				} //end foreach
				unset($firtuids2);
			} //end foreach
			unset($firtuids);
			
			if(!empty($aup))foreach($aup as $up){
				$this->App->update('user_tuijian_fx',$up,'uid',$up['uid']);
			}
			unset($aup);
		} //end if
	}
	
   function update_user_tree($puid = 0,$ppuid=0)
	{
		$three_arr = array();
		$sql = 'SELECT id,uid FROM `'.$this->App->prefix()."user_tuijian` WHERE parent_uid = '$puid'";
		$rt = $this->App->find($sql);
		if(!empty($rt))foreach($rt as $row){
			$id = $row['id'];
			$uid = $row['uid'];//
			//更新
			if($id > 0){
				//$this->App->update('user_tuijian',array('daili_uid'=>$ppuid),'id',$id);
			}
			//判断当前是否是代理
			$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
			$rank = $this->App->findvar($sql);
			if($rank=='1'){ //普通会员
				//$this->update_user_tree($uid,$ppuid);
			}else{
			}
		}
	}
	
	//支付成功改变支付状态(代购模式)
	function pay_successs_tatus($rt=array()){
		$order_sn = $rt['order_sn'];
		$status = $rt['status'];
		
		//送佣金，找出推荐用户
		$pu = $this->App->findrow("SELECT user_id,daili_uid,parent_uid,goods_amount,order_amount,order_sn FROM `{$this->App->prefix()}goods_order_info_daigou` WHERE order_sn='$order_sn' LIMIT 1");
		$parent_uid = isset($pu['parent_uid']) ? $pu['parent_uid'] : 0; //分享者
		$daili_uid = isset($pu['daili_uid']) ? $pu['daili_uid'] : 0; //代理
		$moeys = isset($pu['order_amount']) ? $pu['order_amount'] : 0;
		$uid = isset($pu['user_id']) ? $pu['user_id'] : 0;
		
		//检查
		$tt = "false";
		$sql = "SELECT cid FROM `{$this->App->prefix()}user_money_change` WHERE order_sn='$order_sn'"; //资金
		$cid = $this->App->findvar($sql);
		if($cid > 0){
			return false;exit;
		}else{
			$sql = "SELECT cid FROM `{$this->App->prefix()}user_point_change` WHERE order_sn='$order_sn'"; //积分
			$cid = $this->App->findvar($sql);
			if($cid > 0){
				return false;exit;
			}else{
				$tt = "true";
			}
		}
		
		if($tt == 'true' && $status=='1' && !empty($order_sn)){
			$nickname = $this->App->findvar("SELECT nickname FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
			$dd = array();
			$dd['order_status'] = 2;
			$dd['pay_status'] = 1;
			$dd['pay_time'] = mktime();

			$this->App->update('goods_order_info_daigou',$dd,'order_sn',$order_sn);
			
			$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";//配置信息
			$rts = $this->App->findrow($sql);
			
			$appid = $this->Session->read('User.appid');
			if(empty($appid)) $appid = isset($_COOKIE[CFGH.'USER']['APPID']) ? $_COOKIE[CFGH.'USER']['APPID'] : '';
			$appsecret = $this->Session->read('User.appsecret');
			if(empty($appsecret)) $appsecret = isset($_COOKIE[CFGH.'USER']['APPSECRET']) ? $_COOKIE[CFGH.'USER']['APPSECRET'] : '';
			
			//购物上级以及购物者送积分
			$pointnum =  $rts['pointnum'];
			if($pointnum > 0 && !empty($moeys)){
					if($parent_uid > 0){ //存在上级，积分对半分
						$points = ceil(intval($moeys * $pointnum)/2);
						$points = intval($points);
					}else{
						$points = intval($moeys * $pointnum);
					}
					$thismonth = date('Y-m-d',mktime());
					//购买者送积分
					$sql = "UPDATE `{$this->App->prefix()}user` SET `points_ucount` = `points_ucount`+$points,`mypoints` = `mypoints`+$points WHERE user_id = '$uid'";
					$this->App->query($sql);
					$this->App->insert('user_point_change',array('order_sn'=>$order_sn,'thismonth'=>$thismonth,'points'=>$points,'changedesc'=>'消费返积分','time'=>mktime(),'uid'=>$uid));
					//发送推荐用户通知
					$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
					//$this->action('api','sendtxt',array('openid'=>$pwecha_id,'appid'=>$appid,'appsecret'=>$appsecret,'nickname'=>''),'payreturnpoints');

					//上级推荐用户的
					if($parent_uid > 0){
						$sql = "UPDATE `{$this->App->prefix()}user` SET `points_ucount` = `points_ucount`+$points,`mypoints` = `mypoints`+$points WHERE user_id = '$parent_uid'";
						$this->App->query($sql);
						$this->App->insert('user_point_change',array('order_sn'=>$order_sn,'thismonth'=>$thismonth,'points'=>$points,'changedesc'=>'推荐消费返积分','time'=>mktime(),'uid'=>$parent_uid));
						//发送推荐用户通知
						$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1");
						//$this->action('api','sendtxt',array('openid'=>$pwecha_id,'appid'=>$appid,'appsecret'=>$appsecret,'nickname'=>''),'payreturnpoints_parentuid');

					}
			}
			
			//检查当前用户是否是代理
			$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";//配置信息
			$rank = $this->App->findvar($sql);
			if($rank=='10' && !empty($moeys)){ //如果是代理商，返佣给自己
					$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
					$types = $this->App->findvar($sql);
					
					$off = 0;
					if($types=='1'){ //全职
						if($rts['ticheng360'] < 101 && $rts['ticheng360'] > 0){
							$off = $rts['ticheng360']/100;
						}
					}else{
						if($rts['ticheng180'] < 101 && $rts['ticheng180'] > 0){
							$off = $rts['ticheng180']/100;
						}
					}
					$moeys = format_price($moeys*$off);
					$thismonth = date('Y-m-d',mktime());
					$thism = date('Y-m',mktime());
					$sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$uid'";
					$this->App->query($sql);
					$this->App->insert('user_money_change',array('buyuid'=>$uid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$moeys,'changedesc'=>'购买商品返佣金','time'=>mktime(),'uid'=>$uid));
					
					//发送推荐用户通知
					$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
					$this->action('api','sendtxt',array('openid'=>$pwecha_id,'appid'=>$appid,'appsecret'=>$appsecret,'nickname'=>$nickname),'payreturnmoney');
					
			}elseif($daili_uid > 0 && !empty($moeys)){ //推荐送佣金给代理
					$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$daili_uid' LIMIT 1";
					$types = $this->App->findvar($sql);
					
					$off = 0;
					if($types=='1'){ //全职
						if($rts['ticheng360'] < 101 && $rts['ticheng360'] > 0){
							$off = $rts['ticheng360']/100;
						}
					}else{
						if($rts['ticheng180'] < 101 && $rts['ticheng180'] > 0){
							$off = $rts['ticheng180']/100;
						}
					}
					$moeys = format_price($moeys*$off);
					$thismonth = date('Y-m-d',mktime());
					$thism = date('Y-m',mktime());
					$sql = "UPDATE `{$this->App->prefix()}user` SET `money_ucount` = `money_ucount`+$moeys,`mymoney` = `mymoney`+$moeys WHERE user_id = '$daili_uid'";
					$this->App->query($sql);
					$this->App->insert('user_money_change',array('buyuid'=>$uid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$moeys,'changedesc'=>'推荐用户购买返佣金','time'=>mktime(),'uid'=>$daili_uid));
					
					//发送推荐用户通知
					$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$daili_uid' LIMIT 1");
					$this->action('api','sendtxt',array('openid'=>$pwecha_id,'appid'=>$appid,'appsecret'=>$appsecret,'nickname'=>$nickname),'payreturnmoney');
			}
		}
		
	}
	
	//支付成功或者失败跳转的页面
	function paysuccessjump($t = ''){
		$url = str_replace('paywx/','',ADMIN_URL);
		if($t=='1'){
			$this->jump($url,0,'您已经成功支付，感谢您的支持。');exit;
		}elseif($t=='2'){
			$this->jump($url,0,'支付发生意外错误，请稍后再试。');exit;
		}
		$this->jump($url);exit;
	}
	
	//获取用户的openid
	function get_openid_AND_pay_info(){
		$wecha_id = $this->Session->read('User.wecha_id');
		if(empty($wecha_id)) $wecha_id = isset($_COOKIE[CFGH.'USER']['UKEY']) ? $_COOKIE[CFGH.'USER']['UKEY'] : '';
		$wecha_id = $wecha_id;
		
		//
		$order_sn = isset($_GET['order_sn']) ? $_GET['order_sn'] : '';
		$sql = "SELECT order_sn,order_amount,pay_status,shipping_fee FROM `{$this->App->prefix()}goods_order_info` WHERE pay_status = '0' AND order_sn='$order_sn' LIMIT 1";
		$rt = $this->App->findrow($sql);
		$rt['order_amount'] = $rt['order_amount']+$rt['shipping_fee'];
		if(empty($rt)){
			$this->jump(str_replace('/wxpay','',ADMIN_URL),0,'非法支付提交！'); exit;
		}
		if($rt['pay_status']=='1'){
			$this->jump(str_replace('/wxpay','',ADMIN_URL).'user.php?act=orderlist');exit;
		}
		$rt['openid'] = $wecha_id;
		$rt['body'] = $GLOBALS['LANG']['site_name'].'购物平台';
		return $rt;
	}
	
	//返回订单信息
	function get_order_pay_info($order_sn){
		$sql = "SELECT order_sn,order_id,order_amount,pay_status,shipping_fee FROM `{$this->App->prefix()}goods_order_info` WHERE pay_status = '0' AND order_sn='$order_sn' LIMIT 1";
		$rt = $this->App->findrow($sql);
		$rt['order_amount'] = $rt['order_amount']+$rt['shipping_fee'];
		if(empty($rt)){
			$this->jump(str_replace('/yunpay','',ADMIN_URL),0,'非法支付提交！'); exit;
		}
		if($rt['pay_status']=='1'){
			$this->jump(str_replace('/yunpay','',ADMIN_URL).'user.php?act=orderlist');exit;
		}
		$rt['body'] = $GLOBALS['LANG']['site_name'].'购物平台';
		$order_id = $rt['order_id'];
		$rt['gname'] = $this->App->findvar("SELECT goods_name FROM `{$this->App->prefix()}goods_order` WHERE order_id = '$order_id' LIMIT 1");
		return $rt;
	}
	
	//终端支付跳转
	function _alipayment($rt=array()){
		$pay_id = $rt['pay_id'];
		
		$order_sn = $rt['order_sn']; //网站唯一订单编号
		$order_amount = $rt['order_amount']+$rt['logistics_fee'];
		
		
		if($pay_id=='4'){ //微信支付
			$this->jump(ADMIN_URL.'wxpay/js_api_call.php?order_sn='.$order_sn);exit;	
		}
		
		if($pay_id=='6'){ //云支付
			$this->jump(ADMIN_URL.'yunpay/yunpay.php?order_sn='.$order_sn);exit;	
		}
		
		//余额支付
		if($pay_id=='7'){
			//我的余额
			$uid = $this->Session->read('User.uid');
			if($uid > 0){
				$sql = "SELECT mymoney FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
				$mymoney = $this->App->findvar($sql);
			}else{
				$oid = $this->App->findvar("SELECT order_id FROM `{$this->App->prefix()}user` WHERE order_sn='$order_sn' LIMIT 1");
				$this->jump(ADMIN_URL.'mycart.php?type=pay2&oid='.$oid,0,'余额不足，请选择其他支付方式！');exit;	
			}
			if($mymoney>=$order_amount){
				$money = -$order_amount;
				$sql = "UPDATE `{$this->App->prefix()}user` SET `mymoney` = `mymoney`+$money WHERE user_id = '$uid'";
				$this->App->query($sql);
				
				$sd = array();
				$sd = array('order_sn'=>$order_sn,'status'=>1);
				if($this->pay_successs_tatus2($sd)){
					$sd =  array();
					$thismonth = date('Y-m-d',mktime());
					$thism = date('Y-m',mktime());
					$sd['time'] = mktime();
					$sd['changedesc'] = '余额支付';
					$sd['money'] = $money;
					$sd['uid'] = $uid;
					$sd['buyuid'] = $uid;
					$sd['order_sn'] = $order_sn;
					$sd['thismonth'] = $thismonth;
					$sd['thism'] = $thism;
					$sd['type'] = '3';
					$this->App->insert('user_money_change',$sd);
					unset($sd);
					$this->jump(ADMIN_URL.'user.php?act=orderlist',0,'已成功支付');exit;	
				}else{
					$this->jump(ADMIN_URL.'mycart.php?type=pay2&oid='.$oid,0,'意外错误！');exit;	
				}
			}else{
				$this->jump(ADMIN_URL.'mycart.php?type=pay2&oid='.$oid,0,'余额不足，请选择其他支付方式！');exit;	
			}
		}
		
		$sql = "SELECT `pay_config` FROM `".$this->App->prefix()."payment` WHERE `pay_id`='$pay_id'";
		$pay_config = $this->App->findvar($sql);
		$configr = unserialize($pay_config);
		$paypalmail = isset($configr['pay_no']) ? $configr['pay_no'] : '';
        if(!$paypalmail){
			$this->jump(ADMIN_URL,0,'这是货到付款方式，等待商家发货');exit;	
            return false;
        }
		
		
		if(!$paypalmail){
            return false;
        }
		if($pay_id=='3'){ //支付宝
			//WAP
			$paypal_form = "<form name='aqua' method='post' action='".ADMIN_URL."paywx/alipayapi.php'>
				<input type='hidden' name='WIDout_trade_no' value='".$order_sn."'>
				<input type='hidden' name='WIDseller_email' value='".$paypalmail."'>
				<input type='hidden' name='WIDsubject' value='商城支付系统'>
				<input type='hidden' name='WIDtotal_fee' value='".$order_amount."'>
			</form>";
			$paypal_form.="<script language='javascript'>
					aqua.submit();
					</script>
					";
		echo $paypal_form;
		}
		
		die();
	}
	
	
	//确认订单
	function confirm_daigou2(){
		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL.'user.php?act=login',0,'请先登录！'); exit;}
		$order_sn = date('Y',mktime()).mktime();
		
		if(isset($_POST)&&!empty($_POST)){
			//$totalprice = $_POST['totalprice'];
			//if($totalprice < 0){
				//$this->jump(ADMIN_URL,0,'非法提交');exit;
			//}
			$addresssall = $_POST['address'];
			$pay_id = $_POST['pay_id'];
			$pay_name = $this->App->findvar("SELECT pay_name FROM `{$this->App->prefix()}payment` WHERE pay_id='$pay_id' LIMIT 1");
			$shipping_id = $_POST['shipping_id'];
			$shipping_name = $this->App->findvar("SELECT shipping_name FROM `{$this->App->prefix()}shipping` WHERE shipping_id='$shipping_id' LIMIT 1");
			$postscript = $_POST['postscript'];
			
			$goodslist = $this->Session->read('cart');
			if(empty($goodslist)){
				$this->jump(ADMIN_URL,0,'购物车为空');exit;
			}
			$totalprice = 0;
			$stotalprice = 0;
			foreach($goodslist as $gid=>$row){
				if($row['is_jifen_session']=='1'){
					$this->Session->write("cart.$gid",null);
					$this->Session->write('useradd.$gid',null); 
					continue; 
				}
				if(!($row['number'] > 0)){
					$row['number'] = 1;
					$this->Session->write("cart.{$gid}.number",1);
				}
				$totalprice +=$row['price']*$row['number'];
				$stotalprice +=$row['pifa_price']*$row['number'];
			}
			if(!($totalprice>0)){
				$this->jump(ADMIN_URL,0,'非法 提交');exit;
			}
			//添加订单表
			$orderdata = array();
			$orderdata['pay_id'] = $pay_id;
			$orderdata['shipping_id'] = $shipping_id;
			$orderdata['pay_name'] = $pay_name;
			$orderdata['shipping_name'] = $shipping_name;
			$orderdata['order_sn'] = $order_sn;
			$orderdata['user_id'] = $uid;
			$pr = $this->App->findrow("SELECT parent_uid,daili_uid FROM `{$this->App->prefix()}user_tuijian` WHERE uid='$uid' LIMIT 1");
			$parent_uid = isset($pr['parent_uid']) ? $pr['parent_uid'] : 0;
			$daili_uid = isset($pr['daili_uid']) ? $pr['daili_uid'] : 0;
			$orderdata['parent_uid'] = $parent_uid >0 ? $parent_uid : '0';
			$orderdata['daili_uid'] = $daili_uid >0 ? $daili_uid : '0';
			$orderdata['postscript'] = $postscript;
			$orderdata['type']  = 3;
			$orderdata['goods_amount'] = $stotalprice;
			$orderdata['order_amount'] = $totalprice;
			$orderdata['add_time'] = mktime();
			$this->App->insert('goods_order_info_daigou',$orderdata);
			$orderid = $this->App->iid();
			if($orderid > 0) foreach($goodslist as $row){
				$gid = $row['goods_id'];
				
				//$consignees = $_POST['consignee'][$gid];
				//$numbers = $_POST['goods_number'][$gid];
				//$moblies = $_POST['moblie'][$gid];
				//$provinces = $_POST['province'][$gid];
				//$citys = $_POST['city'][$gid];
				//$districts = $_POST['district'][$gid];
				//$addresss = $_POST['address'][$gid];
				//if(empty($consignees)) continue;
				
				
				//添加订单商品表
				$ds = array();
				$ds['order_id'] = $orderid;
				$ds['goods_id'] = $gid;
				$ds['brand_id'] = $row['brand_id'];
				$ds['goods_name'] = $row['goods_name'];
				$ds['goods_thumb'] = $row['goods_thumb'];
				$ds['goods_bianhao'] = $row['goods_bianhao'];
				$ds['goods_unit'] = $row['goods_unit'];
				$ds['goods_sn'] = $row['goods_sn'];
				$ds['market_price'] = $row['pifa_price'];
				$ds['goods_price'] = $row['price'];
				$ds['goods_number'] = $row['number']; //单个商品的总数量
				if(!empty($row['spec'])) $ds['goods_attr'] = implode("、",$row['spec']);
				$this->App->insert('goods_order_daigou',$ds);
				$rec_id = $this->App->iid();
			
				//添加订单地址表
				if($rec_id > 0){
					$useradd = $this->Session->read("useradd.{$gid}"); 
					$l = 0;
					if(!empty($useradd)) foreach($useradd as $k=>$addresss){
						$dd = array();
						$dd['consignee'] = $addresss['consignee'];
						$dd['goods_number'] = !($addresss['number']>0) ? 1 : $addresss['number'];
						$dd['moblie'] = $addresss['moblie'];
						//$dd['province'] = $provinces[$k];
						//$dd['city'] = $citys[$k];
						//$dd['district'] = $districts[$k];
						$dd['address'] = !empty($addresssall[$gid][$l]) ? $addresssall[$gid][$l] : $addresss['address'];
						$dd['rec_id'] = $rec_id;
						$this->App->insert('goods_order_address',$dd);
						++$l;
					}
				}
				
			}
		}
		$this->Session->write('cart',null);
		$this->Session->write('useradd',null);
		$this->jump(ADMIN_URL.'mycart.php?type=pay&oid='.$orderid);exit;
		
		exit;
	}
	//第三版(代购模式)
	function checkout2(){
		//$this->js('mycart.js');
		$this->title('确认订单 - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL); exit;}
		$goodslist = $this->Session->read('cart');
		if(empty($goodslist)){
			$this->jump(ADMIN_URL,0,'购物车为空！'); exit;
		}
		$useradd = $this->Session->read('useradd');
		
		//查找收货地址 
		$sql = "SELECT tb1.*,tb2.region_name AS provinces,tb3.region_name AS citys,tb4.region_name AS districts FROM `{$this->App->prefix()}user_address` AS tb1";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb2 ON tb2.region_id = tb1.province";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb3 ON tb3.region_id = tb1.city";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS tb4 ON tb4.region_id = tb1.district";
		$sql .=" WHERE tb1.user_id='$uid' AND tb1.is_own='0' ORDER BY tb1.is_default DESC, tb1.address_id ASC LIMIT 1";
		$rt['userress'] = $this->App->findrow($sql);
				
		$rt['goodslist'] = array();
		$counts = 0;
		if(!empty($goodslist)){
			foreach($goodslist as $k=>$row){
				if($row['is_jifen_session']=='1'){
					$this->Session->write("cart.$k",null);
					$this->Session->write('useradd.$k',null); 
					continue; 
				}
				if(empty($useradd[$k]) || !isset($useradd[$k])){ //当前地址为空的时候写入session
					if(empty($rt['userress'])){
						$useradd[$k][1234567] = array('address'=>'','number'=>1,'consignee'=>'','moblie'=>'');
					}else{
						$us = $rt['userress']['provinces'].$rt['userress']['citys'].$rt['userress']['districts'].$rt['userress']['address'];
						$useradd[$k][1234567] = array('address'=>$us,'number'=>1,'consignee'=>$rt['userress']['consignee'],'moblie'=>$rt['userress']['mobile']);
					}
				}
				$counts +=$row['number'];
				$this->Session->write("cart.{$k}.spec.number",null);
			}
			
			//写入地址
			$this->Session->write('useradd',$useradd); 
			
			//计算地址数量
			/*foreach($useradd as $gid=>$item){
					if(!empty($item))foreach($item as $count){
						if(!isset($goodslist[$gid])){
						 $this->Session->write("useradd.$gid",null); 
						 continue;
						}
						++$counts;
					}
			}*/
			
			//计算折扣
			$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";//配置信息
			$rts = $this->App->findrow($sql);
			$off = 1;
			$sql = "SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
			$issubscribe = $this->App->findvar($sql); 
			$guanzhuoff = $rts['guanzhuoff'];
			$address3off = $rts['address3off'];
			$address2off = $rts['address2off'];
			if($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0){
				$off = ($guanzhuoff/100);
			}
			if($counts >= 2 && $address2off < 101 && $address2off > 0){
				$off = ($address2off/100);
			}
			if($counts >= 3 && $address3off < 101 && $address3off > 0){
				$off = ($address3off/100) * $off;
			}
			
			//设置价格
			$useradd = $this->Session->read('useradd'); 
			foreach($goodslist as $k=>$row){
				//$this->Session->write("cart.{$k}.number",count($useradd[$k])); //当前商品的总数量
				$price = format_price($row['pifa_price'] * $off);
				$this->Session->write("cart.{$k}.price",$price);
				$this->Session->write("cart.{$k}.zprice",$price*$row['number']);
			}
		 }
		
		//支付方式
		$sql = "SELECT * FROM `{$this->App->prefix()}payment` WHERE enabled='1'";
		$rt['paymentlist'] = $this->App->find($sql);
		
		//配送方式
		$sql = "SELECT * FROM `{$this->App->prefix()}shipping`";
		$rt['shippinglist'] = $this->App->find($sql);
		
		
		$this->set('rt',$rt);
		if(!defined(NAVNAME)) define('NAVNAME', "确认订单");	
		$this->template('mycart_checkout');		
	}
	
	//
	function ajax_address_writesess($data=array()){
		$kk = $data['kk'];
		$gid = $data['gid'];
		$consignee = $data['consignee'];
		$moblie = $data['moblie'];
		$address = $data['address'];
		$number = $data['number'];
		$ud = array('address'=>$address,'number'=>$number,'consignee'=>$consignee,'moblie'=>$moblie);
		$this->Session->write("useradd.{$gid}.{$kk}",$ud);
		$n = $this->Session->read("cart.{$gid}.number");
		$this->Session->write("cart.{$gid}.number",(intval($n)+intval($number)));
	}
	//移除单个商品地址
	function ajax_remove_goods_address($data=array()){
		$kk = trim($data['kk']);
		$gid = intval($data['gid']);
		$number = intval($data['number']);
		$d = $this->Session->read("useradd.{$gid}.{$kk}");
		$this->Session->write("useradd.{$gid}.{$kk}",null);
		$n = $this->Session->read("cart.{$gid}.number");
		$this->Session->write("cart.{$gid}.number",(intval($n)-intval($number)));
	}
	//改变地址商品数量
	function ajax_change_goods_number($data=array()){
		$kk = $data['kk'];
		$gid = intval($data['gid']);
		$n = intval($data['n']); //当前地址的数量
		$ty = $data['ty'];
		$nums = $this->Session->read("cart.{$gid}.number");
		//echo 'gid:'.$gid.'kk:'.$kk.'nums:'.$nums.'ty:'.$ty.'n:'.$n;
		//exit;
		if($ty=='jian'){
			$this->Session->write("cart.{$gid}.number",(intval($nums)-1));
			$this->Session->write("useradd.{$gid}.{$kk}.number",$n);
		}else{
			$this->Session->write("cart.{$gid}.number",(intval($nums)+1));
			$this->Session->write("useradd.{$gid}.{$kk}.number",$n);
		}
	}
	
	//计算价格
	function ajax_jisuan_price(){
		//返回数据
		/*
		1、error:记录错误参数
		2、totalprice：总价格
		3、单个产品的数据：1、price:惊喜价,2、zprice:小计3、gid:产品ID
		*/
		$err = 0;
		$result = array('error' => $err, 'totalprice'=>'0.00','goods'=>'','message' => '');
		$json = Import::json();
		//die($json->encode($result));
		
		$goodslist = $this->Session->read('cart');
		$useradd = $this->Session->read('useradd');
		
		//计算地址数量
		/*$counts = 0;
		if(!empty($useradd)) foreach($useradd as $gid=>$item){
			if(!empty($item))foreach($item as $count){
				if(!isset($goodslist[$gid])){
				 $this->Session->write("useradd.$gid",null); 
				 continue;
				}
				++$counts;
			}
		}*/
		
		$counts = 0;
		if(!empty($goodslist))foreach($goodslist as $k=>$row){
			$counts +=$row['number'];
		}
			
			
		//计算折扣
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";//配置信息
		$rts = $this->App->findrow($sql);
		$off = 1;
		$sql = "SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
		$issubscribe = $this->App->findvar($sql); 
		$guanzhuoff = $rts['guanzhuoff'];
		$address3off = $rts['address3off'];
		$address2off = $rts['address2off'];
		if($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0){
			$off = ($guanzhuoff/100);
		}
		if($issubscribe == '1' && $counts >= 2 && $address2off < 101 && $address2off > 0){
			$off = ($address2off/100);
		}
		if($issubscribe == '1' && $counts >= 3 && $address3off < 101 && $address3off > 0){
			$off = ($address3off/100) * $off;
		}
		
		//设置价格
		$useradd = $this->Session->read('useradd'); 
		$totalprice = 0;
		$grt = array();
		if(!empty($goodslist)) foreach($goodslist as $k=>$row){
			$price = format_price($row['pifa_price'] * $off);
			$this->Session->write("cart.{$k}.price",$price);
			$zprice = $price*$row['number'];
			$this->Session->write("cart.{$k}.zprice",$zprice); //单个产品的总价
			$totalprice +=$zprice;
			$grt[] = $price.','.$zprice.','.$row['goods_id'];
		}
		if(empty($grt)){
			$result['error'] = 1;
			$result['message'] = "非法错误";
			die($json->encode($result));
		}
		
		$result = array('error' => 0, 'totalprice'=>$totalprice,'goods'=>implode('|',$grt),'message' => '');
		die($json->encode($result));
	}
	
	function ajax_change_carval($data=array()){
		$kk = $data['kk'];
		$gid = $data['gid'];
		$ty = explode('[',$data['type']);
		$type = $ty[0];
		$val = $data['val'];
		switch($type){
			case 'consignee':
			$this->Session->write("useradd.{$gid}.{$kk}.consignee",$val);
			break;
			case 'moblie':
			$this->Session->write("useradd.{$gid}.{$kk}.moblie",$val);
			break;
			case 'address':
			$this->Session->write("useradd.{$gid}.{$kk}.address",$val);
			break;
		}
	}
	
	/******************************************/
   	function index(){
		$this->js('mycart.js');
		$this->title('我的购物车 - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL); exit;}
		
		$hear[] = '<a href="'.ADMIN_URL.'">首页</a>';
		$hear[] = '<a href="'.ADMIN_URL.'mycart.php">我的购物车</a>';
		$rt['hear'] = implode('&nbsp;&gt;&nbsp;',$hear);
		
		//用户等级折扣
		$rt['discount'] = 100;
		$rank = $this->Session->read('User.rank');
		if($rank>0){
			$sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
			$rt['discount'] = $this->App->findvar($sql);
		}
		
		$active = $this->Session->read('User.active');
		//购物车商品
		$goodslist = $this->Session->read('cart'); 
		$rt['goodslist'] = array();
		if(!empty($goodslist)){
			foreach($goodslist as $k=>$row){
				$rt['goodslist'][$k] = $row;
				//$rt['goodslist'][$k]['url'] = get_url($row['goods_name'],$row['goods_id'],'product.php?id='.$row['goods_id'],'goods',array('product','index',$row['goods_id']));
				$rt['goodslist'][$k]['goods_thumb'] = SITE_URL.$row['goods_thumb'];
				$rt['goodslist'][$k]['goods_img'] = SITE_URL.$row['goods_img'];
				$rt['goodslist'][$k]['original_img'] = SITE_URL.$row['original_img'];
				
				//求出实际价格
				 $comd = array();
				 if(!empty($uid)&&$active=='1'){
				 	  $comd[] =  $row['qianggou_price'];
					  //同一折扣价格
					  if($rt['discount']>0){
					      	$comd[] = ($rt['discount']/100)*$row['market_price'];
					  }
					  if($row['shop_price']>0){ //普通会员价格
							$comd[] =  $row['shop_price']; //普通会员价格
					  }
	
				 }else{
						$comd[] = $row['market_price'];
				 }
				 
			     if($row['is_promote']=='1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime()){ //促销价格
					    $comd[] =  $row['promote_price'];
			     }
			     if($row['is_qianggou']=='1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime()){ //抢购价格
					    $comd[] =  $row['qianggou_price'];
			     }
					   
				 $onetotal = min($comd);
				 if(intval($onetotal)<=0) $onetotal = $row['market_price'];
				 $total +=($row['number']*$onetotal); //总价格
				 
			}
			unset($goodslist);
		}
		
		if(!defined(NAVNAME)) define('NAVNAME', "购物车");		 
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/mycart_list');
	}
	//订单确认
	function checkout(){
		$this->action('common','checkjump');
		$this->title('确认订单 - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		
		$goodslist = $this->Session->read('cart');
		
		if(empty($goodslist)){
			//$this->jump(ADMIN_URL,0,'购物车为空，请先加入购物车！');exit;
			if(!defined(NAVNAME)) define('NAVNAME', "去购买吧");		 
			$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
			$this->template($mb.'/mycart_checkout_empty');
			exit;
		}

		$rt['province'] = $this->action('user','get_regions',1);  //获取省列表
		
		$sql = "SELECT ua.*,rg.region_name AS provincename,rg1.region_name AS cityname,rg2.region_name AS districtname FROM `{$this->App->prefix()}user_address` AS ua";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg ON rg.region_id = ua.province";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg1 ON rg1.region_id = ua.city";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg2 ON rg2.region_id = ua.district WHERE ua.user_id='$uid' AND ua.is_own='0' GROUP BY ua.address_id";
		$rt['userress'] = $this->App->find($sql);
		
		$sql = "SELECT * FROM `{$this->App->prefix()}payment` WHERE enabled='1'";
		$rt['paymentlist'] = $this->App->find($sql);
		
		//配送方式
		$sql = "SELECT * FROM `{$this->App->prefix()}shipping`";
		$rt['shippinglist'] = $this->App->find($sql);
		
		//用户等级
		$rank = $this->App->findvar("SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1");
		
		foreach($goodslist as $k=>$row){
			$original_img = $this->App->findvar("SELECT `original_img` FROM `{$this->App->prefix()}goods` WHERE `goods_id`=".$row['goods_id']);
			$goodslist[$k]['original_img'] = '/'.$original_img;
			$comd = array();
			$comd[] = format_price($row['pifa_price']);
			if($row['is_promote']=='1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime() && $row['promote_price']>0){ //促销价格
					$comd[] =  $row['promote_price'];
			 }
			 if($row['is_qianggou']=='1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime() && $row['qianggou_price']>0){ //抢购价格
					$comd[] =  $row['qianggou_price'];
			 }
			$price = min($comd);
			$this->Session->write("cart.{$k}.price",$price);
		}	
		
		//我的余额
		$sql = "SELECT mymoney,user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
		$rt['mymoney'] = $this->App->findvar($sql);
		if(empty($rt['mymoney'])) $rt['mymoney'] = '0.00';

		if(!defined(NAVNAME)) define('NAVNAME', "确认订单");
		$this->set('goodslist',$goodslist);
		$this->set('rank',$rank);
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		
		$this->template($mb.'/mycart_checkout');
	}
	
	/*
	确认订单提交页面
	*/
	function confirm(){
		$this->title('我的购物车 - 订单号 - '.$GLOBALS['LANG']['site_name']);
		$uid = $this->Session->read('User.uid');
		if(empty($uid)){ $this->jump(ADMIN_URL.'user.php?act=login',0,'请先登录！'); exit;}
		
		if(isset($_POST)&&!empty($_POST)){
			//购物车商品
			$cartlist = $this->Session->read('cart');
			
			if(empty($cartlist)){
				$this->jump(ADMIN_URL.'mycart.php',0,'购物车商品为空!'); exit;
			}
			
			$shipping_id = isset($_POST['shipping_id']) ? $_POST['shipping_id'] : 0;
			$userress_id = isset($_POST['userress_id']) ? $_POST['userress_id'] : 0;
			$dd = array();
			if(!($userress_id > 0)){  //如果是提交添加地址的，需要添加到user_address表
				//添加收货地址
				$dd['user_id'] = $uid;
				$dd['consignee'] = $_POST['consignee'];
				if(empty($dd['consignee'])){
					$this->jump(ADMIN_URL.'mycart.php?type=checkout',0,'收货人不能为空！'); exit ;
				}
				$dd['country'] = 1;
				$dd['province'] = $_POST['province'];
				$dd['city'] = $_POST['city'];
				$dd['district'] = $_POST['district'];
				$dd['address'] = $_POST['address'];
				//$dd['email'] = $_POST['email'];
				if(empty($dd['province']) || empty($dd['city']) || empty($dd['district']) ||empty($dd['address'])){
					$this->jump(ADMIN_URL.'mycart.php?type=checkout',0,'收货地址不能为空！'); exit ;
				}
			
				$dd['mobile'] = $_POST['mobile'];
				$dd['is_default'] = '1';
				$dd['shoppingname'] = $shipping_id;
				$this->App->update('user_address',array('is_default'=>'0'),'user_id',$uid);
				$this->App->insert('user_address',$dd); //添加到地址表
				$userress_id  = $this->App->iid();
				
				if(!($userress_id>0)){
					$this->jump(ADMIN_URL.'mycart.php?type=checkout',0,'非法的收货地址！'); exit ;
				}
			}
			
			$pay_id = isset($_POST['pay_id']) ? $_POST['pay_id'] : 0;
			$pay_name = $this->App->findvar("SELECT pay_name FROM `{$this->App->prefix()}payment` WHERE pay_id='$pay_id' LIMIT 1");

			$postscript = isset($_POST['postscript']) ? $_POST['postscript'] : "";
			if(empty($dd)){
				//收货信息
				$sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE address_id='$userress_id' LIMIT 1";
				$user_ress = $this->App->findrow($sql);
				if(empty($user_ress)){ $this->jump(ADMIN_URL.'mycart.php?type=checkout',0,'非法收货地址！'); exit ;}
			}else{
				$user_ress = $dd;
				unset($dd);
			}
			$shipping_name = $this->App->findvar("SELECT shipping_name FROM `{$this->App->prefix()}shipping` WHERE shipping_id='$shipping_id' LIMIT 1");
			
			//添加信息到数据表
			$orderdata['order_sn']= date('Y',mktime()).mktime();
			$orderdata['user_id']= $uid ? $uid : 0;
			
			$daili_uid = $this->return_daili_uid($uid);//一级
			$orderdata['parent_uid'] = $daili_uid;
			
			//查找二级、三级代理
			if($daili_uid > 0){
				$sql = "SELECT p1_uid,p2_uid,p3_uid FROM `{$this->App->prefix()}user_tuijian_fx` WHERE uid ='$daili_uid' LIMIT 1";
				$pr = $this->App->findrow($sql);
				$parent_uid = isset($pr['p1_uid']) ? $pr['p1_uid'] : 0;
				$orderdata['parent_uid2'] = $parent_uid >0&&$parent_uid!=$daili_uid ? $parent_uid : '0'; //上二级
				
				$parent_uid = isset($pr['p2_uid']) ? $pr['p2_uid'] : 0;
				$orderdata['parent_uid3'] = $parent_uid >0&&$parent_uid!=$daili_uid ? $parent_uid : '0'; //上三级

			}
			$orderdata['consignee'] = $user_ress['consignee'] ? $user_ress['consignee'] : "";
			$orderdata['province'] = $user_ress['province'] ? $user_ress['province'] : 0;
			$orderdata['city'] = $user_ress['city'] ? $user_ress['city'] : 0;
			$orderdata['district'] = $user_ress['district'] ? $user_ress['district'] : 0;
			$orderdata['address'] = $user_ress['address'] ? $user_ress['address'] : "";
			$orderdata['mobile']  = $user_ress['mobile'] ? $user_ress['mobile'] : "";
			$orderdata['shipping_id']  = $shipping_id;
			$orderdata['shipping_name']  = $shipping_name;
			if(isset($_POST['best_time']) && !empty($_POST['best_time'])){
				$orderdata['best_time']  = trim($_POST['best_time']);
			}
			$orderdata['pay_id']  = $pay_id ? $pay_id : 0;
			$orderdata['pay_name']  = $pay_name ? $pay_name : "";
			$orderdata['postscript']  = $postscript;
			$orderdata['type']  = 3;

		
			$k=0;
			$total = 0;
			$jifen_onetotal = 0;

			foreach($cartlist as $row){
				 $data[$k]['goods_id'] = $row['goods_id'];
				 $data[$k]['brand_id'] = $row['brand_id'];
				 $data[$k]['goods_name'] = $row['goods_name'];
				 $data[$k]['goods_bianhao'] = $row['goods_bianhao'];
				 $data[$k]['goods_thumb'] = $row['goods_thumb'];
				 $data[$k]['goods_sn'] = $row['goods_sn'];
				 $data[$k]['goods_number'] = $row['number'];
				 if(!empty($row['buy_more_best'])){
				 	$data[$k]['buy_more_best'] = $row['buy_more_best']; //买多送多，如：10送1
				 }
				
				 // $mprice = $row['pifa_price'] > 0 ? $row['pifa_price'] : $row['shop_price'];
				 $mprice = $row['shop_price']; //零售价
				// $onetotal = format_price($row['pifa_price'] * $off);
				 $onetotal = $row['price'];
				//$prices += format_price($onetotal*$row['number']);
		 		 $mprices += $mprice * $row['number'];//零售总价
				 $total += $row['number']*$onetotal; //折扣总价
				 if($row['takemoney1'] > 0) $data[$k]['takemoney1'] = $row['takemoney1']; //佣金
				  if($row['takemoney2'] > 0) $data[$k]['takemoney2'] = $row['takemoney2']; //佣金
				   if($row['takemoney3'] > 0) $data[$k]['takemoney3'] = $row['takemoney3']; //佣金
				 $data[$k]['market_price'] = $mprice;
				 $data[$k]['goods_price'] = $onetotal; //实际价格
				 $data[$k]['goods_attr'] = !empty($row['spec']) ? $row['goods_brief'].implode("<br />",$row['spec']) : $row['goods_brief'];
				 $data[$k]['goods_unit'] = $row['goods_unit'];
				 
				 if(isset($_POST['confirm_jifen']) && intval($_POST['confirm_jifen'])>0){
				 	if($row['is_jifen_session']=='1'){
				 		$data[$k]['from_jifen'] = $row['need_jifen']*$row['number'];
						$jifen_onetotal += $s;
					}
				 }
				 $k++;

			}
			
			
			//邮费
			$d = array('userress_id'=>$userress_id,'shopping_id'=>$shipping_id);
			$fr = $this->ajax_jisuan_shopping($d,'cart'); //邮费
			
			$n = ($fr>0) ? format_price($fr) : '0';
			$orderdata['goods_amount']  = format_price($mprices);
			$orderdata['order_amount']  = $_POST['xinghao'];
			//$orderdata['offprice']  = $moneyinfo['offmoney']; 
			$orderdata['add_time'] = mktime();
			//$orderdata['shipping_fee'] = $n; //邮费
			$orderdata['shipping_fee'] = 0; //邮费
			$orderdata['youfei'] = 0; //邮费
			
			
			if($this->App->insert('goods_order_info',$orderdata)){ //订单成功后
				$iid = $this->App->iid();
				
				foreach($data as $kk=>$rows){
					$rows['order_id'] = $iid;
					
					$this->App->insert('goods_order',$rows);  //添加订单商品表
					
					//更新销售数量
/*					$gid = $rows['goods_id'];
					$num = $rows['goods_number']; //look 添加 库存量在购买成功后减少
					if($gid>0 && $rows['is_gift']!='1'){
						$sql = "UPDATE `{$this->App->prefix()}goods` SET `sale_count` = `sale_count`+1 , `goods_number` = `goods_number`- '$num' WHERE goods_id = '$gid'";
						$this->App->query($sql);
					}*/
				}
				
				$this->_return_money($orderdata['order_sn']);
				
				$this->Session->write('cart',"");
				//发送通知
				/**
				$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$uid' AND is_subscribe='1' LIMIT 1");
				
				if(!empty($pwecha_id)){
					$this->action('api','sendtxt',array('openid'=>$pwecha_id,'appid'=>'','appsecret'=>'','nickname'=>''),'orderconfirm');
				}
				**/
				//通知商家
				$wid = $this->App->findvar("SELECT wid FROM `{$this->App->prefix()}userconfig` WHERE type='basic' LIMIT 1");
				if($wid > 0){
					$pwecha_id = $this->App->findvar("SELECT wecha_id FROM `{$this->App->prefix()}user` WHERE user_id='$wid' AND is_subscribe='1' LIMIT 1");
					if(!empty($pwecha_id)) $this->action('api','sendtxt',array('openid'=>$pwecha_id,'appid'=>'','appsecret'=>'','nickname'=>''),'orderconfirm_toshop');
				}
				
				$this->jump(ADMIN_URL.'mycart.php?type=pay2&oid='.$iid);exit;
				
				
				$rt['order_sn'] = $orderdata['order_sn'];
				$rt['shipping_name'] = $shipping_name;
				$rt['pay_name'] = $pay_name;
				$rt['total'] = format_price($orderdata['order_amount']);
				$rt['shipping_fee'] = 0; //邮费
				
				$rts['pay_id'] = $pay_id;
				$rts['order_sn'] = $rt['order_sn'];
				$rts['order_amount'] = $rt['total'];
				$rts['username'] = $orderdata['consignee'];
				$rts['logistics_fee'] = $rt['shipping_fee'];
				
				$sql = "SELECT ua.address,ua.zipcode,ua.tel,ua.mobile,rg.region_name AS provincename,rg1.region_name AS cityname,rg2.region_name AS districtname FROM `{$this->App->prefix()}user_address` AS ua";
				$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg ON rg.region_id = ua.province";
				$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg1 ON rg1.region_id = ua.city";
				$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg2 ON rg2.region_id = ua.district WHERE ua.address_id='$userress_id' LIMIT 1";
				$userress = $this->App->findrow($sql);
		
				$rts['address'] = $userress['provincename'].'&nbsp;'.$userress['cityname'].'&nbsp;'.$userress['districtname'].'&nbsp;'.$userress['address'];
				$rts['zip'] = !empty($userress['zipcode']) ? $userress['zipcode'] : $orderdata['zipcode'];
				$rts['phone'] = !empty($userress['tel']) ? $userress['tel'] : $orderdata['tel'];
				$rts['mobile'] = !empty($userress['mobile']) ? $userress['mobile'] : $orderdata['mobile'];
				$this->Session->write('cart',"");
				$this->_alipayment($rts);
				
				exit;

			
				$this->set('rt',$rt);
				$this->Session->write('cart',"");
				$this->template('mycart_submit_order');
				exit;
			}else{
				$this->jump(ADMIN_URL.'mycart.php',0,'您的订单没有提交成功，我们是尽快处理出现问题！'); exit;
			}
			
		}else{
			$this->App->write('cart',"");
			$this->jump(ADMIN_URL.'mycart.php');
		}
		$this->App->write('cart',"");
		$this->jump(ADMIN_URL.'mycart.php',0,'意外错误，我们是尽快处理出现问题！'); exit;
	}
	
	//返佣缓存
	function _return_money($order_sn=''){
		@set_time_limit(300); //最大运行时间
		
		//送佣金，找出推荐用户
		$pu = $this->App->findrow("SELECT user_id,daili_uid,parent_uid,parent_uid2,parent_uid3,parent_uid4,goods_amount,order_amount,order_sn,pay_status,order_id FROM `{$this->App->prefix()}goods_order_info` WHERE order_sn='$order_sn' LIMIT 1");
		$parent_uid = isset($pu['parent_uid']) ? $pu['parent_uid'] : 0; //分享者
		$parent_uid2 = isset($pu['parent_uid2']) ? $pu['parent_uid2'] : 0; //分享者
		$parent_uid3 = isset($pu['parent_uid3']) ? $pu['parent_uid3'] : 0; //分享者
		$parent_uid4 = isset($pu['parent_uid4']) ? $pu['parent_uid4'] : 0; //分享者
		
		$daili_uid = isset($pu['daili_uid']) ? $pu['daili_uid'] : 0; //代理
		$moeys = isset($pu['order_amount']) ? $pu['order_amount'] : 0; //实际消费
		$uid = isset($pu['user_id']) ? $pu['user_id'] : 0;
		$pay_status = isset($pu['pay_status']) ? $pu['pay_status'] : 0;
		
		$order_id = isset($pu['order_id']) ? $pu['order_id'] : 0;
		
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";//用户配置信息
		$rts = $this->App->findrow($sql);
		
			
		if(!empty($order_sn)){
			//计算每个产品的佣金
			$sql = "SELECT takemoney1,takemoney2,takemoney3,goods_number FROM `{$this->App->prefix()}goods_order` WHERE order_id='$order_id'";
			$moneys = $this->App->find($sql);
		
			$thismonth = date('Y-m-d',mktime());
			$thism = date('Y-m',mktime());
			
			$moeysall = 0;
			if(!empty($moneys))foreach($moneys as $row){
				if($row['takemoney1'] > 0){
					$moeysall +=$row['takemoney1'] * $row['goods_number'];
				}
			}
			
			//购买者返佣
			$moeys = 0;
			$ticheng360_1 =  $rts['ticheng360_1'];
			if($ticheng360_1 > 0 ){
					$off = $ticheng360_1/100;
					$moeys = format_price($moeysall * $off);
					if($moeys>0){
					$this->App->insert('user_money_change_cache',array('buyuid'=>$uid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$moeys,'changedesc'=>'消费返佣金','time'=>mktime(),'uid'=>$uid,'level'=>'10'));
					}
			}
		
			$record = array();
			$moeys = 0;
			//一级返佣金
			if($parent_uid > 0){
				$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1";
				$rank = $this->App->findvar($sql);
				if($rank != '1'){ //不是普通会员
					//$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid' LIMIT 1";
					//$types = $this->App->findvar($sql);
					
					$off = 0;
						if($rank=='12'){ //普通分销商
							if($rts['ticheng180_1'] < 101 && $rts['ticheng180_1'] > 0){
								$off = $rts['ticheng180_1']/100;
							}
						}elseif($rank=='11'){//高级会员商
							if($rts['ticheng180_h1_1'] < 101 && $rts['ticheng180_h1_1'] > 0){
								$off = $rts['ticheng180_h1_1']/100;
							}
						}elseif($rank=='10'){//特权分销商
							if($rts['ticheng180_h2_1'] < 101 && $rts['ticheng180_h2_1'] > 0){
								$off = $rts['ticheng180_h2_1']/100;
							}
						}
						
					//}

					$moeys = format_price($moeysall * $off);
					if(!empty($moeys)){
						$this->App->insert('user_money_change_cache',array('buyuid'=>$uid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$moeys,'changedesc'=>'购买商品返佣金','time'=>mktime(),'uid'=>$parent_uid,'level'=>'1'));
					}
				}
			}
			
			$moeys = 0;
			//二级返佣金
			if($parent_uid2 > 0){
				$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid2' LIMIT 1";
				$rank = $this->App->findvar($sql);
				if($rank != '1'){ //不是普通会员
					//$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid2' LIMIT 1";
					//$types = $this->App->findvar($sql);
					
					$off = 0;
						if($rank=='12'){ //普通分销商
							if($rts['ticheng180_2'] < 101 && $rts['ticheng180_2'] > 0){
								$off = $rts['ticheng180_2']/100;
							}
						}elseif($rank=='11'){//高级会员商
							if($rts['ticheng180_h1_2'] < 101 && $rts['ticheng180_h1_2'] > 0){
								$off = $rts['ticheng180_h1_2']/100;
							}
						}elseif($rank=='10'){//特权分销商
							if($rts['ticheng180_h2_2'] < 101 && $rts['ticheng180_h2_2'] > 0){
								$off = $rts['ticheng180_h2_2']/100;
							}
						}
						
					//}
					$moeys = format_price($moeysall * $off);
					if(!empty($moeys)){
						$this->App->insert('user_money_change_cache',array('buyuid'=>$uid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$moeys,'changedesc'=>'购买商品返佣金','time'=>mktime(),'uid'=>$parent_uid2,'level'=>'2'));
					}
				}
			}
			
			$moeys = 0;
			//三级返佣金
			if($parent_uid3 > 0){
				$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1";
				$rank = $this->App->findvar($sql);
				if($rank != '1'){ //不是普通会员
					//$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1";
					//$types = $this->App->findvar($sql);
					
					$off = 0;

						if($rank=='12'){ //普通分销商
							if($rts['ticheng180_3'] < 101 && $rts['ticheng180_3'] > 0){
								$off = $rts['ticheng180_3']/100;
							}
						}elseif($rank=='11'){//高级会员商
							if($rts['ticheng180_h1_3'] < 101 && $rts['ticheng180_h1_3'] > 0){
								$off = $rts['ticheng180_h1_3']/100;
							}
						}elseif($rank=='10'){//特权分销商
							if($rts['ticheng180_h2_3'] < 101 && $rts['ticheng180_h2_3'] > 0){
								$off = $rts['ticheng180_h2_3']/100;
							}
						}
						
					$moeys = format_price($moeysall * $off);
					if(!empty($moeys)){
						$this->App->insert('user_money_change_cache',array('buyuid'=>$uid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$moeys,'changedesc'=>'购买商品返佣金','time'=>mktime(),'uid'=>$parent_uid3,'level'=>'3'));
					}
				}
			}//end if
                        
            $moeys = 0;
			//四级返佣金
			if($parent_uid4 > 0){
				$sql = "SELECT user_rank FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid4' LIMIT 1";
				$rank = $this->App->findvar($sql);
				if($rank != '1'){ //不是普通会员
					//$sql = "SELECT types FROM `{$this->App->prefix()}user` WHERE user_id='$parent_uid3' LIMIT 1";
					//$types = $this->App->findvar($sql);
					
					$off = 0;

						if($rank=='12'){ //普通分销商
							if($rts['ticheng180_4'] < 101 && $rts['ticheng180_4'] > 0){
								$off = $rts['ticheng180_4']/100;
							}
						}elseif($rank=='11'){//高级会员商
							if($rts['ticheng180_h1_4'] < 101 && $rts['ticheng180_h1_4'] > 0){
								$off = $rts['ticheng180_h1_4']/100;
							}
						}elseif($rank=='10'){//特权分销商
							if($rts['ticheng180_h2_4'] < 101 && $rts['ticheng180_h2_4'] > 0){
								$off = $rts['ticheng180_h2_4']/100;
							}
						}
						
					$moeys = format_price($moeysall * $off);
					if(!empty($moeys)){
						$this->App->insert('user_money_change_cache',array('buyuid'=>$uid,'order_sn'=>$order_sn,'thismonth'=>$thismonth,'thism'=>$thism,'money'=>$moeys,'changedesc'=>'购买商品返佣金','time'=>mktime(),'uid'=>$parent_uid4,'level'=>'4'));
					}
				}
			}//end if
			
		}
		
	}
	
	//快速支付
	function fastcheckout(){
		$oid = $_POST['order_id'];
		$uid = $this->Session->read('User.uid');
		$sql = "SELECT * FROM `{$this->App->prefix()}goods_order_info` WHERE pay_status = '0' AND order_id='$oid'";	
		$rt = $this->App->findrow($sql);
		
		if(empty($rt)){
			$this->jump(ADMIN_URL,0,'非法支付提交！'); exit;
		}
		
		$rts['pay_id'] = $rt['pay_id'];
		$rts['order_sn'] = $rt['order_sn'];
		$rts['order_amount'] = $rt['order_amount'];
		$rts['username'] = $orderdata['consignee'];
		$rts['logistics_fee'] = $rt['shipping_fee'];
		
		
		$sql = "SELECT ua.address,ua.zipcode,ua.tel,ua.mobile,rg.region_name AS provincename,rg1.region_name AS cityname,rg2.region_name AS districtname FROM `{$this->App->prefix()}goods_order_info` AS ua";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg ON rg.region_id = ua.province";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg1 ON rg1.region_id = ua.city";
		$sql .=" LEFT JOIN `{$this->App->prefix()}region` AS rg2 ON rg2.region_id = ua.district WHERE ua.order_id='$oid' LIMIT 1";
		$userress = $this->App->findrow($sql);

		$rts['address'] = $userress['provincename'].'&nbsp;'.$userress['cityname'].'&nbsp;'.$userress['districtname'].'&nbsp;'.$userress['address'];
		$rts['zip'] = !empty($userress['zipcode']) ? $userress['zipcode'] : $orderdata['zipcode'];
		$rts['phone'] = !empty($userress['tel']) ? $userress['tel'] : $orderdata['tel'];
		$rts['mobile'] = !empty($userress['mobile']) ? $userress['mobile'] : $orderdata['mobile'];
				
		$this->_alipayment($rts);
		unset($rt);
		exit;
	}
		
	//ajax更新购物的价格
	function ajax_change_price($data=array()){
		$json = Import::json();
		$id = $data['id'];
		$number = $data['number'];
		$shipping_id = $data['shipping_id'];
		$userress_id = $data['userress_id'];
		
		$maxnumber = $this->Session->read("cart.{$id}.goods_number");
		if($number>$maxnumber){
			$result = array('error' => 2, 'message' => "购买数量已经超过了库存，您最大只能购买:".$maxnumber);
			die($json->encode($result));
		}
		
		$uid = $this->Session->read('User.uid');
		
		$cartlist = $this->Session->read('cart');
		
		//返回总价
		$sql = "SELECT * FROM `{$this->App->prefix()}userconfig` LIMIT 1";//配置信息
		$rts = $this->App->findrow($sql);
		$sql = "SELECT is_subscribe FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
		$issubscribe = $this->App->findvar($sql); 
		$guanzhuoff = $rts['guanzhuoff'];
		$address3off = $rts['address3off'];
		$address2off = $rts['address2off'];
		$prices = 0;
		$thisprice = 0;
		
		$off = 1;
		if($issubscribe == '1' && $guanzhuoff < 101 && $guanzhuoff > 0){ //关注折扣
			$off = ($guanzhuoff/100);
		}
		$counts = 0;
		foreach($cartlist as $k=>$row){
			$counts += $row['number'];
		}
		if($issubscribe == '1' && $counts >= 2 && $address2off < 101 && $address2off > 0){
			$off = ($address2off/100) * $off; //相对关注再折扣
		}
		if($issubscribe == '1' && $counts >= 3 && $address3off < 101 && $address3off > 0){
			$off = ($address3off/100) * $off; //相对关注再折扣
		}
			
		foreach($cartlist as $k=>$row){
			$comd = array();
			$comd[] = format_price($row['pifa_price'] * $off);
			if($row['is_promote']=='1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime() && $row['promote_price']>0){ //促销价格
					$comd[] =  $row['promote_price'];
			 }
			 if($row['is_qianggou']=='1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime() && $row['qianggou_price']>0){ //抢购价格
					$comd[] =  $row['qianggou_price'];
			 }
			$price = min($comd);
			$this->Session->write("cart.{$k}.price",$price);
			if($id==$k){
				$thisprice = $price;
			}
			$prices += $price * $row['number'];
		}
		$prices = format_price($prices);
		
		unset($cartlist);
		//邮费
		$f = $this->ajax_jisuan_shopping(array('shopping_id'=>$shipping_id,'userress_id'=>$userress_id),'cart');
		$f = empty($f) ? '0' : $f;
		unset($cartlist);
		$result = array('error' => 0, 'message' => '1','prices'=>$prices,'thisprice'=>$thisprice,'freemoney'=>$f);
		die($json->encode($result));
	}
	
	//改变使用积分换取商品
	function ajax_change_jifen($is_confirm='true'){
		$uid = $this->Session->read('User.uid');
		$active = $this->Session->read('User.active');
		
		//用户等级折扣
		$discount = 100;
		$rank = $this->Session->read('User.rank');
		if($rank>0){
			$sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
			$discount = $this->App->findvar($sql);
		}
		
		$cartlist = $this->Session->read('cart');
		$total = 0;
		if(!empty($cartlist)){
			foreach($cartlist as $row){
				 $comd = array();
				  if(!empty($uid)&&$active=='1'){
						if($discount>0){
							$comd[] = ($discount/100)*$row['market_price'];
						}
					   if($row['shop_price']>0){ //普通会员价格
							$comd[] =  $row['shop_price']; //普通会员价格
					   }
	
				  }else{
						$comd[] = $row['market_price'];
				  }
	
				 if($row['is_promote']=='1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime()){ //促销价格
					    $comd[] =  $row['promote_price'];
			     }
			   
			     if($row['is_qianggou']=='1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime()){ //抢购价格
					    $comd[] =  $row['qianggou_price'];
			     }
				 
				//$onetotal = min($comd);
				$onetotal = $row['pifa_price'];
				$total +=($row['number']*$onetotal);
				
				//if($row['is_jifen_session']=='1'){
					$jifen_onetotal += $row['number']*$onetotal;
				//}
			}
		}
		unset($cartlist);
		//我的积分
		$sql = "SELECT SUM(points) FROM `{$this->App->prefix()}user_point_change` WHERE uid='$uid'";
		$mypoints = $this->App->findvar($sql);
		
		if($is_confirm=='true'){
			if($mypoints >= $jifen_onetotal ){
			echo $total-$jifen_onetotal;
			}else{
			echo $total-$mypoints;
			}
		}else{
			echo $total;
		}
		exit;
	}
	
	//ajax计算邮费
	function ajax_jisuan_shopping($data=array(),$tt='ajax'){
		$shopping_id = isset($data['shopping_id']) ? $data['shopping_id']: 0;
		$userress_id = isset($data['userress_id']) ? $data['userress_id']: 0;
		
		if(!($userress_id>0)){
			if($tt=='ajax'){
				die("请选择一个收货地址！");
			}else{
				return "0";
			}
		}
		if(!($shopping_id>0)){
			if($tt=='ajax'){
				die("请选择一个配送方式！");
			}else{
				return "0";
			}
		}
		
		$sql = "SELECT country,province,city,district FROM `{$this->App->prefix()}user_address` WHERE address_id='$userress_id'";
		$ids = $this->App->findrow($sql);
		if(empty($ids)){
			if($tt=='ajax'){
				die("请先设置一个收货地址！");
			}else{
				return "请先设置一个收货地址！";
			}
		}
		
		$cartlist = $this->Session->read('cart');
		$items = 0;
		$weights = 0;
		if(!empty($cartlist)){
			foreach($cartlist as $row){
				if($row['is_shipping']=='1' || $row['is_alone_sale']=='0') continue;
				$items +=$row['number'];
				$weights +=$row['goods_weight'];
			}
		}
		$weights = $weights*$items;
		$sql = "SELECT * FROM `{$this->App->prefix()}shipping_area` WHERE shipping_id='$shopping_id'";
		$area_rt = $this->App->find($sql);
		if(!empty($area_rt)){
			foreach($area_rt as $row){
				if(!empty($row['configure'])){
					$configure = json_decode($row['configure']);
					if(is_array($configure)){
						$type = $row['type'];
						$item_fee = $row['item_fee'];
						$weight_fee = $row['weight_fee'];
						$step_weight_fee = $row['step_weight_fee'];
						$step_item_fee = $row['step_item_fee'];
						$max_money = $row['max_money'];
						   
						if(in_array($ids['district'],$configure)){ //区
							if($type=='item'){  //件计算
								$zyoufei = $item_fee + (($items-1)*$step_item_fee);
								if($zyoufei>$max_money&&intval($max_money)>0) $zyoufei = $max_money;
								if($tt=='ajax'){
									die($row['shipping_area_name'].'+'.$zyoufei);
								}else{
									return $zyoufei;
								}
							}elseif($type=='weight'){ //重量计算
								if($weights>500){
								 	$zyoufei = $weight_fee + ((ceil(($weights-500)/500))*$step_weight_fee);
								 	if($zyoufei>$max_money&&intval($max_money)>0) $zyoufei = $max_money;
									if($tt=='ajax'){
										die($row['shipping_area_name'].'+'.$zyoufei);
									}else{
										return $zyoufei;
									}
								 }else{
								 	if(!($weights>0)) $weight_fee='0.00';
								 	if($tt=='ajax'){
										die($row['shipping_area_name'].'+'.$weight_fee);
									}else{
										return $weight_fee;
									}
								 }
							}
							break;
						}elseif(in_array($ids['city'],$configure)){ //城市
							if($type=='item'){  //件计算
								$zyoufei = $item_fee + (($items-1)*$step_item_fee);
								if($zyoufei>$max_money&&intval($max_money)>0) $zyoufei = $max_money;
								if($tt=='ajax'){
									die($row['shipping_area_name'].'+'.$zyoufei);
								}else{
									return $zyoufei;
								}
							}elseif($type=='weight'){ //重量计算
								if($weights>500){
								 	$zyoufei = $weight_fee + ((ceil(($weights-500)/500))*$step_weight_fee);
								 	if($zyoufei>$max_money&&intval($max_money)>0) $zyoufei = $max_money;
									if($tt=='ajax'){
										die($row['shipping_area_name'].'+'.$zyoufei);
									}else{
										return $zyoufei;
									}
								 }else{
								 	if(!($weights>0)) $weight_fee='0';
								 	if($tt=='ajax'){
										die($row['shipping_area_name'].'+'.$weight_fee);
									}else{
										return $weight_fee;
									}
								 }
							}
							break;
						}elseif(in_array($ids['province'],$configure)){ //省
							if($type=='item'){  //件计算
								$zyoufei = $item_fee + (($items-1)*$step_item_fee);
								if($zyoufei>$max_money&&intval($max_money)>0) $zyoufei = $max_money;
								if($tt=='ajax'){
									die($row['shipping_area_name'].'+'.$zyoufei);
								}else{
									return $zyoufei;
								}
							}elseif($type=='weight'){ //重量计算
								if($weights>500){
								 	$zyoufei = $weight_fee + ((ceil(($weights-500)/500))*$step_weight_fee);
								 	if($zyoufei>$max_money&&intval($max_money)>0) $zyoufei = $max_money;
									if($tt=='ajax'){
										die($row['shipping_area_name'].'+'.$zyoufei);
									}else{
										return $zyoufei;
									}
								 }else{
								 	if(!($weights>0)) $weight_fee='0';
								 	if($tt=='ajax'){
										die($row['shipping_area_name'].'+'.$weight_fee);
									}else{
										return $weight_fee;
									}
								 }
							}
							break;
						}elseif(in_array($ids['country'],$configure)){ //国家
							if($type=='item'){  //件计算
								$zyoufei = $item_fee + (($items-1)*$step_item_fee);
								if($zyoufei>$max_money&&intval($max_money)>0) $zyoufei = $max_money;
								if($tt=='ajax'){
									die($row['shipping_area_name'].'+'.$zyoufei);
								}else{
									return $zyoufei;
								}
							}elseif($type=='weight'){ //重量计算
								if($weights>500){
								 	$zyoufei = $weight_fee + ((ceil(($weights-500)/500))*$step_weight_fee);
								 	if($zyoufei>$max_money&&intval($max_money)>0) $zyoufei = $max_money;
									if($tt=='ajax'){
										die($row['shipping_area_name'].'+'.$zyoufei);
									}else{
										return $zyoufei;
									}
								 }else{
								 	if(!($weights>0)) $weight_fee='0';
								 	if($tt=='ajax'){
										die($row['shipping_area_name'].'+'.$weight_fee);
									}else{
										return $weight_fee;
									}
								 }
							}
							break;
						}
					} //end if
				} // end if
			} // end foreach
		}else{
			if($tt=='ajax'){
				die("");
			}else{
				return $zyoufei;
			}
		}
			if($tt=='ajax'){
				die("");
			}else{
				return $zyoufei;
			}
	}
	
	
	//删除购物车商品
	function ajax_delcart_goods($id=0){
		//if(empty($id)) return "";
		if(!empty($id)){
			$cartlist = $this->Session->read('cart');
			if(isset($cartlist[$id])){ $this->Session->write("cart.{$id}","");}
			unset($cartlist);
		}
		$uid = $this->Session->read('User.uid');
		//用户等级折扣
		$rt['discount'] = 100;
		$rank = $this->Session->read('User.rank');
		if($rank>0){
			$sql = "SELECT discount FROM `{$this->App->prefix()}user_level` WHERE lid='$rank' LIMIT 1";
			$rt['discount'] = $this->App->findvar($sql);
		}
		
		$active = $this->Session->read('User.active');
		$goodslist = $this->Session->read('cart');
		$rt['goodslist'] = array();
		if(!empty($goodslist)){
			foreach($goodslist as $k=>$row){
				$rt['goodslist'][$k] = $row;
				$rt['goodslist'][$k]['url'] = get_url($row['goods_name'],$row['goods_id'],'product.php?id='.$row['goods_id'],'goods',array('product','index',$row['goods_id']));
				$rt['goodslist'][$k]['goods_thumb'] = SITE_URL.$row['goods_thumb'];
				$rt['goodslist'][$k]['goods_img'] = SITE_URL.$row['goods_img'];
				$rt['goodslist'][$k]['original_img'] = SITE_URL.$row['original_img'];
				
				//求出实际价格
				 $comd = array();
				 if(!empty($uid)&&$active=='1'){
				 	 $comd[] = $row['market_price'];
						if($rt['discount']>0){
							$comd[] = ($rt['discount']/100)*$row['market_price'];
						}
					   if($row['shop_price']>0){ //普通会员价格
							$comd[] =  $row['shop_price']; //普通会员价格
					   }
	
				 }else{
						$comd[] = $row['market_price'];
				 }
				 
			     if($row['is_promote']=='1' && $row['promote_start_date'] < mktime() && $row['promote_end_date'] > mktime()){ //促销价格
					    $comd[] =  $row['promote_price'];
			     }
			     if($row['is_qianggou']=='1' && $row['qianggou_start_date'] < mktime() && $row['qianggou_end_date'] > mktime()){ //抢购价格
					    $comd[] =  $row['qianggou_price'];
			     }
					   
				 $onetotal = min($comd);
				 if(intval($onetotal)<=0) $onetotal = $row['market_price'];
				 $total +=($row['number']*$onetotal); //总价格
			}
			unset($goodslist);
		}
		
		//赠品类型
		$fn = SYS_PATH.'data/goods_spend_gift.php';
		$spendgift = array();
		if(file_exists($fn) && is_file($fn)){
				include_once($fn);
		}
		$rt['gift_typesd'] = $spendgift;
		unset($spendgift);
		
		//商品赠品模块
		$minspend = array();
		if(!empty($rt['gift_typesd'])){
			foreach($rt['gift_typesd'] as $k=>$row){
				++$k;
				$minspend[$k] = $row['minspend'];
			}
			arsort($minspend);
		}
		
		$rt['gift_goods'] = array();
		$type = 0; 
		if(count($minspend)>0){
			$count = count($minspend);
			foreach($minspend as $t=>$val){  //已最高消费赠品为准
				if($total>=$val){
					$type = $t; //赠品等级
					break;
				}
			}
			unset($minspend);
			//赠品
			$rt['gift_goods_ids'] = array();
			if($type>0){
				$sql = "SELECT tb2.goods_id,tb1.type,tb2.goods_name,tb2.market_price,tb2.goods_sn ,tb2.goods_bianhao,tb2.goods_thumb  FROM `{$this->App->prefix()}goods_gift` AS tb1";
				$sql .=" LEFT JOIN `{$this->App->prefix()}goods` AS tb2 ON tb1.goods_id=tb2.goods_id";
				$sql .=" WHERE (tb2.is_alone_sale='0' OR tb2.is_alone_sale IS NOT NULL) AND tb2.is_on_sale='1' tb2.is_check='1' AND AND tb2.is_delete='0' AND tb1.type='$type'";
				$gift_goods = $this->App->find($sql);
				if(!empty($gift_goods)){
					foreach($gift_goods as $k=>$row){
						$rt['gift_goods_ids'][] = $row['goods_id']; //记录赠品的id
					}
					unset($gift_goods);
				}
			}

		}	
		
		$this->set('rt',$rt);
		$con = $this->fetch('ajax_mycart',true);
		die($con);
	}

        //清空购物车
        function mycart_clear(){
            $this->Session->write("cart",null);
			$this->Session->write('useradd',null);
            $this->jump(ADMIN_URL);
            exit;
        }
}


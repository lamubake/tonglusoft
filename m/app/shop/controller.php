<?php
class ShopController extends Controller{
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
	
	function get_regions($type,$parent_id=0){
		$p = "";
		if(!empty($parent_id)) $p = "AND parent_id='$parent_id'";
		
		$sql= "SELECT region_id,region_name FROM `{$this->App->prefix()}region` WHERE region_type='$type' {$p} ORDER BY region_id ASC";
		return  $this->App->find($sql);
	}
	
	function applyshop(){
		$uid = $this->checked_login();
		//$this->action('common','checkjump');
		
		$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
		$rt['userinfo'] = $this->App->findrow($sql);
		if($rt['userinfo']['isshop']=='1'){
			$this->set('fxrank','1'); //是店铺
		}else{
			$this->set('fxrank','2'); //申请店铺
		}
		
		$rt['province'] = $this->get_regions(1);  //获取省列表
		
		//当前用户的收货地址
		$sql = "SELECT * FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";
		$rt['userress'] = $this->App->findrow($sql);

		if($rt['userress']['province']>0) $rt['city'] = $this->get_regions(2,$rt['userress']['province']);  //城市
		if($rt['userress']['city']>0) $rt['district'] = $this->get_regions(3,$rt['userress']['city']);  //区	
		
		$s = $fxrank!='1'? '申请开店' : '编辑资料';
		$this->title($s);
		
		$this->set('rt',$rt);
		if(!defined(NAVNAME)) define('NAVNAME', $s);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->template($mb.'/v3_applyshop');
	}
	
	function ajax_update_shopinfo($data= array()){
		$json = Import::json();
		$uid = $this->checked_login();
		if(empty($uid)){
			$result = array('error' => 3, 'message' => '先您先登录!');
			die($json->encode($result));
		}
		
		$result = array('error' => 2, 'message' => '传送的数据为空!');
		if(empty($data['fromAttr']))  die($json->encode($result));
		
		$fromAttr = $json->decode($data['fromAttr']); //反json ,返回值为对象
		unset($data);
		
		
		$datas['isshop'] = '2';
		$datas['email'] = $fromAttr->email;
		if(empty($datas['email'])){
			$result = array('error' => 4, 'message' => '请填写正确邮箱！');
			die($json->encode($result));
		}
		$datas['mobile_phone'] = $fromAttr->mobile_phone;
		if(empty($datas['mobile_phone'])){
			$result = array('error' => 4, 'message' => '请填写手机号码！');
			die($json->encode($result));
		}
		//检测该号码是否存在
		$mb = $datas['mobile_phone'];
		$sql = "SELECT user_id FROM `{$this->App->prefix()}user` WHERE mobile_phone = '$mb' AND user_id!='$uid' LIMIT 1";
		$id = $this->App->findvar($sql);
		if($id >0){
			$result = array('error' => 4, 'message' => '该手机号码已经被使用！');
			die($json->encode($result));
		}
		
		$datas['msn'] = $fromAttr->msn; //微信号
		if(empty($datas['msn'])){
			$result = array('error' => 4, 'message' => '请填写微信号！');
			die($json->encode($result));
		}
		$ni = $fromAttr->consignee;
		if(empty($ni)){
			$result = array('error' => 4, 'message' => '请填写真实姓名！');
			die($json->encode($result));
		}
		$this->App->update('user',$datas,'user_id',$uid);
		unset($datas);
			
		$sql = "SELECT address_id FROM `{$this->App->prefix()}user_address` WHERE user_id='$uid' AND is_own='1' LIMIT 1";
		$rsid = $this->App->findvar($sql);
	
		$datas['user_id'] = $uid;
		$datas['email'] = $fromAttr->email;
		$datas['mobile'] = $fromAttr->mobile_phone;
		$datas['consignee'] = $ni;
		$datas['province'] = $fromAttr->province;
		$datas['city'] = $fromAttr->city;
		$datas['district'] = $fromAttr->district;
		$datas['address'] = $fromAttr->address;
		if(!($datas['province'] > 0) || !($datas['city'] > 0) || !($datas['district'] > 0) || empty($datas['address'])){
			$result = array('error' => 4, 'message' => '请填写必要区域地址！');
			die($json->encode($result));
		}
		$datas['is_own'] = 1;
		
		if($rsid > 0){ //更新
			$this->App->update('user_address',$datas,'address_id',$rsid);
		}else{ //添加
			$this->App->insert('user_address',$datas);			
		}
		
		$result = array('error' => 4, 'message' => '操作成功！');
		die($json->encode($result));
			
		
		unset($datas);		
	}//end function
	
	//获取用户的openid
	function get_openid_AND_pay_info(){
		$wecha_id = $this->Session->read('User.wecha_id');
		if(empty($wecha_id)) $wecha_id = isset($_COOKIE[CFGH.'USER']['UKEY']) ? $_COOKIE[CFGH.'USER']['UKEY'] : '';
		
		//
		$order_sn = isset($_GET['order_sn']) ? $_GET['order_sn'] : '';
		if(empty($order_sn)) exit;
		
		$sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming_order` WHERE pay_status = '0' AND order_sn='$order_sn' LIMIT 1";
		$rt = $this->App->findrow($sql);
		if(empty($rt)){
			$this->jump(str_replace('/wxpay','',ADMIN_URL),0,'非法支付提交！'); exit;
		}
		if($rt['pay_status']=='1'){
			$this->jump(str_replace('/wxpay','',ADMIN_URL));exit;
		}
		$rt['openid'] = $wecha_id;
		$rt['body'] = $GLOBALS['LANG']['site_name'].'-在线报名';
		return $rt;
	}
	
	function confirmpay($data=array()){
		if(!empty($_POST)){
			$uname = $_POST['uname'];
			$upne = $_POST['upne'];
			$price = $_POST['price'];
			$ids = $_POST['ids'];
			if(empty($uname)||empty($upne)||empty($price)||empty($ids)){
				exit;
			}
			$uid = $this->checked_login();
			$on = date('Y',mktime()).mktime();
			$dd = array();
			$dd['bid'] = $ids;
			$dd['order_sn'] = $on;
			$dd['user_id'] = $uid;
			$dd['order_amount'] = $price;
			$dd['uname'] = $uname;
			$dd['upne'] = $upne;
			$dd['add_time'] = mktime();
			
			if($this->App->insert('cx_baoming_order',$dd)){
				$this->jump(ADMIN_URL.'wxpay/js_api_call.php?order_sn='.$on.'&bm=baoming');
				exit;	
			}else{
				$this->jump(ADMIN_URL,0,'意外错误');
				exit;
			}
		}
		
	}
	
	//报名
	function baoming($data=array()){
		//$this->action('common','checkjump');
	 
		$id = isset($_GET['id']) ? $_GET['id'] : 0;
	 	$t = Common::_return_px();
	    $cache = Import::ajincache();
	    $cache->SetFunction(__FUNCTION__);
	    $cache->SetMode('page'.$t);
	    $fn = $cache->fpath(array('0'=>$id));
	    if(file_exists($fn)&&!$cache->GetClose()){
				include($fn);
	    }
	    else
	    {
			$s = '';
			if($id > 0) $s = "WHERE id='$id'";
			$sql = "SELECT * FROM `{$this->App->prefix()}cx_baoming` {$s} ORDER BY id DESC LIMIT 1";
			$rt['pinfo'] = $this->App->findrow($sql);
			
			$cache->write($fn, $rt,'rt');
		}
		
		$uid = $this->Session->read('User.uid');
		$rt['tjr']['nickname'] = '[官网]';
		$rt['tjr']['headimgurl'] = ADMIN_URL.'images/uclicon.jpg';
		$rt['uinfo'] = array();
		if($uid > 0){
			$sql = "SELECT tb1.nickname,tb1.headimgurl FROM `{$this->App->prefix()}user` AS tb1 LEFT JOIN `{$this->App->prefix()}user_tuijian` AS tb2 ON tb1.user_id = tb2.parent_uid LEFT JOIN `{$this->App->prefix()}user` AS tb3 ON tb3.user_id = tb2.uid WHERE tb3.user_id='$uid' LIMIT 1";
			$rt['tjr'] = $this->App->findrow($sql);
			if(empty($rt['tjr'])){
					$rt['tjr']['nickname'] = '[官网]';
					$rt['tjr']['headimgurl'] = ADMIN_URL.'images/uclicon.jpg';
			}
			$sql = "SELECT * FROM `{$this->App->prefix()}user` WHERE user_id='$uid' LIMIT 1";
			$rt['uinfo'] = $this->App->findrow($sql);
			if(!empty($rt['uinfo']['headimgurl'])){
				$rt['tjr']['headimgurl'] = $rt['uinfo']['headimgurl'];
			}
		}
		
		$this->title($rt['pinfo']['title']);
		
		$this->set('rt',$rt);
		$mb = $GLOBALS['LANG']['mubanid'] > 0 ? $GLOBALS['LANG']['mubanid'] : '';
		$this->set('mubanid',$GLOBALS['LANG']['mubanid']);
		$this->template($mb.'/v3_baoming');
	}
}
?>
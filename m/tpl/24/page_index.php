<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/css.css?v=18" media="all" />
<?php $this->element('guanzhu',array('shareinfo'=>$lang['shareinfo']));?>

<?php if(!empty($rt['lunbo'])){?>
<!--顶栏焦点图24-->
<div class="flexslider" style="margin-bottom:0px;">
	 <ul class="slides">
	 <?php if(!empty($rt['lunbo']))foreach($rt['lunbo'] as $row){
	 $a = basename($row['ad_img']);
	 ?>			 
		<li><a href="<?php echo $row['ad_url'];?>"><img src="<?php echo SITE_URL.$row['ad_img'];?>" width="100%" alt="<?php echo $row['ad_name'];?>"/></a></li>
	 <?php } ?>												
	  </ul>
</div>
<?php } ?>
<div id="main">
	<div style="display:none" ><img src="<?php echo !empty($lang['site_logo'])? SITE_URL.$lang['site_logo'] : $this->img('logo4.png');?>" class="logo"/></div>
	<?php $this->element('24/menu_top',array('lang'=>$lang,'rt'=>$rt));?>
	<?php
	if(!empty($rt['navtop'])){
	?>
	<div style="padding:10px 0px 10px 0px; background:#fff">
	<?php foreach($rt['navtop'] as $row){?>
	<a style="display:block; width:25%; float:left; text-align:center" href="<?php echo $row['url'];?>">
	<?php if(!empty($row['img'])){?><img src="<?php echo SITE_URL.$row['img'];?>" style="width:55%" /><?php } ?>
	<p><?php echo $row['name'];?></p>
	</a>
	<?php } ?>
	<div class="clear"></div>
	</div>
	<?php
	}
	?>
	<div style="padding-top:0px;">
    <?php if(!empty($rt['cat']))foreach($rt['cat'] as $row){?>
	<div class="indexitem" style="padding-bottom:5px; padding-top:0px">
		<p class="ptitle"><span style="float:left; width:70%"><a href="<?php echo ADMIN_URL.'catalog.php?cid='.$row['cat_id'];?>" style="display:block; line-height:30px;"><?php echo $row['cat_name'];?></a></span><span style="float:right; padding-right:5px; display:inherit; font-weight:400; width:auto"><a class="indexmore" href="<?php echo ADMIN_URL.'catalog.php?cid='.$row['cat_id'];?>"></a></span></p>
		<?php if(!empty($row['cat_img'])&&file_exists(SYS_PATH.$row['cat_img'])){?>
		<p><a href="<?php echo $row['cat_url'];?>"><img src="<?php echo SITE_URL.$row['cat_img'];?>" style="width:100%"/></a></p>
		<?php } ?>
		<ul class="goodslists">
		<?php if(!empty($rt['goods'][$row['cat_id']]))foreach($rt['goods'][$row['cat_id']] as $k=>$rows){?>
				<li style="width:100%; position:relative;">
				<div style="padding:4px">
				<a style="background:#fff; padding:5px; display:block;" href="<?php echo ADMIN_URL.($row['is_jifen']=='1'?'exchange':'product').'.php?id='.$rows['goods_id'];?>">
					<div style="height:160px; overflow:hidden; position:relative; z-index:10;">
						<img src="<?php echo SITE_URL.$rows['goods_img'];?>" style="max-width:99%;display:inline;" alt="<?php echo $rows['goods_name'];?>"/>
					</div>
					<p style="line-height:30px; height:30px; overflow:hidden; text-align:center; padding-bottom:5px; border-bottom:1px #dedede dotted; font-size:16px"><?php echo $rows['goods_name'];?></p>
					<p style="line-height:24px; height:24px; overflow:hidden; width:60%; float:left"><span style="float:left">惊喜价:</span><b class="price" style="font-size:16px; float:left; padding-left:3px;">￥<?php echo str_replace('.00','',$rows['pifa_price']);?></b>元</p>
					<p style="line-height:24px; height:24px; overflow:hidden; color:#999999; width:40%; float:right; text-align:right">原价<del>￥<?php echo str_replace('.00','',$rows['shop_price']);?></del></p>
					<div class="clear"></div>
				</a>
				</div>
			</li>
		<?php } ?>
		<div class="clear"></div>
		</ul>
	</div>
<?php } ?>
<?php if(!empty($rt['listsjf'])){?>
<!--积分兑换-->
	<div class="indexitem" style="padding:0px">
		<p class="ptitle" style="position:relative"><span><a href="<?php echo ADMIN_URL.'exchange.php';?>">积分兑换</a></span><span style="float:right; padding-right:5px; display:inherit; font-weight:400; width:auto"><a class="indexmore" href="<?php echo ADMIN_URL.'exchange.php';?>"></a></span></p>
		<ul class="goodslists">
		<?php foreach($rt['listsjf'] as $k=>$row){?>
			<li style="width:100%; float:left;">
				<div style="padding:4px;">
				<a style="background:#fff; padding:5px; display:block;" href="<?php echo ADMIN_URL.'exchange.php?id='.$row['goods_id'];?>">
					<div style=" height:160px; overflow:hidden; text-align:center;">
						<img src="<?php echo SITE_URL.$row['goods_img'];?>" style="max-width:99%;display:inline;" alt="<?php echo $row['goods_name'];?>"/>
					</div>
					<p style="line-height:20px; height:20px; overflow:hidden; text-align:center"><?php echo $row['goods_name'];?></p>
					<p style="line-height:22px; height:22px; overflow:hidden; text-align:center; background:#fafafa; border:1px solid #ededed;border-radius:5px;">所需积分:<b class="price" style="font-size:12px;"><?php echo $row['need_jifen'];?></b></p>
				</a>
				</div>
			</li>
		<?php } ?>
		<div class="clear"></div>
		</ul>
	</div>
<?php } ?>
	</div>	
</div>
<div class="show_zhuan" style=" display:none;width:100%; height:100%; position:fixed; top:0px; right:0px; z-index:9999999;filter:alpha(opacity=90);-moz-opacity:0.9;opacity:0.9; background:url(<?php echo $this->img('gz/121.png');?>) right top no-repeat #000;background-size:100% auto;" onclick="$(this).hide();"></div>
<?php
 $thisurl1 = Import::basic()->thisurl();
 $rr = explode('?',$thisurl1);
 $t2 = isset($rr[1])&&!empty($rr[1]) ? $rr[1] : "";
 $dd = array();
 if(!empty($t2)){
 	$rr2 = explode('&',$t2);
	if(!empty($rr2))foreach($rr2 as $v){
		$rr2 = explode('=',$v);
		if($rr2[0]=='from' || $rr2[0]=='isappinstalled'|| $rr2[0]=='code'|| $rr2[0]=='state') continue;
		$dd[] = $v;
	}
 }
 $thisurl = $rr[0].'?'.(!empty($dd) ? implode('&',$dd) : 'tid=0');
?>
<script type="text/javascript">
  function _report(a,c){
		$.post('<?php ADMIN_URL;?>product.php',{action:'ajax_share',type:a,msg:c,thisurl:'<?php echo Import::basic()->thisurl();?>',imgurl:'<?php echo !empty($lang['site_logo'])? SITE_URL.$lang['site_logo'] : $this->img('logo4.png');?>',title:'<?php echo $title;?>'},function(data){
		});
  }
<?php
$t = mktime();
$signature = sha1('jsapi_ticket='.$lang['jsapi_ticket'].'&noncestr='.$lang['nonceStr'].'&timestamp='.$t.'&url='.$thisurl1);
?>		
wx.config({
    debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    appId: '<?php echo $lang['appid'];?>', // 必填，公众号的唯一标识
    timestamp: '<?php echo $t;?>', // 必填，生成签名的时间戳
    nonceStr: '<?php echo $lang['nonceStr'];?>', // 必填，生成签名的随机串
    signature: '<?php echo $signature;?>',// 必填，签名，见附录1
    jsApiList: ['onMenuShareAppMessage','onMenuShareTimeline','onMenuShareQQ'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
});

wx.ready(function () {
	wx.onMenuShareAppMessage({
		title: '<?php echo $lang['metatitle'];?>', // 分享标题
		desc: '<?php echo $lang['metadesc'];?>', // 分享描述
		link: '<?php echo $thisurl;?>', // 分享链接
		imgUrl: '<?php echo !empty($lang['site_logo'])? SITE_URL.$lang['site_logo'] : $this->img('logo4.png');?>', // 分享图标
		success: function () { 
			// 用户确认分享后执行的回调函数
			_report('send_msg', 'st:ok');
		},
		cancel: function () { 
			// 用户取消分享后执行的回调函数
		}
	});
});

wx.onMenuShareTimeline({
      title: '<?php echo $lang['metatitle'];?>', // 分享标题
	  link: '<?php echo $thisurl;?>', // 分享链接
	  imgUrl: '<?php echo !empty($lang['site_logo'])? SITE_URL.$lang['site_logo'] : $this->img('logo4.png');?>', // 分享图标
      success: function () { 
			// 用户确认分享后执行的回调函数
			 _report('timeline', 'st:ok');
		},
		cancel: function () { 
			// 用户取消分享后执行的回调函数
		}
});
</script>

<?php $this->element('24/footer',array('lang'=>$lang)); ?>
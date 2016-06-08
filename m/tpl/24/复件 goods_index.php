<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/css.css?v=1" media="all" />
<?php $this->element('guanzhu',array('shareinfo'=>$lang['shareinfo']));?>
<div id="home">
	<div id="header">
		<div class="logo" style="height:28px; padding-top:10px; background:url(<?php echo $this->img('xy.png');?>) 10px 8px no-repeat"><span onclick=" history.go(-1);">&nbsp;</span></div>
		<div class="shoptitle"><span><?php echo NAVNAME;?></span></div>
		<div class="logoright">
			<p style="height:45px; line-height:45px;">
			<a href="javascript:void(0)" onclick="$('.show_zhuan').show();"><span>我要推广</span></a>
			</p>
		</div>
	</div>
</div>	

<style type="text/css">
body{ background:#FFF !important;}
#main .goods_desc table,#main .goods_desc table td,#main .goods_desc img,#main .goods_desc div img,#main .goods_desc p img,#main .goods_desc table td img{ max-width:100%;}
.pages a{ padding:1px 5px 1px 5px; border-bottom:2px solid #ccc; border-right:2px solid #ccc; border-left:1px solid #ededed; border-top:1px solid #ededed; margin-left:3px; background:#fafafa}
.buyclass div.spec_p{ line-height:24px; height:auto; margin-bottom:7px}
.buyclass .spec_p label{white-space:nowrap;}
.buyclass .spec_p span{ float:left; line-height:25px}
.buyclass .spec_p a{
float: left;
color: #373832;
background: #ededed;
border: 1px solid #ededed;
padding-left: 0px;
padding: 1px 5px 1px 5px;
line-height: 18px;
margin-left: 7px;
text-align: center;
border-radius: 0px; margin-bottom:3px; margin-top:3px}
.buyclass .spec_p a:hover{ border:1px solid #FF0000; background:#FF0000; color:#fff}

.rate-control{ padding-top:5px;}
.rate-control li{ width:80px; float:left}
.rate-control label {
float: left;
padding: 4px 5px;
cursor: pointer;
}
.rate-control label input{vertical-align: bottom;}
.icon {
background: url(<?php echo $this->img('haoping.png');?>) no-repeat;
display: inline-block;
vertical-align: text-bottom;
}
.icon-good {
width: 16px;
height: 17px;
background-position: 0px -34px;
}
.icon-bad {
width: 17px;
height: 17px;
background-position: 0px 0px;
}
.icon-normal {
width: 16px;
height: 17px;
background-position: 0px -17px;
}
.icon-img-upload{ float:left; margin-left:2px; display:block; width:17px; height:20px;background:url(<?php echo $this->img('imgb.png');?>) 0px center no-repeat}
.thumbs{float:left; margin-left:5px; height:50px;}
.thumbs img{ margin-right:5px; border:1px solid #ededed; padding:1px;}
.guest_submit {
width: 75px;
height: 31px;
line-height: 31px;
background: url(<?php echo $this->img('more_bg.jpg');?>) repeat-x;
float: left;
border: none;
cursor: pointer;
border-radius: 3px;
font-size: 12px;
text-align: center;
color: #FFF;
margin-left:5px;
}
</style>
<!--顶栏焦点图--> 
<div class="flexslider" style="margin-bottom:0px;">
	 <ul class="slides">
	 <?php if(!empty($rt['gallery']))foreach($rt['gallery'] as $ks=>$row){?>		 
		<li><img<?php echo $ks=='0' ? ' class="ggimg"' :'';?> src="<?php echo $row['goods_img'];?>" width="100%" alt="<?php echo $row['img_desc'];?>"/></li>
	  <?php } ?>					
	  </ul>
</div>
		
<div id="main">
	<div class="mainhead" style="border-top:1px solid #ededed;border-bottom:1px solid #ededed; background:#FFF">
        <form id="ECS_FORMBUY" name="ECS_FORMBUY" method="post" action="">
		<input type="hidden" name="price" id="btprice" value="<?php echo str_replace('.00','',$rt['goodsinfo']['pifa_price']);?>" />
		<div class="shopinfol" style="font-size:14px">
		<h1 style="font-size:16px"><?php echo $rt['goodsinfo']['goods_name'];?></h1>
		<?php if(!empty($rt['goodsinfo']['sort_desc'])){?><p><?php echo $rt['goodsinfo']['sort_desc'];?></p><?php } ?>
		<p style="font-size:16px;">市场价:<font class="spirce"><del>￥<?php echo str_replace('.00','',$rt['goodsinfo']['shop_price']);?></del></font>&nbsp;&nbsp;<span class="vippfont">会员价:</span><span class="price yt-num">￥<?php echo str_replace('.00','',$rt['goodsinfo']['pifa_price']);?></span></p>
		<?php if($rt['goodsinfo']['is_promote']=='1' && $rt['goodsinfo']['promote_end_date'] > mktime()){?>
		<p style="padding-bottom:10px; padding-top:10px; font-size:16px; color:#666">促销价:
		<span style="float:none; font-weight:bold; color:#9a0000">￥<?php echo $rt['goodsinfo']['promote_price']?></span>&nbsp;&nbsp;<b id="lefttime_2" style="font-size:16px; float:none; display:inline; width:auto; color:#C4B697;text-shadow: 1px 0 0 #9a0000;">--:--:--</b>
		</p>
		<?php } ?>
		<?php if($rt['config']['address2off']!='100'){
		$unt = !empty($rt['goodsinfo']['goods_unit']) ? $rt['goodsinfo']['goods_unit'] : '件';
		?>
		<p class="gdesc" style="padding-top:5px; font-size:16px; font-weight:bold; padding-top:5px;">两<?php echo $unt.$rt['config']['address2off']/10;?>折,三<?php echo $unt;?>起<?php echo ($rt['config']['address2off']/10)*($rt['config']['address3off']/100);?>折,还会有更多折扣哦！</p><?php } ?>	 
		<p style="height:24px; line-height:24px; padding-top:8px;"><a class="gjian" style="cursor:pointer; display:block; float:left; width:35px; height:22px;line-height:22px;text-align:center; font-size:18px; font-weight:bold; border:1px solid #e8e8e8; background:#ededed;">-</a><input readonly="" id="<?php echo $k;?>" name="number" value="1" class="inputBg" style="float:left;text-align: center; width:20px; height:22px; line-height:22px;border-bottom:1px solid #e8e8e8; border-top:1px solid #e8e8e8" type="text"> <a class="gjia" style="cursor:pointer; display:block; float:left; width:35px; height:22px;line-height:22px;text-align:center; font-size:18px; font-weight:bold; border:1px solid #e8e8e8; background:#ededed;">+</a><b style="margin-left:3px;"><?php  echo $row['goods_unit'];?></b></p>
		<?php
			  if(!empty($rt['spec'])){
					foreach($rt['spec'] as $row){
							if(empty($row)||!is_array($row) || $row[0]['is_show_cart']==0) continue;
							$rl[$row[0]['attr_keys']] = $row[0]['attr_name'];
							$attr[$row[0]['attr_keys']] = $row[0]['attr_is_select'];
					}
			   }
			?>
                  <div class="buyclass">
		  <?php
                    if(!empty($rt['spec'])){
                    foreach($rt['spec'] as $row){
                    if(empty($row)||!is_array($row) || $row[0]['is_show_cart']==0) continue;

		  ?>		 
                    <?php if(!empty($row[0]['attr_name'])){?>
		    <div class="spec_p"><span style="display:block; float:left"><?php  echo $row[0]['attr_name'].":";?></span>
                      <?php
                      if($row[0]['attr_is_select']==3){ //复选
					  		   $st = "";
                              foreach($row as $rows){
                                    $st .= '<label><input type="checkbox" name="'.$row[0]['attr_keys'].'" id="quanxuan" value="'.$rows['attr_value'].'" />'.$rows['attr_value']."&nbsp;&nbsp;</label>\n";
                              }
							  echo $st;
                              //echo $st .='<label class="quxuanall" id="ALL" style="border:1px solid #ADADAD; background-color:#E1E5E6; padding-left:3px; height:18px; line-height:18px;padding:2px;">全选</label>';
                      }else{
                              echo '<input type="hidden" name="'.$row[0]['attr_keys'].'" value="" />'."\n";
                              foreach($row as $rows){
                                            if(!empty($rows['attr_addi']) && @is_file(SYS_PATH.$rows['attr_addi'])){//如果是图片
                                                    echo '<a lang="'.trim($rows['attr_addi']).'" href="javascript:;" name="'.$row[0]['attr_keys'].'" id="'.trim($rows['attr_value']).'"><img src="'.(empty($rows['attr_addi']) ? 'theme/images/grey.png':$rows['attr_addi']).'" alt="'.$rows['attr_value'].'" title="'.$rows['attr_value'].'" width="40" height="50" /></a>';
                                            }else{
                                                    echo '<a lang="'.trim($rows['attr_addi']).'" href="javascript:;" name="'.$row[0]['attr_keys'].'" id="'.trim($rows['attr_value']).'">'.$rows['attr_value'].'</a>';
                                            }
                              }
                      } //end if
                    ?>
				<div style="clear:both"></div>
		  </div><?php } ?>
                  <div class="clear"></div>
		 <?php } // end foreach
		  } //end if?>
		</div>
<!--		<p style="height:32px; line-height:32px; padding-top:5px;">
		<input type="button" class="pushf" value="加入购物车" style="cursor:pointer;" onclick="return addToCart('<?php echo $rt['goodsinfo']['goods_id'];?>')">
                <input type="button" id="cart" class="addcar" value="惊喜价购买" style="cursor:pointer;" onclick="return addToCart('<?php echo $rt['goodsinfo']['goods_id'];?>','jumpshopping')">
		</p>-->
		</div>
            </form>
	</div>
	<div class="mainbottombg">
	<span class="ac" id="tab1">产品详情</span><span style="left:97px" id="tab2">用户评论</span>
	</div>
	<div style="padding:10px;" class="goods_desc">
	<div class="tabs tab1">
	<?php echo $rt['goodsinfo']['goods_desc'];?>
	</div>
	<div class="tabs tab2" style="display:none; text-align:center; min-height:200px;">
	<div style="min-height:50px; border-bottom:1px solid #ededed; padding-bottom:5px" class="GOODSCOMMENT">
	<?php $this->element('ajax_comment',array('rt'=>$rt));?>
	</div>

		<form name="MESSAGE" id="MESSAGE" action="" method="post">
				<div class="rate-control">
					<ul>
						<li class="good rate-checked">
							<label for="rate-good-841112656649579">
								<input checked="checked" name="ranks" type="radio" value="3" class="good-rate">
								<i class="icon icon-rank icon-good"></i>
								<span class="label-hidden">好评</span>
							</label>
							<span class="rate-score rate-score-good"></span>
						</li>
						<li class="normal">
							<label for="rate-normal-841112656649579">
								<input name="ranks" type="radio" value="2" class="noraml-rate">
								<i class="icon icon-rank icon-normal"></i>
								<span class="label-hidden">中评</span>
							</label>
							<span class="rate-score rate-score-normal"></span>
						</li>
						<li class="bad">
							<label for="rate-bad-841112656649579">
								<input name="ranks" type="radio" value="1" class="bad-rate">
								<i class="icon icon-rank icon-bad"></i>
								<span class="label-hidden">差评</span>
							</label>
							<span class="rate-score rate-score-bad"></span>
						</li>
					</ul>
				</div>
				<div style="text-align:left">
					<textarea name="content" style="border-radius:5px; border:1px solid #e8e8e8; height:80px; width:92%; margin-top:10px; margin-left:5px;"></textarea>
				</div>
				<div style="padding:5px;height:65px">
					<div style="float:left; cursor:pointer;width:62px; height:22px; overflow:hidden"><iframe id="iframe_t" name="iframe_t" border="0" src="<?php echo ADMIN_URL;?>uploadajax2/" scrolling="no" width="62" frameborder="0" height="22"></iframe></div>
					<div class="thumbs">
					
					</div>
				</div>
				<input type="button" class="guest_submit" value="我要评论" onclick="return ajax_submit_mes()">
				<div style="height:24px; line-height:24px; clear:both; text-align:center; color:#FF0000" class="returnmes"></div>
			</form>
	</div>
	</div>
</div>
<div class="show_zhuan" style=" display:none;width:100%; height:100%; position:fixed; top:0px; right:0px; z-index:9999999;filter:alpha(opacity=90);-moz-opacity:0.9;opacity:0.9; background:url(<?php echo $this->img('gz/121.png');?>) right top no-repeat #000;background-size:100% auto;" onclick="$(this).hide();"></div>
<div class="show_gz" style=" display:none;width:100%; height:100%; position:fixed; top:44px; right:0px; z-index:9999999;filter:alpha(opacity=60);-moz-opacity:0.6;opacity:0.6; background:url(<?php echo $this->img('gz/gz.png');?>) right top no-repeat #000;" onclick="$(this).hide();"></div>
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
  var picrt = [];
  function run(pic){
	$('.thumbs').append('<img src="<?php echo SITE_URL;?>'+pic+'" width="60" height="60" />');
	picrt.push(pic);
  }
  
  function _report(a,c){
	$.post('<?php ADMIN_URL;?>product.php',{action:'ajax_share',type:a,msg:c,thisurl:'<?php echo Import::basic()->thisurl();?>',imgurl:'<?php echo SITE_URL.$rt['goodsinfo']['goods_img'];?>',title:'<?php echo $rt['goodsinfo']['goods_name'];?>'},function(data){
	});
  }
  
<?php
$t = mktime();
$signature = sha1('jsapi_ticket='.$lang['jsapi_ticket'].'&noncestr='.$lang['nonceStr'].'&timestamp='.$t.'&url='.$thisurl1);
?>
    function ajax_submit_mes(){
  	  var goods        = new Object();
	  createwindow();
	  goods.ranks = $('input[name="ranks"]:checked').val();
	  content = $('textarea[name="content"]').val();
	  if(content=="" || typeof(content)=="undefined"){
	  	$('.returnmes').html('内容不能为空！');
		return false;
	  }
	  goods.goods_id = '<?php echo $rt['goodsinfo']['goods_id'];?>';
	  goods.content = content;
	  goods.pics = picrt.join('|');
	  
	  $.ajax({
		   type: "POST",
		   url: "<?php echo ADMIN_URL;?>product.php?action=ajax_submit_mes",
		   data: "goods=" + $.toJSON(goods),
		   dataType: "json",
		   success: function(data){
				removewindow();
				if(data.error=='0'){
					$('.GOODSCOMMENT').html(data.message);
				}else{
					$('.returnmes').html(data.message);
				}
				
		   }//end sucdess
		});
  }


/*  document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
        window.shareData = {  
            "imgUrl": "<?php echo SITE_URL.$rt['goodsinfo']['goods_img'];?>",
            "LineLink": '<?php echo $thisurl;?>',
            "Title": "<?php echo $rt['goodsinfo']['goods_name'];?>",
            "Content": "<?php echo !empty($rt['goodsinfo']['sort_desc']) ? $rt['goodsinfo']['sort_desc'] : $rt['goodsinfo']['goods_name'];?>"
        };
        // 发送给好友
        WeixinJSBridge.on('menu:share:appmessage', function (argv) {
            WeixinJSBridge.invoke('sendAppMessage', { 
                "img_url": window.shareData.imgUrl,
                "img_width": "640",
                "img_height": "640",
                "link": window.shareData.LineLink,
                "desc": window.shareData.Content,
                "title": window.shareData.Title
            }, function (res) {
                _report('send_msg', res.err_msg);
            })
        });
        // 分享到朋友圈
        WeixinJSBridge.on('menu:share:timeline', function (argv) {
            WeixinJSBridge.invoke('shareTimeline', {
                "img_url": window.shareData.imgUrl,
                "img_width": "640",
                "img_height": "640",
                "link": window.shareData.LineLink,
                "desc": window.shareData.Content,
                "title": window.shareData.Title
            }, function (res) {
                _report('timeline', res.err_msg);
            });
        });
        // 分享到微博
        WeixinJSBridge.on('menu:share:weibo', function (argv) {
            WeixinJSBridge.invoke('shareWeibo', {
                "content": window.shareData.Content,
                "url": window.shareData.LineLink,
            }, function (res) {
                _report('weibo', res.err_msg);
            });
        });
        }, false)*/
		
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
		title: '<?php echo $rt['goodsinfo']['goods_name'];?>', // 分享标题
		desc: '<?php echo !empty($rt['goodsinfo']['sort_desc']) ? $rt['goodsinfo']['sort_desc'] : $rt['goodsinfo']['goods_name'];?>', // 分享描述
		link: '<?php echo $thisurl;?>', // 分享链接
		imgUrl: '<?php echo SITE_URL.$rt['goodsinfo']['goods_img'];?>', // 分享图标
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
      title: '<?php echo $rt['goodsinfo']['goods_name'];?>', // 分享标题
	  link: '<?php echo $thisurl;?>', // 分享链接
	  imgUrl: '<?php echo SITE_URL.$rt['goodsinfo']['goods_img'];?>', // 分享图标
      success: function () { 
			// 用户确认分享后执行的回调函数
			 _report('timeline', 'st:ok');
		},
		cancel: function () { 
			// 用户取消分享后执行的回调函数
		}
});
</script>
    
<script type="text/javascript">
$('.mainbottombg span').click(function(){
	$(this).parent().find('span').removeClass('ac');
	$(this).addClass('ac');
	$('.tabs').hide();
	art = $(this).attr('id');
	$('.'+art).show();
	
});
$('input[name="number"]').change(function(){
	vall = $(this).val();
	if(!(vall>0)){
		$(this).val('1');
	}
});

$('.spec_p a').click(function(){
	na = $(this).attr('name');
	vl = $(this).attr('id');
	$('input[name="'+na+'"]').val(vl);
	
	$(this).parent().find('a').each(function(i){
	   this.style.border='1px solid #ededed';
	   this.style.background='#ededed';
	   this.style.color='#373832';
	});
	
	$(this).css('border','1px solid #FF0000');
	$(this).css('background','#FF0000');
	$(this).css('color','#fff');
	
	price = $(this).attr('lang');
	if(price>0){
		$('.yt-num').html('￥'+price);
		$('#btprice').val(price);
	}
	return false;
	
});

$('#main .gjia').click(function(){
	var tnum = $(this).parent().find('input').val();
	$(this).parent().find('input').val(parseInt(tnum)+1);
});
$('#main .gjian').click(function(){
	var tnum = $(this).parent().find('input').val();
	tnum = parseInt(tnum);
	if(tnum>1){
		$(this).parent().find('input').val(tnum-1);
	}
});	
			
function checkcartattr(){
	<?php 
	if(!empty($rl)){
		foreach($rl as $k=>$v){?>
		a<?php echo $k;?> = $('.buyclass input[name="<?php echo $k;?>"]<?php echo $attr[$k]==3 ? ':checked' : "";?>').val();
		if(a<?php echo $k;?> ==""||typeof(a<?php echo $k;?>)=='undefined'){
		  alert("必须选择<?php echo $v;?>");
		  return false;
		}
	<?php } }?>
	return true;
}


var dt = '<?php echo $rt['goodsinfo']['is_promote']&&$rt['goodsinfo']['promote_start_date']<mktime() ? ($rt['goodsinfo']['promote_end_date']-mktime()) : ($rt['goodsinfo']['promote_end_date']-$rt['goodsinfo']['promote_start_date']);?>';
var st = new showTime('2', dt);  
st.desc = "促销结束";
st.preg = "倒计时	{a}天	{b}:{c}:{d}";
st.setid = "lefttime_";
st.setTimeShow(); 
</script>
<?php //$this->element('24/footer',array('lang'=>$lang)); ?>
<style type="text/css">
body { padding-bottom:60px !important; }
.top_menu li b {width: 38px;height: 20px;line-height: 17px;display: block;color: #fff;text-align: center;font-size: 12px;}
.top_menu li b em {padding:0px 3px 0px 3px;border-radius: 100%;text-align: center;background-color: red;display: block;position: absolute;z-index: 9999;margin-top: -10px;margin-left: 22px;}
user agent stylesheeti, cite, em, var, address, dfn {font-style: italic;}

.top_menu li.li2 a.butt-cart{display: inline-block;font-size: 15px;width: 90%;height: 40px;line-height: 38px;margin: 6px auto 5px auto;padding: 0;color: #FFF;border-radius: 3px;background:#32a000;}
.top_menu li.li4 a.butt-buy {display: inline-block;font-size: 15px;width: 90%;height: 40px;line-height: 38px;margin: 6px auto 5px auto;padding: 0;color: #FFF;border-radius: 3px;background:#ff6400;}
</style>
<?php
$nums = 0;
$thiscart = $this->Session->read('cart');
if(!empty($thiscart))foreach($thiscart as $row){
	$nums +=$row['number'];
}
?>
<div class="top_bar" style="-webkit-transform:translate3d(0,0,0);background:rgba(230,230,230,0.9);">
   <nav>
    <ul id="top_menu" class="top_menu">
    <li class="li1" style="width:20%"><a href="<?php echo ADMIN_URL;?>" style="border:none"><label>首页</label></a></li>
	<li class="li2" style="width:30%">
	<a id="btnCart" onclick="return addToCart('<?php echo $rt['goodsinfo']['goods_id'];?>')" class="butt-cart" style="border:none">加入购物车</a>
	</li>
	<li class="li4" style="width:30%">
	<a id="btnBuy" onclick="return addToCart('<?php echo $rt['goodsinfo']['goods_id'];?>','jumpshopping')" class="butt-buy" style="border:none">立即购买</a>
	</li>
	<li class="li5" style="width:20%"><a href="<?php echo ADMIN_URL;?>mycart.php" style="height:56px; padding:0px">
	<span style="width:30px; height:32px; display:block; margin:0px auto"><b><em id="buy_price" class="mycarts" value="1" style="display:block"><?php echo $nums;?></em></b></span>
	<label>购物车</label>
	</a></li>    
	</ul>
  </nav>
</div>

<div id="collectBox"></div>
<?php $this->element('24/vfooter',array('lang'=>$lang)); ?>
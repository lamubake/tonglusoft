<!--##########################################################
	技术支持：烟台通路网络科技有限公司
	电话：15553510105
	QQ：33614970
	##############################################################
-->
<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/css.css?v=1" media="all" />

<div id="main">
	<div class="mainhead">
        <form id="ECS_FORMBUY" name="ECS_FORMBUY" method="post" action="">
		<input type="hidden" name="price" id="btprice" value="<?php echo str_replace('.00','',$rt['goodsinfo']['pifa_price']);?>" />
		<input type="hidden" name="youfei" id="youfei" value="<?php echo $rt['goodsinfo']['youfei']? $rt['goodsinfo']['youfei']:'0';?>" />
		<div class="shopinfol">
		<div class="goods_content">
			 <?php echo $rt['goodsinfo']['goods_desc'];?>
		</div>
		<div class="top_bar" style="background:#E03106; text-align:center; height:50px" onclick="return addToCart('<?php echo $rt['goodsinfo']['goods_id'];?>','jumpshopping')">
			<a id="btnBuy" onclick="return addToCart('<?php echo $rt['goodsinfo']['goods_id'];?>','jumpshopping')" class="butt-buy">立即抢购</a>
		</div>
		
		<p style="clear:both;display:none"></p>
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

		</div>
            </form>
	</div>
	
</div>

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


</script>
<?php //$this->element('24/footer',array('lang'=>$lang)); 

$nums = 0;
$thiscart = $this->Session->read('cart');
if(!empty($thiscart))foreach($thiscart as $row){
	$nums +=$row['number'];
}
?>
<!--
<div class="top_bar">
   <nav>
    <ul id="top_menu" class="top_menu">
    <li class="li1" style="width:20%"><a href="<?php echo ADMIN_URL;?>" style="border:none"><label>首页</label></a></li>
	<li class="li4" style="width:60%; text-align:center">
		<a id="btnBuy" onclick="return addToCart('<?php echo $rt['goodsinfo']['goods_id'];?>','jumpshopping')" class="butt-buy" style="border:none">立即购买</a>
	</li>
	<li class="li5" style="width:20%"><a href="<?php echo ADMIN_URL;?>mycart.php" style="height:56px; padding:0px">
	<span style="width:30px; height:32px; display:block; margin:0px auto"><b><em id="buy_price" class="mycarts" value="1" style="display:block"><?php echo $nums;?></em></b></span>
	<label>购物车</label>
	</a></li>    
	</ul>
  </nav>
</div>
-->
<div id="collectBox"></div>
<br /><br />
<?php $this->element('24/vfooter',array('lang'=>$lang)); ?>
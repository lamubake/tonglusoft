<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/css.css" media="all" />
<!--##########################################################
	技术支持：烟台通路网络科技有限公司
	电话：15553510105
	QQ：33614970
	##############################################################
-->
<style type="text/css">
body{ background:#FFF !important;}
.jbjb{background:#E94E56}
.pw{
border: 1px solid #ddd;
border-radius: 5px;
background-color: #fff; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.pw{
border: 1px solid #ddd;
border-radius: 5px;
background-color: #fff; padding-left:5px; padding-right:5px;
-moz-border-radius:5px;/*仅Firefox支持，实现圆角效果*/
-webkit-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
-khtml-border-radius:5px;/*仅Safari,Chrome支持，实现圆角效果*/
border-radius:5px;/*仅Opera，Safari,Chrome支持，实现圆角效果*/
}
.meCenterTitle {
background: #fff;
line-height: 24px;
height: 24px;
overflow: hidden;
padding: 2px;
color: #999;
padding-left: 10px;
}
.meCenterBox {
position: relative;
}
.meCenterBoxWriting {
position: absolute;
left: 36%;
top: 20%;
}
.meCenterBoxAvatar {
display: block;
position: absolute;
width: 25%;
left: 8%;
top: 20%;
}
.meCenterBoxEditor {
 position: absolute; 
right: 10px;
top: 10px;
}
.meCenterBoxWriting p {
margin-bottom: 8px;
line-height: 14px;
color: #fff;
}
.meCenterBoxWriting p {
margin-bottom: 8px;
line-height: 14px;
color: #fff;
}

.meCenterBoxAvatar a img {
display: block;
border: 6px solid #fff;
overflow: hidden;
width:100%;
}
.gonglist{border-radius: 5px; border:1px solid #d1d1d1; border-bottom:none; overflow:hidden; margin:5px; display:none}
.gonglist li{ text-align:center;width:100%;line-height:44px; height:44px; float:left; overflow:hidden;padding-bottom:2px;background-image: -webkit-gradient(linear,left top,left bottom,from(#FEFEFE),to(#eeeeee));background-image: -webkit-linear-gradient(#FEFEFE,#eeeeee);background-image: linear-gradient(#FEFEFE,#eeeeee); border-bottom:1px solid #d1d1d1}
.gonglist li a{ font-size:14px; display:block;background:url(<?php echo $this->img('pot.png');?>) 93% center no-repeat}
.gonglist li a:hover{ background:url(<?php echo $this->img('pot.png');?>) 93% center no-repeat #EAEAEA;font-weight:bold;}
.gonglist li.uli2 a{} 
.gonglist li p{ position:relative}
.gonglist li p a{ text-align:left}
.gonglist li p i{ background-size:80%;list-style:decimal; width:20px; height:44px; float:left; margin-left:7%;background:url(<?php echo $this->img('m.png');?>) center center no-repeat; margin-right:3px}
.gonglist li p a span{height:24px; line-height:24px;display:block;text-align:center; font-size:12px; font-weight:bold; color:#B70000; cursor:pointer; position:absolute;right:25%; top:12px; z-index:99;}
.uitem{ margin-bottom:10px;}
.uitem p.pp{ position:relative; height:40px; line-height:40px;margin-bottom:7px;  text-align:left; background:#E9E9E9}
.uitem p.pp a{ font-size:14px; display:block; padding-right:10%;}
.uitem p.pp a i{background-size:80%;list-style:decimal; width:55px; height:40px; float:left; margin-left:7%; margin-right:5px}
.uitem p.pp a span{height:24px; line-height:24px; padding-left:15px; padding-right:15px;display:block;background:#ff0000; text-align:center;  color:#FFF; position:absolute;right:10%; top:8px; z-index:99;}

.userindex a{ display:block; text-align:center; float:left; width:33.3%}
.userindex a p{ padding:10px;}
.userindex a img{ border:2px solid #FFF}

</style>
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>css/styles.css?v=12"/>
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>css/jquery.mobile-1.3.2.min.css?v=12"/>
<?php $ad = $this->action('banner','banner','会员中心',1);?>
<div style="min-height:300px; padding-bottom:10px;" class="ucenter">
	<div class="meCenter">
		<ul class="meCenterBox">
		  <li class="meCenterBoxWriting">
			<p>会员ID：<?php echo $rt['userinfo']['user_id'];?><!--&nbsp;&nbsp;&nbsp;积分:<?php echo empty($rt['userinfo']['mypoints']) ? '0' : $rt['userinfo']['mypoints'];?>--></p>
			<p>昵称：<?php echo empty($rt['userinfo']['nickname']) ? '未知' : $rt['userinfo']['nickname'];?></p>
			<p>
			<?php if(empty($rt['userinfo']['subscribe_time'])){ ?>
			关注时间：<font class="price1"><?php echo date('Y-m-d',$rt['userinfo']['reg_time']);?></font>
			<?php 
			}else{
			?>
			关注时间：<?php echo date('Y-m-d',$rt['userinfo']['subscribe_time']);?>
			<?php
			}
			?>
			</p>
			<p>族长：<?php echo $rt['userinfo']['level_name']=='会员'?'否':'是' ?></p>
			<p>会员级别：<?php echo $rt['userinfo']['level_name'];?></p>
		  </li>
		  <li class="meCenterBoxAvatar"><a href="<?php echo ADMIN_URL;?>user.php?act=myinfos_u" data-ajax="false"><img src="<?php echo !empty($rt['userinfo']['headimgurl']) ? $rt['userinfo']['headimgurl'] : (!empty($rt['userinfo']['avatar']) ? SITE_URL.$rt['userinfo']['avatar'] : $this->img('noavatar_big.jpg'));?>" style="padding:1px; width:97%"></a></li>
		  <li><?php  if(!empty($ad['ad_img'])){?><img src="<?php echo SITE_URL.$ad['ad_img'];?>" width="100%" style="min-height:100px"><?php }else{?><p style="display:block; width:100%; min-height:130px;" class="jbjb"></p><?php } ?></li>
		</ul>
        </div>
	<div style="background:#E03106; height:8px">&nbsp;</div>
	<!--
	<?php if(!empty($lang['site_notice'])){?>
	<div style="height:30px; line-height:30px; overflow:hidden">
	<marquee style="WIDTH:100%;" scrollamount="4" direction="left"><?php echo $lang['site_notice'];?></marquee>
	</div>
	<?php } ?>
	-->
	<p style="line-height:30px; text-align:center;">
		您是由【<?php echo empty($rt['tjren']) ? '官网':$rt['tjren'];?>】推荐
	</p>
	
		<div class="uitem">
			<p class="pp">
				<a href="javascript:;" onclick="ajax_show_sub(1,this);"><i style="background:url(/m/images/jiazu.gif) no-repeat center"></i>家族成员<span><?php echo empty($rt['zcount']) ? '0' : $rt['zcount'];?>人</span></a>
			</p>
			<ul class="gonglist gg1">
				<li class="uli6"><p><a href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=1';?>"><i></i>我的一级会员<span><?php echo empty($rt['zcount1']) ? '0' : $rt['zcount1'];?>人</span></a></p></li>
				<li class="uli9"><p><a href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=2';?>"><i></i>我的二级会员<span><?php echo empty($rt['zcount2']) ? '0' : $rt['zcount2'];?>人</span></a></p></li>
				<li class="uli10"><p><a href="<?php echo ADMIN_URL.'daili.php?act=myuser&t=3';?>"><i></i>我的三级会员<span><?php echo empty($rt['zcount3']) ? '0' : $rt['zcount3'];?>人</span></a></p></li>		
				<div class="clear"></div>
			</ul>
			
		</div>
		<!--
		<div class="uitem">
			<p class="pp">
				<a href="javascript:;" onclick="ajax_show_sub(2,this);"><i></i>我的推广<span><?php echo $rt['userinfo']['share_ucount'] > 0 ? $rt['userinfo']['share_ucount'] : '0';?>人</span></a>
			</p>
			<ul class="gonglist gg2">
				<li class="uli6"><p><a href="<?php echo ADMIN_URL.'user.php?act=myshare';?>"><i></i>点击链接<span><?php echo $rt['userinfo']['share_ucount'] > 0 ? $rt['userinfo']['share_ucount'] : '0';?>人</span></a></p></li>
				<li class="uli9"><p><a href="<?php echo ADMIN_URL.'user.php?act=myuser';?>"><i></i>成功关注<span><?php echo $rt['userinfo']['guanzhu_ucount'] > 0 ? $rt['userinfo']['guanzhu_ucount'] : '0';?>人</span></a></p></li>
				<li class="uli10"><p><a href="javascript:void(0)"><i></i>下单购买<span><?php echo empty($rt['userinfo']['ordercount']) ? '0' : $rt['userinfo']['ordercount'];?>单</span></a></p></li>
				<li class="uli10"><p><a href="<?php echo ADMIN_URL.'daili.php?act=my_is_daili';?>"><i></i>成为米友<span><?php echo empty($rt['userinfo']['fxcount']) ? '0' : $rt['userinfo']['fxcount'];?>人</span></a></p></li>	
				<li class="uli10"><p><a href="<?php echo ADMIN_URL.'user.php?act=myerweima';?>"><i></i>我的二维码</a></p></li>	
				<div class="clear"></div>
			</ul>
		</div>
		-->
		<div class="uitem">
			<p class="pp">
				<a href="javascript:;" onclick="ajax_show_sub(3,this);"><i style="background:url(/m/images/myyongjin.gif) no-repeat center"></i>我的佣金</a>
			</p>
			<ul class="gonglist gg3">
				<?php
					$moneyall = $rt['userinfo']['mymoney']+$rt['userinfo']['money_ucount'];
				?>
				<li class="uli6"><p><a href="<?php echo ADMIN_URL.'daili.php?act=mymoneydata&status=weifu';?>"><i></i>我的总佣金<span><?php echo !empty($moneyall) ? $moneyall : '0.00';?>元</span></a></p></li>
				<li class="uli9"><p><a href="javascript:void(0)"><i></i>已领取佣金<span><?php echo !empty($rt['userinfo']['money_ucount']) ? $rt['userinfo']['money_ucount'] : '0.00';?>元</span></a></p></li>
				<!--<li class="uli10"><p><a href="javascript:tikuan()"><i></i>未领取佣金<span><?php echo !empty($rt['userinfo']['mymoney']) ? $rt['userinfo']['mymoney'] : '0.00';?>元</span></a></p></li>-->
				<div class="clear"></div>
			</ul>
		</div>
		
		<div class="uitem">
			<p class="pp">
				<a href="javascript:;" onclick="ajax_show_sub(4,this);"><i style="background:url(/m/images/lhongbao.gif) no-repeat center"></i>申领红包专区</a>
			</p>
			<ul class="gonglist gg4">
				<li class="uli6"><p><a href="<?php echo ADMIN_URL.'hongbao.php?act=index&cengji=1';?>"><i></i>申领一级会员红包</a></p></li>
				<li class="uli9"><p><a href="<?php echo ADMIN_URL.'hongbao.php?act=index&cengji=2';?>"><i></i>申领二级会员红包</a></p></li>
				<li class="uli10"><p><a href="<?php echo ADMIN_URL.'hongbao.php?act=index&cengji=3';?>"><i></i>申领三级会员红包</a></p></li>
				<div class="clear"></div>
			</ul>
		</div>
		<div class="uitem">
			<p class="pp">
				<a href="javascript:;" onclick="ajax_show_sub(5,this);"><i style="background:url(/m/images/yanzhengma.gif) no-repeat center"></i>验证码</a>
			</p>
			<ul class="gg5" style="display:none">
				&nbsp;&nbsp;&nbsp;&nbsp;<?php
					foreach($sn as $key=>$s){
						echo "<div style='padding-left:30px'>".$s."</div><br />";
					}
				?>
				<div class="clear"></div>
			</ul>
		</div>
		<!--
		<div class="uitem">
			<p class="pp">
				<a href="<?php echo ADMIN_URL;?>daili.php?act=postmoney" style="background:url(<?php echo $this->img('404-2.png');?>) 90% center no-repeat"><i style="background:url(<?php echo $this->img('x.png');?>) 10% center no-repeat"></i>申请提现</a>
			</p>
		</div>
		
		<div class="uitem">
			<p class="pp">
				<a href="<?php echo ADMIN_URL;?>daili.php?act=postmoneydata" style="background:url(<?php echo $this->img('404-2.png');?>) 90% center no-repeat"><i style="background:url(<?php echo $this->img('x.png');?>) 10% center no-repeat"></i>提款记录</a>
			</p>
		</div>

		<div class="uitem">
			<p class="pp">
				<a href="<?php echo ADMIN_URL.'daili.php?act=gonggao';?>" style="background:url(<?php echo $this->img('404-2.png');?>) 90% center no-repeat"><i style="background:url(<?php echo $this->img('x.png');?>) 10% center no-repeat"></i>代理公告</a>
			</p>
		</div>
		-->
  </div>
  
</div>
<script type="text/javascript">
function tikuan(){
	var r = confirm("确定提现吗？\n最高一次提现200元。\n其余扣留在余额中，一分钟后再提款。");
	if(r){
		window.location.href='/m/hongbao.php?act=yueti';
	}else{
		alert('cancel');
	}
}
function ajax_show_sub(k,obj){
	$(".gg"+k).toggle();
	ll = $(".gg"+k).css('display');
}
function ajax_checked_fenxiao(obj){
	//createwindow();
	$.post('<?php echo ADMIN_URL;?>user.php',{action:'ajax_checked_fenxiao'},function(data){ 
			//removewindow();
			if(data=='1'){
				window.location.href='<?php echo ADMIN_URL.'user.php?act=dailicenter';?>';
			}else{
				$(obj).parent().parent().hide(200);
				$('.ajax_checked_fenxiao').show();
				$('.ajax_checked_fenxiao').html(data);
				return false;
			}
	})
	return false;
}
</script>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>


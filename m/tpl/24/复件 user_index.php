<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/css.css" media="all" />
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<style type="text/css">
body{background:#FFF!important}
.jbjb{background-image:-webkit-gradient(linear,left top,left bottom,from(#FBFBFB),to(#FAF09E));background-image:-webkit-linear-gradient(#FBFBFB,#FAF09E);background-image:-moz-linear-gradient(#FBFBFB,#FAF09E);background-image:-ms-linear-gradient(#FBFBFB,#FAF09E);background-image:-o-linear-gradient(#FBFBFB,#FAF09E);background-image:linear-gradient(#FBFBFB,#FAF09E)}
.pw{border:1px solid #ddd;border-radius:5px;background-color:#fff;padding-left:5px;padding-right:5px;-moz-border-radius:5px;-webkit-border-radius:5px;-khtml-border-radius:5px;border-radius:5px}
.meCenterTitle{background:#fff;line-height:24px;height:24px;overflow:hidden;padding:2px;color:#999;padding-left:10px}
.meCenterBox{position:relative}
.meCenterBoxWriting{position:absolute;left:36%;top:20%}
.meCenterBoxAvatar{display:block;position:absolute;width:18%;left:10%;top:20%}
.meCenterBoxEditor{position:absolute;right:10px;top:10px}
.meCenterBoxWriting p{margin-bottom:8px;line-height:14px;color:#fff}
.meCenterBoxWriting p{margin-bottom:8px;line-height:14px;color:#fff}
.meCenterBoxAvatar a img{display:block;border:6px solid #fff;border-radius:10px;overflow:hidden;width:100%}
.mc-menu .m-arrows{position:absolute;right:.8em;top:50%;margin-top:-1.6em}

.icon-arrows-right{ float:right;background:url(<?php echo $this->img('24/images/arrows.png');?>) center center no-repeat;padding-top:58px; padding-right:60px; background-size:0.5em;
}
.navbar li{ width:33.3%; overflow:hidden}
.navbar li a{ color:#666}
.gonglist{  border-top:1px solid #ededed; overflow:hidden;}
.gonglist li{ text-align:left;width:100%;height:48px; float:left; overflow:hidden; background:#FFF;  font-size:16px;}
.gonglist li a{ display:block;/*background:url(<?php echo $this->img('pot.png');?>) 93% center no-repeat*/ height:48px;}
.gonglist li a:hover{ /*background:url(<?php echo $this->img('pot.png');?>) 93% center no-repeat #EAEAEA*/}
.gonglist li.uli2 a{} 
.gonglist li p{}
.gonglist li p i{ list-style:decimal; width:28px; height:44px; float:left; margin-left:7%;margin-right:10px;}
.gonglist li.uli6 {width:100%;}
.gonglist li.uli1 p i{ background:url(<?php echo $this->img('icon/li2.png');?>) center center no-repeat;background-size:28px auto;}
.gonglist li.uli2 p i{ background:url(<?php echo $this->img('24/images/3433.png');?>) center center no-repeat;background-size:28px auto;}
.gonglist li.uli3 p i{ background:url(<?php echo $this->img('24/images/2323.png');?>) center center no-repeat;background-size:28px auto;}
.gonglist li.uli4 p i{ background:url(<?php echo $this->img('icon/li6.png');?>) center center no-repeat;background-size:28px auto;}
.gonglist li.uli5 p i{ background:url(<?php echo $this->img('24/images/5434.png');?>) center center no-repeat;background-size:28px auto;}
.gonglist li.uli6 p i{ background:url(<?php echo $this->img('24/images/home.png');?>) center center no-repeat;background-size:28px auto;}
.gonglist li.uli7 p i{ background:url(<?php echo $this->img('icon/li8.png');?>) center center no-repeat;background-size:28px auto;}
.gonglist li.uli8 p i{ background:url(<?php echo $this->img('24/images/544.png');?>) center center no-repeat;background-size:28px auto;}
.gonglist li.uli9 p i{ background:url(<?php echo $this->img('24/images/43434.png');?>) center center no-repeat;background-size:28px auto;}
.gonglist li.uli10 p i{ background:url(<?php echo $this->img('24/images/4343.png');?>) center center no-repeat;background-size:28px auto;}
.hbk1 {border-bottom:1px solid #ededed; height:47px; line-height:55px}

.gonglist li.li3 p i{ background:url(<?php echo $this->img('icon/hb.png');?>) center center no-repeat;background-size:28px auto;}
.gonglist li.li5 p i{ background:url(<?php echo $this->img('icon/77.png');?>) center center no-repeat;background-size:28px auto;}
.gonglist li.li6 p i{ background:url(<?php echo $this->img('icon/dd.png');?>) center center no-repeat;background-size:28px auto;}
.gonglist li.li1 p i{ background:url(<?php echo $this->img('icon/li3.png');?>) center center no-repeat;background-size:28px auto;}
.navbar{-webkit-box-shadow:none; border-bottom:1px solid #f0f0f0}
.navbar li.li1 a{ border-right:1px solid #f0f0f0}

.userCenter{ border:1px solid #DDD}
.userCenter ul li{width: 33.3%;text-align: center;float: left;height:10rem;border-bottom: 0.1rem solid #ddd;}
.userCenter ul li img{width: 3rem; height:3rem}
.userCenter ul li b{display: block;line-height: 2rem;}
.userCenter ul li a{display: block;border-right: 0.1rem solid #ddd;padding: 2rem 0;height: 6rem;}
.userCenter ul li:nth-child(3n) a{border-right: 0;}
</style>
</style>
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>css/styles.css?v=12"/>
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL; ?>css/jquery.mobile-1.3.2.min.css?v=12"/>
<?php $ad = $this->action('banner','banner','会员中心',1);?>
<div>
	<div class="meCenter">
		<ul class="meCenterBox">
		  <li class="meCenterBoxWriting">
			<p>会员ID: DM8<?php echo $rt['userinfo']['user_id'];?><!--&nbsp;&nbsp;&nbsp;积分:<?php echo empty($rt['userinfo']['mypoints']) ? '0' : $rt['userinfo']['mypoints'];?>--></p>
			<p>昵称: <?php echo empty($rt['userinfo']['nickname']) ? '未知' : $rt['userinfo']['nickname'];?></p>
			<p>
			<?php if(empty($rt['userinfo']['subscribe_time'])){ ?>
			邀请时间: <font class="price1"><?php echo date('Y-m-d',$rt['userinfo']['reg_time']);?></font>
			<?php 
			}else{
			?>
			关注时间：<font class="price" style="font-size:16px"><?php echo date('Y-m-d',$rt['userinfo']['subscribe_time']);?></font>
			<?php
			}
			?>
			</p>
			<p>经销商级别：<font class="price" style="font-size:16px"><?php echo $rt['userinfo']['level_name'];?></font></p>
		  </li>
		  <li class="meCenterBoxAvatar" style="max-height:90px; overflow:hidden"><a href="<?php echo ADMIN_URL;?>user.php?act=myinfos_u" data-ajax="false"><img src="<?php echo !empty($rt['userinfo']['headimgurl']) ? $rt['userinfo']['headimgurl'] : (!empty($rt['userinfo']['avatar']) ? SITE_URL.$rt['userinfo']['avatar'] : $this->img('noavatar_big.jpg'));?>" style="border-radius:50%; padding:1px; width:97%"></a></li>
		  <li><?php  if(!empty($ad['ad_img'])){?><img src="<?php echo SITE_URL.$ad['ad_img'];?>" width="100%" style="min-height:100px"><?php }else{?><p style="display:block; width:100%; min-height:120px;" class="jbjb"></p><?php } ?></li>
		</ul>
    </div>
	
	<div class="navbar" style="background:#fff;">
	<ul>
		<li class="li1"><a href="<?php echo ADMIN_URL.'daili.php?act=monrydeial';?>">佣金:<font color="red">￥<?php echo empty($rt['userinfo']['mymoney']) ? '0' : $rt['userinfo']['mymoney'];?></font></a></li>
		<li class="li1"><a href="<?php echo ADMIN_URL;?>user.php?act=mypoints">积分:<font color="red"><?php echo empty($rt['userinfo']['mypoints']) ? '0' : $rt['userinfo']['mypoints'];?></font></a></li>
		<li><a href="<?php echo ADMIN_URL;?>daili.php?act=myuser&t=1">推荐人:<font color="red"><?php echo empty($rt['zcount1']) ? '0' : $rt['zcount1'];?></font>个</a></li>
	</ul>
	</div>
	
	<p style="line-height:50px; text-align:center;background:#FAF2AF">
		您的邀请人是：<font color="#00761d"><?php echo empty($rt['tjren']) ? '官网':$rt['tjren'];?></font>
	</p>
	<?php if(!empty($lang['site_notice'])){?>
	<div style="height:30px; line-height:30px; overflow:hidden; background:#FBF9E8; color:#DB383E; font-family:'微软雅黑'">
	<marquee style="WIDTH:100%;" scrollamount="4" direction="left"><?php echo $lang['site_notice'];?></marquee>
	</div>
	<?php } ?>
 </div> 


<section class="userCenter">
    <ul>
       <li><a href="/m/user.php?act=myinfos">
           <img src="/center/img2/userCenter_icon8.png"><b>我的资料<br />提款资料</b></a></li>
        <li><a href="http://bbs.tongtiankeji.com/thread-1-1-1.html">
            <img src="/center/img2/userCenter_icon4.png"><b>模式说明<br />月入过万</b></a></li>

        <!-- <li><a href="javascript:GetQRCode();">
           <img src="/center/img2/userCenter_icon5.png"><b>推广二维码</b></a></li> -->
        <li><a href="/m/user.php?act=myerweima">
            <img src="/center/img2/userCenter_icon5.png"><b>推广二维码</b></a></li>

        <li><a href="/m/user.php?act=myuser">
            <img src="/center/img2/userCenter_icon6.png"><b style="color: blue">团队成员</b></a></li>
        <li><a href="/m/user.php?act=dailicenter">
            <img src="/center/img2/userCenter_icon7.png"><b>推广统计</b></a></li>
        <li><a href="/m/daili.php?act=monrydeial">
            <img src="/center/img2/userCenter_icon10.png"><b>我的佣金</b></a></li>
        <!-- <li><a href="http://hmwx.k99p.com/Application/Tpl/App/default/Index/Email_NoteTips.html">
             <img src="/center/img2/userCenter_icon9.png"><b>领取微信群</b></a></li> -->
        <li><a href="/hufen/list.php?ac=qun&uid=<?php echo $rt['userinfo']['wecha_id'] ?>">
            <img src="/center/img2/userCenter_icon9.png"><b>疯狂微信群</b></a></li>

        <li><a href="/m/daili.php?act=postmoney">
            <img src="/center/img2/userCenter_icon3.png"><b>申请提现</b></a></li>

        <!-- <li><a href="http://hmwx.k99p.com/Public/Tools/WXDK_Android_iOS.html"><img
                src="/center/img2/userCenter_icon13.png"><b>微信多开</b></a></li>
        <li><a href="http://hmwx.k99p.com/Public/Tools/ZDHB_Android_iOS.html"><img
                src="/center/img2/userCenter_icon14.png"><b>自动红包</b></a></li> -->
        <li><a href="http://jfs.8208111.com/m/new.php?cid=9">
            <img src="/center/img2/userCenter_icon11.png"><b>新手上路</b></a></li>

        <!-- <li><a href="#"><img src="/center/img2/userCenter_icon12.png"><b>销售排行</b></a> </li>
        <li><a href="#"><b></b></a> </li> -->
    </ul>
</section>
<script type="text/javascript">
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
<br /><br /><br /><br />
<!--FOOTER-->
<?php if(!strpos($_SERVER['PHP_SELF'],'user.php') && !strpos($_SERVER['PHP_SELF'],'daili.php')){?>
<?php } if(!empty($lang['copyright'])){?>
<div style="text-align:center;padding-bottom:10px; padding-top:10px;"><?php echo $lang['copyright'];?></div>
<?php } ?>

<?php
$nums = 0;
$thiscart = $this->Session->read('cart');
if(!empty($thiscart))foreach($thiscart as $row){
	$nums +=$row['number'];
}
?>
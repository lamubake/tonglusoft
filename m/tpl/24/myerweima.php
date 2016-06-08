<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/css.css" media="all" />
<?php $this->element('24/top',array('lang'=>$lang)); ?>
<div id="main" style="min-height:300px">
	<div>
		<img src="<?php echo $qcodeimg;?>" style=" width:100%;max-width:100%; cursor:pointer" />
	</div>
	<div style="margin:10%; margin-top:0px; background:#4c4343;text-align:center; height:45px; line-height:45px; font-size:14px; font-weight:bold; color:#FFF;-webkit-box-shadow: 0px 4px 4px #abaaaa; margin-bottom:5px;">
	长按保存图片或将本页面分享给好友
	</div>
	<div style="margin:5px; margin-top:0px;">
	<div style=" display:block; color:#999999">若没有生成图片二维码，请返回微信主菜单重新生成</div>
	</div>
</div>
推广链接：<br />
<label class="copyurl" style="width:100%; color:#666; background:#FAFAFA; text-indent:20px; margin:0 auto; overflow:hidden" onclick="clickselect()">
	  <?php echo $thisurl;?><br />
</label>
<div style="height:40px; clear:both"></div>

<script type="text/javascript">
function clickselect(obj){
	$(obj).select();
}
</script>
<!--FOOTER-->
<?php if(!strpos($_SERVER['PHP_SELF'],'user.php') && !strpos($_SERVER['PHP_SELF'],'daili.php')){?>
<?php } if(!empty($lang['copyright'])){?>
<div style="text-align:center;padding-bottom:10px; padding-top:10px;"><?php echo $lang['copyright'];?></div>
<?php } ?>
<style type="text/css">
body { padding-bottom:60px !important; }
</style>
<?php
$nums = 0;
$thiscart = $this->Session->read('cart');
if(!empty($thiscart))foreach($thiscart as $row){
	$nums +=$row['number'];
}
?>
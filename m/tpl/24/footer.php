

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

<div id="collectBox"></div>
 
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL;?>tpl/24/css/font-awesome.css">
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URL;?>tpl/24/css/PreFoot.css">
 
<div class="fixed bottom">
<dl class="sub-nav nav-b5">
    <dd style="width:34%">
        <div class="nav-b5-relative"><a href="<?php echo ADMIN_URL.'product.php?id=108';?>"><i class="icon-nav-bag"></i>立即购买</a></div>
    </dd>
    
    <dd style="width:33%">
        <div class="nav-b5-relative"><a href="<?php echo ADMIN_URL.'/user.php?act=orderlist' ?>"><i class="icon-nav-store"></i>我的订单</a></div>
    </dd>
    <dd style="width:33%">
        <div class="nav-b5-relative"><a href="<?php echo ADMIN_URL.'user.php';?>"><i class="icon-nav-heart"></i>家族成就</a></div>
    </dd>
	<!--
    <dd>
        <div class="nav-b5-relative"><a href="<?php echo ADMIN_URL.'mycart.php';?>"><i class="icon-nav-cart"></i>我的二维码</a></div>
    </dd>
	-->
</dl>
</div>
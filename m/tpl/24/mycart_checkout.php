<link type="text/css" rel="stylesheet" href="<?php echo ADMIN_URL;?>tpl/24/css.css" media="all" />
<!--##########################################################
	技术支持：烟台通路网络科技有限公司
	电话：15553510105
	QQ：33614970
	##############################################################
-->
<style type="text/css">
.checkout{background:#FFF; padding-bottom:10px; font-size:0.9rem}
.checkout p.title{background:#eaeaea;height:27px;line-height:27px;text-indent:10px;width:100%;color:#9a0000;font-weight:700;margin:10px 0 0 0;border-bottom:2px solid #CCC}
.checkout table{text-align:left;color:#333333;margin:0;}
.checkout td{line-height:20px;padding:3px 0 3px 0}
.checkout .number{font-size:1rem; margin-left:5px}
.checkout .userreddinfo td{line-height:20px;padding:2px 0 2px 0}
.selectlabel{line-height:32px; margin-left:5px; margin-right:5px; float:left; background:#fa6a00; color:#FFF; padding-right:5px}
.selectlabel .xinghao{opacity: 0; position: absolute; z-index: -1;}
.pw{line-height:23px;height:23px}
.addgallery i{font-style:normal}

.addgallery{ padding-left:14px;background:url(<?php echo $this->img('+.png');?>) 3px center no-repeat}
.removegallery{ padding-left:14px;background:url(<?php echo $this->img('-.png');?>) 3px center no-repeat}
</style>
<div id="main" style="min-height:300px;">
	<div class="checkout">
	<form action="<?php echo ADMIN_URL;?>mycart.php?type=confirm" method="post" name="CONSIGNEE_ADDRESS" id="CONSIGNEE_ADDRESS">
		
		<table border="0" cellpadding="0" cellspacing="0" style="width:100%; margin-top:-7px">
		 <?php 
				  if(!empty($goodslist)){
				  $total= 0;
				  $uid = $this->Session->read('User.uid');
				  $active = $this->Session->read('User.active');
				  foreach($goodslist as $k=>$row){
					$total +=$row['price']*$row['number'];
		   ?>
			<tr><td colspan="2"><img src="<?php echo $row['original_img'];?>" title="<?php echo $row['goods_name'];?>" border="0" style="width:100%"></td></tr>
			<tr>
				<td align="left" colspan="2">
				<?php
					if($rank==1){
				?>
					<div class="selectlabel" id="sl1" onclick="slbg('sl1')"><input type="radio" name="xinghao" class="xinghao" id="xinghao3" value="1500" onclick="typemoney('c600')" /><label for="xinghao3"><span class="number">钻石卡</span></label></div>
					<div class="selectlabel" id="sl2" onclick="slbg('sl2')"><input type="radio" name="xinghao" class="xinghao" id="xinghao2" value="500" onclick="typemoney('b300')" /><label for="xinghao2"><span class="number">金卡</span></label></div>
					<div class="selectlabel" id="sl3" onclick="slbg('sl3')"><input type="radio" name="xinghao" class="xinghao" id="xinghao1" value="100" onclick="typemoney('a100')" /><label for="xinghao1"><span class="number">银卡</span></label></div>
				<?php
					}elseif($rank==12){
				?>
					<div class="selectlabel" id="sl1" onclick="slbg('sl1')"><input type="radio" name="xinghao" class="xinghao" id="xinghao3" value="1400" onclick="typemoney('c500')" /><label for="xinghao3"><span class="number">钻石卡</span></div>
					<div class="selectlabel" id="sl2" onclick="slbg('sl2')"><input type="radio" name="xinghao" class="xinghao" id="xinghao2" value="400" onclick="typemoney('b200')" /><label for="xinghao2"><span class="number">金卡</span></div>
					<div class="selectlabel" id="sl3"><input type="radio" disabled name="xinghao" class="xinghao" id="xinghao1" value="100" onclick="typemoney('a100')" /><label for="xinghao1" style="text-decoration:line-through"><span class="number">银卡</span></label></div>
				<?php
					}elseif($rank==13){
				?>
					<div class="selectlabel" id="sl1" onclick="slbg('sl1')"><input type="radio" name="xinghao" class="xinghao" id="xinghao3" value="1000" onclick="typemoney('c300')" /><label for="xinghao3"><span class="number">钻石卡</span></label></div>
					<div class="selectlabel" id="sl2"><input type="radio" disabled name="xinghao" class="xinghao" id="xinghao2" value="300" onclick="typemoney('b300')" /><label for="xinghao2" style="text-decoration:line-through"><span class="number">金卡</span></label></div>
					<div class="selectlabel" id="sl3"><input type="radio" disabled name="xinghao" class="xinghao"  id="xinghao1" value="100" onclick="typemoney('a100')" /><label for="xinghao1" style="text-decoration:line-through"><span class="number">银卡</span></label></div>
				<?php
					}elseif($rank==14){
				?>
					<div class="selectlabel"><input type="radio" disabled name="xinghao" class="xinghao" id="xinghao3" value="300" onclick="typemoney('c300')" /><label for="xinghao3" style="text-decoration:line-through"><span class="number">钻石卡</span></label></div>
					<div class="selectlabel"><input type="radio" disabled name="xinghao" class="xinghao" id="xinghao2" value="300" onclick="typemoney('b300')" /><label for="xinghao2" style="text-decoration:line-through"><span class="number">金卡</span></label></div>
					<div class="selectlabel"><input type="radio" disabled name="xinghao" class="xinghao"  id="xinghao1" value="100" onclick="typemoney('a100')" /><label for="xinghao1" style="text-decoration:line-through"><span class="number">银卡</span></label></div>
				<?php
					/**
						echo "<script>alert('对不起，您已经是最高等级了。')</script>";
						echo "<script>window.location.href='/m/user.php'</script>";
						exit();
					**/
					}
				?>					
				</td>
			</tr>
			<tr>
			<td colspan="2" style="border-top:#E03106 solid 1px; border-bottom:#E03106 solid 1px; padding:15px;">
				<div style="width:220px; margin:0 auto; font-size:16px">
				<p>购物车总价：&nbsp;￥<span id="yuanjia_span" style="text-decoration:line-through;">0</span></p>
				<p>商品单价：￥100.00&nbsp;&nbsp;￥<span id="price_span" style="font-size:20px; color:#E03106">100</span></p>
				</div>
			</td>
			</tr>
			<tr style="display:none">
				<td style="width:80px; text-align:center; height:80px; padding-top:10px; overflow:hidden; border-bottom:1px solid #ededed;" valign="top">
				&nbsp;
				</td>
				<td style="text-align:left; border-bottom:1px solid #ededed; vertical-align:top" valign="top">
				<p style="padding-left:10px; position:relative; line-height:25px;">
					<b style="color:#333; font-size:16px"><?php echo $row['goods_name'];?></b>			
					<span style="padding:5px;   right:5px; top:0px; z-index:22;  border:1px solid #CCC; border-radius:5px; color:#FF3300" class="delcartid" id="<?php echo $k;?>">删除</span></p>
				 <input type="hidden" name="youfei" value="0" />
				 <p style=" padding-left:10px;font-size:12px;line-height:20px;" class="raturnprice raturnprice<?php echo $k;?>">原价:<font color="#333333">￥<?php echo $row['shop_price'];?></font>&nbsp;&nbsp;抢购价:<font color="#FF0000" class="gprice<?php echo $k;?>">￥<?php echo $row['price']>0 ? $row['price']  : $row['pifa_price'];?></font></p>
				 <div class="item" style="height:20px; line-height:20px; position:relative; padding-left:10px; padding-top:7px">
						<a class="jian" style="cursor:pointer; display:block; float:left; width:35px; height:22px;line-height:22px;text-align:center; font-size:18px; font-weight:bold; border:1px solid #ccc; background:#ededed">-</a><input readonly="" id="<?php echo $k;?>" name="goods_number" value="<?php echo $row['number'];?>" class="inputBg" style="float:left;text-align: center; width:20px; height:22px; line-height:22px;border-bottom:1px solid #ccc; border-top:1px solid #ccc" type="text"> <a class="jia" style="cursor:pointer; display:block; float:left; width:35px; height:22px;line-height:22px;text-align:center; font-size:18px; font-weight:bold; border:1px solid #ccc; background:#ededed">+</a><b style="margin-left:3px;"><?php  echo $row['goods_unit'];?></b>
						&nbsp;&nbsp;小计:<font color="#FF0000" class="gzprice<?php echo $k;?>">￥<?php echo $row['price']*$row['number'];?></font>
				  </div>
				</td>
			</tr>
			
			 <?php } } ?>
		</table>
		
		<table style="display:block">
			<tr>
				  <td align="right" width="22%"><span>支付方式：</span></td>
				  <td align="left" width="78%">
				  <?php 
				if(!empty($rt['paymentlist'])){
					echo '<table border="0" cellpadding="0" cellspacing="0" style="width:100%;"><tr>';
					foreach($rt['paymentlist'] as $k=>$row){
					if($row['pay_id']=='7'){
					?>
					<td><label><span><input name="pay_id"  id="pay_id"<?php if($k=='0'){ echo ' checked="checked"';}?> value="<?php echo $row['pay_id'];?>" type="radio"></span><strong><?php echo $row['pay_name'].'(<font color=red>￥'.$rt['mymoney'].'</font>)';?></strong></label></td>
					<?php
					}else{
					?>
					  <td><label><span><input name="pay_id"  id="pay_id"<?php if($k=='0'){ echo ' checked="checked"';}?> value="<?php echo $row['pay_id'];?>" type="radio"></span><strong><?php echo $row['pay_name'];?></strong></label></td>
					<?php
					}
					}
					echo '</tr></table>';
				}
				?>
				  </td>
			</tr>
			<tr style="display:none">
				  <td align="right" width="22%"><span>配送方式：</span></td>
				  <td align="left" width="78%">
					<?php 
					$free = array();
					if(!empty($rt['shippinglist'])){
					echo '<table border="0" cellpadding="0" cellspacing="0" style="width:100%;"><tr>';
					foreach($rt['shippinglist'] as $k=>$row){
					?>
					   <td><label><span><input onclick="return jisuan_shopping('<?php echo $row['shipping_id'];?>')"<?php echo $k=='0' ? ' checked="checked"':'';?> name="shipping_id" id="shipping_id" value="<?php echo $row['shipping_id'];?>" type="radio" /></span><strong><?php echo $row['shipping_name'];?></strong></label>
					  <?php 
						$f = $this->action('shopping','ajax_jisuan_shopping',array('shopping_id'=>$row['shipping_id'],'userress_id'=>($userress_id > 0 ? $userress_id : '5')),'cart');
						$f = $f>0 ? $f : '0.00';
						$free[] = $f;
						?>
					  </td>
					<?php
					}
					echo '</tr></table>';
				}
				?>
				  </td>
			</tr>
			<tr style="display:none">
				<td align="right" width="22%">订单留言：</td>
				<td>
				<textarea class="pw" name="postscript" id="postscript" style="width:96%; height:30px;"></textarea>
				</td>
			</tr>
		</table>
		
		<br />
	  <table border="0" cellpadding="0" cellspacing="0" style="width:100%;border-top:1px solid #ededed;">
		<tr>
		<td style="border:#D2D2D2 solid 1px; padding:5px">
			&nbsp;<b style="color:#E03106; text-shadow: #E03106 0 1px 0;">∨</b>&nbsp;收货信息
		</td>
		</tr>
		<?php if(!empty($rt['userress'])){?>
		<?php $userress_id = 0; foreach($rt['userress'] as $row){?>
		  <tr>
		  <td>
		  <label style="padding-left:10px;"><input<?php echo $row['is_default']=='1' ? ' checked="checked"' : '';?> type="radio" class="showaddress" name="userress_id" value="<?php echo $row['address_id'];?>"/>
		  <?php
		  echo $row['provincename'].$row['cityname'].$row['districtname'].$row['address'].'<br/><span style="padding-left:26px;"></span>'.'电话:'. (!empty($row['mobile']) ? $row['mobile'] : $row['tel']) .'&nbsp;联系人:'. $row['consignee'];
		  ?></label>
		  <p style="padding-left:26px;">
		  <a href="javascript:;" onclick="ressinfoop('<?php echo $row['address_id'];?>','showupdate',this)" style="border-radius:5px;display:block;background:#3083CE;cursor:pointer;width:60px; height:22px; line-height:22px; font-size:12px; color:#FFF; text-align:center">修改</a>
		  </p>
		  </td>
		  </tr>
		  <?php } }?>
		  <?php 
			$userress_id = $userress_id > 0 ? $userress_id : (isset($rt['userress'][0]) ? $rt['userress'][0]['address_id'] : 0);
		  ?>
		  <tr>
		  <td><label style="padding-left:10px;"><input class="showaddress" name="userress_id" type="radio" value="0" />&nbsp;添加新收货地址</label></td>
		  </tr>
		  <tr>
		  	<td align="left">
				<table width="100%" border="0" cellpadding="0" cellspacing="0"<?php if(!empty($rt['userress'])) echo ' style="display:none"';?> class="userreddinfo">
				  <tr>
					<td align="right">姓名：</td>
					<td align="left"><input type="text" value="" name="consignee"  class="pw" style="width:95%;;"/> 
					</td>
				  </tr>
				   <tr>
					<td align="right">区域：</td>
					<td align="left">
				<?php $this->element('address',array('resslist'=>$rt['province']));?>
					</td>
					
				  </tr>
				  <tr class="address_sh">
					<td align="right">地址：</td>
					<td align="left"><input type="text" value=" " name="address"  class="pw" style="width:95%;;"/></td>
				  </tr>
				  <tr>
					<td align="right">电话：</td>
					<td align="left"><input type="text" value="" name="mobile"  class="pw" style="width:95%;"/></td>
				  </tr>
				  <tr>
				  <td>&nbsp;</td>
				  <td align="left" colspan="2"><img src="<?php echo $this->img('btu_add.gif');?>" alt="" style="cursor:pointer" onclick="ressinfoop('0','add','CONSIGNEE_ADDRESS')"/></td>
				  </tr>
			</table>
			</td>
		  </tr>
	    </table>
		
	  <div style="display:none">
			<?php $free[0] = empty($free[0]) ? '0.00' : $free[0]; ?>
			<p style="line-height:22px; color:#222; font-size:14px; font-weight:bold; color:#9A0000; padding-top:5px;">&nbsp;商品金额:￥<span class="ztotals"><?php echo $zp = $total; ?></font></span>元</p>
	  </div>
	  <?php
		if($rank>=14){
	  ?>
	  <p style="margin-top:10px; width:100%; text-align:center">
			<span style="padding:10px; width:88%; height:50px; line-height:50px; font-size:20px; background:#FFA521; color:#FFFFFF; text-align:center;">最高等级，无法购买</span>
	  </p>
	  <?php
		}else{
	  ?>
	  <p style="margin-top:10px; width:100%; text-align:center">
			<input value="立即抢购" type="submit" align="absmiddle" onclick="return checkvar()" style="width:88%; height:50px; line-height:50px; font-size:20px; background:#FFA521; color:#FFFFFF; text-align:center;" />
	  </p>
	  <?php
		}
	  ?>
	</form>
	</div>
</div>
<br /><br />
<?php  $thisurl = ADMIN_URL.'mycart.php'; ?> 
<script language="javascript" type="text/javascript">
//2位小数
function toDecimal(x) {  
	var f = parseFloat(x);  
	if (isNaN(f)) {  
		return;  
	}  
	f = Math.round(x*100)/100;  
	return f;  
} 

function ajax_clear(){
	if(confirm('确定吗')){
		window.location.href='<?php echo ADMIN_URL;?>mycart.php?type=clear';
		return true;
	}
	return false;
}
$('.showaddress').live('click',function(){
	var vv= $(this).val();
	if(vv==0){
	$('.userreddinfo').show();
	}else{
	$('.userreddinfo').hide();
	}
	//$('.userreddinfo').toggle();
});

function typemoney(money){
	var m = 100;
	var n = 100;
	switch(money){
		case 'a100':
			m=100;
			n=100;
			break;
		case 'b300':
			m=500;
			n=500;
			break;
		case 'c600':
			m=1500;
			n=1500;
			break;
		case 'b200':
			m=400;
			n=400;
			break;
		case 'c500':
			m=1400;
			n=1400;
			break;
		case 'c300':
			m=1000;
			n=1000;
			break;
	}
	document.getElementById('price_span').innerHTML = m;
	document.getElementById('yuanjia_span').innerHTML = n;
}

function checkvar(){
	pp = $('input[name="pay_id"]:checked').val(); 
	if(typeof(pp)=='undefined' || pp ==""){
		alert("请选择支付方式！");
		return false;
	}
	
	xinghao = $('input[name="xinghao"]:checked').val(); 
	if(typeof(xinghao)=='undefined' || xinghao ==""){
		alert("请选择购买型号");
		return false;
	}
	
	ss = $('input[name="shipping_id"]:checked').val(); 
	if(typeof(ss)=='undefined' || ss ==""){
		alert("请选择配送方式！");
		return false;
	}
	
	userress_id = $('input[name="userress_id"]:checked').val();
	if(userress_id == '0' || userress_id == '' || typeof(userress_id)=='undefined'){
			consignee = $('input[name="consignee"]').val(); 
			if(typeof(consignee)=='undefined' || consignee ==""){
				alert("收货人不能为空！");
				return false;
			}
			
			provinces = $('select[name="province"]').val();
			if ( provinces == '0' )
			{
				alert("请选择收货地址！");
				return false;
			}
			
			city = $('select[name="city"]').val();
			if ( city == '0' )
			{
				alert("请完整选择收货地址！");
				return false;
			}
			
			district = $('select[name="district"]').val();
			if ( district == '0' )
			{
				alert("请完整选择收货地址！");
				return false;
			}
		
			address = $('input[name="address"]').val(); 
			if(typeof(address)=='undefined' || address ==""){
				alert("详细地址不能为空！");
				return false;
			}
			
			mobile = $('input[name="mobile"]').val(); 
			tel = $('input[name="tel"]').val(); 
			if(mobile =="" && tel ==""){
				alert("请输入手机或者电话号码！");
				return false;
			}
	}	

	return true;
}

function slbg(divbg){
	document.getElementById('sl1').style.backgroundColor = "#FA6A00";
	document.getElementById('sl2').style.backgroundColor = "#FA6A00";
	document.getElementById('sl3').style.backgroundColor = "#FA6A00";
	document.getElementById(divbg).style.backgroundColor = "#E03106";
}
</script>
<?php $this->element('24/footer',array('lang'=>$lang)); ?>
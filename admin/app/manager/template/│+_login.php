<style type="text/css">
.main{ border:none; background:none}
.maincontent{ text-align:center; width:100%; margin-left:0px}
.loginbox{ text-align:center}
.logincontent table td{ line-height:30px}
</style>
<div class="loginbox">
<div class="logincontent">
<div style="margin:0px auto; width:900px; text-align:center">
  <form id="form1" name="form1" method="post" action="">
	<table  align="center" width="270" style=" float:right; padding-top:10px">
  	<tr>
	<td align="left"> <input type="text" name="adminname" class="uname"  style="width:200px; height:20px; line-height:20px"/></td>
	</tr>
	<tr>
	<td align="left">  <input type="password" name="password" class="pass" style="width:200px;height:20px; line-height:20px"/></td>
	</tr>
	<tr>
	 <td align="left">  <input type="text" name="vifcode"  size="10" class="vifcode"  style="width:140px;height:20px; line-height:20px; float:left"/><img  src="<?php echo ADMIN_URL;?>captcha.php" onclick="this.src='<?php echo ADMIN_URL;?>captcha.php?'+Math.random()" align="absmiddle" style=" margin-left:5px; float:left"/></td>
	</tr>
	<tr>
	 <td align="left">
	 <p style=" margin:0px; padding:0px;padding-top:5px">
	 <a style="display:block; width:70px; height:30px; float:left;" class="login_button"></a>
	 <input type="reset" value="" style="width:70px; height:30px; float:left; background:none; border:none; cursor:pointer" />
	 </p>
	 </td>
	</tr>
	<tr>
		<td style="height:20px; line-height:20px;" align="left"><span class="error_msg" style="color:#FF0000; font-size:13px"></span>
		</td>
	</tr>
  </table>
  </form>
  <div style="clear:both"></div>
  </div>
</div>
</div>
<?php  $thisurl = ADMIN_URL.'login.php'; ?>
<script type="text/javascript">
$('.login_button').click(function(){
	submit_data();
});
	
//回车键提交
document.onkeypress=function(e)
{
	　　var code;
	　　if  (!e)
	　　{
	　　		var e=window.event;
	　　}
	　　if(e.keyCode)
	　　{
	　　		code=e.keyCode;
	　　}
	　　else if(e.which)
	　　{
	　　		code   =   e.which;
	　　}
	　　if(code==13) //回车键
	　　{
			submit_data();
	　　}
}

function submit_data(){
		name = $('.uname').val(); 
        pas = $('.pass').val();
		vifcodes = $('.vifcode').val();
		if(name == "" || pas == "" || vifcodes == "") return false;
		createwindow();
		$.post('<?php echo $thisurl;?>',{action:'login',adminname:name,password:pas,vifcode:vifcodes},function(data){ 
			removewindow();
			if(data != ""){
				$('.error_msg').html(data);
			}else{
			 	location.href='<?php echo ADMIN_URL;?>';
				return;
			}
		});
}	

</script>
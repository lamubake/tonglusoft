<style type="text/css">
.gototype a{ padding:2px; border-bottom:2px solid #ccc; border-right:2px solid #ccc;}
</style>
<div class="contentbox">
<form id="form1" name="form1" method="post" action="">
     <table cellspacing="2" cellpadding="5" width="100%">
		<tr>
			<td class="label" width="15%">消费送积分：<br/>[购买者]&nbsp;&nbsp;</td>
			<td>
			消费1元获得<input name="pointnum" value="<?php echo isset($rt['pointnum']) ? $rt['pointnum'] : '0';?>" size="10" type="text" />&nbsp;<font color="#FF0000">*</font>&nbsp;<input name="pointnum_ag" value="<?php echo isset($rt['pointnum_ag']) ? $rt['pointnum_ag'] : '1.0';?>" size="10" type="text" />&nbsp;积分
			</td>
		</tr>
		<tr>
			<td class="label" width="15%">消费送积分：<br/>[上一级]&nbsp;&nbsp;</td>
			<td>
			消费1元获得<input name="tjpointnum" value="<?php echo isset($rt['tjpointnum']) ? $rt['tjpointnum'] : '0';?>" size="10" type="text" />&nbsp;<font color="#FF0000">*</font>&nbsp;<input name="tjpointnum_ag" value="<?php echo isset($rt['tjpointnum_ag']) ? $rt['tjpointnum_ag'] : '1.0';?>" size="10" type="text" />&nbsp;积分
			</td>
		</tr>
		<tr>
			<td class="label" width="15%">消费送积分：<br/>[上二级]&nbsp;&nbsp;</td>
			<td>
			消费1元获得<input name="tjpointnum2" value="<?php echo isset($rt['tjpointnum2']) ? $rt['tjpointnum2'] : '0';?>" size="10" type="text" />&nbsp;<font color="#FF0000">*</font>&nbsp;<input name="tjpointnum_ag2" value="<?php echo isset($rt['tjpointnum_ag2']) ? $rt['tjpointnum_ag2'] : '1.0';?>" size="10" type="text" />&nbsp;积分
			</td>
		</tr>
		<tr>
			<td class="label" width="15%">消费送积分：<br/>[上三级]&nbsp;&nbsp;</td>
			<td>
			消费1元获得<input name="tjpointnum3" value="<?php echo isset($rt['tjpointnum3']) ? $rt['tjpointnum3'] : '0';?>" size="10" type="text" />&nbsp;<font color="#FF0000">*</font>&nbsp;<input name="tjpointnum_ag3" value="<?php echo isset($rt['tjpointnum_ag3']) ? $rt['tjpointnum_ag3'] : '1.0';?>" size="10" type="text" />&nbsp;积分
			</td>
		</tr>
		<tr>
			<td class="label">推荐关注送积分：</td>
			<td>
			 推荐一个用户关注<input name="tuijiannum" value="<?php echo isset($rt['tuijiannum']) ? $rt['tuijiannum'] : '0';?>" size="10" type="text" />积分
			  </td>
		</tr>
		<tr>
			<td class="label">关注送积分：</td>
			<td>
			 关注所得<input name="ticheng360_2" value="<?php echo isset($rt['ticheng360_2']) ? $rt['ticheng360_2'] : '0';?>" size="10" type="text" />积分
			  </td>
		</tr>
		<tr>
			<td class="label">数量享折扣：</td>
			<td>
			   二件享<input name="address2off" value="<?php echo isset($rt['address2off']) ? $rt['address2off'] : '100';?>" size="10" type="text" />%。【相对关注后的价格再折扣】<br/><br/>
			   三件以上享<input name="address3off" value="<?php echo isset($rt['address3off']) ? $rt['address3off'] : '100';?>" size="10" type="text" />%。【相对关注后的价格再折扣再相对2个数量的价格再折扣】<br/>
			  </td>
		</tr>
		<tr>
			<td class="label">关注享折扣：</td>
			<td>
			 关注后享<input name="guanzhuoff" value="<?php echo isset($rt['guanzhuoff']) ? $rt['guanzhuoff'] : '100';?>" size="6" type="text" />%。【相对当前的销售价格，即PC端购买享受不到该折扣】
			  </td>
		</tr>
		<tr>
			<td colspan="2">
			<hr/>
			</td>
		</tr>
		<tr>
			<td class="label" width="15%">最小提款额度：</td>
			<td>
			<input name="dixin360" value="<?php echo isset($rt['dixin360']) ? $rt['dixin360'] : '0';?>" size="10" type="text" />元
			</td>
		</tr>
		<tr>
			<td class="label">购买者提成点：</td>
			<td>
			 返佣比例<input name="ticheng360_1" value="<?php echo isset($rt['ticheng360_1']) ? $rt['ticheng360_1'] : '0';?>" size="10" type="text" />%&nbsp;<em style="color:#FF0000"></em><br/>
<!--		
			 三级分佣<input name="ticheng360_3" value="<?php echo isset($rt['ticheng360_3']) ? $rt['ticheng360_3'] : '0';?>" size="10" type="text" />%<br/>-->
			  </td>
		</tr>
		<tr>
			<td class="label">普通分销提成点：</td>
			<td>
			 一级返佣比例<input name="ticheng180_1" value="<?php echo isset($rt['ticheng180_1']) ? $rt['ticheng180_1'] : '0';?>" size="10" type="text" />%<br/>
			 二级返佣比例<input name="ticheng180_2" value="<?php echo isset($rt['ticheng180_2']) ? $rt['ticheng180_2'] : '0';?>" size="10" type="text" />%<br/>
			 三级返佣比例<input name="ticheng180_3" value="<?php echo isset($rt['ticheng180_3']) ? $rt['ticheng180_3'] : '0';?>" size="10" type="text" />%<br/>
			 四级返佣比例<input name="ticheng180_4" value="<?php echo isset($rt['ticheng180_4']) ? $rt['ticheng180_4'] : '0';?>" size="10" type="text" />%<br/>
			  </td>
		</tr>
		<tr>
			<td class="label">高级分销提成点：</td>
			<td>
			 一级返佣比例<input name="ticheng180_h1_1" value="<?php echo isset($rt['ticheng180_h1_1']) ? $rt['ticheng180_h1_1'] : '0';?>" size="10" type="text" />%<br/>
			 二级返佣比例<input name="ticheng180_h1_2" value="<?php echo isset($rt['ticheng180_h1_2']) ? $rt['ticheng180_h1_2'] : '0';?>" size="10" type="text" />%<br/>
			 三级返佣比例<input name="ticheng180_h1_3" value="<?php echo isset($rt['ticheng180_h1_3']) ? $rt['ticheng180_h1_3'] : '0';?>" size="10" type="text" />%<br/>
			 四级返佣比例<input name="ticheng180_h1_4" value="<?php echo isset($rt['ticheng180_h1_4']) ? $rt['ticheng180_h1_4'] : '0';?>" size="10" type="text" />%<br/>
			  </td>
		</tr>
		<tr>
			<td class="label">高级特权分销：</td>
			<td>
			 <!--当销售额达<input name="minsalenum" value="<?php echo isset($rt['minsalenum']) ? $rt['minsalenum'] : '0.00';?>" size="6" type="text" />元，享受以下三层提成点外，再享线下所有会员的业绩红包<input name="ticheng180_all" value="<?php echo isset($rt['ticheng180_all']) ? $rt['ticheng180_all'] : '0.00';?>" size="6" type="text" />%【相对销售业绩】<br/>-->
			 一级返佣比例<input name="ticheng180_h2_1" value="<?php echo isset($rt['ticheng180_h2_1']) ? $rt['ticheng180_h2_1'] : '0';?>" size="10" type="text" />%<br/>
			 二级返佣比例<input name="ticheng180_h2_2" value="<?php echo isset($rt['ticheng180_h2_2']) ? $rt['ticheng180_h2_2'] : '0';?>" size="10" type="text" />%<br/>
			 三级返佣比例<input name="ticheng180_h2_3" value="<?php echo isset($rt['ticheng180_h2_3']) ? $rt['ticheng180_h2_3'] : '0';?>" size="10" type="text" />%<br/>
			 四级返佣比例<input name="ticheng180_h2_4" value="<?php echo isset($rt['ticheng180_h2_4']) ? $rt['ticheng180_h2_4'] : '0';?>" size="10" type="text" />%<br/>

			  </td>
		</tr>
		<tr>
			<td colspan="2">
			<hr/>
			</td>
		</tr>
		<tr>
			<td class="label">开通分销设置：</td>
			<td>
			  <label><input type="checkbox" name="openfxbuy" value="1"<?php echo isset($rt['openfxbuy'])&&$rt['openfxbuy']=='1' ? ' checked="checked"' : '';?> />&nbsp;购买自动开通&nbsp;</label>【最小购买金额<input name="openfx_minmoney" value="<?php echo isset($rt['openfx_minmoney']) ? $rt['openfx_minmoney'] : '0';?>" size="10" type="text" />元】<em style="color:#FF0000">如果设置了级别升级，这里的优先级低</em>&nbsp;&nbsp;
			  <label><input type="checkbox" name="openfxauto" value="1"<?php echo isset($rt['openfxauto'])&&$rt['openfxauto']=='1' ? ' checked="checked"' : '';?> />&nbsp;默认关注后自动开通&nbsp;&nbsp;</label>&nbsp;&nbsp;
			  <label><input type="checkbox" name="openfx_baoming" value="1"<?php echo isset($rt['openfx_baoming'])&&$rt['openfx_baoming']=='1' ? ' checked="checked"' : '';?> />&nbsp;在线报名自动开通&nbsp;</label>
			</td>
		</tr>
		<tr>
			<td class="label">分销中心查看设置：</td>
		  <td>
			  <label><input type="radio" name="viewfxset" value="1"<?php echo isset($rt['viewfxset'])&&$rt['viewfxset']=='1' ? ' checked="checked"' : '';?> />&nbsp;普通会员可查看&nbsp;&nbsp;</label>
			  <label><input type="radio" name="viewfxset" value="0"<?php echo isset($rt['viewfxset'])&&$rt['viewfxset']=='0' ? ' checked="checked"' : '';?> />&nbsp;普通会员不可查看&nbsp;&nbsp;</label>
			  </td>
		</tr>
		<tr>
			<td class="label">关注购买：</td>
		  <td>
			  <label><input type="radio" name="guanzhubuy" value="1"<?php echo isset($rt['guanzhubuy'])&&$rt['guanzhubuy']=='1' ? ' checked="checked"' : '';?> />&nbsp;可以购买&nbsp;&nbsp;</label>
			  <label><input type="radio" name="guanzhubuy" value="0"<?php echo isset($rt['guanzhubuy'])&&$rt['guanzhubuy']=='0' ? ' checked="checked"' : '';?> />&nbsp;不可以购买&nbsp;&nbsp;</label>
			  </td>
		</tr>
		<tr>
			<td colspan="2">
			<hr/>
			</td>
		</tr>
		<tr>
			<td class="label">绑定平台<br/>通知微信</td>
		 <td>
		<select>
		<?php if(isset($rt['nickname'])&&!empty($rt['nickname'])){?>
		<option value="<?php echo $rt['wid'];?>"><?php echo $rt['nickname'];?></option>
		<?php }else{?>
		<option value="0">绑定微信</option>
		<?php } ?>
		</select>
		关键字搜索<input type="text" class="searchval" style="width:100px; border:1px solid #ccc" />
	 	<input type="button" value=" 搜索 "  style="cursor:pointer" onclick="ajax_u_name(this)"/>&nbsp;&nbsp;<input type="button" value=" 绑定 "  style="cursor:pointer" onclick="ajax_save_wid(this)"/>
		</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>
			  <input type="hidden" name="type" value="basic" />
			<label>
			  <input type="submit" value="确认保存" class="submit" style="cursor:pointer; padding:2px 4px 2px 4px"/>
		  </label></td>
		</tr>
		</table>
</form>
</div>
<script language="javascript">
function ajax_u_name(obj){
	va = $(obj).parent().find('.searchval').val();
	$.post('<?php echo ADMIN_URL.'goods.php';?>',{action:'ajax_u_name_shopid',searchval:va},function(data){
		if(data == ""){
			alert("未找到！");
		}else{
			$(obj).parent().find('select').html(data);
		}
	});
}
function ajax_save_wid(obj){
	wid = $(obj).parent().find('select').val();
	$.post('<?php echo ADMIN_URL.'weixin.php';?>',{action:'ajax_save_wid',wid:wid},function(data){
		alert(data);
	});
}

$('.submit').click(function(){
	
	return true;
});



function ger_ress_copy(type,obj,seobj){
	parent_id = $(obj).val();
	if(parent_id=="" || typeof(parent_id)=='undefined'){ return false; }
	$.post('user.php',{action:'get_ress',type:type,parent_id:parent_id},function(data){
		if(data!=""){
			$(obj).parent().find('#'+seobj).html(data);
			
			if(type==5){ //村
				$(obj).parent().find('#'+seobj).show();
				$(obj).parent().find('#select_peisong').hide();
			}else if(type==4){ //城镇
				$(obj).parent().find('#select_village').hide();
				$(obj).parent().find('#select_village').html('<option value="0" >选择村</option>');
				$(obj).parent().find('#select_peisong').hide();
				
				$(obj).parent().find('#select_town').show();
				//$(obj).parent().find('#select_town').html("");
			}else if(type==3){ //区
				$(obj).parent().find('#select_peisong').hide();
				$(obj).parent().find('#select_peisong').html('<option value="0" >选择配送店</option>');
				
				$(obj).parent().find('#select_village').hide();
				$(obj).parent().find('#select_village').html('<option value="0" >选择村</option>');
				
				$(obj).parent().find('#select_town').hide();
				$(obj).parent().find('#select_town').html('<option value="0" >选择城镇</option>');
				
				$(obj).parent().find('#select_district').show();
				//$(obj).parent().find('#select_district').html("");
				
			}else if(type==2){ //市
				$(obj).parent().find('#select_peisong').hide();
				$(obj).parent().find('#select_peisong').html('<option value="0" >选择配送店</option>');
				
				$(obj).parent().find('#select_village').hide();
				$(obj).parent().find('#select_village').html('<option value="0" >选择村</option>');
				
				$(obj).parent().find('#select_town').hide();
				$(obj).parent().find('#select_town').html('<option value="0" >选择城镇</option>');
				
				$(obj).parent().find('#select_district').hide();
				$(obj).parent().find('#select_district').html('<option value="0" >选择区</option>');
				
				//$(obj).parent().find('#select_city').hide();
				//$(obj).parent().find('#select_city').html("");
			}

		}else{
			alert(data);
		}
	});
}




function change_user_points_money(uid,thisobj,type){
	val = $(thisobj).val();
	if(val>0 || val<0){
		if(confirm("你确定执行该操作吗？")){
			createwindow();
			$.post('user.php',{action:'change_user_points_money',uid:uid,type:type,val:val},function(data){
				if(typeof(data)!='undefined' && data!=""){
					removewindow();
					if(parseInt(data)>0){
						if(type=='money'){
							$(thisobj).parent().find('.thismoney').html(data);
						}else if(type =='points'){
							$(thisobj).parent().find('.thispoints').html(data);
						}
					}
					alert("操作成功！");
				}else{
					alert("操作失败！");
				}
			});
		}
	}
	return false;
}

  function get_userpoint_page_list(page,uid){
  		createwindow();
		$.post('user.php',{action:'pointinfo',page:page,uid:uid},function(data){
			removewindow();
			if(data !="" && typeof(data)!='undefined'){
				$('.user_point').html(data);
			}
		});
  }
  
  function get_usermoney_page_list(page,uid){
  		createwindow();
		$.post('user.php',{action:'mymoney',page:page,uid:uid},function(data){
			removewindow();
			if(data !="" && typeof(data)!='undefined'){
				$('.user_money').html(data);
			}
		});
}
</script>

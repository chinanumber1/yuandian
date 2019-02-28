<include file="Public:header"/>
	<form id="myform"  frame="true" refresh="true">
		  <ul class="tab_ul">
		   <li class="active"><a data-toggle="tab" href="#tab_mer_pr">商家</a></li>
		   <li><a data-toggle="tab" href="#tab_group_pr">团购</a></li>
		   <li><a data-toggle="tab" href="#tab_shop_pr">快店</a></li>
		   <li><a data-toggle="tab" href="#tab_meal_pr">餐饮</a></li>
		   <if condition="C('config.appoint_page_row')"><li><a data-toggle="tab" href="#tab_appoint_pr">预约</a></li></if>
		   <if condition="$config['is_cashier'] OR $config['pay_in_store']"><li><a data-toggle="tab" href="#tab_store_pr">{pigcms{$config.cash_alias_name}</a></li></if>
		   <if condition="$config['is_cashier'] OR $config['pay_in_store']"><li><a data-toggle="tab" href="#tab_cash_pr">到店支付</a></li></if>
		   <if condition="$config['wxapp_url']"><li><a data-toggle="tab" href="#tab_wxapp_pr">营销</a></li></if>
		   <if condition="$config['is_open_weidian']"><li><a data-toggle="tab" href="#tab_weidian_pr">微店</a></li></if>
		   <li><a data-toggle="tab" href="#tab_activity_pr">平台活动活动</a></li>
		  </ul>
		  <table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_mer_pr" style="display: table;">
		   <tbody>
			<tr>
			 <th width="160">商家抽成比例：</th>
			 <td><input type="text" class="input-text" name="merchant_percent" id="config_merchant_percent" value="{pigcms{$mer_pr.merchant_percent}" size="10" validate="number:true,max:100" tips="商家独立抽成比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
			<tr>
			 <th width="160">商家分佣比例：</th>
			 <td><input type="text" class="input-text" name="merchant_rate" id="config_merchant_rate" value="{pigcms{$mer_pr.merchant_rate}" size="10" validate="number:true,max:100" tips="商家独立分佣比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
			<tr>
				<th width="160">商家独立设置线下支付：</th>
				<td  class="radio_box">
					<span class="cb-enable"><label class="cb-enable  <if condition="$mer_pr.merchant_offline eq 1">selected</if>"><span>设置</span><input type="radio" name="merchant_offline" value="1"  <if condition="$mer_pr.merchant_offline eq 1">checked="checked"</if>/></label></span>			     
					<span class="cb-disable"><label class="cb-disable  <if condition="$mer_pr.merchant_offline eq 0">selected</if>"><span>跳过</span><input type="radio" name="merchant_offline" value="0"   <if condition="$mer_pr.merchant_offline eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
		   </tbody>
		  </table>
		 <table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_group_pr" style="display: none;">
		   <tbody>
			<tr>
			 <th width="160">商家团购抽成比例：</th>
			 <td><input type="text" class="input-text" name="group_percent" id="config_group_percent" value="{pigcms{$mer_pr.group_percent}" size="10" validate="number:true,max:100" tips="团购独立抽成比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
			<tr>
			 <th width="160">商家团购分佣比例：</th>
			 <td><input type="text" class="input-text" name="group_rate" id="config_group_rate" value="{pigcms{$mer_pr.group_rate}" size="10" validate="number:true,max:100" tips="团购独立分佣比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
			<tr>
				<th width="160">商家团购独立设置线下支付：</th>
				<td  class="radio_box">
					<span class="cb-enable"><label class="cb-enable  <if condition="$mer_pr.group_offline eq 1">selected</if>"><span>设置</span><input type="radio" name="group_offline" value="1"  <if condition="$mer_pr.group_offline eq 1">checked="checked"</if>/></label></span>			     
					<span class="cb-disable"><label class="cb-disable  <if condition="$mer_pr.group_offline eq 0">selected</if>"><span>跳过</span><input type="radio" name="group_offline" value="0"   <if condition="$mer_pr.group_offline eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
		   </tbody>
		  </table>
		  <table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_shop_pr" style="display: none;">
		   <tbody>
			<tr>
			 <th width="160">商家快店抽成比例：</th>
			 <td><input type="text" class="input-text" name="shop_percent" id="config_shop_percent" value="{pigcms{$mer_pr.shop_percent}" size="10" validate="number:true,max:100" tips="快店独立抽成比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
			<tr>
			 <th width="160">商家快店分佣比例：</th>
			 <td><input type="text" class="input-text" name="shop_rate" id="config_shop_rate" value="{pigcms{$mer_pr.shop_rate}" size="10" validate="number:true,max:100" tips="快店独立分佣比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
		
			
			<tr>
				<th width="160">设置线下支付：</th>
				<td  class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$mer_pr.shop_offline eq 1">selected</if>"><span>设置</span><input type="radio" name="shop_offline" value="1"  <if condition="$mer_pr.meal_offline eq 1">checked="checked"</if>/></label></span>			     
					<span class="cb-disable"><label class="cb-disable <if condition="$mer_pr.shop_offline eq 0">selected</if>"><span>跳过</span><input type="radio" name="shop_offline" value="0" <if condition="$mer_pr.meal_offline eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
		   </tbody>
		  </table>
		  <table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_meal_pr" style="display: none;">
		   <tbody>
			<tr>
			 <th width="160">商家餐饮抽成比例：</th>
			 <td><input type="text" class="input-text" name="meal_percent" id="config_meal_percent" value="{pigcms{$mer_pr.meal_percent}" size="10" validate="number:true,max:100" tips="餐饮独立抽成比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
			<tr>
			 <th width="160">商家餐饮分佣比例：</th>
			 <td><input type="text" class="input-text" name="meal_rate" id="config_meal_rate" value="{pigcms{$mer_pr.meal_rate}" size="10" validate="number:true,max:100" tips="餐饮独立分佣比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
			<tr>
				<th width="160">设置线下支付：</th>
				<td  class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$mer_pr.meal_offline eq 1">selected</if>"><span>设置</span><input type="radio" name="meal_offline" value="1"  <if condition="$mer_pr.meal_offline eq 1">checked="checked"</if>/></label></span>			     
					<span class="cb-disable"><label class="cb-disable <if condition="$mer_pr.meal_offline eq 0">selected</if>"><span>跳过</span><input type="radio" name="meal_offline" value="0" <if condition="$mer_pr.meal_offline eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
		   </tbody>
		  </table>
		  <table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_appoint_pr" style="display: none;">
		   <tbody>
			<tr>
			 <th width="160">商家预约抽成比例：</th>
			 <td><input type="text" class="input-text" name="appoint_percent" id="config_appoint_percent" value="{pigcms{$mer_pr.appoint_percent}" size="10" validate="number:true,max:100" tips="预约独立抽成比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
			<tr>
			 <th width="160">商家预约分佣比例：</th>
			 <td><input type="text" class="input-text" name="appoint_rate" id="config_appoint_rate" value="{pigcms{$mer_pr.appoint_rate}" size="10" validate="number:true,max:100" tips="预约独立分佣比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
		   </tbody>
		  </table>
		  <table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_store_pr" style="display: none;">
		   <tbody>
			<tr>
			 <th width="160">商家{pigcms{$config.cash_alias_name}抽成比例：</th>
			 <td><input type="text" class="input-text" name="store_percent" id="config_store_percent" value="{pigcms{$mer_pr.store_percent}" size="10" validate="number:true,max:100" tips="{pigcms{$config.cash_alias_name}抽成比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
		
			<tr>
			 <th width="160">商{pigcms{$config.cash_alias_name}店分佣比例：</th>
			 <td><input type="text" class="input-text" name="store_rate" id="config_store_rate" value="{pigcms{$mer_pr.store_rate}" size="10" validate="number:true,max:100" tips="到店独立分佣比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
			
		
			
		   </tbody>
		  </table>
		  
		   <table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_cash_pr" style="display: none;">
		   <tbody>
			<tr>
			 <th width="160">商家到店消费抽成比例：</th>
			 <td><input type="text" class="input-text" name="cash_percent" id="config_cash_percent" value="{pigcms{$mer_pr.cash_percent}" size="10" validate="number:true,max:100" tips="到店消费抽成比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
		
			<tr>
			 <th width="160">商家到店消费店分佣比例：</th>
			 <td><input type="text" class="input-text" name="cash_rate" id="config_cash_rate" value="{pigcms{$mer_pr.cash_rate}" size="10" validate="number:true,max:100" tips="到店消费独立分佣比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
			
		
		   </tbody>
		  </table>
		  
		  
		  
			
			
		  <table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_wxapp_pr" style="display: none;">
		   <tbody>
			<tr>
			 <th width="160">商家营销抽成比例：</th>
			 <td><input type="text" class="input-text" name="wxapp_percent" id="config_wxapp_percent" value="{pigcms{$mer_pr.wxapp_percent}" size="10" validate="number:true,max:100" tips="营销独立抽成比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
			<tr>
			 <th width="160">商家营销分佣比例：</th>
			 <td><input type="text" class="input-text" name="wxapp_rate" id="config_wxapp_rate" value="{pigcms{$mer_pr.wxapp_rate}" size="10" validate="number:true,max:100" tips="营销独立分佣比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
		   </tbody>
		  </table>
		  <table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_weidian_pr" style="display: none;">
		   <tbody>
			<tr>
			 <th width="160">商家微店抽成比例：</th>
			 <td><input type="text" class="input-text" name="weidian_percent" id="config_weidian_percent" value="{pigcms{$mer_pr.weidian_percent}" size="10" validate="number:true,max:100" tips="微店独立抽成比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
			<tr>
			 <th width="160">商家微店分佣比例：</th>
			 <td><input type="text" class="input-text" name="weidian_rate" id="config_weidian_rate" value="{pigcms{$mer_pr.weidian_rate}" size="10" validate="number:true,max:100" tips="微店独立分佣比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
		   </tbody>
		  </table>
		  <table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_activity_pr" style="display: none;">
		   <tbody>
			<tr>
			 <th width="160">商家平台活动抽成比例：</th>
			 <td><input type="text" class="input-text" name="activity_percent" id="config_activity_percent" value="{pigcms{$mer_pr.activity_percent}" size="10" validate="number:true,max:100" tips="平台独立活动抽成比例，平台活动包括一元夺宝，优惠券（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
			<tr>
			 <th width="160">商家平台活动分佣比例：</th>
			 <td><input type="text" class="input-text" name="activity_rate" id="config_activity_rate" value="{pigcms{$mer_pr.activity_rate}" size="10" validate="number:true,max:100" tips="平台独立活动分佣比例（按百分比，不要填写%，填-1 则跳过当前业务设置）" /></td>
			</tr>
		   </tbody>
		  </table> 
		<input type="hidden" name="mer_id" value="{pigcms{$mer_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			
		</table>
		<div class="btn ">
			<input  type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			
		</div>
	</form>
	<script>
		$(function(){
			$('#dosubmit').click(function(){
				$.post('{pigcms{:U('Merchant/edit_percent_rate')}', $('#myform').serialize(), function(data, textStatus, xhr) {
					if(data.status==1){
						window.top.msg(2,data.info,true,2);
					}else{
						window.top.msg(0,data.info,true,2);
					}
					
					window.top.art.dialog({id:'edit_percent_rate'}).close();
				});
				
			});
			
			
			$('.table_form:eq(0)').show();
			
			$('.tab_ul li a').click(function(){
				$(this).closest('li').addClass('active').siblings('li').removeClass('active');
				$($(this).attr('href')).show().siblings('.table_form').hide();
				return false;
			});
			$('#im_key').click(function(){
				window.top.msg(2,'正在请求中,请稍等...',true,100);
				$.get("{pigcms{:U('Config/im')}",function(data){
					if(data.error_code){
						window.top.msg(0,data.msg,true,3);
					}else{
						window.top.msg(1,data.msg,true,3);
					}
				},'json');
			});
			$('#live_service_key').click(function(){
				window.top.msg(2,'正在请求中,请稍等...',true,100);
				$.get("{pigcms{:U('Config/live_service')}",function(data){
					if(data.error_code){
						window.top.msg(0,data.msg,true,3);
					}else{
						window.top.msg(1,data.msg,true,3);
					}
				},'json');
			});
			
	
			
		});
	</script>
	<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
	<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
	<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
	<script type="text/javascript">
		KindEditor.ready(function(K){
			var site_url = "{pigcms{$config.site_url}";
			var editor = K.editor({
				allowFileManager : true
			});
			$('.config_upload_image_btn').click(function(){
				var upload_file_btn = $(this);
				editor.uploadJson = "{pigcms{:U('Config/ajax_upload_pic')}";
				editor.loadPlugin('image', function(){
					editor.plugin.imageDialog({
						showRemote : false,
						clickFn : function(url, title, width, height, border, align) {
							upload_file_btn.siblings('.input-image').val(site_url+url);
							editor.hideDialog();
						}
					});
				});
			});
			$('.config_upload_file_btn').click(function(){
				var upload_file_btn = $(this);
				editor.uploadJson = "{pigcms{:U('Config/ajax_upload_file')}&name="+upload_file_btn.siblings('.input-file').attr('name');
				editor.loadPlugin('insertfile', function(){
					editor.plugin.fileDialog({
						showRemote : false,
						clickFn : function(url, title, width, height, border, align) {
							upload_file_btn.siblings('.input-file').val(url);
							editor.hideDialog();
						}
					});
				});
			});
		});
	</script>
	
	<style>
		.table_form{border:1px solid #ddd;}
		.tab_ul{margin-top:10px;border-color:#C5D0DC;margin-bottom:0!important;margin-left:0;position:relative;top:1px;border-bottom:1px solid #ddd;padding-left:0;list-style:none;}
		.tab_ul>li{position:relative;display:block;float:left;margin-bottom:-1px;}
		.tab_ul>li>a {
			position: relative;
			display: block;
			padding: 10px 15px;
			margin-right: 2px;
			line-height: 1.42857143;
			border: 1px solid transparent;
			border-radius: 4px 4px 0 0;
			padding: 7px 12px 8px;
			min-width: 50px;
			text-align: center;
		}
		.tab_ul>li>a, .tab_ul>li>a:focus {
			border-radius: 0!important;
			border-color: #c5d0dc;
			background-color: #F9F9F9;
			color: #999;
			margin-right: -1px;
			line-height: 18px;
			position: relative;
		}
		.tab_ul>li>a:focus, .tab_ul>li>a:hover {
			text-decoration: none;
			background-color: #eee;
		}
		.tab_ul>li>a:hover {
			border-color: #eee #eee #ddd;
		}
		.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
			color: #555;
			background-color: #fff;
			border: 1px solid #ddd;
			border-bottom-color: transparent;
			cursor: default;
		}
		.tab_ul>li>a:hover {
			background-color: #FFF;
			color: #4c8fbd;
			border-color: #c5d0dc;
		}
		.tab_ul>li:first-child>a {
			margin-left: 0;
		}
		.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
			color: #576373;
			border-color: #c5d0dc #c5d0dc transparent;
			border-top: 2px solid #4c8fbd;
			background-color: #FFF;
			z-index: 1;
			line-height: 18px;
			margin-top: -1px;
			box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
		}
		.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
			color: #555;
			background-color: #fff;
			border: 1px solid #ddd;
			border-bottom-color: transparent;
			cursor: default;
		}
		.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
			color: #576373;
			border-color: #c5d0dc #c5d0dc transparent;
			border-top: 2px solid #4c8fbd;
			background-color: #FFF;
			z-index: 1;
			line-height: 18px;
			margin-top: -1px;
			box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
		}
		.tab_ul:before,.tab_ul:after{
			content: " ";
			display: table;
		}
		.tab_ul:after{
			clear: both;
		}
	</style>
<include file="Public:footer"/>
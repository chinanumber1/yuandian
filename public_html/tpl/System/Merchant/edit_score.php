<include file="Public:header"/>
	<form id="myform"  frame="true" refresh="true">
		 
		  <table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_mer_pr" style="display: table;">
		  <thead>
		    <tr>
			<th style="color:red">使用百分比请填写 % ，如2%</th>
			</tr>
		  </thead>
		   <tbody>
			
			<tr>
			 <th width="160">商家最大{pigcms{$config.score_name}使用数：</th>
			 <td>
				<span class="cb-enable">
						<label class="cb-enable  <php>if($mer_pr['merchant_score_max']>=0&&$mer_pr['merchant_score_max']!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if($mer_pr['merchant_score_max']>=0&&$mer_pr['merchant_score_max']!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if($mer_pr['merchant_score_max']<0||$mer_pr['merchant_score_max']==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if($mer_pr['merchant_score_max']<0||$mer_pr['merchant_score_max']==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					
			 <input type="text" class="input-text" name="merchant_score_max" id="config_merchant_score_max" value="{pigcms{$mer_pr.merchant_score_max}" size="10"  tips="商家最大{pigcms{$config.score_name}使用数（填写大于等于0的整数，填-1 则跳过当前业务设置,按上级平台的{pigcms{$config.score_name}最大使用数算）" <php>if($mer_pr['merchant_score_max']<0||$mer_pr['merchant_score_max']==""){</php>style="display:none"<php>}</php> /></td>
			</tr>
			
		
			<tr>
			 <th width="160">{pigcms{$config.group_alias_name}最大{pigcms{$config.score_name}使用数：</th>
			 <td>
				<span class="cb-enable">
						<label class="cb-enable  <php>if($mer_pr['merchant_group_score_max']>=0&&$mer_pr['merchant_group_score_max']!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if($mer_pr['merchant_group_score_max']>=0&&$mer_pr['merchant_group_score_max']!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if($mer_pr['merchant_group_score_max']<0||$mer_pr['merchant_group_score_max']==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if($mer_pr['merchant_group_score_max']<0||$mer_pr['merchant_group_score_max']==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
			 <input type="text" class="input-text" name="merchant_group_score_max" id="config_group_rate" value="{pigcms{$mer_pr.merchant_group_score_max}" size="10" tips="团购最大{pigcms{$config.score_name}使用数（填写大于等于0的整数，填-1 则跳过当前业务设置,按上级【商家最大{pigcms{$config.score_name}使用数】算）" <php>if($mer_pr['merchant_group_score_max']<0||$mer_pr['merchant_group_score_max']==""){</php>style="display:none"<php>}</php>/></td>
			</tr>
		
			<tr>
			 <th width="160">{pigcms{$config.shop_alias_name}最大{pigcms{$config.score_name}使用数：</th>
			 <td>
			 
				<span class="cb-enable">
						<label class="cb-enable  <php>if($mer_pr['merchant_shop_score_max']>=0&&$mer_pr['merchant_shop_score_max']!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if($mer_pr['merchant_shop_score_max']>=0&&$mer_pr['merchant_shop_score_max']!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if($mer_pr['merchant_shop_score_max']<0||$mer_pr['merchant_shop_score_max']==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if($mer_pr['merchant_shop_score_max']<0||$mer_pr['merchant_shop_score_max']==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
			 <input type="text" class="input-text" name="merchant_shop_score_max" id="config_shop_rate" value="{pigcms{$mer_pr.merchant_shop_score_max}" size="10"  tips="快店最大{pigcms{$config.score_name}使用数（填写大于等于0的整数，填-1 则跳过当前业务设置,按上级【商家最大{pigcms{$config.score_name}使用数】算）" <php>if($mer_pr['merchant_shop_score_max']<0||$mer_pr['merchant_shop_score_max']==""){</php>style="display:none"<php>}</php>/></td>
			</tr>
		
			<tr>
			 <th width="160">{pigcms{$config.meal_alias_name}最大{pigcms{$config.score_name}使用数：</th>
			 <td>
				<span class="cb-enable">
						<label class="cb-enable  <php>if($mer_pr['merchant_meal_score_max']>=0&&$mer_pr['merchant_meal_score_max']!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if($mer_pr['merchant_meal_score_max']>=0&&$mer_pr['merchant_meal_score_max']!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if($mer_pr['merchant_meal_score_max']<0||$mer_pr['merchant_meal_score_max']==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if($mer_pr['merchant_meal_score_max']<0||$mer_pr['merchant_meal_score_max']==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					
			 
			 <input type="text" class="input-text" name="merchant_meal_score_max" id="config_meal_rate" value="{pigcms{$mer_pr.merchant_meal_score_max}" size="10" tips="餐饮最大{pigcms{$config.score_name}使用数（填写大于等于0的整数，填-1 则跳过当前业务设置,按上级【商家最大{pigcms{$config.score_name}使用数】算）" 
			 <php>if($mer_pr['merchant_meal_score_max']<0||$mer_pr['merchant_meal_score_max']==""){</php>style="display:none"<php>}</php>/></td>
			</tr>
			<if condition="C('config.appoint_page_row')">

			<tr>
			 <th width="160">{pigcms{$config.appoint_alias_name}最大{pigcms{$config.score_name}使用数：</th>
			 <td>
				<span class="cb-enable">
						<label class="cb-enable  <php>if($mer_pr['merchant_appoint_score_max']>=0&&$mer_pr['merchant_appoint_score_max']!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if($mer_pr['merchant_appoint_score_max']>=0&&$mer_pr['merchant_appoint_score_max']!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if($mer_pr['merchant_appoint_score_max']<0||$mer_pr['merchant_appoint_score_max']==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if($mer_pr['merchant_appoint_score_max']<0||$mer_pr['merchant_appoint_score_max']==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			 
			 <input type="text" class="input-text" name="merchant_appoint_score_max" id="config_appoint_rate" value="{pigcms{$mer_pr.merchant_appoint_score_max}" size="10" tips="预约最大{pigcms{$config.score_name}使用数（填写大于等于0的整数，填-1 则跳过当前业务设置,按上级【商家最大{pigcms{$config.score_name}使用数】算）" <php>if($mer_pr['merchant_appoint_score_max']<0||$mer_pr['merchant_appoint_score_max']==""){</php>style="display:none"<php>}</php>/></td>
			</tr>
			</if>
			
		

			<tr>
			 <th width="160">{pigcms{$config.cash_alias_name}最大{pigcms{$config.score_name}使用数：</th>
			 <td>
				<span class="cb-enable">
						<label class="cb-enable  <php>if($mer_pr['merchant_store_score_max']>=0&&$mer_pr['merchant_store_score_max']!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if($mer_pr['merchant_store_score_max']>=0&&$mer_pr['merchant_store_score_max']!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if($mer_pr['merchant_store_score_max']<0||$mer_pr['merchant_store_score_max']==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if($mer_pr['merchant_store_score_max']<0||$mer_pr['merchant_store_score_max']==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			 
			 <input type="text" class="input-text" name="merchant_store_score_max" id="config_appoint_rate" value="{pigcms{$mer_pr.merchant_store_score_max}" size="10" tips="预约最大{pigcms{$config.score_name}使用数（填写大于等于0的整数，填-1 则跳过当前业务设置,按上级【商家最大{pigcms{$config.score_name}使用数】算）" <php>if($mer_pr['merchant_store_score_max']<0||$mer_pr['merchant_store_score_max']==""){</php>style="display:none"<php>}</php>/></td>
			</tr>
			
		   </tbody>
		  </table> 
		<input type="hidden" name="mer_id" value="{pigcms{$_GET['mer_id']}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			
		</table>
		<div class="btn ">
			<input  type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			
		</div>
	</form>
	<script>
		$(function(){
			$('#dosubmit').click(function(){
				$.post('{pigcms{:U('Merchant/edit_percent')}', $('#myform').serialize(), function(data, textStatus, xhr) {
					//console.log(data);
					if(data.status==1){
						window.top.msg(2,data.info,true,2);
					}else{
						window.top.msg(0,data.info,true,2);
					}
					window.top.art.dialog({id:'edit_score'}).close();
				});
				
			});
			
			$('.table_form:eq(0)').show();
			var data_rate_arr = [];
			var test = $('.input-text').each(function(index,val){
				var ids = $(val).attr('id');
				data_rate_arr[ids] = $(val).val()
				
			});
			
			
			$('.input-text').blur(function(){
				$('.input-text').each(function(index,val){
					var ids = $(val).attr('id');
					data_rate_arr[ids] = $(val).val()
					console.log(data_rate_arr)
				});
			})

			
			$('input:radio').click(function(){
				var percent = $(this).parents('td').find('input[type="text"]');
				var text_id = percent.attr('id');
				var	open_percent = data_rate_arr[text_id];
				if($(this).val()==1){
					if(open_percent<0){
						percent.val('');
					}else{
						percent.val(open_percent);
					}
					percent.show();
				}else{
					percent.hide();
					percent.val(-1);
					percent.hide();
				}
			});
			
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
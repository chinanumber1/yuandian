<include file="Public:header"/>
	<form id="myform" frame="true" refresh="true">
		 
		  <table cellpadding="0" cellspacing="0" class="table_form" width="100%" id="tab_mer_pr" style="display: table;">
		   <tbody>
		
			<tr>
				<th width="160">商家推广分佣比例：</th>
				<td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if($mer_pr['merchant_rate']>=0&&$mer_pr['merchant_rate']!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if($mer_pr['merchant_rate']>=0&&$mer_pr['merchant_rate']!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if($mer_pr['merchant_rate']<0||$mer_pr['merchant_rate']==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if($mer_pr['merchant_rate']<0||$mer_pr['merchant_rate']==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					<input type="text" class="input-text" name="merchant_rate" id="config_merchant_rate" value="{pigcms{$mer_pr.merchant_rate}" size="10" validate="number:true,max:100" tips="分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前业务设置，向上查找系统的相关业务的分佣比例）" <php>if($mer_pr['merchant_rate']<0||$mer_pr['merchant_rate']==""){</php>style="display:none"<php>}</php>/>
				</td>
			</tr>
			
		
			<tr>
			 <th width="160">团购商家推广分佣比例：</th>
			 <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if($mer_pr['group_rate']>=0&&$mer_pr['group_rate']!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_group_rate" value="1" <php>if($mer_pr['group_rate']>=0&&$mer_pr['group_rate']!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if($mer_pr['group_rate']<0||$mer_pr['group_rate']==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_group_rate" value="0" <php>if($mer_pr['group_rate']<0||$mer_pr['group_rate']==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" class="input-text" name="group_rate" id="config_group_rate" value="{pigcms{$mer_pr.group_rate}" size="10" validate="number:true,max:100" tips="分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前业务设置，向上查找商家推广分佣比例）" <php>if($mer_pr['group_rate']<0||$mer_pr['group_rate']==""){</php>style="display:none"<php>}</php>/>
			 </td>
			</tr>
		
			<tr>
			 <th width="160">快店商家推广分佣比例：</th>
			 <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if($mer_pr['shop_rate']>=0&&$mer_pr['shop_rate']!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_shop_rate" value="1" <php>if($mer_pr['shop_rate']>=0&&$mer_pr['shop_rate']!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if($mer_pr['shop_rate']<0||$mer_pr['shop_rate']==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_shop_rate" value="0" <php>if($mer_pr['shop_rate']<0||$mer_pr['shop_rate']==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" class="input-text" name="shop_rate" id="config_shop_rate" value="{pigcms{$mer_pr.shop_rate}" size="10" validate="number:true,max:100" tips="分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前业务设置，向上查找推广分佣比例）" <php>if($mer_pr['shop_rate']<0||$mer_pr['shop_rate']==""){</php>style="display:none"<php>}</php>/></td>
			</tr>
		
			<tr>
			 <th width="160">餐饮商家推广分佣比例：</th>
				
			 <td>
			 <span class="cb-enable">
					<label class="cb-enable  <php>if($mer_pr['meal_rate']>=0&&$mer_pr['meal_rate']!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_meal_rate" value="1" <php>if($mer_pr['meal_rate']>=0&&$mer_pr['meal_rate']!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if($mer_pr['meal_rate']<0||$mer_pr['meal_rate']==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_meal_rate" value="0" <php>if($mer_pr['meal_rate']<0||$mer_pr['meal_rate']==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			 <input type="text" class="input-text" name="meal_rate" id="config_meal_rate" value="{pigcms{$mer_pr.meal_rate}" size="10" validate="number:true,max:100" tips="分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前业务设置，向上查找推广分佣比例）" <php>if($mer_pr['meal_rate']<0||$mer_pr['meal_rate']==""){</php>style="display:none"<php>}</php>/></td>
			</tr>
		<if condition="C('config.appoint_page_row')">

			<tr>
				
			 <th width="160">预约商家推广分佣比例：</th>
			 <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if($mer_pr['appoint_rate']>=0&&$mer_pr['appoint_rate']!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_appoint_rate" value="1" <php>if($mer_pr['appoint_rate']>=0&&$mer_pr['appoint_rate']!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if($mer_pr['appoint_rate']<0||$mer_pr['appoint_rate']==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_appoint_rate" value="0" <php>if($mer_pr['appoint_rate']<0||$mer_pr['appoint_rate']==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" class="input-text" name="appoint_rate" id="config_appoint_rate" value="{pigcms{$mer_pr.appoint_rate}" size="10" validate="number:true,max:100" tips="分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前业务设置，向上查找推广分佣比例）" <php>if($mer_pr['appoint_rate']<0||$mer_pr['appoint_rate']==""){</php>style="display:none"<php>}</php>/></td>
			</tr>
		</if>
		<if condition="$config['is_cashier'] OR $config['pay_in_store']">

			<tr>
			 <th width="160">{pigcms{$config.cash_alias_name}商家推广分佣比例：</th>
			 <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if($mer_pr['store_rate']>=0&&$mer_pr['store_rate']!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_store_rate" value="1" <php>if($mer_pr['store_rate']>=0&&$mer_pr['store_rate']!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if($mer_pr['store_rate']<0||$mer_pr['store_rate']==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_store_rate" value="0" <php>if($mer_pr['store_rate']<0||$mer_pr['store_rate']==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

			 <input type="text" class="input-text" name="store_rate" id="config_store_rate" value="{pigcms{$mer_pr.store_rate}" size="10" validate="number:true,max:100" tips="分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前业务设置，向上查找推广分佣比例）" <php>if($mer_pr['store_rate']<0||$mer_pr['store_rate']==""){</php>style="display:none"<php>}</php>/></td>
			</tr>
		
			<tr>
			 <th width="160">到店消费商家推广分佣比例：</th>
			 <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if($mer_pr['cash_rate']>=0&&$mer_pr['cash_rate']!=''){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_cash_rate" value="1" <php>if($mer_pr['cash_rate']>=0&&$mer_pr['cash_rate']!=''){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if($mer_pr['cash_rate']<0||$mer_pr['cash_rate']==''){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_cash_rate" value="0" <php>if($mer_pr['cash_rate']<0||$mer_pr['cash_rate']==''){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			 <input type="text" class="input-text" name="cash_rate" id="config_cash_rate" value="{pigcms{$mer_pr.cash_rate}" size="10" validate="number:true,max:100" tips="分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前业务设置，向上查找推广分佣比例）" <php>if($mer_pr['cash_rate']<0||$mer_pr['cash_rate']==''){</php>style="display:none"<php>}</php>/></td>
			</tr>
			</if>
			<if condition="$config['wxapp_url']">
			<tr>
			 <th width="160">营销商家推广分佣比例：</th>
			 <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if($mer_pr['wxapp_rate']>=0&&$mer_pr['wxapp_rate']!=''){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_wxapp_rate" value="1" <php>if($mer_pr['wxapp_rate']>=0&&$mer_pr['wxapp_rate']!=''){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if($mer_pr['wxapp_rate']<0||$mer_pr['wxapp_rate']==''){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_wxapp_rate" value="0" <php>if($mer_pr['wxapp_rate']<0||$mer_pr['wxapp_rate']==''){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" class="input-text" name="wxapp_rate" id="config_wxapp_rate" value="{pigcms{$mer_pr.wxapp_rate}" size="10" validate="number:true,max:100" tips="分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前业务设置，向上查找推广分佣比例）" <php>if($mer_pr['wxapp_rate']<0||$mer_pr['wxapp_rate']==''){</php>style="display:none"<php>}</php>/></td>
			</tr>
		  </if>
			<if condition="$config['is_open_weidian']">

			<tr>
			 <th width="160">微店商家推广分佣比例：</th>
			 <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if($mer_pr['weidian_rate']>=0&&$mer_pr['weidian_rate']!=''){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_weidian_rate" value="1" <php>if($mer_pr['weidian_rate']>=0&&$mer_pr['weidian_rate']!=''){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if($mer_pr['weidian_rate']<0||$mer_pr['weidian_rate']==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_weidian_rate" value="0" <php>if($mer_pr['weidian_rate']<0||$mer_pr['weidian_rate']==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" class="input-text" name="weidian_rate" id="config_weidian_rate" value="{pigcms{$mer_pr.weidian_rate}" size="10" validate="number:true,max:100" tips="分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前业务设置，向上查找推广分佣比例）" <php>if($mer_pr['weidian_rate']<0||$mer_pr['weidian_rate']==""){</php>style="display:none"<php>}</php>/></td>
			</tr>
		  </if>
	
			<tr>
			 <th width="160">平台活动商家推广分佣比例：</th>
			 <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if($mer_pr['activity_rate']>=0&&$mer_pr['activity_rate']!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_activity_rate" value="1" <php>if($mer_pr['activity_rate']>=0&&$mer_pr['activity_rate']!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if($mer_pr['activity_rate']<0||$mer_pr['activity_rate']==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_activity_rate" value="0" <php>if($mer_pr['activity_rate']<0||$mer_pr['activity_rate']==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type="text" class="input-text" name="activity_rate" id="config_activity_rate" value="{pigcms{$mer_pr.activity_rate}" size="10" validate="number:true,max:100" tips="分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前业务设置，向上查找推广分佣比例）"  <php>if($mer_pr['activity_rate']<0||$mer_pr['activity_rate']==""){</php>style="display:none"<php>}</php>/></td>
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
					if(data.status==1){
						window.top.msg(2,data.info,true,2);
					}else{
						window.top.msg(0,data.info,true,2);
					}
					window.top.art.dialog({id:'edit_rate'}).close();
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
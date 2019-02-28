<include file="Public:header"/>
<style>
	.control{
		width: 10%;
	}
</style>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Percent/rate')}" class="on">商家推广分佣设置</a>
				</ul>
			</div>
		  <form id="myform" method="post" action="/admin.php?g=System&c=Config&a=amend" refresh="true"> 
		   <table cellpadding="0" cellspacing="0" class="table_form" width="100%"  id="tab_0">
			<tbody>
			 <tr>
			  <th width="160">平台总体商家推广分佣比例：</th>
				<td >
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.platform_get_merchant_rate')>=0&&C('config.platform_get_merchant_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.platform_get_merchant_rate')>=0&&C('config.platform_get_merchant_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.platform_get_merchant_rate')<0||C('config.platform_get_merchant_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.platform_get_merchant_rate')<0||C('config.platform_get_merchant_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="text" class="input-text" name="platform_get_merchant_rate" id="config_platform_get_merchant_rate" value="{pigcms{:C('config.platform_get_merchant_rate')}" size="10" validate="number:true,max:100" tips="商家推广，让用户成为平台用户后，平台按该用户在别的商家处购买商品时使用平台余额和在线支付的金额，按比例支付给商家佣金（按百分比，不要填写%）,填写0表示不拿抽成" <php>if(C('config.platform_get_merchant_rate')<0||C('config.platform_get_merchant_rate')==""){</php>style="display:none"<php>}</php>/>
				</td>
				
			 </tr>
			 <tr>
			  <th width="160">团购商家推广分佣比例：</th>
			  <td>
			
				<span class="cb-enable">
					<label class="cb-enable  <php>if(C('config.group_rate')>=0&&C('config.group_rate')!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_group_rate" value="1" <php>if(C('config.group_rate')>=0&&C('config.group_rate')!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if(C('config.group_rate')<0||C('config.group_rate')==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_group_rate" value="0" <php>if(C('config.group_rate')<0||C('config.group_rate')==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
			 
				<input type="text" class="input-text" name="group_rate" id="config_group_rate" value="{pigcms{:C('config.group_rate')}" size="10" validate="number:true,max:100" tips="商家推广分佣比例，点击设置后，请填写百分比，不要填写%，选择跳过则使用平台总设定值" <php>if(C('config.group_rate')<0||C('config.group_rate')==""){</php>style="display:none"<php>}</php>/>
				</td>
			 </tr>
			 <tr>
			  <th width="160">快店商家推广佣金比例：</th>
			  <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if(C('config.shop_rate')>=0&&C('config.shop_rate')!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_group_rate" value="1" <php>if(C('config.shop_rate')>=0&&C('config.shop_rate')!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if(C('config.shop_rate')<0||C('config.shop_rate')==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_group_rate" value="0" <php>if(C('config.shop_rate')<0||C('config.shop_rate')==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
			  <input type="text" class="input-text" name="shop_rate" id="config_shop_rate" value="{pigcms{:C('config.shop_rate')}" size="10" validate="number:true,max:100" tips="商家推广分佣比例，点击设置后，请填写百分比，不要填写%，选择跳过则使用平台总设定值" <php>if(C('config.shop_rate')<0||C('config.shop_rate')==""){</php>style="display:none"<php>}</php>/></td>
			 </tr>
			 <tr>
			  <th width="160">餐饮商家推广分佣比例：</th>
			  <td>
			  <span class="cb-enable">
					<label class="cb-enable  <php>if(C('config.meal_rate')>=0&&C('config.meal_rate')!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_group_rate" value="1" <php>if(C('config.meal_rate')>=0&&C('config.meal_rate')!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if(C('config.meal_rate')<0||C('config.meal_rate')==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_group_rate" value="0" <php>if(C('config.meal_rate')<0||C('config.meal_rate')==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
			  <input type="text" class="input-text" name="meal_rate" id="config_meal_rate" value="{pigcms{:C('config.meal_rate')}" size="10" validate="number:true,max:100" tips="平台餐饮推广分佣比例（按百分比，不要填写%），-1代表跳过此值，使用平台总设定值" <php>if(C('config.meal_rate')<0||C('config.meal_rate')==""){</php>style="display:none"<php>}</php>/></td>
			 </tr>
			 <if condition="C('config.appoint_page_row')">
			 <tr>
			  <th width="160">预约商家推广分佣比例：</th>
			  <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if(C('config.appoint_rate')>=0&&C('config.appoint_rate')!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_group_rate" value="1" <php>if(C('config.appoint_rate')>=0&&C('config.appoint_rate')!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if(C('config.appoint_rate')<0||C('config.appoint_rate')==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_group_rate" value="0" <php>if(C('config.appoint_rate')<0||C('config.appoint_rate')==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
				
				<input type="text" class="input-text" name="appoint_rate" id="config_appoint_rate" value="{pigcms{:C('config.appoint_rate')}" size="10" validate="number:true,max:100" tips="平台预约推广分佣比例（按百分比，不要填写%），-1代表跳过此值，使用平台总设定值" <php>if(C('config.appoint_rate')<0||C('config.appoint_rate')==""){</php>style="display:none"<php>}</php>/></td>
			 </tr>
			 </if>
			 <if condition="$config['is_cashier'] OR $config['pay_in_store']">
			 <tr>
			  <th width="160">到店消费商家推广分佣比例：</th>
			  <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if(C('config.cash_rate')>=0&&C('config.cash_rate')!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_group_rate" value="1" <php>if(C('config.cash_rate')>=0&&C('config.cash_rate')!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if(C('config.cash_rate')<0||C('config.cash_rate')==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_group_rate" value="0" <php>if(C('config.cash_rate')<0||C('config.cash_rate')==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
				
			  <input type="text" class="input-text" name="cash_rate" id="config_cash_rate" value="{pigcms{:C('config.cash_rate')}" size="10" validate="number:true,max:100" tips="商家推广分佣比例（按百分比，不要填写%），-1代表跳过此值，使用平台总设定值" <php>if(C('config.cash_rate')<0||C('config.cash_rate')==""){</php>style="display:none"<php>}</php>/></td>
			 </tr>
			
			 <tr>
			  <th width="160">{pigcms{$config.cash_alias_name}商家推广分佣比例：</th>
			  <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if(C('config.store_rate')>=0&&C('config.store_rate')!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_group_rate" value="1" <php>if(C('config.store_rate')>=0&&C('config.store_rate')!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if(C('config.store_rate')<0||C('config.store_rate')==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_group_rate" value="0" <php>if(C('config.store_rate')<0||C('config.store_rate')==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
				<input type="text" class="input-text" name="store_rate" id="config_store_rate" value="{pigcms{:C('config.store_rate')}" size="10" validate="number:true,max:100" tips="商家到店推广分佣比例（按百分比，不要填写%），-1代表跳过此值，使用平台总设定值" <php>if(C('config.store_rate')<0||C('config.store_rate')==""){</php>style="display:none"<php>}</php>/></td>
			 </tr>
			  </if>
			  <if condition="$config['wxapp_url']">
			 <tr>
			  <th width="160">营销商家推广分佣比例：</th>
			  <td>
			  <span class="cb-enable">
					<label class="cb-enable  <php>if(C('config.wxapp_rate')>=0&&C('config.wxapp_rate')!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_group_rate" value="1" <php>if(C('config.wxapp_rate')>=0&&C('config.wxapp_rate')!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if(C('config.wxapp_rate')<0||C('config.wxapp_rate')==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_group_rate" value="0" <php>if(C('config.wxapp_rate')<0||C('config.wxapp_rate')==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
				
				
			  <input type="text" class="input-text" name="wxapp_rate" id="config_wxapp_rate" value="{pigcms{:C('config.wxapp_rate')}" size="10" validate="number:true,max:100" tips="平台营销推广分佣比例（按百分比，不要填写%），-1代表跳过此值，使用平台总设定值"  <php>if(C('config.wxapp_rate')<0||C('config.wxapp_rate')==""){</php>style="display:none"<php>}</php>/></td>
			 </tr>
			 </if>
			 <if condition="$config['is_open_weidian']">
			 <tr>
			  <th width="160">微店商家推广分佣比例：</th>
			  <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if(C('config.weidian_rate')>=0&&C('config.weidian_rate')!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="open_group_rate" value="1" <php>if(C('config.weidian_rate')>=0&&C('config.weidian_rate')!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if(C('config.weidian_rate')<0||C('config.weidian_rate')==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="open_group_rate" value="0" <php>if(C('config.weidian_rate')<0||C('config.weidian_rate')==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
				
			  <input type="text" class="input-text" name="weidian_rate" id="config_weidian_rate" value="{pigcms{:C('config.weidian_rate')}" size="10" validate="number:true,max:100" tips="商家推广分佣比例（按百分比，不要填写%），-1代表跳过此值，使用平台总设定值" <php>if(C('config.weidian_rate')<0||C('config.weidian_rate')==""){</php>style="display:none"<php>}</php>/></td>
			 </tr>
			 </if>
			 <tr>
			  <th width="160">平台活动商家推广分佣比例：</th>
			  <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if(C('config.activity_rate')>=0&&C('config.activity_rate')!=""){</php>selected<php>}</php>">
						<span>设置</span>
						<input type="radio" name="activity_rate" value="1" <php>if(C('config.activity_rate')>=0&&C('config.activity_rate')!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if(C('config.activity_rate')<0||C('config.activity_rate')==""){</php>selected<php>}</php>">
						<span>跳过</span>
						<input type="radio" name="activity_rate" value="0" <php>if(C('config.activity_rate')<0||C('config.activity_rate')==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
				
				
			  <input type="text" class="input-text" name="activity_rate" id="config_activity_rate" value="{pigcms{:C('config.activity_rate')}" size="10" validate="number:true,max:100" tips="商家推广分佣比例（按百分比，不要填写%），-1代表跳过此值，使用平台总设定值" <php>if(C('config.activity_rate')<0||C('config.activity_rate')==""){</php>style="display:none"<php>}</php>/></td>
			 </tr>
			 
			 
			 <!--tr>
			  <th width="160">商家分佣劈腿型/永久型：</th>
			  <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if(C('config.merchant_replace_money')>=0&&C('config.merchant_replace_money')!=""){</php>selected<php>}</php>">
						<span>劈腿型</span>
						<input type="radio" name="merchant_replace_money" value="1" <php>if(C('config.merchant_replace_money')>=0&&C('config.merchant_replace_money')!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if(C('config.merchant_replace_money')<0||C('config.merchant_replace_money')==""){</php>selected<php>}</php>">
						<span>永久型</span>
						<input type="radio" name="merchant_replace_money" value="0" <php>if(C('config.merchant_replace_money')<0||C('config.merchant_replace_money')==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
				
				
			  <input type="text" class="input-text" name="merchant_replace_money" id="config_merchant_replace_money" value="{pigcms{:C('config.merchant_replace_money')}" size="10" validate="number:true,max:100" tips="0 不替换 1 消费满一元就替换" <php>if(C('config.merchant_replace_money')<0||C('config.merchant_replace_money')==""){</php>style="display:none"<php>}</php>/></td>
			 </tr>
			 
			  <tr>
			  <th width="160">用户在推广自己的商家消费，商家是否可获得：</th>
			  <td>
				<span class="cb-enable">
					<label class="cb-enable  <php>if(C('config.spread_money_get_type')>0&&C('config.spread_money_get_type')!=""){</php>selected<php>}</php>">
						<span>获得</span>
						<input type="radio" name="spread_money_get_type" value="1" <php>if(C('config.spread_money_get_type')>0&&C('config.spread_money_get_type')!=""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>			     
				<span class="cb-disable">
					<label class="cb-disable  <php>if(C('config.spread_money_get_type')<=0||C('config.spread_money_get_type')==""){</php>selected<php>}</php>">
						<span>不获得</span>
						<input type="radio" name="spread_money_get_type" value="0" <php>if(C('config.spread_money_get_type')<=0||C('config.spread_money_get_type')==""){</php>checked="checked"<php>}</php>/>
					</label>
				</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			 </tr-->
			 
		
			</tbody>
		   </table> 
		   <div class="btn" style="margin-top:20px;"> 
			<input type="submit" name="dosubmit" value="提交" class="button" /> 
			
		   </div> 
		  </form>

		</div>
		<script>
			$(function(){
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
				
			})
			function add(){
				var item = $('.plus:last');
				if($('.plus').length<=1&&$('.plus').css('display')=='none'){
					$('.plus').show();
				}else{
					var newitem = $(item).clone(true);
					var No = parseInt(item.find(".sort").html())+1;
					$(item).after(newitem);
					newitem.find('input').attr('value','');
					newitem.find(".sort").html(No);
				}
				// newitem.find('input[name="url[]"]').attr('id','url'+No);
			}
			
			function del(obj){
				if($('.plus').length<=1){
					$('.plus').hide();
				}else{
					$(obj).parents('.plus').remove();
					$.each($('.plus'), function(index, val) {
						var No =index+1;
						$(val).find('.sort').html(No);
						$(val).find('input[name="url[]"]').attr('id','url'+No);
						
					});
				}
			}
		</script>
		<style>
			.table_form{border:1px solid #ddd;}
			.tab_ul{margin-top:20px;border-color:#C5D0DC;margin-bottom:0!important;margin-left:0;position:relative;top:1px;border-bottom:1px solid #ddd;padding-left:0;list-style:none;}
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
				min-width: 100px;
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
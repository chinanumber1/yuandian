<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Percent/user_rate')}" class="on">用户推广分佣设置</a>
					
				</ul>
			</div>
			<form id="myform" method="post" action="/admin.php?g=System&c=Config&a=amend" refresh="true"> 
			   <ul class="tab_ul">
				<li class="active"><a data-toggle="tab" href="#tab_system_pr">平台</a></li>
				<li><a data-toggle="tab" href="#tab_group_pr">{pigcms{$config.group_alias_name}</a></li>
				<li><a data-toggle="tab" href="#tab_shop_pr">{pigcms{$config.shop_alias_name}</a></li>
				<li><a data-toggle="tab" href="#tab_meal_pr">{pigcms{$config.meal_alias_name}</a></li>
				<if condition="$config['is_cashier'] OR $config['pay_in_store']"><li><a data-toggle="tab" href="#tab_store_pr">{pigcms{$config.cash_alias_name}</a></li>
				<li><a data-toggle="tab" href="#tab_cash_pr">到店消费</a></li></if>
				
				 <if condition="$config['open_sub_card'] ">
			   <li><a data-toggle="tab" href="#tab_sub_card_pr">免单套餐</a></li>
			   <li><a data-toggle="tab" href="#tab_yuedan_pr">约单</a></li>
			 
			   </if>
			   </ul> 
			   <table cellpadding="0" cellspacing="0" class="table_form" width="100%"  id="tab_system_pr">
				<tbody>
				 <tr>
				  <th width="160">直接推广用户分享<if condition="$config.open_extra_price eq 1">{pigcms{$config.score_name}<else />佣金</if>比例：</th>
				  <td>
					
				  
					<input type="text" class="input-text" name="user_spread_rate" id="$config_user_spread_rate" value="{pigcms{:C('config.user_spread_rate')}" size="10" validate="required:true,number:true,range:[0,100]" tips="用户分享链接购买商品获得<if condition="$config.open_extra_price eq 1">{pigcms{$config.score_name}<else />佣金</if>比例（百分比 0-100）微信中分享有效！"  ></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户的上级分享<if condition="$config.open_extra_price eq 1">{pigcms{$config.score_name}<else />佣金</if>比例：</th>
				  <td>
					
				  
				  <input type="text" class="input-text" name="user_first_spread_rate" id="$config_user_first_spread_rate" value="{pigcms{:C('config.user_first_spread_rate')}" size="10" validate="required:true,number:true,range:[0,100]" tips="直接推广用户的上级分享链接购买商品获得<if condition="$config.open_extra_price eq 1">{pigcms{$config.score_name}<else />佣金</if>比例（百分比 0-100）微信中分享有效！如果此值+用户分享的<if condition="$config.open_extra_price eq 1">{pigcms{$config.score_name}<else />佣金</if>值和大于100，则取100减去用户分享的<if condition="$config.open_extra_price eq 1">{pigcms{$config.score_name}<else />佣金</if>值" ></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户的上上级分享<if condition="$config.open_extra_price eq 1">{pigcms{$config.score_name}<else />佣金</if>比例：</th>
				  <td>
					
					
				  
				  <input type="text" class="input-text" name="user_second_spread_rate" id="$config_user_second_spread_rate" value="{pigcms{:C('config.user_second_spread_rate')}" size="10" validate="required:true,number:true,range:[0,100]" tips="直接推广用户的上上级分享链接购买商品获得<if condition="$config.open_extra_price eq 1">{pigcms{$config.score_name}<else />佣金</if>比例（百分比 0-100）微信中分享有效！如果此值+用户分享的<if condition="$config.open_extra_price eq 1">{pigcms{$config.score_name}<else />佣金</if>值和大于100，则取100减去用户分享的<if condition="$config.open_extra_price eq 1">{pigcms{$config.score_name}<else />佣金</if>值" /></td>
				 </tr>
				</tbody>
			   </table>
			   <!--团购-->
			   <table cellpadding="0" cellspacing="0" class="table_form" width="100%" style="display:none;" id="tab_group_pr">
				<tbody>
				 <tr>
				  <th width="160">直接推广用户分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.group_first_rate')>=0&&C('config.group_first_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.group_first_rate')>=0&&C('config.group_first_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.group_first_rate')<0||C('config.group_first_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.group_first_rate')<0||C('config.group_first_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
			
				  <input type="text" class="input-text" name="group_first_rate" id="$config_group_first_rate" value="{pigcms{:C('config.group_first_rate')}" size="10" validate="number:true,max:100" tips="平台团购用户推广分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.group_first_rate')<0||C('config.group_first_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户上级分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.group_second_rate')>=0&&C('config.group_second_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.group_second_rate')>=0&&C('config.group_second_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.group_second_rate')<0||C('config.group_second_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.group_second_rate')<0||C('config.group_second_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					
					
				  <input type="text" class="input-text" name="group_second_rate" id="$config_group_second_rate" value="{pigcms{:C('config.group_second_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户上级分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.group_second_rate')<0||C('config.group_second_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户上上级分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.group_third_rate')>=0&&C('config.group_third_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.group_third_rate')>=0&&C('config.group_third_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.group_third_rate')<0||C('config.group_third_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.group_third_rate')<0||C('config.group_third_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					
				  <input type="text" class="input-text" name="group_third_rate" id="$config_group_third_rate" value="{pigcms{:C('config.group_third_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户上上级分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.group_third_rate')<0||C('config.group_third_rate')==""){</php>style="display:none"<php>}</php> /></td>
				 </tr>
				</tbody>
				
				 <!--快店-->
			   </table>
			   <table cellpadding="0" cellspacing="0" class="table_form" width="100%" style="display:none;" id="tab_shop_pr">
				<tbody>
				 <tr>
				  <th width="160">直接推广用户分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.shop_first_rate')>=0&&C('config.shop_first_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.shop_first_rate')>=0&&C('config.shop_first_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.shop_first_rate')<0||C('config.shop_first_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.shop_first_rate')<0||C('config.shop_first_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
				
					
				  <input type="text" class="input-text" name="shop_first_rate" id="$config_shop_first_rate" value="{pigcms{:C('config.shop_first_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.shop_first_rate')<0||C('config.shop_first_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户上级分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.shop_second_rate')>=0&&C('config.shop_second_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.shop_second_rate')>=0&&C('config.shop_second_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.shop_second_rate')<0||C('config.shop_second_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.shop_second_rate')<0||C('config.shop_second_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
				
					
				  <input type="text" class="input-text" name="shop_second_rate" id="$config_shop_second_rate" value="{pigcms{:C('config.shop_second_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户上级分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.shop_second_rate')<0||C('config.shop_second_rate')==""){</php>style="display:none"<php>}</php> /></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户上上级分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.shop_third_rate')>=0&&C('config.shop_third_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.shop_third_rate')>=0&&C('config.shop_third_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.shop_third_rate')<0||C('config.shop_third_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.shop_third_rate')<0||C('config.shop_third_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
					
				  <input type="text" class="input-text" name="shop_third_rate" id="$config_shop_third_rate" value="{pigcms{:C('config.shop_third_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户上上级分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.shop_third_rate')<0||C('config.shop_third_rate')==""){</php>style="display:none"<php>}</php> /></td>
				 </tr>
				</tbody>
			   </table>
			    <!--餐饮买单-->
				
				<table cellpadding="0" cellspacing="0" class="table_form" width="100%" style="display:none;" id="tab_meal_pr">
				<tbody>
				 <tr>
				  <th width="160">直接推广用户分佣比例：</th>
				  <td>
				  <span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.meal_first_rate')>=0&&C('config.meal_first_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.meal_first_rate')>=0&&C('config.meal_first_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.meal_first_rate')<0||C('config.meal_first_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.meal_first_rate')<0||C('config.meal_first_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
				
				  <input type="text" class="input-text" name="meal_first_rate" id="$config_meal_first_rate" value="{pigcms{:C('config.meal_first_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.meal_first_rate')<0||C('config.meal_first_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户上级分佣比例：</th>
				  <td>	
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.meal_second_rate')>=0&&C('config.meal_second_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.meal_second_rate')>=0&&C('config.meal_second_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.meal_second_rate')<0||C('config.meal_second_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.meal_second_rate')<0||C('config.meal_second_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
					
					
				  <input type="text" class="input-text" name="meal_second_rate" id="$config_meal_second_rate" value="{pigcms{:C('config.meal_second_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户上级分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.meal_second_rate')<0||C('config.meal_second_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户上上级分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.meal_third_rate')>=0&&C('config.meal_third_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.meal_third_rate')>=0&&C('config.meal_third_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.meal_third_rate')<0||C('config.meal_third_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.meal_third_rate')<0||C('config.meal_third_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
				  <input type="text" class="input-text" name="meal_third_rate" id="$config_meal_third_rate" value="{pigcms{:C('config.meal_third_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户上上级分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.meal_third_rate')<0||C('config.meal_third_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				</tbody>
			   </table>
			    <!--{pigcms{$config.cash_alias_name}-->
			   <table cellpadding="0" cellspacing="0" class="table_form" width="100%" style="display:none;" id="tab_store_pr">
				<tbody>
				 <tr >
				  <th width="160">直接推广用户分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.store_first_rate')>=0&&C('config.store_first_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.store_first_rate')>=0&&C('config.store_first_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.store_first_rate')<0||C('config.store_first_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.store_first_rate')<0||C('config.store_first_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					
				  <input type="text" class="input-text" name="store_first_rate" id="$config_store_first_rate" value="{pigcms{:C('config.store_first_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.store_first_rate')<0||C('config.store_first_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户上级分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.store_second_rate')>=0&&C('config.store_second_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.store_second_rate')>=0&&C('config.store_second_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.store_second_rate')<0||C('config.store_second_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.store_second_rate')<0||C('config.store_second_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
				
					
				  <input type="text" class="input-text" name="store_second_rate" id="$config_store_second_rate" value="{pigcms{:C('config.store_second_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户上级分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.store_second_rate')<0||C('config.store_second_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户上上级分佣比例：</th>
				  <td>
				  <span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.store_third_rate')>=0&&C('config.store_third_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.store_third_rate')>=0&&C('config.store_third_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.store_third_rate')<0||C('config.store_third_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.store_third_rate')<0||C('config.store_third_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
					
				  
				  <input type="text" class="input-text" name="store_third_rate" id="$config_store_third_rate" value="{pigcms{:C('config.store_third_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户上上级分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.store_third_rate')<0||C('config.store_third_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				</tbody>
				
			   </table>
				 <!--到店消费-->
			   <table cellpadding="0" cellspacing="0" class="table_form" width="100%" style="display:none;" id="tab_cash_pr">
				<tbody>
				 <tr>
				  <th width="160">直接推广用户分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.cash_first_rate')>=0&&C('config.cash_first_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.cash_first_rate')>=0&&C('config.cash_first_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.cash_first_rate')<0||C('config.cash_first_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.cash_first_rate')<0||C('config.cash_first_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
					
				  <input type="text" class="input-text" name="cash_first_rate" id="$config_cash_first_rate" value="{pigcms{:C('config.cash_first_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.cash_first_rate')<0||C('config.cash_first_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户上级分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.cash_second_rate')>=0&&C('config.cash_second_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.cash_second_rate')>=0&&C('config.cash_second_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.cash_second_rate')<0||C('config.cash_second_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.cash_second_rate')<0||C('config.cash_second_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
					
				  <input type="text" class="input-text" name="cash_second_rate" id="$config_cash_second_rate" value="{pigcms{:C('config.cash_second_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户上级分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.cash_second_rate')<0||C('config.cash_second_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户上上级分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.cash_third_rate')>=0&&C('config.cash_third_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.cash_third_rate')>=0&&C('config.cash_third_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.cash_third_rate')<0||C('config.cash_third_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.cash_third_rate')<0||C('config.cash_third_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
				
				  <input type="text" class="input-text" name="cash_third_rate" id="$config_cash_third_rate" value="{pigcms{:C('config.cash_third_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户上上级分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.cash_third_rate')<0||C('config.cash_third_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				</tbody>
			   </table> 
			   
			   	 <!--免单套餐消费-->
			   <table cellpadding="0" cellspacing="0" class="table_form" width="100%" style="display:none;" id="tab_sub_card_pr">
				<tbody>
				 <tr>
				  <th width="160">直接推广用户分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.sub_card_first_rate')>=0&&C('config.sub_card_first_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.sub_card_first_rate')>=0&&C('config.sub_card_first_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.sub_card_first_rate')<0||C('config.sub_card_first_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.sub_card_first_rate')<0||C('config.sub_card_first_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
					
				  <input type="text" class="input-text" name="sub_card_first_rate" id="$config_sub_card_first_rate" value="{pigcms{:C('config.sub_card_first_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.sub_card_first_rate')<0||C('config.sub_card_first_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户上级分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.sub_card_second_rate')>=0&&C('config.sub_card_second_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.sub_card_second_rate')>=0&&C('config.sub_card_second_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.sub_card_second_rate')<0||C('config.sub_card_second_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.sub_card_second_rate')<0||C('config.sub_card_second_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
					
				  <input type="text" class="input-text" name="sub_card_second_rate" id="$config_sub_card_second_rate" value="{pigcms{:C('config.sub_card_second_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户上级分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.sub_card_second_rate')<0||C('config.sub_card_second_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户上上级分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.sub_card_third_rate')>=0&&C('config.sub_card_third_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.sub_card_third_rate')>=0&&C('config.sub_card_third_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.sub_card_third_rate')<0||C('config.sub_card_third_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.sub_card_third_rate')<0||C('config.sub_card_third_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
				
				  <input type="text" class="input-text" name="sub_card_third_rate" id="$config_sub_card_third_rate" value="{pigcms{:C('config.sub_card_third_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户上上级分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.sub_card_third_rate')<0||C('config.sub_card_third_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				</tbody>
			   </table> 
			   
			   <!--约单-->
			   
			    <table cellpadding="0" cellspacing="0" class="table_form" width="100%" style="display:none;" id="tab_yuedan_pr">
				<tbody>
				 <tr>
				  <th width="160">直接推广用户分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.yuedan_first_rate')>=0&&C('config.yuedan_first_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.yuedan_first_rate')>=0&&C('config.yuedan_first_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.yuedan_first_rate')<0||C('config.yuedan_first_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.yuedan_first_rate')<0||C('config.yuedan_first_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
					
				  <input type="text" class="input-text" name="yuedan_first_rate" id="$config_sub_card_first_rate" value="{pigcms{:C('config.yuedan_first_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.yuedan_first_rate')<0||C('config.yuedan_first_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户上级分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.yuedan_second_rate')>=0&&C('config.yuedan_second_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.yuedan_second_rate')>=0&&C('config.yuedan_second_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.yuedan_second_rate')<0||C('config.yuedan_second_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.yuedan_second_rate')<0||C('config.yuedan_second_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
					
				  <input type="text" class="input-text" name="yuedan_second_rate" id="$config_sub_card_second_rate" value="{pigcms{:C('config.yuedan_second_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户上级分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.yuedan_second_rate')<0||C('config.yuedan_second_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr <if condition="$config.open_extra_price eq 1">style="display:none"</if>>
				  <th width="160">直接推广用户上上级分佣比例：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.yuedan_third_rate')>=0&&C('config.yuedan_third_rate')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.yuedan_third_rate')>=0&&C('config.yuedan_third_rate')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.yuedan_third_rate')<0||C('config.yuedan_third_rate')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.yuedan_third_rate')<0||C('config.yuedan_third_rate')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
				
				  <input type="text" class="input-text" name="yuedan_third_rate" id="$config_sub_card_third_rate" value="{pigcms{:C('config.yuedan_third_rate')}" size="10" validate="number:true,max:100" tips="直接推广用户上上级分佣比例（点击设置后，请填写百分比，不要填写%，选择跳过则跳过当前设置，向上查找平台的用户分佣比例）" <php>if(C('config.yuedan_third_rate')<0||C('config.yuedan_third_rate')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
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
		
			$('.tab_ul li a').click(function(){
					$(this).closest('li').addClass('active').siblings('li').removeClass('active');
					$($(this).attr('href')).show().siblings('.table_form').hide();
					return false;
				});

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
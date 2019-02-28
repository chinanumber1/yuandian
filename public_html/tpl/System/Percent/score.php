<include file="Public:header"/>
		
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Percent/score')}" class="on">积分设置</a>
				
				</ul>
			</div>
			 <form id="myform" method="post" action="/admin.php?g=System&c=Config&a=amend" refresh="true"> 
			   <table cellpadding="0" cellspacing="0" class="table_form" width="100%"  id="tab_user_score">
				<tbody>
				<tr>
				  <th width="160">积分别名</th>
				  <td><input type="text" class="input-text" name="score_name" id="config_score_name" value="{pigcms{$config.score_name}" size="10" validate="required:true" tips="积分别名" /></td>
				 </tr>
				 <!--<tr>
				  <th width="160">消费1元获得积分：</th>
				  <td><input type="text" class="input-text" name="user_score_get" id="config_user_score_get" value="{pigcms{:C('config.user_score_get')}" size="10" validate="required:true,number:true,min:0" tips="消费1元获得的积分" /></td>
				 </tr>-->
				 <if condition="isset($config['user_own_pay_get_score'])">
				 <tr>
				  <th width="160">自有支付获得积分</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable <php>if(C('config.user_own_pay_get_score')==1){</php>selected<php>}</php>"><span>开启</span>
						<input type="radio" name="user_own_pay_get_score" value="1" <php>if(C('config.user_own_pay_get_score')==1){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<span class="cb-disable">
						<label class="cb-disable <php>if(C('config.user_own_pay_get_score')==0){</php>selected<php>}</php>"><span>关闭</span>
						<input type="radio" name="user_own_pay_get_score" value="0" <php>if(C('config.user_own_pay_get_score')==0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<em tips="开启后，自有支付也可以获得平台积分，包括子商户支付" class="notice_tips"></em></td>
				 </tr>
				 </if>
				 <tr>
				  <th width="160">开启按平台抽成送用户积分：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable <php>if(C('config.add_score_by_percent')==1){</php>selected<php>}</php>"><span>开启</span>
						<input type="radio" name="add_score_by_percent" value="1" <php>if(C('config.add_score_by_percent')==1){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<span class="cb-disable">
						<label class="cb-disable <php>if(C('config.add_score_by_percent')==0){</php>selected<php>}</php>"><span>关闭</span>
						<input type="radio" name="add_score_by_percent" value="0" <php>if(C('config.add_score_by_percent')==0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<em tips="平台按对商家的抽成给用户增加积分，只取整，不涉及抽成的增加积分项不变" class="notice_tips"></em></td>
				 </tr>
				 
				  <tr>
				  <th width="160">消费1元获得积分：</th>
				  <td>
				  
				  <span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.open_score_get_percent')==0){</php>selected<php>}</php>">
							<span>积分</span>
							<input type="radio" name="open_score_get_percent" value="0" <php>if(C('config.open_score_get_percent')==0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.open_score_get_percent')==1){</php>selected<php>}</php>">
							<span>积分百分比</span>
							<input type="radio" name="open_score_get_percent" value="1" <php>if(C('config.open_score_get_percent')==1){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="text" class="input-text" name="user_score_get" id="config_user_score_get" value="{pigcms{:C('config.user_score_get')}" size="10" validate="number:true,min:0" <php>if(C('config.open_score_get_percent')==1){</php>style="display:none"<php>}</php>/>
					<input type="text" class="input-text" name="score_get_percent" id="config_score_get_percent" value="{pigcms{:C('config.score_get_percent')}" size="10" validate="number:true,min:0" <php>if(C('config.open_score_get_percent')==0){</php>style="display:none"<php>}</php>/>
					
					<img src="./tpl/System/Static/images/help.gif" class="tips_img" title="<php>if(C('config.open_score_get_percent')==0){</php>消费1元获得的积分<php>}else{</php>消费1元获得积分百分比<php>}</php>" style="margin-top:1px;">
					 
				  </td>
				  </tr>
				  
					<tr>
						<th width="160">话费消费1元获得积分：</th>
						<td>

						<span class="cb-enable">
							<label class="cb-enable  <php>if(C('config.mobile_score_get_percent')==0){</php>selected<php>}</php>">
								<span>积分</span>
								<input type="radio" name="mobile_score_get_percent" value="0" <php>if(C('config.mobile_score_get_percent')==0){</php>checked="checked"<php>}</php>/>
							</label>
						</span>			     
						<span class="cb-disable">
							<label class="cb-disable  <php>if(C('config.mobile_score_get_percent')==1){</php>selected<php>}</php>">
								<span>积分百分比</span>
								<input type="radio" name="mobile_score_get_percent" value="1" <php>if(C('config.mobile_score_get_percent')==1){</php>checked="checked"<php>}</php>/>
							</label>
						</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="text" class="input-text" name="user_mobile_score_get" id="config_user_mobile_score_get" value="{pigcms{:C('config.user_mobile_score_get')}" size="10" validate="number:true,min:0" <php>if(C('config.mobile_score_get_percent')==1){</php>style="display:none"<php>}</php>/>
						
						<input type="text" class="input-text" name="mobile_score_percent" id="config_mobile_score_percent" value="{pigcms{:C('config.mobile_score_percent')}" size="10" validate="number:true,min:0" <php>if(C('config.mobile_score_get_percent')==0){</php>style="display:none"<php>}</php>/>

						<img src="./tpl/System/Static/images/help.gif" class="tips_img" title="<php>if(C('config.mobile_score_get_percent')==0){</php>消费1元获得的积分<php>}else{</php>消费1元获得积分百分比<php>}</php>" style="margin-top:1px;">
						</td>
					</tr>
					
					<tr>
						<th width="160">到店付1元获得积分：</th>
						<td>

						<span class="cb-enable">
							<label class="cb-enable  <php>if(C('config.store_score_get_percent')==0){</php>selected<php>}</php>">
								<span>积分</span>
								<input type="radio" name="store_score_get_percent" value="0" <php>if(C('config.store_score_get_percent')==0){</php>checked="checked"<php>}</php>/>
							</label>
						</span>			     
						<span class="cb-disable">
							<label class="cb-disable  <php>if(C('config.store_score_get_percent')==1){</php>selected<php>}</php>">
								<span>积分百分比</span>
								<input type="radio" name="store_score_get_percent" value="1" <php>if(C('config.store_score_get_percent')==1){</php>checked="checked"<php>}</php>/>
							</label>
						</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="text" class="input-text" name="user_store_score_get" id="config_user_store_score_get" value="{pigcms{:C('config.user_store_score_get')}" size="10" validate="number:true,min:0" <php>if(C('config.store_score_get_percent')==1){</php>style="display:none"<php>}</php>/>
						
						<input type="text" class="input-text" name="store_score_percent" id="config_store_score_percent" value="{pigcms{:C('config.store_score_percent')}" size="10" validate="number:true,min:0" <php>if(C('config.store_score_get_percent')==0){</php>style="display:none"<php>}</php>/>

						<img src="./tpl/System/Static/images/help.gif" class="tips_img" title="<php>if(C('config.store_score_get_percent')==0){</php>消费1元获得的积分<php>}else{</php>消费1元获得积分百分比<php>}</php>" style="margin-top:1px;">
						</td>
					</tr>
				 <!-- <tr>
				  <th width="160">消费1元获得积分百分比：</th>
				  <td><input type="text" class="input-text" name="score_get_percent" id="config_score_get_percent" value="{pigcms{:C('config.score_get_percent')}" size="10" validate="required:true,number:true,min:0" tips="消费1元获得积分百分比" /></td>
				 </tr>-->
				   <tr>
				  <th width="160">是否开启积分清零：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable <php>if(C('config.open_score_clean')==1){</php>selected<php>}</php>"><span>开启</span>
						<input type="radio" name="open_score_clean" value="1" <php>if(C('config.open_score_clean')==1){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<span class="cb-disable">
						<label class="cb-disable <php>if(C('config.open_score_clean')==0){</php>selected<php>}</php>"><span>关闭</span>
						<input type="radio" name="open_score_clean" value="0" <php>if(C('config.open_score_clean')==0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<em tips="开启积分将按时清零" class="notice_tips"></em>
					</td>
				 </tr>
				 <tr>
				  <th width="160">积分清零类型</th>
				  <td>
					<select name="clean_score_type" class="valid">
						<option value="0" <if condition="$config.clean_score_type eq 0">selected</if>>奖励积分</option>
						<option value="1" <if condition="$config.clean_score_type eq 1">selected</if>>全部积分</option>
						
					</select>
				  </td>
				 </tr>
				 <tr>
				  <th width="160">积分清零时间</th>
				  <td>
				  <input type="text" class="input-text" name="score_clean_time" id="config_score_clean_time" value="{pigcms{:C('config.score_clean_time')}" size="10"  tips="设置后会在这一天清零积分，清零前一天会有微信模板消息提醒用户"  onfocus="WdatePicker({readOnly:true,dateFmt:'MM-dd'})"/>	</td>
				 </tr>
				 
				  <tr>
				  <th width="160">积分清零比例</th>
				  <td><input type="text" class="input-text" name="score_clean_percent" id="config_score_clean_percent" value="{pigcms{:C('config.score_clean_percent')}" size="10" validate="required:true,number:true,min:0,max:100" tips="积分清零比例,0 不清除  100 全部清除 80 清除 80%" /></td>
				 </tr>
				 
				
				 
				 <tr>
				  <th width="160">是否开启积分兑换余额：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable <php>if(C('config.score_recharge')==1){</php>selected<php>}</php>"><span>开启</span>
						<input type="radio" name="score_recharge" value="1" <php>if(C('config.score_recharge')==1){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<span class="cb-disable">
						<label class="cb-disable <php>if(C('config.score_recharge')==0){</php>selected<php>}</php>"><span>关闭</span>
						<input type="radio" name="score_recharge" value="0" <php>if(C('config.score_recharge')==0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>
					<em tips="开启则可以使用积分充值，但是这部分余额不能提现，只允许用户消费" class="notice_tips"></em>
					</td>
				 </tr>
				 <tr>
				  <th width="160">兑换 1元 余额所需积分量：</th>
				  <td><input type="text" class="input-text" name="user_score_recharge_percent" id="config_user_score_recharge_percent" value="{pigcms{:C('config.user_score_recharge_percent')}" size="10" validate="required:true,number:true,min:0" tips="(1) 0 相当于不使用积分抵扣&lt;br/&gt;(2) 如30：相当于30积分抵扣一元人民币  " /></td>
				 </tr>
				 <tr>
				  <th width="160">积分使用条件：</th>
				  <td><input type="text" class="input-text" name="user_score_use_condition" id="config_user_score_use_condition" value="{pigcms{:C('config.user_score_use_condition')}" size="10" validate="required:true,number:true,min:0" tips="(1) 0 相当于不使用积分抵扣 &lt;br/&gt;(2)积分使用条件,如: 50 ,订单满50元才可以使用" /></td>
				 </tr>
				 <tr>
				  <th width="160">抵扣1元所需积分量：</th>
				  <td><input type="text" class="input-text" name="user_score_use_percent" id="config_user_score_use_percent" value="{pigcms{:C('config.user_score_use_percent')}" size="10" validate="required:true,number:true,min:0" tips="(1) 0 相当于不使用积分抵扣&lt;br/&gt;(2) 如30：相当于30积分抵扣一元人民币  " /></td>
				 </tr>
				 <tr>
				  <th colspan="2" style="text-align:left;color:red">积分设置填写 ‘%’ 积分数将按比例计算,如20%,不填 ‘%’ 则按积分数计算，数值应大于等于0，百分比不能大于100%，不包括社区业务</th>
				 </tr>
				 <tr>
				  <th width="160">平台总体每单最大使用积分：</th>
				  <td><input type="text" class="input-text" name="user_score_max_use" id="config_user_score_max_use" value="{pigcms{:C('config.user_score_max_use')}" size="10" validate="required:true" tips="(1) 0 相当于不使用积分抵扣&lt;br/&gt;(2)每单最大使用积分, 如 50,每单最多可以使用50个积分" /></td>
				 </tr>
				 <tr class="score_max">
				  <th width="160">团购每单最大使用积分：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.group_score_max')>=0&&C('config.group_score_max')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.group_score_max')>=0&&C('config.group_score_max')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.group_score_max')<0||C('config.group_score_max')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.group_score_max')<0||C('config.group_score_max')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
					
					
				  
				  <input type="text" class="input-text" name="group_score_max" id="config_group_score_max" value="{pigcms{:C('config.group_score_max')}" size="10" validate="required:true" tips="(1)如果这里选择跳过，最大使用数量将按【平台总体每单使用积分】算&lt;br/&gt;(2) 0 相当于不使用积分抵扣&lt;br/&gt;(3)每单最大使用积分, 如 50,每单最多可以使用50个积分" <php>if(C('config.group_score_max')<0||C('config.group_score_max')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr class="score_max">
				  <th width="160">快店每单最大使用积分：</th>
				  <td>
				  <span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.shop_score_max')>=0&&C('config.shop_score_max')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.shop_score_max')>=0&&C('config.shop_score_max')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.shop_score_max')<0||C('config.shop_score_max')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.shop_score_max')<0||C('config.shop_score_max')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			
				  
				  <input type="text" class="input-text" name="shop_score_max" id="config_shop_score_max" value="{pigcms{:C('config.shop_score_max')}" size="10" validate="required:true" tips="(1)如果这里选择跳过，最大使用数量将按【平台总体每单使用积分】算&lt;br/&gt;(2) 0 相当于不使用积分抵扣&lt;br/&gt;(3)每单最大使用积分, 如 50,每单最多可以使用50个积分" <php>if(C('config.shop_score_max')<0||C('config.shop_score_max')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr class="score_max">
				  <th width="160">餐饮每单最大使用积分：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.meal_score_max')>=0&&C('config.meal_score_max')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.meal_score_max')>=0&&C('config.meal_score_max')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.meal_score_max')<0||C('config.meal_score_max')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.meal_score_max')<0||C('config.meal_score_max')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
				  <input type="text" class="input-text" name="meal_score_max" id="config_meal_score_max" value="{pigcms{:C('config.meal_score_max')}" size="10" validate="required:true" tips="(1)如果这里选择跳过，最大使用数量将按【平台总体每单使用积分】算&lt;br/&gt;(2) 0 相当于不使用积分抵扣&lt;br/&gt;(3)每单最大使用积分, 如 50,每单最多可以使用50个积分" <php>if(C('config.meal_score_max')<0||C('config.meal_score_max')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 <tr class="score_max">
				  <th width="160">预约每单最大使用积分：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.appoint_score_max')>=0&&C('config.appoint_score_max')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.appoint_score_max')>=0&&C('config.appoint_score_max')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.appoint_score_max')<0||C('config.appoint_score_max')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.appoint_score_max')<0||C('config.appoint_score_max')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					
				  <input type="text" class="input-text" name="appoint_score_max" id="config_appoint_score_max" value="{pigcms{:C('config.appoint_score_max')}" size="10" validate="required:true" tips="(1)如果这里选择跳过，最大使用数量将按【平台总体每单使用积分】算&lt;br/&gt;(2) 0 相当于不使用积分抵扣&lt;br/&gt;(3)每单最大使用积分, 如 50,每单最多可以使用50个积分" <php>if(C('config.appoint_score_max')<0||C('config.appoint_score_max')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
				 
				  <tr class="score_max">
				  <th width="160">优惠买单每单最大使用积分：</th>
				  <td>
					<span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.store_score_max')>=0&&C('config.store_score_max')!=""){</php>selected<php>}</php>">
							<span>设置</span>
							<input type="radio" name="open_rate" value="1" <php>if(C('config.store_score_max')>=0&&C('config.store_score_max')!=""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.store_score_max')<0||C('config.store_score_max')==""){</php>selected<php>}</php>">
							<span>跳过</span>
							<input type="radio" name="open_rate" value="0" <php>if(C('config.store_score_max')<0||C('config.store_score_max')==""){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					
					
				  <input type="text" class="input-text" name="store_score_max" id="config_appoint_score_max" value="{pigcms{:C('config.store_score_max')}" size="10"  tips="(1)如果这里选择跳过，最大使用数量将按【平台总体每单使用积分】算&lt;br/&gt;(2) 0 相当于不使用积分抵扣&lt;br/&gt;(3)每单最大使用积分, 如 50,每单最多可以使用50个积分" <php>if(C('config.store_score_max')<0||C('config.store_score_max')==""){</php>style="display:none"<php>}</php>/></td>
				 </tr>
                 
                 <tr class="score_max">
				  <th width="160">物业缴费允许获得积分：</th>
				  <td>
				  <span class="cb-enable cb-enable-integral">
						<label class="cb-enable  <php>if(C('config.village_pay_integral')>0){</php>selected<php>}</php>">
							<span>开启</span>
							<input type="radio" name="village_pay_integral" value="1" <php>if(C('config.village_pay_integral')>0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable cb-disable-integral">
						<label class="cb-disable  <php>if(C('config.village_pay_integral')<=0){</php>selected<php>}</php>">
							<span>关闭</span>
							<input type="radio" name="village_pay_integral" value="0" <php>if(C('config.village_pay_integral')<=0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<em tips="(1)开启后，用户缴纳物业费时候可以获得积分&lt;br/&gt;(2)使用积分缴纳的物业费部分不会获得积分" class="notice_tips"></em>
				 </td>
				 </tr>
                 
                 <tr class="class-village-pay-integral" <php>if(C('config.village_pay_integral')==0){</php>style="display:none"<php>}</php>>
				  <th width="160">欠缴物业费允许获得积分：</th>
				  <td>
				  <span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.village_owe_pay_integral')>0){</php>selected<php>}</php>">
							<span>开启</span>
							<input type="radio" name="village_owe_pay_integral" value="1" <php>if(C('config.village_owe_pay_integral')>0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.village_owe_pay_integral')<=0){</php>selected<php>}</php>">
							<span>关闭</span>
							<input type="radio" name="village_owe_pay_integral" value="0" <php>if(C('config.village_owe_pay_integral')<=0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<em tips="用户欠缴纳的物业费缴费时是否获得积分" class="notice_tips"></em>
				 </td>
				 </tr>
                 
                 
                 <tr class="score_max">
				  <th width="160">物业缴费允许使用积分：</th>
				  <td>
				   <span class="cb-enable cb-enable-user-integral">
						<label class="cb-enable  <php>if(C('config.village_pay_use_integral')==1){</php>selected<php>}</php>">
							<span>开启</span>
							<input type="radio" name="village_pay_use_integral" value="1" <php>if(C('config.village_pay_use_integral')==1){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable cb-disable-user-integral">
						<label class="cb-disable  <php>if(C('config.village_pay_use_integral')==0){</php>selected<php>}</php>">
							<span>关闭</span>
							<input type="radio" name="village_pay_use_integral" value="0" <php>if(C('config.village_pay_use_integral')==0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<em tips="开启后，用户缴物业费时可以使用积分" class="notice_tips"></em>
				 </tr>
                 
                 <tr class="dis-user-integral" <php>if(C('config.village_pay_use_integral')==0){</php>style="display:none"<php>}</php>>
				  <th width="160">欠缴物业费允许使用积分：</th>
				  <td>
				   <span class="cb-enable">
						<label class="cb-enable  <php>if(C('config.village_owe_pay_use_integral')==1){</php>selected<php>}</php>">
							<span>开启</span>
							<input type="radio" name="village_owe_pay_use_integral" value="1" <php>if(C('config.village_owe_pay_use_integral')==1){</php>checked="checked"<php>}</php>/>
						</label>
					</span>			     
					<span class="cb-disable">
						<label class="cb-disable  <php>if(C('config.village_owe_pay_use_integral')==0){</php>selected<php>}</php>">
							<span>关闭</span>
							<input type="radio" name="village_owe_pay_use_integral" value="0" <php>if(C('config.village_owe_pay_use_integral')==0){</php>checked="checked"<php>}</php>/>
						</label>
					</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<em tips="用户缴纳物业费时，欠缴的物业费是否允许使用积分" class="notice_tips"></em>
				 </tr>
                 <tr class="dis-user-integral" <php>if(C('config.village_pay_use_integral')==0){</php>style="display:none"<php>}</php>>
				  <th width="160">物业缴费可使用最大积分：</th>
				  <td>
				   
                   <input type="text" class="input-text" name="use_max_integral_num" id="use_max_integral_num" value="{pigcms{:C('config.use_max_integral_num')}" size="10" />积分
					<em tips="缴费最多使用积分量，如果设置了 缴费可使用积分占总金额百分比 则此项设置无效" class="notice_tips"></em>
				 </tr>
                 <tr class="dis-user-integral" <php>if(C('config.village_pay_use_integral')==0){</php>style="display:none"<php>}</php>>
				  <th width="160">可使用积分占总金额百分比：</th>
				  <td>
				   
                   <input type="text" class="input-text" name="use_max_integral_percentage" id="use_max_integral_percentage" value="{pigcms{:C('config.use_max_integral_percentage')}" size="10" />%
					<em tips="缴费最多使用积分占总金额的百分比，如果设置了此项 则缴费可使用最大积分设置功能无效" class="notice_tips"></em>
				 </tr>
                 
                 
				</tbody>
			   </table> 
			   <div class="btn" style="margin-top:20px;"> 
				<input type="submit" name="dosubmit" value="提交" class="button" /> 
				
			   </div> 
			</form> 
		</div>
		<script>
			$('.cb-enable-user-integral').click(function(){
				$(".dis-user-integral").css("display","");
			});
			$('.cb-disable-user-integral').click(function(){
				$(".dis-user-integral").css("display","none");
			});
			
			$('.cb-enable-integral').click(function(){
				$('.class-village-pay-integral').css("display","");
			});
			$('.cb-disable-integral').click(function(){
				$('.class-village-pay-integral').css("display","none");
			});
			
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

				
				$('.score_max input:radio').click(function(){
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
				
				$('input[name="open_score_get_percent"]:radio').click(function(){
				
					if($(this).val()==0){
						
						$('#config_user_score_get').show();
						$('#config_score_get_percent').hide();
						$('#config_score_get_percent').nextAll().remove();
						$('#config_score_get_percent').after('<img src="./tpl/System/Static/images/help.gif" class="tips_img" title="消费1元获得的积分" style="margin-top:1px;">');
					}else{
						$('#config_score_get_percent').show();
						$('#config_user_score_get').hide();
						$('#config_score_get_percent').nextAll().remove();
						$('#config_score_get_percent').after('<img src="./tpl/System/Static/images/help.gif" class="tips_img" title="消费1元获得积分百分比" style="margin-top:1px;">');
					}
				});
				
				$('input[name="mobile_score_get_percent"]:radio').click(function(){
				//config_user_mobile_score_get config_mobile_score_percent
					if($(this).val()==0){
						$('#config_user_mobile_score_get').show();
						$('#config_mobile_score_percent').hide();
						$('#config_mobile_score_percent').nextAll().remove();
						$('#config_mobile_score_percent').after('<img src="./tpl/System/Static/images/help.gif" class="tips_img" title="消费1元获得的积分" style="margin-top:1px;">');
					}else{
						$('#config_mobile_score_percent').show();
						$('#config_user_mobile_score_get').hide();
						$('#config_mobile_score_percent').nextAll().remove();
						$('#config_mobile_score_percent').after('<img src="./tpl/System/Static/images/help.gif" class="tips_img" title="消费1元获得积分百分比" style="margin-top:1px;">');
					}
				});
				
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
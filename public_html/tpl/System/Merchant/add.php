<include file="Public:header"/>
	<style>
		.sub_mch{
			display:none
		}
	</style>
	<form id="myform" method="post" action="{pigcms{:U('Merchant/modify')}" frame="true" refresh="true">
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="160">商户账号</th>
				<td><input type="text" class="input fl" name="account" size="25" placeholder="商户平台的帐号" validate="maxlength:20,required:true" tips="设定之后，以后不能再修改！"/></td>
			</tr>
			<tr>
				<th width="160">商户密码</th>
				<td><input type="password" id="check_pwd" check_width="180" class="input fl" name="pwd" size="25" placeholder="商户平台的密码" validate="required:true,minlength:6" tips="商户的密码很重要，填写难度较强的密码有效保护商户的信息，也可以保护网站的数据安全。"/></td>
			</tr>
			<tr>
				<th width="160">商户名称</th>
				<td><input type="text" class="input fl" name="name" size="25" placeholder="商户的公司名或..." validate="maxlength:20,required:true"/></td>
			</tr>
			<if condition="$config.open_score_discount eq 1">
			<tr>
				<th width="160">用户支付优惠方式</th>
				<td>
				<select name="user_discount_type" class="valid">
					<option value="0" >优惠可控通道</option>
					<option value="1" >纯优惠通道</option>
					<option value="2" >积分通道</option>
					<option value="3" >积分优惠2选1通道</option>
				</select>
				<em class="notice_tips" tips="优惠可控通道，即用户下单时，只可享受业务该有的优惠，不可获得平台积分（取决于用户消费1元获得积分设置项）；纯优惠通道，即用户下单时，只可享受业务该有的优惠，不可获得平台积分（用户消费1元获得积分的值不管是否设置，不受影响）；积分通道，即用户下单时，用户只可获得平台积分（取决于用户消费1元获得积分设置项），不可使用业务下优惠；2选1模式，即用户下单时，可在2个模式中随意选择其一（取决于用户消费1元获得积分设置项）"></em>
				</td>
			</tr>
			</if>
			<tr>
				<th width="160">是否开启扫码核销积分优惠券</th>
				<td>
					<span class="cb-enable"><label class="cb-enable" ><span>是</span>
					<input type="radio" name="san_pay_score_coupon" value="1" /></label></span>
					<span class="cb-disable " ><label class="cb-disable selected" ><span>否</span>
					<input type="radio" name="san_pay_score_coupon" value="0" checked="checked"/></label></span>
					<em class="notice_tips" tips="开启则核销，不开启则不核销。线下零售中商户用扫码枪扫描用户的商家会员微信卡包里的二维码与微信付款码时，系统将自动核销可以抵扣的用户平台积分与平台最优优惠券；同理，店内收银中商户用扫码枪扫描用户微信付款码时，系统将自动核销可以抵扣的用户平台积分与最优优惠券"></em>
				</td>
			</tr>
			<if condition="C('config.open_sub_mchid') eq 1">
			<tr>
				<th width="160">是否开启特约子商支付功能</th>
				<td>
					<span class="cb-enable"><label class="cb-enable"><span>是</span><input type="radio" name="open_sub_mchid" value="1"/></label></span>
					<span class="cb-disable"><label class="cb-disable  selected"><span>否</span><input type="radio" name="open_sub_mchid" value="0" checked="checked" /></label></span>
					<em class="notice_tips" tips="开启后，该商户不能设置使用自有的微信支付，该商家的支付将按服务商的子商户号支付<br>(开启后请正确配置子商户号、子商户退款、是否允许使用平台余额、是否允许使用平台优惠)"></em>
				</td>
			</tr>

			
			<tr class="sub_mch">
				<th width="160">子商户号</th>
				<td>
				<input type="text" class="input fl" name="sub_mch_id" size="25" placeholder="子商户号" validate="" />
				<em class="notice_tips" tips="开启子商户支付后必须要填写正确的子商户号，子商户号可以在微信子商户平台查看"></em>
				</td>
				
			</tr>
			
			<tr class="sub_mch">
				<th width="160">是否开启子商户支付退款</th>
				<td>
					<span class="cb-enable"><label class="cb-enable "><span>开启</span><input type="radio" name="sub_mch_refund" value="1"  /></label></span>
					<span class="cb-disable"><label class="cb-disable selected"><span>关闭</span><input type="radio" name="sub_mch_refund" value="0" checked="checked"/></label></span>
					<font color="red">请确认子商户是否授权退款权限,如果没有请不要开启</font>
					<em class="notice_tips" tips="开启子商户支付退款，需要子商户申请退款权限给微信服务商，如果未得到授权，退款将失败;<br>关闭后用户将不能退款，当开启子商户支付功能时才有效"></em>
				</td>
			</tr>
			<tr class="sub_mch">
				<th width="160">是否允许使用平台余额</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>开启</span><input type="radio" name="sub_mch_system_pay" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="sub_mch_system_pay" value="0"/></label></span>
					<em class="notice_tips" tips="开启后用户通过子商户支付的同时也可以使用平台余额，且平台余额部分参与平台跟商家对账,必须开启特约子商户功能;<br>关闭后，用户不能使用平台余额，当开启子商户支付功能时才有效"></em>
				</td>
			</tr>
			<tr class="sub_mch">
				<th width="160">是否允许使用平台优惠(积分优惠券)</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>开启</span><input type="radio" name="sub_mch_discount" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="sub_mch_discount" value="0"/></label></span>
					<em class="notice_tips" tips="开启后,用户通过子商户支付的同时也可以使用平台优惠，且优惠部分参与平台跟商家对账,必须开启特约子商户功能;<br>关闭后用户不能使用平台优惠券，平台积分抵扣，当开启子商户支付功能时才有效"></em>
				</td>
			</tr>
			
					</if>
			<if condition="$config.open_mer_owe_money">
			<tr >
				<th width="160">是否允许商家余额欠费</th>
				<td>
				<input type="text" class="input fl" name="mch_owe_money" size="25" placeholder="子商户欠钱额度" validate="" />
				<em class="notice_tips" tips="0 表示不允许欠款，一旦商家余额不足够{pigcms{$config['deliver_name']}，用户则不能下单，1000 表示平台允许商家欠平台1000元，超过1000后用户不能使用{pigcms{$config['deliver_name']}下单"></em>
				</td>
				
			</tr>
			
			</if>
			<if condition="C('config.open_extra_price') eq 1">
			<tr>
				<th width="160">{pigcms{:C('config.extra_price_alias_name')}结算比例</th>
				<td><input type="text" class="input fl" name="extra_price_percent" value="" size="25"  validate="required:true,min:0,max:100" tips=""/></td>
			</tr>
			<tr>
				<th width="160">消费1元赠送{pigcms{:C('config.score_name')}数</th>
				<td><input type="text" class="input fl" name="score_get" value="" size="25"  validate="required:true,min:0" tips=""/>0 相当于不得{pigcms{$config.score_name}</td>
			</tr>
			</if>
			<if condition="$config.international_phone eq 1">
			<tr>
				<th width="160">区号</th>
				<td>
					<select name="phone_country_type" id="phone_country_type" class="fl" style="margin-right:5px;">
						<option value="">请选择国家...,choose country</option>
					    <option value="86" <if condition="$config.qcloud_sms_default_country eq 86">selected</if>>+86 中国 China</option>
					    <option value="1" <if condition="$config.qcloud_sms_default_country eq 1">selected</if>>+1 加拿大 Canada</option>
				    </select>
				</td>
			</tr>

			</if>
			<tr>
				<th width="160">联系电话</th>
				<td>
				<input type="text" class="input fl" name="phone" size="25" placeholder="联系人的电话" validate="required:true" tips="多个电话号码以空格分开,多个手机号码时，在修改手机号时将使用第一个手机号发送验证码且只可修改第一个手机号，建议只填写一个手机号"/></td>
			</tr>
		
			<tr>
				<th width="160">联系邮箱</th>
				<td><input type="text" class="input fl" name="email" size="25" placeholder="可不填写" validate="email:true" tips="只供管理员后台记录，前台不显示"/></td>
			</tr>
			<tr style="display:none;">
				<th width="160">对账周期</th>
				<td><input type="text" class="input fl" name="bill_period" size="25" placeholder="可不填写" validate="number:true,min:1" tips="对账周期，不填则按系统对账周期计算,最小为一天"/></td>
			</tr>
			
			<if condition="$system_session['area_type'] neq 3">
			<tr>
				<th width="160">所在区域</th>
				<td id="choose_cityarea" province_id="{pigcms{$merchant.province_id}" city_id="{pigcms{$merchant.city_id}" area_id="{pigcms{$merchant.area_id}" circle_id="-1"></td>
			</tr>
			<else />
				<tr>
					<th width="160">所在区域</th>
					<td>
					{pigcms{$merchant.province_name} {pigcms{$merchant.city_name} {pigcms{$merchant.area_name}
					<input type="hidden" name="area_id"  value = "{pigcms{$system_session['area_id']}">
					
					<input type="hidden" name="city_id"  value = "{pigcms{$merchant['city_id']}">
					<input type="hidden" name="province_id"  value = "{pigcms{$merchant['province_id']}">
					</td>
				</tr>
			</if>
			<tr>
				<th width="160">到期时间</th>
				<td><input type="text" class="input fl" name="merchant_end_time" size="25" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd HH:mm'})" tips="商户到期时间，到期之后不允许进入商户平台并关闭该商户！清空为永不过期"/></td>
			</tr>
			<tr>
				<th width="160">商户状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>启用</span><input type="radio" name="status" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>关闭</span><input type="radio" name="status" value="0" /></label></span>
				</td>
			</tr>
			<tr>
				<th width="160">签约商家</th>
				<td>
					<span class="cb-enable"><label class="cb-enable"><span>是</span><input type="radio" name="issign" value="1"/></label></span>
					<span class="cb-disable"><label class="cb-disable  selected"><span>否</span><input type="radio" name="issign" value="0" checked="checked" /></label></span>
				</td>
			</tr>
			<tr>
				<th width="160">认证商家</th>
				<td>
					<span class="cb-enable"><label class="cb-enable"><span>是</span><input type="radio" name="isverify" value="1" /></label></span>
					<span class="cb-disable"><label class="cb-disable  selected"><span>否</span><input type="radio" name="isverify" value="0"  checked="checked"/></label></span>
				</td>
			</tr>
			<if condition="$config['wx_token']">
			<tr>
				<th width="160">使用公众号</th>
				<td>
					<span class="cb-enable"><label class="cb-enable selected"><span>允许</span><input type="radio" name="is_open_oauth" value="1" checked="checked" /></label></span>
					<span class="cb-disable"><label class="cb-disable"><span>禁止</span><input type="radio" name="is_open_oauth" value="0"/></label></span>
					<em class="notice_tips" tips="如果系统设置中允许所有商家都使用公众号，请禁止无效。"></em>
				</td>
			</tr>
			</if>
			<if condition="$config['is_open_weidian']">
				<tr>
					<th width="160">开微店</th>
					<td>
						<span class="cb-enable"><label class="cb-enable selected"><span>允许</span><input type="radio" name="is_open_oauth" value="1" checked="checked" /></label></span>
						<span class="cb-disable"><label class="cb-disable"><span>禁止</span><input type="radio" name="is_open_oauth" value="0"/></label></span>
						<em class="notice_tips" tips="如果系统设置中允许所有商家都能开微店，请禁止无效。"></em>
					</td>
				</tr>
			</if>
		
			
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	<script>
		$(function(){
			$('input[name="open_sub_mchid"]').click(function(){
				var sub = $(this);
				if(sub.val()==1){
					$('.sub_mch').show();
				}else{
					$('.sub_mch').hide();
				}
			});
		});
	</script>
	
<include file="Public:footer"/>
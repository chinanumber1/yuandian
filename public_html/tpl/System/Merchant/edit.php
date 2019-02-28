<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('Merchant/amend')}" frame="true" refresh="true">
		<input type="hidden" name="mer_id" value="{pigcms{$merchant.mer_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="160">商户账号</th>
				<td><div class="show">{pigcms{$merchant.account}</div></td>
			</tr>
			<tr>
				<th width="160">注册时间</th>
				<td><div class="show">{pigcms{$merchant.reg_time|date='Y-m-d H:i:s',###}</div></td>
			</tr>
			<tr>
				<th width="160">商户密码</th>
				<td><input type="password" id="check_pwd" check_width="180" check_event="keyup" class="input fl" name="pwd" value="" size="25" placeholder="不修改则不填写！" validate="minlength:6" tips="不修改则不填写！"/></td>
			</tr>
			<tr>
				<th width="160">商户名称</th>
				<td><input type="text" class="input fl" name="name" value="{pigcms{$merchant.name}" size="25" placeholder="商户的公司名或..." validate="maxlength:20,required:true"/></td>
			</tr>
			
			<if condition="$config.open_score_discount eq 1">
			<tr>
				<th width="160">用户支付优惠方式</th>
				<td>
				<select name="user_discount_type" class="valid">
					<option value="0" <if condition="$merchant['user_discount_type'] eq 0">selected="selected"</if>>优惠可控通道</option>
					<option value="1" <if condition="$merchant['user_discount_type'] eq 1">selected="selected"</if>>纯优惠通道</option>
					<option value="2" <if condition="$merchant['user_discount_type'] eq 2">selected="selected"</if>>积分通道</option>
					<option value="3" <if condition="$merchant['user_discount_type'] eq 3">selected="selected"</if>>积分优惠2选1通道</option>
				</select>
				<em class="notice_tips" tips="优惠可控通道，即用户下单时，只可享受业务该有的优惠，可获得平台积分（取决于用户消费1元获得积分设置项）；纯优惠通道，即用户下单时，只可享受业务该有的优惠，不可获得平台积分（用户消费1元获得积分的值不管是否设置，不受影响）；积分通道，即用户下单时，用户只可获得平台积分（取决于用户消费1元获得积分设置项），不可使用业务下优惠；2选1模式，即用户下单时，可在2个模式中随意选择其一（取决于用户消费1元获得积分设置项）"></em>
				</td>
			</tr>
			</if>
			
			
			<tr>
				<th width="160">是否开启扫码核销积分优惠券</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['san_pay_score_coupon'] eq 1">selected</if>"><span>是</span>
					<input type="radio" name="san_pay_score_coupon" value="1" <if condition="$merchant['san_pay_score_coupon'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable " ><label class="cb-disable  <if condition="$merchant['san_pay_score_coupon'] eq 0">selected</if>"><span>否</span>
					<input type="radio" name="san_pay_score_coupon" value="0" <if condition="$merchant['san_pay_score_coupon'] eq 0">checked="checked"</if>/></label></span>
					<em class="notice_tips" tips="开启则核销，不开启则不核销。线下零售中商户用扫码枪扫描用户的商家会员微信卡包里的二维码与微信付款码时，系统将自动核销可以抵扣的用户平台积分与平台最优优惠券；同理，店内收银中商户用扫码枪扫描用户微信付款码时，系统将自动核销可以抵扣的用户平台积分与最优优惠券"></em>
				</td>
			</tr>
			
			
			<if condition="$config.open_sub_mchid eq 1">
			<tr>
				<th width="160">是否开启特约子商功能</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['open_sub_mchid'] eq 1">selected</if>"><span>是</span>
					<input type="radio" name="open_sub_mchid" value="1" <if condition="$merchant['open_sub_mchid'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable " ><label class="cb-disable  <if condition="$merchant['open_sub_mchid'] eq 0">selected</if>"><span>否</span>
					<input type="radio" name="open_sub_mchid" value="0" <if condition="$merchant['open_sub_mchid'] eq 0">checked="checked"</if>/></label></span>
					<em class="notice_tips" tips="开启后，该商户不能设置使用自有的微信支付，该商家的支付将按服务商的子商户号支付<br>(开启后请正确配置子商户号、子商户退款、是否允许使用平台余额、是否允许使用平台优惠)"></em>
				</td>
			</tr>
			</if>
			
			<if condition="$config['sub_mchid_only_offline'] eq 1">
				<tr class="sub_mch">
					<th width="160">只允许子商户线下支付</th>
					<td>
						<span class="cb-enable">
							<label class="cb-enable  <if condition="$merchant['sub_mchid_only_offline'] eq 1">selected</if>">
								<span>开启</span><input type="radio" name="sub_mchid_only_offline" value="1" <if condition="$merchant['sub_mchid_only_offline'] eq 1">checked="checked"</if> />
							</label>
						</span>
						<span class="cb-disable">
							<label class="cb-disable <if condition="$merchant['sub_mchid_only_offline'] eq 0">selected</if>">
								<span>关闭</span><input type="radio" name="sub_mchid_only_offline" value="0" <if condition="$merchant['sub_mchid_only_offline'] eq 0">checked="checked"</if> />
							</label>
						</span>
						
						<em class="notice_tips" tips="开启后线上走平台，线下走子商户，关闭后都走子商户"></em>
					</td>
				</tr>
			</if>
			<tr class="sub_mch">
				<th width="160">微信子商户号</th>
				<td><input type="text" class="input fl" name="sub_mch_id" size="25" value="{pigcms{$merchant['sub_mch_id']}" placeholder="子商户号" validate="" />
					<em class="notice_tips" tips="开启子商户支付后必须要填写正确的子商户号，子商户号可以在微信子商户平台查看"></em>
				</td>
			</tr>
			<tr class="sub_mch">
				<th width="160">支付宝子商户授权码</th>
				<td><input type="text" class="input fl" name="alipay_auth_code" size="25" value="{pigcms{$merchant['alipay_auth_code']}" placeholder="支付宝子商户授权码" validate="" />
					<em class="notice_tips" tips="开启子商户支付后必须要填写正确的子商户号授权码，可以在支付宝服务商平台查看，支付宝子商户只适用到店付，不能退款，不能使用平台余额及优惠"></em>
				</td>
			</tr>
			<tr class="sub_mch">
				<th width="160">是否开启子商户支付退款</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['sub_mch_refund'] eq 1">selected</if>"><span>开启</span><input type="radio" name="sub_mch_refund" value="1" <if condition="$merchant['sub_mch_refund'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$merchant['sub_mch_refund'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="sub_mch_refund" value="0" <if condition="$merchant['sub_mch_refund'] eq 0">checked="checked"</if>/></label></span>
					<font color="red">请确认子商户是否授权退款权限,如果没有请不要开启</font>
					<em class="notice_tips" tips="开启子商户支付退款，需要子商户申请退款权限给微信服务商，如果未得到授权，退款将失败;<br>关闭后用户将不能退款，当开启子商户支付功能时才有效"></em>
				</td>
			</tr>
			
			
			<tr class="sub_mch">
				<th width="160">是否允许适用平台余额</th>
				<td>
					<span class="cb-enable"><label class="cb-enable  <if condition="$merchant['sub_mch_sys_pay'] eq 1">selected</if>"><span>开启</span><input type="radio" name="sub_mch_sys_pay" value="1" <if condition="$merchant['sub_mch_sys_pay'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable  <if condition="$merchant['sub_mch_sys_pay'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="sub_mch_sys_pay" value="0" <if condition="$merchant['sub_mch_sys_pay'] eq 0">checked="checked"</if>/></label></span>
					<em class="notice_tips" tips="开启后用户通过子商户支付的同时也可以使用平台余额，且平台余额部分参与平台跟商家对账,必须开启特约子商户功能;<br>关闭后，用户不能使用平台余额，当开启子商户支付功能时才有效"></em>
				</td>
			</tr>
			<tr class="sub_mch">
				<th width="160">是否允许适用平台优惠(积分优惠券)</th>
				<td>
					<span class="cb-enable"><label class="cb-enable  <if condition="$merchant['sub_mch_discount'] eq 1">selected</if>"><span>开启</span><input type="radio" name="sub_mch_discount" value="1" <if condition="$merchant['sub_mch_discount'] eq 1">checked="checked"</if>  /></label></span>
					<span class="cb-disable"><label class="cb-disable  <if condition="$merchant['sub_mch_discount'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="sub_mch_discount" value="0" <if condition="$merchant['sub_mch_discount'] eq 0">checked="checked"</if> /></label></span>
					<em class="notice_tips" tips="开启后,用户通过子商户支付的同时也可以使用平台优惠，且优惠部分参与平台跟商家对账,必须开启特约子商户功能;<br>关闭后用户不能使用平台优惠券，平台积分抵扣，当开启子商户支付功能时才有效"></em>
				</td>
			</tr>
			
			</if>
		
			<if condition="$config.open_mer_owe_money eq 1">
			<tr >
				<th width="160">是否允许商家余额欠费</th>
				<td>
				<input type="text" class="input fl" name="mch_owe_money" size="25" placeholder="商户欠钱额度" value="{pigcms{$merchant.mch_owe_money}" validate="" />
				<em class="notice_tips" tips="0 表示不允许欠款，一旦商家余额不足够{pigcms{$config['deliver_name']}，用户则不能下单，1000 表示平台允许商家欠平台1000元，超过1000后用户不能使用{pigcms{$config['deliver_name']}下单"></em>
				</td>
				
			</tr>
			
			</if>
			
			<tr>
				<th width="160">打包费是否抽成</th>
				<td>
					<span class="cb-enable"><label class="cb-enable  <if condition="$merchant['package_fee_percent'] eq 1">selected</if>"><span>开启</span><input type="radio" name="package_fee_percent" value="1" <if condition="$merchant['package_fee_percent'] eq 1">checked="checked"</if>  /></label></span>
					<span class="cb-disable"><label class="cb-disable  <if condition="$merchant['package_fee_percent'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="package_fee_percent" value="0" <if condition="$merchant['package_fee_percent'] eq 0">checked="checked"</if> /></label></span>
					<em class="notice_tips" tips="开启后{pigcms{$shop_alias_name}业务的打包费也会抽成"></em>
				</td>
			</tr>
			
			
			<if condition="$config.open_extra_price eq 1">
			<tr>
				<th width="160">商家欠平台{pigcms{:C('config.extra_price_alias_name')}数</th>
				<td><input type="text" class="input fl" name="extra_price_pay_for_system" value="{pigcms{$merchant.extra_price_pay_for_system|floatval}" size="25"  validate="required:true,min:0" tips=""/>,即{pigcms{:sprintf("%.2f", $merchant['extra_price_pay_for_system']*$merchant['extra_price_percent']/100)}元</td>
			</tr>
			<tr>
				<th width="160">{pigcms{:C('config.extra_price_alias_name')}结算比例</th>
				<td><input type="text" class="input fl" name="extra_price_percent" value="{pigcms{$merchant.extra_price_percent|floatval}" size="25"  validate="required:true,min:0,max:100" tips=""/></td>
			</tr>
			<tr>
				<th width="160">消费1元赠送{pigcms{:C('config.score_name')}数</th>
				<td><input type="text" class="input fl" name="score_get" value="{pigcms{$merchant.score_get|floatval}" size="25"  validate="required:true,min:0" tips=""/>0 相当于不得{pigcms{$config.score_name}</td>
			</tr>
			</if>
	
			
			<tr>
			
				<tr>
				<th width="160">消费1元赠送{pigcms{:C('config.score_name')}百分比</th>
				<td>
					<span class="cb-enable"><label class="cb-enable  <if condition="$merchant['score_get_percent'] egt 0">selected</if>"><span>设置</span>
					<input type="radio" name="set_score_percent" value="1" <if condition="$merchant['score_get_percent'] egt 0">checked="checked"</if>/></label></span>
					<span class="cb-disable " ><label class="cb-disable  <if condition="$merchant['score_get_percent'] lt 0">selected</if>"><span>跳过</span>
					<input type="radio" name="set_score_percent" value="0" <if condition="$merchant['score_get_percent'] lt 0">checked="checked"</if>/></label>
					</span>
					
		
					<input type="text" name="score_get_percent" class="input fl" value="{pigcms{$merchant['score_get_percent']}" style="margin-left:5px;" <if condition="$merchant['score_get_percent'] lt 0">style="display:none"</if>/>
					<em class="notice_tips" tips="（按百分比，不要填写%，填-1 则跳过当前业务设置，填0则用户不获得平台积分）"></em>
				</td>
			</tr>
			<if condition="$config.international_phone eq 1">
			<tr>
				<th width="160">区号</th>
				<td>
					<select name="phone_country_type" id="phone_country_type" class="fl" style="margin-right:5px;">
						<option value="">请选择国家...,choose country</option>
					    <option value="86" <if condition="$merchant.phone_country_type eq 86">selected</if>>+86 中国 China</option>
					    <option value="1"  <if condition="$merchant.phone_country_type eq 1">selected</if>>+1 加拿大 Canada</option>
				    </select>
				</td>
			</tr>
			</if>
			<if condition="!isset($config['not_o2o_demo'])">
			<tr>
				<th width="160">联系电话</th>
				<td>
			
				<input type="text" class="input fl" name="phone" value="{pigcms{$merchant.phone}" size="25" placeholder="联系人的电话" validate="required:true" tips="多个电话号码以空格分开,多个手机号码时，在修改手机号时将使用第一个手机号发送验证码且只可修改第一个手机号，建议只填写一个手机号"/></td>
			</tr>
			</if>
			<tr>
				<th width="160">联系邮箱</th>
				<td><input type="text" class="input fl" name="email" value="{pigcms{$merchant.email}" size="25" placeholder="可不填写" validate="email:true" tips="只供管理员后台记录，前台不显示"/></td>
			</tr>
			<tr style="display:none;">
				<th width="160">对账周期</th>
				<td><input type="text" class="input fl" name="bill_period" value="{pigcms{$merchant.bill_period}" size="25" placeholder="可不填写" validate="number:true,min:0" tips="对账周期，填0则按系统对账周期计算,最小为一天"/></td>
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
					</td>
				</tr>
			</if>
			<tr>
				<th width="160">到期时间</th>
				<td><input type="text" class="input fl" name="merchant_end_time" value="{pigcms{$merchant.merchant_end_time}" size="25" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd HH:mm'})" tips="商户到期时间，到期之后不允许进入商户平台并关闭该商户！清空为永不过期"/></td>
			</tr>
			<tr>
				<th width="160">商户状态</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['status'] eq 1">selected</if>"><span>启用</span><input type="radio" name="status" value="1" <if condition="$merchant['status'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$merchant['status'] neq 1">selected</if>"><span>关闭</span><input type="radio" name="status" value="0" <if condition="$merchant['status'] neq 1">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr style="display:none">
				<th width="160">线下支付</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['is_close_offline'] eq 0">selected</if>"><span>启用</span><input type="radio" name="is_close_offline" value="0" <if condition="$merchant['is_close_offline'] eq 0">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$merchant['is_close_offline'] eq 1">selected</if>"><span>关闭</span><input type="radio" name="is_close_offline" value="1" <if condition="$merchant['is_close_offline'] eq 1">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<tr>
				<th width="160">签约商家</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['issign'] eq 1">selected</if>"><span>是</span><input type="radio" name="issign" value="1" <if condition="$merchant['issign'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable  <if condition="$merchant['issign'] neq 1">selected</if>"><span>否</span><input type="radio" name="issign" value="0"  <if condition="$merchant['issign'] neq 1">checked="checked"</if> /></label></span>
					<em class="notice_tips" tips="开启后商家中心会显示此商家已签约标签即商家是优质客户，所有新增的产品都无需审核"></em>
				</td>
			</tr>
			<tr>
				<th width="160">认证商家</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['isverify'] eq 1">selected</if>"><span>是</span><input type="radio" name="isverify" value="1" <if condition="$merchant['isverify'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$merchant['isverify'] neq 1">selected</if>"><span>否</span><input type="radio" name="isverify" value="0"  <if condition="$merchant['isverify'] neq 1">checked="checked"</if> /></label></span>
					<em class="notice_tips" tips="开启后商家中心会显示此商家已认证标签"></em>
				</td>
			</tr>
			<tr>
				<th width="160">使用公众号</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$merchant['is_open_oauth'] eq 1">selected</if>"><span>允许</span><input type="radio" name="is_open_oauth" value="1" <if condition="$merchant['is_open_oauth'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$merchant['is_open_oauth'] eq 0">selected</if>"><span>禁止</span><input type="radio" name="is_open_oauth" value="0" <if condition="$merchant['is_open_oauth'] eq 0">checked="checked"</if>/></label></span>
				</td>
			</tr>
			<if condition="$config['is_open_weidian']">
				<tr>
					<th width="160">开微店</th>
					<td>
						<span class="cb-enable"><label class="cb-enable <if condition="$merchant['is_open_weidian'] eq 1">selected</if>"><span>允许</span><input type="radio" name="is_open_weidian" value="1" <if condition="$merchant['is_open_weidian'] eq 1">checked="checked"</if> /></label></span>
						<span class="cb-disable"><label class="cb-disable <if condition="$merchant['is_open_weidian'] eq 0">selected</if>"><span>禁止</span><input type="radio" name="is_open_weidian" value="0" <if condition="$merchant['is_open_weidian'] eq 0">checked="checked"</if>/></label></span>
					</td>
				</tr>
			</if>
			<if condition="isset($config['group_page_row']) && isset($config['now_scenic']) && $config['now_scenic'] neq 2">
				<tr>
					<th width="160">开通景区</th>
					<td>
						<span class="cb-enable"><label class="cb-enable <if condition="$merchant['is_open_scenic'] eq 1">selected</if>"><span>允许</span><input type="radio" name="is_open_scenic" value="1" <if condition="$merchant['is_open_scenic'] eq 1">checked="checked"</if> /></label></span>
						<span class="cb-disable"><label class="cb-disable <if condition="$merchant['is_open_scenic'] eq 0">selected</if>"><span>禁止</span><input type="radio" name="is_open_scenic" value="0" <if condition="$merchant['is_open_scenic'] eq 0">checked="checked"</if>/></label></span>
					</td>
				</tr>
			</if>
			<if condition="$can_percent">
			<tr>
				<th width="160">批发抽成(%)</th>
				<td><input type="text" class="input fl" name="market_percent" value="{pigcms{$merchant.market_percent}" size="25" tips="批发抽成，请填写百分比，不要填写%"/></td>
			</tr>
			<tr>
				<th width="160">抽成设置</th>
				<td>
				<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/edit_percent',array('mer_id'=>$merchant['mer_id'],'type'=>'merchant'))}','编辑商家抽成比例',800,560,true,false,false,null,'edit_percent',true);" style="color:blue">设置商家抽成比例</a>&nbsp;&nbsp;&nbsp;&nbsp;
			</tr>
			</if>
            <!--<if condition="$config['is_open_merchant_discount'] eq 1">
            <tr>
                <th width="160">最多折扣订单数</th>
                <td><input type="text" class="input fl" name="discount_order_num" value="{pigcms{$merchant.discount_order_num}" size="25" tips="该商家最多有多少单享有该优惠折扣，0表示不限制折扣订单数"/></td>
            </tr>
            <tr>
                <th width="160">商家折扣比例</th>
                <td><input type="text" class="input fl" name="discount_percent" value="{pigcms{$merchant.discount_percent}" size="25" tips="开启后，用户在该商家下所有业务所有店铺付款时会优先享用该折扣（填写0-100的数字，数字越大优惠的越少，如99，就按照 99%进行优惠, 0和100都是无折扣）"/></td>
            </tr>
            </if>-->
			<if condition="$config.open_juhepay eq 1">
			<tr>
				<th width="160">聚合支付</th>
				<td>

				<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/juhepay',array('mer_id'=>$merchant['mer_id']))}','编辑商家聚合支付',400,200,true,false,false,null,'edit_juhe',true);" style="color:blue">编辑商家聚合支付</a>&nbsp;&nbsp;&nbsp;&nbsp;
			
				</td>

			</tr>
			</if>
			<tr>
				<th width="160">分佣设置</th>
				<td>

				<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/edit_rate',array('mer_id'=>$merchant['mer_id']))}','编辑商家推广分佣比例',800,560,true,false,false,null,'edit_rate',true);" style="color:blue">设置商家推广分佣比例</a>&nbsp;&nbsp;&nbsp;&nbsp;
				<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/edit_user_rate',array('mer_id'=>$merchant['mer_id']))}','用户分佣设置',800,560,true,false,false,null,'edit_user_rate',true);" style="color:blue">设置用户分佣比例</a>
				</td>

			</tr>
			<tr>
				<th width="160">线下支付设置</th>
				<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/edit_offline',array('mer_id'=>$merchant['mer_id']))}','设置线下支付',800,560,true,false,false,null,'edit_offline',true);" style="color:blue">设置线下支付</a></td>
			</tr>
			<tr>
				<th width="160">{pigcms{$config.score_name}使用设置</th>
				<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/edit_score',array('mer_id'=>$merchant['mer_id']))}','设置不同业务{pigcms{$config.score_name}最大使用数量',800,560,true,false,false,null,'edit_score',true);" style="color:blue">设置不同业务{pigcms{$config.score_name}使用数量</a></td>
			</tr>
			<tr>
				<th width="160">商家权限设置</th>
				<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/menu',array('mer_id'=>$merchant['mer_id']))}','设置商家使用权限',700,500,true,false,false,null,'menu',true);" style="color:blue">设置商家使用权限</a><td>
			</tr>
            <if condition="$config['is_open_merchant_foodshop_discount'] eq 1">
            <tr>
                <th width="160">商家餐饮折扣设置</th>
                <td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/m_discount',array('mer_id'=>$merchant['mer_id']))}','设置商家餐饮折扣',700,500,true,false,false,null,'m_discount',true);" style="color:blue">设置商家餐饮折扣</a><td>
            </tr>
            </if>
			<tr>
				<th width="160">平台{pigcms{$config.score_name}</th>
				<td><input type="text" class="input fl" name="plat_score" value="{pigcms{$merchant.plat_score}" size="10" placeholder="0" tips="平台{pigcms{$config.score_name}"/></td>
			</tr>
			<tr>
				<th width="160">权限分组</th>
				<td>
					
					<select name="authority_group_id" tips="权限分组">
						<option value="0" <if condition="empty($merchant['authority_group_id'])">selected="selected"</if>>不选</option>	
						<volist name="authority_group" id="vo">
							<option value="{pigcms{$vo.id}" <if condition="$merchant['authority_group_id'] eq $vo['id']">selected="selected"</if>>{pigcms{$vo.name}</option>								
						</volist>
					</select>
				</td>
				
			</tr>

			<tr><th colspan="2" style="color: red;text-align:center"> 超级广告设置 </th></tr>
			<tr>
				<th width="160">首页宣传状态</th>
				<td>
					<select name="share_open" class="valid">
					<option value="0" <if condition="$merchant['share_open'] eq 0">selected="selected"</if>>关闭</option>
					<option value="1" <if condition="$merchant['share_open'] eq 1">selected="selected"</if>>开启显示商家信息</option>
					<option value="2" <if condition="$merchant['share_open'] eq 2">selected="selected"</if>>开启跳转到商家微网站</option>
					</select>
				</td>
			</tr>
			<tr>
				<th width="160">广告语</th>
				<td><input type="text" class="input fl" name="a_title" value="{pigcms{$home_share.title}" size="25" placeholder="可不填写" tips="粉丝看到自己的第一次进入本站来自哪个商家的店铺"/></td>
			</tr>
			<tr>
				<th width="160">进入提示语</th>
				<td><input type="text" class="input fl" name="a_name" value="{pigcms{$home_share.a_name}" size="5" placeholder="可不填写" tips="提示粉丝进入的提示语言"/></td>
			</tr>
			<tr>
				<th width="160">进入网址</th>
				<td><input type="text" class="input fl" name="a_href" value="{pigcms{$home_share.a_href}" size="60" placeholder="可不填写" tips="跳转到指定地方的网址"  validate="url:true"/></td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
	
	<script>
		$(function(){
			if($('input[name="open_sub_mchid"]:checked').val()==1){
				$('.sub_mch').show();
			}else{
				$('.sub_mch').hide();
			}
			var sub_mch_id = $('input[name="sub_mch_id"]').val();
			var alipay_auth_code = $('input[name="alipay_auth_code"]').val();
			$('input[name="sub_mch_id"]').change(function(){
				sub_mch_id=  $(this).val();
			})
			
			$('input[name="alipay_auth_code"]').change(function(){
				alipay_auth_code=  $(this).val();
			})
			
			$('input[name="open_sub_mchid"]').click(function(){
				var sub = $(this);
				if(sub.val()==1){
					$('input[name="sub_mch_id"]').val(sub_mch_id)
					$('input[name="alipay_auth_code"]').val(alipay_auth_code)
					$('.sub_mch').show();
				}else{
					$('input[name="sub_mch_id"]').val('')
					$('input[name="alipay_auth_code"]').val('')
					$('.sub_mch').hide();
				}
			});
			
			if($('input[name="score_get_percent"]').val()<0){
				$('input[name="score_get_percent"]').hide();
			}else{
				$('input[name="score_get_percent"]').show();
			}
			
			$('input[name="set_score_percent"]').click(function(){
				var sub = $(this);
				if(sub.val()==1){
					if($('input[name="score_get_percent"]').val()<0){
						$('input[name="score_get_percent"]').val(0);
					}
					$('input[name="score_get_percent"]').show();
				}else{
					$('input[name="score_get_percent"]').hide();
					$('input[name="score_get_percent"]').val(-1);
				}
			});
			
			
		});
	</script>
	
<include file="Public:footer"/>
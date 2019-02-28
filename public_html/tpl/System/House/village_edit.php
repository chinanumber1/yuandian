<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('House/village_edit')}" frame="true" refresh="true">
		<input type="hidden" name="village_id" value="{pigcms{$now_village.village_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="120">小区名称</th>
				<td><input type="text" class="input fl" name="village_name" value="{pigcms{$now_village.village_name}" size="40" placeholder="请输入小区名称" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="120">小区地址</th>
				<td><input type="text" class="input fl" name="village_address" value="{pigcms{$now_village.village_address}" size="40" placeholder="请输入小区地址" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="120">物业公司名称</th>
				<td><input type="text" class="input fl" name="property_name" value="{pigcms{$now_village.property_name}" size="40" placeholder="请输入物业公司名称" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="120">物业联系地址</th>
				<td><input type="text" class="input fl" name="property_address" value="{pigcms{$now_village.property_address}" size="40" placeholder="请输入物业联系地址" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="120">物业联系电话</th>
				<td><input type="text" class="input fl" name="property_phone" value="{pigcms{$now_village.property_phone}" size="20" placeholder="请输入物业联系电话" validate="maxlength:50,required:true" tips="多个号码以空格分开"/></td>
			</tr>
			<tr>
				<th width="120">社区后台管理帐号</th>
				<td><input type="text" class="input fl" name="account" value="{pigcms{$now_village.account}" size="20" placeholder="请输入社区后台管理帐号" validate="maxlength:50,required:true" tips="多个社区帐号一致，将认为是同一家物业公司。进入社区后台会提示进入哪个小区"/></td>
			</tr>
			<tr>
				<th width="120">社区后台管理密码</th>
				<td><input type="text" class="input fl" name="pwd" size="20" placeholder="不修改请勿填写" validate="maxlength:50,minlength:6" tips="不修改请勿填写"/></td>
			</tr>
			<tr>
				<th width="100">社区到期时间</th>
				<td><input type="text" class="input fl" name="expiration_time" size="25" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd HH:mm'})" tips="社区到期时间，到期之后物业与用户均不允许进入社区并关闭该社区，清空为永不过期" readonly="" value="{pigcms{$now_village.expiration_time}"></td>
			</tr>
			<tr>
				<th width="120">社区抽成比例</th>
				<td>
				
					<span class="cb-enable"><label class="cb-enable <if condition="$now_village['percent'] egt 0">selected</if>"><span>设置</span>
					<input type="radio" name="open_own_percent" value="1" <if condition="$now_village['percent'] egt 0">checked="checked"</if>/></label></span>
					<span class="cb-disable " ><label class="cb-disable  <if condition="$now_village['percent'] lt 0">selected</if>"><span>跳过</span>
					<input type="radio" name="open_own_percent" value="0" <if condition="$now_village['percent'] lt 0">checked="checked"</if>/></label></span>
					<em class="notice_tips" tips="设置后将按这个抽成比例抽成"></em>
				
					<input type="text" class="input fl" name="percent" id="percent" value="{pigcms{$now_village.percent}" size="20" <if condition="$now_village['percent'] lt 0">style="display:none"</if> validate="number:true" />
				
				</td>
			</tr>
            <tr>
                <th width="160">是否开启物业管理功能</th>
                <td>
                    <span class="cb-enable"><label class="cb-enable <if condition="$now_village['is_open_estate'] eq 1">selected</if>"><span>是</span>
                    <input type="radio" name="is_open_estate" value="1" <if condition="$now_village['is_open_estate'] eq 1">checked="checked"</if>/></label></span>
                    <span class="cb-disable " ><label class="cb-disable  <if condition="$now_village['is_open_estate'] eq 0">selected</if>"><span>否</span>
                    <input type="radio" name="is_open_estate" value="0" <if condition="$now_village['is_open_estate'] eq 0">checked="checked"</if>/></label></span>
                    <em class="notice_tips" tips="开启，则社区后台左侧功能导航会出现“物业管理”功能，反之，则隐藏该功能"></em>
                </td>
            </tr>
			<if condition="$config.open_sub_mchid eq 1">
			<tr>
				<th width="160">是否开启特约子商功能</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_village['open_sub_mchid'] eq 1">selected</if>"><span>是</span>
					<input type="radio" name="open_sub_mchid" value="1" <if condition="$now_village['open_sub_mchid'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable " ><label class="cb-disable  <if condition="$now_village['open_sub_mchid'] eq 0">selected</if>"><span>否</span>
					<input type="radio" name="open_sub_mchid" value="0" <if condition="$now_village['open_sub_mchid'] eq 0">checked="checked"</if>/></label></span>
					<em class="notice_tips" tips="开启后，该社区不能设置使用自有的微信支付，该社区的支付将按服务商的子社区号支付<br>(开启后请正确配置子社区号、子社区退款、是否允许使用平台余额、是否允许使用平台优惠)"></em>
				</td>
			</tr>
			
			<if condition="$config['buy_sms'] eq 1">
				<tr <php>if($can_recharge==0){echo 'style="display:none"';}</php> >
					<th width="15%">短信条数</th>
					<td width="85%" colspan="3"><div style="height:30px;line-height:24px;">当前短信：{pigcms{$now_village.now_sms_number}（条） &nbsp;&nbsp;&nbsp;&nbsp;<select name="set_sms_type"><option value="1">增加</option><option value="2">减少</option></select>&nbsp;&nbsp;<input type="text" class="input" name="set_sms_number" size="10" validate="number:true," tips="此处填写增加或减少的条数，不是将短信条数变为此处填写的值"/>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('House/sms_order',array('village_id'=>$now_village['village_id'],'frame_show'=>true))}','查看短信变动记录',800,600,true,false,false,false,'detail',true);">记录</a>
					<input type="hidden" name="now_sms_number" value="{pigcms{$now_village.now_sms_number}">
					</div></td>
				</tr>
			</if>

			<tr class="sub_mch">
				<th width="160">微信子社区号</th>
				<td><input type="text" class="input fl" name="sub_mch_id" size="25" value="{pigcms{$now_village['sub_mch_id']}" placeholder="子社区号" validate="" />
					<em class="notice_tips" tips="开启子社区支付后必须要填写正确的子社区号，子社区号可以在微信子社区平台查看"></em>
				</td>
			</tr>
			
			<!--<tr class="sub_mch" style="display:none">
				<th width="160">是否开启子社区支付退款</th>
				<td>
					<span class="cb-enable"><label class="cb-enable <if condition="$now_village['sub_mch_refund'] eq 1">selected</if>"><span>开启</span><input type="radio" name="sub_mch_refund" value="1" <if condition="$now_village['sub_mch_refund'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_village['sub_mch_refund'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="sub_mch_refund" value="0" <if condition="$now_village['sub_mch_refund'] eq 0">checked="checked"</if>/></label></span>
					<font color="red">请确认子社区是否授权退款权限,如果没有请不要开启</font>
					<em class="notice_tips" tips="开启子社区支付退款，需要子社区申请退款权限给微信服务商，如果未得到授权，退款将失败;<br>关闭后用户将不能退款，当开启子社区支付功能时才有效"></em>
				</td>
			</tr>-->
			
			
			<tr class="sub_mch">
				<th width="160">是否允许适用平台余额</th>
				<td>
					<span class="cb-enable"><label class="cb-enable  <if condition="$now_village['sub_mch_sys_pay'] eq 1">selected</if>"><span>开启</span><input type="radio" name="sub_mch_sys_pay" value="1" <if condition="$now_village['sub_mch_sys_pay'] eq 1">checked="checked"</if> /></label></span>
					<span class="cb-disable"><label class="cb-disable  <if condition="$now_village['sub_mch_sys_pay'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="sub_mch_sys_pay" value="0" <if condition="$now_village['sub_mch_sys_pay'] eq 0">checked="checked"</if>/></label></span>
					<em class="notice_tips" tips="开启后用户通过子社区支付的同时也可以使用平台余额，且平台余额部分参与平台跟社区对账,必须开启特约子社区功能;<br>关闭后，用户不能使用平台余额，当开启子社区支付功能时才有效"></em>
				</td>
			</tr>
			<tr class="sub_mch">
				<th width="160">是否允许适用平台优惠(积分)</th>
				<td>
					<span class="cb-enable"><label class="cb-enable  <if condition="$now_village['sub_mch_discount'] eq 1">selected</if>"><span>开启</span><input type="radio" name="sub_mch_discount" value="1" <if condition="$now_village['sub_mch_discount'] eq 1">checked="checked"</if>  /></label></span>
					<span class="cb-disable"><label class="cb-disable  <if condition="$now_village['sub_mch_discount'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="sub_mch_discount" value="0" <if condition="$now_village['sub_mch_discount'] eq 0">checked="checked"</if> /></label></span>
					<em class="notice_tips" tips="开启后,用户通过子社区支付的同时也可以使用平台优惠，且优惠部分参与平台跟社区对账,必须开启特约子社区功能;<br>关闭后用户不能使用平台优惠券，平台积分抵扣，当开启子社区支付功能时才有效"></em>
				</td>
			</tr>
			
				<if condition="$config.open_mer_owe_money">
				<tr class="sub_mch">
					<th width="160">是否允许社区余额欠费</th>
					<td>
					<input type="text" class="input fl" name="village_owe_money" size="25" placeholder="子社区欠钱额度" value="{pigcms{$now_village.village_owe_money|floatval}" validate="" />
					<em class="notice_tips" tips="0 表示不允许欠款，一旦社区余额不足够平台抽成，平台将关闭社区，1000 表示平台允许社区欠平台1000元，超过1000后平台将关闭社区"></em>
					</td>
					
				</tr>
				
				</if>
			</if>
			
			<tr>
				<th width="120">{pigcms{$config.house_market_name}快店编号</th>
				<td><input type="text" class="input fl" name="shop_id" value="{pigcms{$now_village.shop_id}" size="5" validate="number:true" tips="{pigcms{$config.house_market_name}单独绑定的快店编号，0为不绑定"/></td>
			</tr>
            
            
            <!-- wangdong star-->
            <tr>
				<!-- <th width="120">物业缴费获得积分</th> -->
				<th width="120">缴费获得积分</th>
				<td>
                <div class="radio-line">
                <input type="radio" id="village_pay_integral_1" name="village_pay_integral" class="regular-radio" value="1" <if condition="$now_village['village_pay_integral']==1">checked</if>/><label for="village_pay_integral_1" class="label-class"><div></div><span>开启</span></label>
                <input type="radio" id="village_pay_integral_2" name="village_pay_integral" class="regular-radio " value="0" <if condition="$now_village['village_pay_integral']==0">checked</if>/><label for="village_pay_integral_2" class="label-class"><div></div><span>继承平台</span></label>
                <input type="radio" id="village_pay_integral_3" name="village_pay_integral" class="regular-radio" value="-1" <if condition="$now_village['village_pay_integral']==-1">checked</if> /><label for="village_pay_integral_3" class="label-class"><div></div><span>关闭</span></label>
                </div>
                &nbsp; <em tips="(1)开启后，用户缴费可以获得积分&lt;br/&gt;(2)使用积分缴纳的部分不会获得积分" class="notice_tips"></em>
                    </td>
			</tr>
            
            
            <tr class="class-village-pay-integral" <if condition="$now_village['village_pay_integral']!=1">style="display:none"</if>>
				<th width="120">欠缴物业费获得积分</th>
				<td>
                <span class="cb-enable"><label class="cb-enable <if condition="$now_village['village_owe_pay_integral'] eq 1">selected</if>"><span>开启</span><input type="radio" name="village_owe_pay_integral" value="1" <if condition="$now_village['village_owe_pay_integral'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_village['village_owe_pay_integral'] eq 0">selected</if>"><span>关闭</span>
					<input type="radio" name="village_owe_pay_integral" value="0" <if condition="$now_village['village_owe_pay_integral'] eq 0">checked="checked"</if>/></label></span>
                    <em tips="开启后，用户欠缴物业费缴费时会获得积分，只做用户前台在线缴费时不给获得积分" class="notice_tips"></em>
                </td>
			</tr>

            <tr class="class-village-owe-pay-integral" <if condition="$now_village['village_pay_integral'] neq 1">style="display:none"</if>>
                <th width="120">消费1元获得的积分</th>
                <td>
                     <span class="cb-enable">
                        <label class="cb-enable  <if condition="$now_village['open_score_get_percent'] eq 0">selected</if>">
                            <span>积分</span>
                            <input type="radio" name="open_score_get_percent" value="0" <if condition="$now_village['open_score_get_percent'] eq 0">checked="checked"</if>/>
                        </label>
                     </span>
                     <span class="cb-disable" style="margin-right: 5px;">
                        <label class="cb-disable  <if condition="$now_village['open_score_get_percent'] eq 1">selected</if>">
                            <span>积分百分比</span>
                            <input type="radio" name="open_score_get_percent" value="1" <if condition="$now_village['open_score_get_percent'] eq 1">checked="checked"</if>"/>
                        </label>
                    </span>
                    <input type="text" class="input fl" name="user_score_get" value="{pigcms{$now_village.user_score_get}" size="10" <if condition="$now_village['open_score_get_percent'] neq 0">style="display:none"</if>/><div class="fl user_score_get"  <if condition="$now_village['open_score_get_percent'] neq 0">style="display:non;"</if>> <em tips="消费一元获得的积分" class="notice_tips"></em></div>
                    <input type="text" class="input fl" name="score_get_percent" value="{pigcms{$now_village.score_get_percent}" size="10" <if condition="$now_village['open_score_get_percent'] neq 1">style="display:none"</if>/><div class="fl score_get_percent"  <if condition="$now_village['open_score_get_percent'] neq 1">style="display:none;"</if>> <em tips="消费一元获得积分百分比" class="notice_tips"></em></div>
                </td>
            </tr>

            <tr>
				<th width="120">缴费使用积分</th>
				<td>
               <div class="radio-line">
                <input type="radio" id="village_pay_use_integral_1" name="village_pay_use_integral" class="regular-radio" value="1" <if condition="$now_village['village_pay_use_integral']==1">checked</if>/><label for="village_pay_use_integral_1" class="label-class"><div></div><span>开启</span></label>
                <input type="radio" id="village_pay_use_integral_2" name="village_pay_use_integral" class="regular-radio " value="0" <if condition="$now_village['village_pay_use_integral']==0">checked</if>/><label for="village_pay_use_integral_2" class="label-class"><div></div><span>继承平台</span></label>
                <input type="radio" id="village_pay_use_integral_3" name="village_pay_use_integral" class="regular-radio" value="-1" <if condition="$now_village['village_pay_use_integral']==-1">checked</if> /><label for="village_pay_use_integral_3" class="label-class"><div></div><span>关闭</span></label>
                </div>
               &nbsp; <em tips="开启后，用户缴费时可以使用积分" class="notice_tips"></em>
                    </td>
			</tr>
            <tr class="class-village-pay-use-integral" <if condition="$now_village['village_pay_use_integral']!=1">style="display:none"</if>>
				<th width="120">欠缴物业费使用积分</th>
				<td><span class="cb-enable"><label class="cb-enable <if condition="$now_village['village_pay_owe_use_integral'] eq 1">selected</if>"><span>开启</span><input type="radio" name="village_pay_owe_use_integral" value="1" <if condition="$now_village['village_pay_owe_use_integral'] eq 1">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_village['village_pay_owe_use_integral'] eq 0">selected</if>"><span>关闭</span><input type="radio" name="village_pay_owe_use_integral" value="0" <if condition="$now_village['village_pay_owe_use_integral'] eq 0">checked="checked"</if>/></label></span>
                    <em tips="开启后，用户在缴纳欠缴的物业费时可以使用积分，只做用户前台在线缴费时不给使用积分" class="notice_tips"></em></td>
			</tr>
            <tr class="class-village-pay-use-integral" <if condition="$now_village['village_pay_use_integral']!=1">style="display:none"</if>>
				<th width="120">可使用最大积分</th>
				<td><input type="text" class="input fl" name="use_max_integral_num" value="{pigcms{$now_village.use_max_integral_num}" size="10"/>&nbsp;<span style="line-height:32px;">积分</span> <em tips="缴费最多使用积分数量，如果设置了 缴费可使用积分占总金额百分比功能 则此功能设置无效" class="notice_tips"></em></td>
			</tr>
            
           <tr class="class-village-pay-use-integral" <if condition="$now_village['village_pay_use_integral']!=1">style="display:none"</if>>
				<th width="120">积分占总金额百分比</th>
				<td><input type="text" class="input fl" name="use_max_integral_percentage" value="{pigcms{$now_village.use_max_integral_percentage}" size="10"/>&nbsp;<span style="line-height:32px;">%</span> <em tips="缴费最多使用积分占总金额的百分比，如果设置了此项 则缴费可使用最大积分功能 无效" class="notice_tips"></em></td>
			</tr>
            
            <!---  wangdong end -->
            
            
			<tr>
				<th width="120">状态</th>
				<td class="radio_box">
					<span class="cb-enable"><label class="cb-enable <if condition="$now_village['status'] eq 1 || $now_village['status'] eq 0">selected</if>"><span>正常</span><input type="radio" name="status" value="<if condition="$now_village['status'] eq 0">0<else/>1</if>" <if condition="$now_village['status'] eq 1 || $now_village['status'] eq 0">checked="checked"</if>/></label></span>
					<span class="cb-disable"><label class="cb-disable <if condition="$now_village['status'] eq 2">selected</if>"><span>禁止</span><input type="radio" name="status" value="2" <if condition="$now_village['status'] eq 2">checked="checked"</if>/></label></span>
				</td>
			</tr>
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
function addLink(domid,iskeyword){
	art.dialog.data('domid', domid);
	art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
}

$(":radio[name='village_pay_integral']").click(function(){
	//document.write($(this).val());	
	var pay_integral_val = $(this).val();
	if(pay_integral_val!=1){
		$(".class-village-pay-integral").hide(200);
        $(".class-village-owe-pay-integral").hide(200);
	}else{
		$(".class-village-pay-integral").show(200);
        $(".class-village-owe-pay-integral").show(200);
	}
});


$(":radio[name='open_score_get_percent']").click(function(){
    var open_score_get_percent_val = $(this).val();
    if(open_score_get_percent_val!=1){
        $(".user_score_get").show(200);
        $(".score_get_percent").hide(200);
    }else{
        $(".user_score_get").hide(200);
        $(".score_get_percent").show(200);
    }
});


$(":radio[name='village_pay_use_integral']").click(function(){
	var pay_use_integral_val = $(this).val();
	if(pay_use_integral_val!=1){
		$(".class-village-pay-use-integral").hide(200);	
	}else{
		$(".class-village-pay-use-integral").show(200);	
	}	
});

var percent = $('#percent');
$('input[name="open_own_percent"]').click(function(){
	if($(this).val()==1){
		percent.show();
		if(percent.val()<0){
			percent.val('');
		}
		
	}else{
		percent.hide();
		percent.attr('value',-1);
		
	}
});

if($('input[name="open_sub_mchid"]:checked').val()==1){
	$('.sub_mch').show();
}else{
	$('.sub_mch').hide();
}
$('input[name="open_sub_mchid"]').click(function(){
	var sub = $(this);
	if(sub.val()==1){
		$('.sub_mch').show();
	}else{
		$('.sub_mch').hide();
	}
});

</script>
<include file="Public:footer"/>
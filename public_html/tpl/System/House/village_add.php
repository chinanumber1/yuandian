<include file="Public:header"/>
	<form id="myform" method="post" action="{pigcms{:U('House/village_add')}" frame="true" refresh="true">
		<input type="hidden" name="cat_id" value="{pigcms{$now_category.cat_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="100">小区名称</th>
				<td><input type="text" class="input fl" name="village_name" size="30" placeholder="请输入小区名称" validate="maxlength:20,required:true"/></td>
			</tr>
			<tr>
				<th width="100">小区地址</th>
				<td><input type="text" class="input fl" name="village_address" size="30" placeholder="请输入小区地址" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="100">物业公司名称</th>
				<td><input type="text" class="input fl" name="property_name" size="30" placeholder="请输入物业公司名称" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="100">物业联系地址</th>
				<td><input type="text" class="input fl" name="property_address" size="30" placeholder="请输入物业联系地址" validate="maxlength:50,required:true"/></td>
			</tr>
			<tr>
				<th width="100">物业联系电话</th>
				<td><input type="text" class="input fl" name="property_phone" size="20" placeholder="请输入物业联系电话" validate="maxlength:50,required:true" tips="多个号码以空格分开"/></td>
			</tr>
			<tr>
				<th width="100">社区后台管理帐号</th>
				<td><input type="text" class="input fl" name="account" size="20" placeholder="请输入社区后台管理帐号" validate="maxlength:50,required:true" tips="多个社区帐号一致，将认为是同一家物业公司。进入社区后台会提示进入哪个小区"/></td>
			</tr>
			<tr>
				<th width="100">社区后台管理密码</th>
				<td><input type="text" class="input fl" name="pwd" size="20" placeholder="请输入社区后台管理密码" validate="maxlength:50,required:true,minlength:6"/></td>
			</tr>
			<tr>
				<th width="100">{pigcms{$config.house_market_name}快店编号</th>
				<td><input type="text" class="input fl" name="shop_id" value="" size="5" validate="number:true" tips="{pigcms{$config.house_market_name}单独绑定的快店编号，0为不绑定"/></td>
			</tr>
			<tr>
				<th width="100">社区到期时间</th>
				<td><input type="text" class="input fl" name="expiration_time" size="25" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd HH:mm'})" tips="社区到期时间，到期之后不允许进入社区平台并关闭该社区，清空为永不过期" readonly=""></td>
			</tr>
            
            <!-- wangdong star-->
            <!--<tr>
				<th width="120">物业费生成积分</th>
				<td><span class="cb-enable cb-enable-integral"><label class="cb-enable"><span>开启</span><input type="radio" name="village_pay_integral" value="1"/></label></span>
					<span class="cb-disable cb-disable-integral"><label class="cb-disable selected"><span>关闭</span><input type="radio" name="village_pay_integral" value="0" checked="checked"/></label></span>
                    <em tips="(1)开启后，用户缴物业费可以生成积分&lt;br/&gt;(2)使用积分缴纳的部分不会产生积分" class="notice_tips"></em>
                    </td>
			</tr>
            <tr class="class-village-pay-integral" style="display:none">
				<th width="120">欠缴物业费生成积分</th>
				<td><span class="cb-enable"><label class="cb-enable"><span>开启</span><input type="radio" name="village_owe_pay_integral" value="1" /></label></span>
					<span class="cb-disable"><label class="cb-disable selected"><span>关闭</span><input type="radio" name="village_owe_pay_integral" value="0" checked="checked"/></label></span>
                    <em tips="开启后，用户欠缴物业费缴费时会产生积分" class="notice_tips"></em>
                    </td>
			</tr>
            <tr>
				<th width="120">物业费使用积分</th>
				<td><span class="cb-enable cb-enable-use-integral"><label class="cb-enable"><span>开启</span><input type="radio" name="village_pay_use_integral" value="1" checked="checked"/></label></span>
					<span class="cb-disable cb-disable-use-integral"><label class="cb-disable selected"><span>关闭</span><input type="radio" name="village_pay_use_integral" value="0" checked="checked"/></label></span>
                    <em tips="开启后，用户缴物业费时可以使用积分" class="notice_tips"></em>
                    </td>
			</tr>
            <tr class="class-village-pay-use-integral" style="display:none">
				<th width="120">物业费使用积分比</th>
				<td><span class="cb-enable"><label class="cb-enable"><span>开启</span><input type="radio" name="village_pay_owe_use_integral" value="1"/></label></span>
					<span class="cb-disable"><label class="cb-disable selected"><span>关闭</span><input type="radio" name="village_pay_owe_use_integral" value="0" checked="checked"/></label></span>
                    <em tips="开启后，用户在缴纳欠缴的物业费时可以使用积分" class="notice_tips"></em></td>
			</tr>
            <tr class="class-village-pay-use-integral" style="display:none">
				<th width="120">可使用最大积分</th>
				<td><input type="text" class="input fl" name="use_max_integral_num" value="0" size="10"/>&nbsp;<span style="line-height:32px;">积分</span> <em tips="缴费最多使用积分数量，如果设置了 缴费可使用积分百分比 则侧设置无效" class="notice_tips"></em></td>
			</tr>
            
           <tr class="class-village-pay-use-integral" style="display:none">
				<th width="120">积分占总金额百分比</th>
				<td><input type="text" class="input fl" name="use_max_integral_percentage" value="0" size="10"/>&nbsp;<span style="line-height:32px;">%</span> <em tips="缴费最多使用积分占总金额的百分比，如果设置了此项 缴费可使用积分设置则无效设置无效" class="notice_tips"></em></td>
			</tr>
            -->
            <!---  wangdong end -->
            
			
		</table>
		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>
	</form>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
$(".cb-enable-integral").on('click',function(){
	$(".class-village-pay-integral").show(300);
});

$(".cb-disable-integral").on('click',function(){
	$(".class-village-pay-integral").hide(200);
});

$(".cb-enable-use-integral").on('click',function(){
	$(".class-village-pay-use-integral").show(300);
});

$(".cb-disable-use-integral").on('click',function(){
	$(".class-village-pay-use-integral").hide(200);
});


function addLink(domid,iskeyword){
	art.dialog.data('domid', domid);
	art.dialog.open('?g=Admin&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:800,height:500,yesText:'关闭',background: '#000',opacity: 0.45});
}
</script>
<include file="Public:footer"/>
<!DOCTYPE html>
<html>
<head id="Head1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" />
<meta name="keywords" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<link type="text/css" href="/images/icon.ico" rel="shortcut icon" />

<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
<link type="text/css" href="{pigcms{$static_path}css/property_base.css" rel="stylesheet" />
<link type="text/css" href="{pigcms{$static_path}css/property_page.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
<title>{pigcms{$now_village.village_name}</title></head>
<body id="body">
 <header class="pageSliderHide"><div onclick="javascript :history.back(-1)" id="backBtn"></div>{pigcms{$pay_name}</header>
<script type="text/javascript" language="javascript">
var ajax_get_presented_property_month_url = "{pigcms{:U('ajax_get_presented_property_month')}";
var ajax_diy_get_presented_property_month_url = "{pigcms{:U('ajax_diy_get_presented_property_month')}";
var village_id = "{pigcms{$_GET['village_id']}";
</script>
<style type="text/css">
.minus,.plus{ padding:0 .2rem;border: none; color:#ff6600}
</style>
<div class="czCon">
	<div class="tcTime clearfix">
		<p class="fl cp">
			业主信息：
		</p>
		<p class="dq">
			房屋面积：<span class="green">{pigcms{$now_user_info['housesize']}㎡</span>
			&nbsp;&nbsp;
			物业单价：
			<span class="green">
			<if condition = '$now_user_info["property_fee"] neq "0.00"'>
				{pigcms{$now_user_info["property_fee"]}
			<else />
				{pigcms{$now_village["property_price"]}
			</if>
			元/平方米/月
			</span>
			
			&nbsp;&nbsp;
			类型：
			<if condition = '$now_user_info["floor_type_name"]'>
				<span class="green">{pigcms{$now_user_info["floor_type_name"]}</span>
			<else />
				暂无
			</if>
			
		</p>
		<p class="dq">
			<if condition='$now_user_info["property_time_str"]'>物业费服务时间：{pigcms{$now_user_info["property_time_str"]}</if>
		</p>
	</div>


	<p class="xzp">请<span>选择</span>您要缴纳的物业周期</p>
	<div class="kdCon">
		<ul id="package" class="clearfix scUl">
			<volist name='property_list["list"]' id="property">
				<li id="p{pigcms{$property['id']}">
				{pigcms{$property['property_month_num']}个月
					<p class="dh"></p>
				</li>
			</volist>
			<if condition='$now_village["has_property_pay"]'>
				<li id="p0">
					<button type="button" class="btn btn-weak minus">-</button><input type="text" style="width:.8rem;height:28px; border:none;text-align:center; font-size:12px" id="diy_propertyt_month_num" readonly="readonly" /><button type="button" class="btn btn-weak plus">+</button>
				</li>
			</if>
		</ul>
		
		
		<div class="Give Clearfix">
			<p id="gift" class="zs1 fl clearfix">
				<span class="fl sp1">送</span>
				<span id="addmonth" class="fl sp2">0个月</span>
			</p>
			
		</div>
		<style>
			.use-integral { padding:10px auto; line-height:50px; height:50px; color:#999; display:block; float:left; width:100%; display:none}
			.use-integral-span-info { float:left;}
			.use-integral-span-btn { float:right}
			.pigcms-text-f60 { color:#F60}
			.pigcms-text-left { float:left;}
			.tcData { line-height:40px; height:40px;float:left; width:100%;}
			.tcData .gmShu p { float:right; height:40px; text-align:right; line-height:40px; width:100%;} 
			.czCon .kdCon .tcData .gmShu p.pp3 { margin-left:0px;}
			.czCon .kdCon .order {display:block; float:left; width:100%; margin-top:20px; text-align:center}
			.czCon .kdCon .order img { width:43%;}
		</style>
        <style>
.chk {
    display: none;
}
.chk + label {
    float: right;
    background-color: #ccc;
    padding: 9px;
    border-radius: 50px;
    display: inline-block;
    position: relative;
    -webkit-transition: all 0.1s ease-in;
    transition: all 0.1s ease-in;
    width: 25px;
    height: 2px;
    margin-bottom: 3px;
	margin-top:17px;
}
.chk + label::before {
    content: ' ';
    position: absolute;
    background: white;
    top: 0px;
    left: 0px;
    z-index: 999;
    width: 20px;
    -webkit-transition: all 0.1s ease-in;
    transition: all 0.1s ease-in;
    height: 20px;
    border-radius: 100px;
    box-shadow: 0 3px 1px rgba(0,0,0,0.05), 0 0px 1px rgba(0,0,0,0.3);
}
.chk:checked + label::before {
    content: ' ';
    position: absolute;
    left: 24px;
    border-radius: 100px;
}
.chk + label::after {
    content: ' ';
    position: absolute;
    top: 0;
    -webkit-transition: box-shadow 0.1s ease-in;
    transition: box-shadow 0.1s ease-in;
    left: 0;
    width: 100%;
    height: 100%;
    border-radius: 100px;
    box-shadow: inset 0 0 0 0 #eee, 0 0 1px rgba(0,0,0,0.4);
}
.chk:checked + label::after {
    content: ' ';
    font-size: 1.5em;
    position: absolute;
    background: #f60;
    box-shadow: 0 0 1px #f60;
}
</style> 
        
        <div class="use-integrals" id="integralSelects" style="display:none">
        	<span class="use-integral-span-info">本单可使用积分 <span class="pigcms-text-f60 pigcms_integral_num">0</span> 个，可抵扣金额 <span class="pigcms-text-f60">￥</span><span class="pigcms-text-f60 pigcms_integraltomoney_num">0</span> 元</span><span class="use-integral-span-btn"><input type="checkbox" id="checkbox_c1" class="chk" ><label for="checkbox_c1" class="onbtnintegral"></label> </span>
        </div>
		<div class="tcData" id="tcSelect">
			<div class="gmShu">
				<p class="pp3"><span class="pigcms-text-left">缴费总额</span><b>￥<span id="totalmoney" data-money="0.00">0.00</span></b> </p>
			</div>
		</div>
		<p class="order" id="confirm"><img src="{pigcms{$static_path}images/ljOrder.png"/></p>
		
		<p class="ycp">计算公式：物业总价 = 房屋面积 * 物业单价 * 购买月份</p>
		<p class="red">注：单位：月。<if condition='$now_village["has_property_pay"]'>自定义最大36个月</if></p>
	</div>
</div>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
<script src="{pigcms{$static_path}js/BuySet.js" type="text/javascript"></script>

<script type="text/javascript">
	$(".onbtnintegral").on('click' , function(){
		if($('#checkbox_c1').is(':checked')) {
			var t_money = $("#totalmoney").text();	
			var u_money = $(".pigcms_integraltomoney_num").text();
			$("#totalmoney").text((parseFloat(t_money)+parseFloat(u_money)).toFixed(2));
		}else{
			var t_money = $("#totalmoney").text();	
			var u_money = $(".pigcms_integraltomoney_num").text();
			$("#totalmoney").text((parseFloat(t_money)-parseFloat(u_money)).toFixed(2));
		}
	});

</script>

</body>
</html>
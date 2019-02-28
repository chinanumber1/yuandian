<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/datePicker.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/mobiscroll_min.css" media="all">
<script type="text/javascript" src="{pigcms{$static_path}shop/js/jquery1.8.3.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}shop/js/dialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}shop/js/mobiscroll_min.js"></script>

<title>{pigcms{$store['name']|default="快店"}</title>
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta content="telephone=no, address=no" name="format-detection">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="format-detection" content="telephone=no"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/main.css" media="all">
<style>
	ul, menu, dir {
		    display: block;
		    list-style-type: disc;
		    -webkit-margin-before: 0em;
		    -webkit-margin-after: 0em;
		    -webkit-margin-start: 0px;
		    -webkit-margin-end: 0px;
		    -webkit-padding-start: 0px;
		}
		.mask {
			position: fixed;
			left: 0;
		    top: 0;
		    width: 100%;
		    height: 100%;
		    background-color: rgba(0, 0, 0, .2);
		    z-index: 100;
		}
		.address_tanceng{
			position: fixed;
			bottom: 0;
			width: 100%;
			height: 300px;
			background: #fff;
			z-index: 10000;
		}
		ul li{
			list-style:none;
			margin:0;padding:0; 
			/*border-bottom: 1px solid #f1f1f1;*/
		}
		.address_tanceng>ul{
			width: 100%;
			height: 300px;
			text-align: center;
			overflow-y: scroll;
		}
		.address_tanceng>ul>li{
			padding:10px; 
		}
		.hidden{
			display: none;
		}
#dingcai_adress_info{
border-top: 1px solid #ddd8ce;
border-bottom: 1px solid #ddd8ce;
position: relative;
}
#dingcai_adress_info:after{
position: absolute;
right: 8px;
top: 50%;
display: block;
content: '';
width: 13px;
height: 13px;
border-left: 3px solid #999;
border-bottom: 3px solid #999;
-webkit-transform: translateY(-50%) scaleY(0.7) rotateZ(-135deg);
-moz-transform: translateY(-50%) scaleY(0.7) rotateZ(-135deg);
-ms-transform: translateY(-50%) scaleY(0.7) rotateZ(-135deg);
}


#enter_im_div {
  bottom: 121px;
  z-index: 11;
  display: none;
  position: fixed;
  width: 100%;
  max-width: 640px;
  height: 1px;
}
#enter_im {
  width: 94px;
  margin-left: 110px;
  position: relative;
  left: -100px;
  display: block;
}
a {
  color: #323232;
  outline-style: none;
  text-decoration: none;
}
#to_user_list {
  height: 30px;
  padding: 7px 6px 8px 8px;
  background-color: #00bc06;
  border-radius: 25px;
  /* box-shadow: 0 0 2px 0 rgba(0,0,0,.4); */
}
#to_user_list_icon_div {
  width: 20px;
  height: 16px;
  background-color: #fff;
  border-radius: 10px;
}

.rel {
  position: relative;
}
.left {
  float: left;
}
.to_user_list_icon_em_a {
  left: 4px;
}
#to_user_list_icon_em_num {
  background-color: #f00;
}
#to_user_list_icon_em_num {
  width: 14px;
  height: 14px;
  border-radius: 7px;
  text-align: center;
  font-size: 12px;
  line-height: 14px;
  color: #fff;
  top: -14px;
  left: 68px;
}
.hide {
  display: none;
}
.abs {
  position: absolute;
}
.to_user_list_icon_em_a, .to_user_list_icon_em_b, .to_user_list_icon_em_c {
  width: 2px;
  height: 2px;
  border-radius: 1px;
  top: 7px;
  background-color: #00ba0a;
}
.to_user_list_icon_em_a {
  left: 4px;
}
.to_user_list_icon_em_b {
  left: 9px;
}
.to_user_list_icon_em_c {
  right: 4px;
}
.to_user_list_icon_em_d {
  width: 0;
  height: 0;
  border-style: solid;
  border-width: 4px;
  top: 14px;
  left: 6px;
  border-color: #fff transparent transparent transparent;
}
#to_user_list_txt {
  color: #fff;
  font-size: 13px;
  line-height: 16px;
  padding: 1px 3px 0 5px;
}
.post_package {
    background-color: #4A96D4;
    border-color: #A5DE37;
}
.btn_express {
    color: #fff;
    font-weight: 300;
    font-size: 16px;
    text-decoration: none;
    text-align: center;
    line-height: 34px;
    padding: 0px 15px;
    margin: 0;
    display: inline-block;
    cursor: pointer;
    border: none;
    box-sizing: border-box;
    transition-property: all;
    transition-duration: 0.3s;
    border-radius: 4px;
}

.pick_in_store_click {
    background-color: #fff;
    color: #000;
    border: 1px solid #4A96D4;
}
.post_package_click {
    background-color: #fff;
    color: #000;
    border: 1px solid #4A96D4;
}
.pick_in_store {
    background-color: #4A96D4;
    border-color: #A5DE37;
}
/*加载层*/
.motifyShade{
	display: none;
	position: fixed;
	top: 0;
	left: 0;
	bottom:0;
	padding: 0;
	z-index: 998;
	width: 100%;
}
.motify {
	display: none;
	position: fixed;
	top: 35%;
	left: 50%;
	width: 260px;
	padding: 0;
	margin: 0 0 0 -130px;
	z-index: 999;
	background: rgba(0, 0, 0, 0.8);
	color: #fff;
	font-size: 14px;
	line-height: 1.5em;
	border-radius: 6px;
	-webkit-box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
	box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.2);
}
.motify .motify-inner {
	padding: 10px 10px;
	text-align: center;
	word-wrap: break-word;
}
</style>
</head>
<script type="text/javascript" src="{pigcms{$static_path}shop/js/scroller.js"></script>
<body onselectstart="return true;" ondragstart="return false;">
<div class="container">
    <if condition="$onlybuy">
        <form name="cart_confirm_form" action="{pigcms{:U('Mall/save_order',array('store_id'=> $store['store_id'], 'mer_id' => $store['mer_id'], 'village_id'=>$village_id,'onlybuy'=>$onlybuy))}" method="post">
        <else />
        <form name="cart_confirm_form" action="{pigcms{:U('Mall/save_order',array('store_id'=> $store['store_id'], 'mer_id' => $store['mer_id'], 'village_id'=>$village_id))}" method="post">
    </if>
	<section class="menu_wrap pay_wrap">
		<ul class="box">
			<li>
				<a class="">配送方式：</a>&nbsp;&nbsp;
				<if condition="$delivery_type neq 2">
				<a class="btn_express <if condition="$pick_addr_id">pick_in_store_click<else />pick_in_store</if>" id="post_package">配送</a>&nbsp;&nbsp;
				</if>
				<if condition="in_array($delivery_type, array(2, 3, 4))">
				<a class="btn_express <if condition="$pick_addr_id">pick_in_store<elseif condition="$delivery_type neq 2" />pick_in_store_click<else />pick_in_store</if>" id="pick_in_store">自提</a>
				</if>
			</li>
			<if condition="$delivery_type neq 2">
			<li id="li_delivery" <if condition="$pick_addr_id">style="display:none"</if>>
				<a href="{pigcms{:U('My/adress',array('buy_type' => 'mall', 'store_id'=>$store['store_id'], 'village_id'=>$village_id, 'mer_id' => $store['mer_id'], 'current_id'=>$user_adress['adress_id'],'params'=>$_GET['params']))}">
					<strong>
						<span id="showAddres"><if condition="$user_adress['adress_id']">{pigcms{$user_adress['province_txt']} {pigcms{$user_adress['city_txt']} {pigcms{$user_adress['area_txt']} {pigcms{$user_adress['adress']} {pigcms{$user_adress['detail']}<else/>请点击添加送货地址</if></span><br>
						<span id="showName">{pigcms{$user_adress['name']}</span>
						<span id="showTel">{pigcms{$user_adress['phone']}</span>
					</strong>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
			</if>
			<if condition="in_array($delivery_type, array(2, 3, 4))">
			<li id="li_pick" <if condition="$delivery_type neq 2 AND empty($pick_addr_id)">style="display:none"</if>>
				<a href="{pigcms{:U('My/pick_address',array('buy_type' => 'mall', 'store_id'=>$store['store_id'], 'village_id' => $village_id, 'mer_id' => $store['mer_id'],'pick_addr_id' => $pick_address['pick_addr_id']))}">
					<strong>
						<span id="showAddres">地址：{pigcms{$pick_address['name']}</span><br>
						<span id="showName">电话：{pigcms{$pick_address['phone']}</span><br>
						<span id="showTel">省市区：{pigcms{$pick_address['area_info']['province']} {pigcms{$pick_address['area_info']['city']} {pigcms{$pick_address['area_info']['area']}</span>
					</strong>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
			</if>
		</ul>
		<ul class="box pay_box">
			<li>
				<a href="javascript:void(0);" id="remarkBtn">
					<strong>订单备注</strong>
					<span id="remarkTxt">点击添加订单备注</span>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
		</ul>
		<ul class="box " <if condition="$store['is_invoice'] AND $store['invoice_price'] elt $price">style="display:block"<else/>style="display:none"</if>>
			<li>
				<a href="javascript:void(0);" id="invoiceBtn">
					<strong id="invoiceTxt">点击添加发票抬头</strong>
					<span ></span>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
		</ul>
		<if condition="$cue_field">
			<ul class="box pay_box">
				<volist name="cue_field" id="vo">
					<if condition="$vo['type'] eq 1">
					<li>
						<a href="javascript:void(0);" id="cue_field_btn_{pigcms{$key}">
							<strong>{pigcms{$vo.name}</strong>
							<span id="cue_field_{pigcms{$key}_txt">点击填写{pigcms{$vo.name}</span>
							<div><i class="ico_arrow"></i></div>
						</a>
					</li>
					<elseif condition="$vo['type'] eq 2" />
					<li class="cue_field">
                        <a href="javascript:void(0);">
						<strong>{pigcms{$vo.name}</strong>
                        <span id="cue_field_txt_{pigcms{$key}">请选择</span>
                        <div><i class="ico_arrow"></i></div>
                        </a>
						<php> $ii = $key;</php>
						<input type="hidden" name="cue_field[{pigcms{$key}][title]" value="{pigcms{$vo.name}" />
						<input type="hidden" name="cue_field[{pigcms{$key}][txt]" value="" id="cue_field_{pigcms{$key}_head" class="cue_field_txt_{pigcms{$key}"/>
                        
                        <div class="mask hidden" data-index="{pigcms{$key}">
                            <div class="address_tanceng">
                                <ul>
                                <php>if (!$vo['iswrite']) {</php>
                                <li data-s="0">请选择</li>
                                <php>}</php>
                                <volist name="vo['use_field']" id="radio">
                                    <li>{pigcms{$radio}</li>
                                </volist>
                                </ul>
                            </div>
                        </div>
					</li>
					<!-- <ul class="box pay_box markbox">
						<li class="show_address">
							<a href="javascript:void(0);">
								<strong>地方</strong>
								<span id="address_text">点击添加订单备注</span>
								<div><i class="ico_arrow"></i></div>
							</a>
						</li>
					</ul> -->
					</if>
				</volist>
			</ul>
		</if>
		<if condition="!empty($goods)">
		<ul class="menu_list order_list" id="orderList">
		<volist name="goods" id="ditem">
		<li>
			<div>
			<if condition="!empty($ditem['image'])">
				<img src="{pigcms{$ditem['image']}" alt="">
			</if>
			</div>
			<div>
				<h3>{pigcms{$ditem['name']}</h3>
				<div>
					<div>
						<span style="color:#999">{pigcms{$ditem['str']}</span>
					</div>
					<span class="count">{pigcms{$ditem['num']}</span>
					<strong>￥<span class="unit_price">{pigcms{$ditem['price']}<if condition="$config.open_extra_price eq 1 AND $ditem.extra_price gt 0">+{pigcms{$ditem.extra_price}{pigcms{$config.extra_price_alias_name}</if></span> <span style="color: gray; font-size:10px">({pigcms{$ditem['num']}{pigcms{$ditem['unit']})</span></strong>
				</div>
			</div>
		</li>
		</volist>
		</ul>
		<ul class="menu_list box" style="margin-bottom:20px;">
			<li>
				<div>
					<h3>折扣后商品总价：<strong style="display: inline;font-size:14px;">￥{pigcms{$vip_discount_money|floatval}</strong>元<if condition="$config.open_extra_price eq 1 AND $extra_price gt 0">+{pigcms{$extra_price|floatval}{pigcms{$config.extra_price_alias_name}</if></h3>
				</div>
			</li>
			<if condition="$packing_charge">
			<li>
				<div>
					<h3>{pigcms{$store['pack_alias']|default='打包费'}：<strong style="display: inline;font-size:14px;">￥{pigcms{$packing_charge|floatval}</strong>元</h3>
				</div>
			</li>
			</if>
			<if condition="$discount_list">
    			<volist name="discount_list" id="row">
        			<li>
        				<if condition="$row['discount_type'] eq 3">
        					<div>
        						<h3>商家首单优惠：满￥{pigcms{$row['money']|floatval}元,减<strong style="display: inline;font-size:14px;">￥{pigcms{$row['minus']|floatval}</strong>元</h3>
        					</div>
        				<elseif condition="$row['discount_type'] eq 4" />
        					<div>
        						<h3>商家满减优惠：满￥{pigcms{$row['money']|floatval}元,减<strong style="display: inline;font-size:14px;">￥{pigcms{$row['minus']|floatval}</strong>元</h3>
        					</div>
        				<elseif condition="$row['discount_type'] eq 1" />
        					<div>
        						<h3>平台首单优惠：满￥{pigcms{$row['money']|floatval}元,减<strong style="display: inline;font-size:14px;">￥{pigcms{$row['minus']|floatval}</strong>元</h3>
        					</div>
        				<elseif condition="$row['discount_type'] eq 2" />
        					<div>
        						<h3>平台满减优惠：满￥{pigcms{$row['money']|floatval}元,减<strong style="display: inline;font-size:14px;">￥{pigcms{$row['minus']|floatval}</strong>元</h3>
        					</div>
        				<else />
        				
        				</if>
        			</li>
    			</volist>
			</if>
		</ul>
		</if>
	</section>
	<div style="display:none;">
		<input class="hidden" id="ouserName" name="ouserName" value="{pigcms{$user_adress['name']}">
		<input class="hidden" id="ouserTel" name="ouserTel" value="{pigcms{$user_adress['phone']}">
		<input class="hidden" id="ouserAddres" name="ouserAddres" value="{pigcms{$user_adress['province_txt']} {pigcms{$user_adress['city_txt']} {pigcms{$user_adress['area_txt']} {pigcms{$user_adress['adress']}  {pigcms{$user_adress['detail']}">
		<input class="hidden" id="address_id" name="adress_id" value="{pigcms{$user_adress['adress_id']}">
		<input type="hidden" name="pick_address" value="{pigcms{$pick_address['area_info']['province']} {pigcms{$pick_address['area_info']['city']} {pigcms{$pick_address['area_info']['area']} {pigcms{$pick_address['name']} 电话：{pigcms{$pick_address['phone']}"/>
		<input type="hidden" name="pick_id" value="{pigcms{$pick_address['pick_addr_id']}"/>
		<input class="hidden" id="oarrivalDate" name="oarrivalDate" value="{pigcms{$arrive_date}">
		<input class="hidden" id="oarrivalTime" name="oarrivalTime" value="{pigcms{$arrive_time}">
		<input class="hidden" id="omark" name="omark" value="">
		<input class="hidden" id="invoice_head" name="invoice_head" value="">
		<input class="hidden" id="deliver_type" name="deliver_type" value="<if condition="$delivery_type eq 2 OR $pick_addr_id">1<else />0</if>"/>
		<if condition="$cue_field">
			<volist name="cue_field" id="vo">
				<if condition="$vo['type'] eq 1">
				<input class="hidden" id="cue_field_{pigcms{$key}_head" name="cue_field[{pigcms{$key}][txt]" value=""/>
				<input class="hidden" name="cue_field[{pigcms{$key}][title]" value="{pigcms{$vo.name}"/>
				</if>
			</volist>
		</if>
	</div>
	</form>
	<div class="addres_box" id="remarkBox">
		<ul>
			<li><textarea class="txt max" placeholder="请填写备注" id="userMark"></textarea></li>
			<li class="btns_wrap">
			<span><a href="javascript:void(0);" class="comm_btn higher disabled" id="cancleRemark">取消</a></span>
			<span><a href="javascript:void(0);" class="comm_btn higher" id="saveRemark">确认</a></span>
			</li>
		</ul>
	</div>
	<div class="addres_box" id="invoice_head_box">
		<ul>
			<li><textarea class="txt max" placeholder="请填发票抬头" id="invoice_head_txt"></textarea></li>
			<li class="btns_wrap">
			<span><a href="javascript:void(0);" class="comm_btn higher disabled" id="cancleInvoice">取消</a></span>
			<span><a href="javascript:void(0);" class="comm_btn higher" id="saveInvoice">确认</a></span>
			</li>
		</ul>
	</div>
	<if condition="$cue_field">
		<volist name="cue_field" id="vo">
			<div class="addres_box" id="cue_field_{pigcms{$key}_head_box">
				<ul>
					<li><textarea class="txt max" placeholder="请填{pigcms{$vo.name}" id="cue_field_{pigcms{$key}_head_txt"></textarea></li>
					<li class="btns_wrap">
						<span><a href="javascript:void(0);" class="comm_btn higher disabled" id="cancle_cue_field_{pigcms{$key}">取消</a></span>
						<span><a href="javascript:void(0);" class="comm_btn higher" id="save_cue_field_{pigcms{$key}">确认</a></span>
					</li>
				</ul>
			</div>
		</volist>
	</if>
</div>
<div class="fixed" style="min-height:90px;padding:14px;">
	<p>
		<span class="fr">商品总计：<strong>￥<span id="totalPrice_">{pigcms{$price}<if condition="$config.open_extra_price eq 1 AND $extra_price gt 0">+{pigcms{$extra_price|floatval}{pigcms{$config.extra_price_alias_name}</if></span></strong> / <span id="cartNum_">{pigcms{$total}</span>份</span>
		<p id="show_delivery_fee">{pigcms{$store['freight_alias']|default='配送费'}：￥{pigcms{$delivery_fee}</p>		
	</p>
	<span class="fr" style="position: absolute; bottom: 8px; right: 20px;">
	<a href="javascript:;" class="comm_btn" id="submit_order" >确认订单</a>
	</span>
</div>
<if condition="$cue_field">
	<volist name="cue_field" id="vo">
		<script type="text/javascript">
			// 添加自定义
			$('#cue_field_btn_{pigcms{$key}').bind('click', function(){
				var cue_field = $('#cue_field_{pigcms{$key}_txt').text();
				if(cue_field == '点击填写{pigcms{$vo.name}') cue_field = '';
				$('#cue_field_{pigcms{$key}_txt,#cue_field_{pigcms{$key}_head_txt').val(cue_field);
				$('#cue_field_{pigcms{$key}_head_box').dialog({title: '点击填写{pigcms{$vo.name}'});
			});

			$('#cancle_cue_field_{pigcms{$key}').bind('click', function(){
				$('#cue_field_{pigcms{$key}_head_box').dialog('close');
			});

			$('#save_cue_field_{pigcms{$key}').bind('click', function(){
				var cue_field = $('#cue_field_{pigcms{$key}_head_txt').val();
				if(cue_field == '') cue_field = '点击填写{pigcms{$vo.name}';
				$('#cue_field_{pigcms{$key}_txt').text(cue_field);
				$('#cue_field_{pigcms{$key}_head').val(cue_field);
				
				$('#cue_field_{pigcms{$key}_head_txt').val('');
				$('#cue_field_{pigcms{$key}_head_box').dialog('close');
			});
		</script>
	</volist>
</if>
<script type="text/javascript">
var motify = {
	timer:null,
	log:function(msg,time,shade){
		$('.motifyShade,.motify').hide();
		if(motify.timer) clearTimeout(motify.timer);
		if($('.motify').size() > 0){
			$('.motify').show().find('.motify-inner').html(msg);
		}else{
			$('body').append('<div class="motify" style="display:block;"><div class="motify-inner">'+msg+'</div></div>');
		}
		if(shade && shade.show){
			if($('.motifyShade').size() > 0){
				$('.motifyShade').css({'background-color':'rgba(0,0,0,'+(shade.opcity ? shade.opcity : '0.3')+')'}).show();
			}else{
				$('body').append('<div class="motifyShade" style="display:block;background-color:rgba(0,0,0,'+(shade.opcity ? shade.opcity : '0.3')+');"></div>');
			}
		}
		if(typeof(time) == 'undefined'){
			time = 3000;
		}
		if(time != 0){
			motify.timer = setTimeout(function(){
				$('.motify').hide();
			},time);
		}
	}
};
$(document).ready(function () {
	$('.cue_field').click(function(e){
        $(this).find('.mask').removeClass('hidden');
	});
	$('.address_tanceng li').click(function(e){
        var index = $(this).parents('.mask').data('index');
		var text = $(this).text();
		$('#cue_field_txt_' + index).text(text);
		if ($(this).data('s') == undefined) {
		  $('.cue_field_txt_' + index).val(text);
		} else {
		    $('.cue_field_txt_' + index).val('');
		}
		$('.mask').addClass('hidden');
        return false;
	});
	$('.mask').click(function(){
		$('.mask').addClass('hidden');
        return false;
	});
    
	$(window).scrollTop(1);
	$('div.fixed').css('bottom','0');
	
	$('#post_package').click(function(){
		$(this).removeClass('pick_in_store_click').addClass('pick_in_store').siblings('.btn_express').removeClass('pick_in_store').addClass('pick_in_store_click');
		$('#li_pick').css('display', 'none');
		$('#li_delivery, #show_arrive_date, #show_arrive_time, #show_delivery_fee').css('display', 'block');
		$('#deliver_type').val(0);
	});
	$('#post_package_express').click(function(){
		$(this).removeClass('pick_in_store_click').addClass('pick_in_store').siblings('.btn_express').removeClass('pick_in_store').addClass('pick_in_store_click');
		$('#li_delivery').css('display', 'block');
		$('#li_pick, #show_arrive_date, #show_arrive_time, #show_delivery_fee').css('display', 'none');
		$('#deliver_type').val(0);
	});
	$('#pick_in_store').click(function(){
		$(this).removeClass('pick_in_store_click').addClass('pick_in_store').siblings('.btn_express').removeClass('pick_in_store').addClass('pick_in_store_click');
		$('#li_pick').css('display', 'block');
		$('#li_delivery, #show_arrive_date, #show_arrive_time, #show_delivery_fee').css('display', 'none');
		$('#deliver_type').val(1);
	});

	// 添加备注
	$('#remarkBtn').bind('click', function(){
		var remark = $('#remarkTxt').text();
		if(remark == '点击添加订单备注') remark = '';
		$('#userMark').val(remark);
		$('#remarkBox').dialog({title: '添加备注'});
	});

	$('#cancleRemark').bind('click', function(){
		$('#remarkBox').dialog('close');
	});

	$('#saveRemark').bind('click', function(){
		$('#remarkTxt').text($('#userMark').val());
		$('#userMark').val('');
		$('#remarkBox').dialog('close');
	});
	
	// 添加发票
	$('#invoiceBtn').bind('click', function(){
		var invoice = $('#invoiceTxt').text();
		if(invoice == '点击添加发票抬头') invoice = '';
		$('#invoiceTxt').val(invoice);
		$('#invoice_head_box').dialog({title: '添加发票抬头'});
	});

	$('#cancleInvoice').bind('click', function(){
		$('#invoice_head_box').dialog('close');
	});

	$('#saveInvoice').bind('click', function(){
		$('#invoiceTxt').text($('#invoice_head_txt').val());
		$('#invoice_head_txt').val('');
		$('#invoice_head_box').dialog('close');
	});

	$("#submit_order").click(function(){
		
		if($('#deliver_type').val() == 0 && $('#address_id').val() == ''){
			motify.log('请您先添加配送地址');
			return false;
		}
		if(!$(this).hasClass('disabled')){
			<?php
				if($cue_field){ 
					foreach($cue_field as $key=>$value){
						if($value['iswrite']){
			?>
							if($('#cue_field_<?php echo $key; ?>_head').val() == ''){
								motify.log("请填写<?php echo $value['name'];?>");
								return false;
							}
			<?php
						}
					}
				}
			?>
			$(this).addClass('disabled');
			
// 			var wo_delivery_time = $.trim($("#arriveTime").html());
// 			if(wo_delivery_time == '尽快送出'){
// 				wo_delivery_time = '';
// 			}
// 			$('#oarrivalTime').val(wo_delivery_time);
			
			var wo_memo = $.trim($("#remarkTxt").html());
			if(wo_memo == '点击添加订单备注') {
				wo_memo = '';
			}
			$('#omark').val(wo_memo);
			var invoice_head = $.trim($("#invoiceTxt").html());
			if(invoice_head == '点击添加发票抬头') {
				invoice_head = '';
			}
			$('#invoice_head').val(invoice_head);
			/*$.post($('#cart_confirm_form').attr('action'), $('#cart_confirm_form').serialize(), function(response){
				if (response.status) {
					window.location.href = response.url;
				} else {
					alert(response.info);
				}
			});
			return false;*/
			document.cart_confirm_form.submit();
		}
		return false;
	});
	
	if(/(pigcmso2oreallifeapp)/.test(navigator.userAgent.toLowerCase()) || (/(pigcmso2olifeapp)/.test(navigator.userAgent.toLowerCase()) && /(life_app)/.test(navigator.userAgent.toLowerCase()))){
		var reg = /versioncode=(\d+),/;
		var arr = reg.exec(navigator.userAgent.toLowerCase());
		if(arr == null){
			
		}else{
			var version = parseInt(arr[1]);
			if(version >= 50){
				if(/(iphone|ipad|ipod)/.test(navigator.userAgent.toLowerCase())){
					$('body').append('<iframe src="pigcmso2o://hideWebViewHeader/true" style="display:none;"></iframe>');
					$('#li_delivery a').click(function(){
						var address_id = $('#address_id').val() == '' ? '0' : $('#address_id').val();
						$('body').append('<iframe src="pigcmso2o://getUserAddress/'+address_id+'" style="display:none;"></iframe>');
						return false;
					});
				}else{
					$('#li_delivery a').click(function(){
						var address_id = $('#address_id').val() == '' ? '0' : $('#address_id').val();
						window.lifepasslogin.getUserAddress(address_id);
						return false;
					});
				}
			}
		}
	}
});

function callbackUserAddress(address){
	var addressArr = address.split('<>');
	// $('#remarkTxt').html(addressArr[0]);
	$('#address_id').val(addressArr[0]);
	$('#showName').html(addressArr[1]);
	$('#showTel').html(addressArr[2]);
	$('#showAddres').html(addressArr[3]);
	<php>
		$tmpGet = $_GET;
		unset($tmpGet['adress_id']);
	</php>
	window.location.href = "{pigcms{:U('confirm_order',$tmpGet)}&adress_id="+addressArr[0];
}
</script>
</body>
{pigcms{$hideScript}
</html>
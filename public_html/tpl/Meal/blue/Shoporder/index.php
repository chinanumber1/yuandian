<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>{pigcms{$config.seo_title}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}"/>
<meta name="description" content="{pigcms{$config.seo_description}"/>

<link href="{pigcms{$static_path}css/css.css" type="text/css" rel="stylesheet"/>
<link href="{pigcms{$static_path}css/header.css" rel="stylesheet" type="text/css"/>
<link href="{pigcms{$static_path}css/buy-process.css" rel="stylesheet" type="text/css"/>
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>




<script type="text/javascript">
   var  shop_alias_name = "{pigcms{$config.shop_alias_name}";
</script>
<script src="{pigcms{$static_path}js/common.js"></script>
<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>

<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script> 

<script><if condition="$user_session">var is_login=true;<else/>var is_login=false;var login_url="{pigcms{:U('Index/Login/frame_login')}";</if><if condition="$user_session['phone']">var has_phone=true;<else/>var has_phone=false;var phone_url="{pigcms{:U('Index/Login/frame_phone')}";</if></script>
<!--[if IE 6]>
<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
<script type="text/javascript">
   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');
</script>
<script type="text/javascript">DD_belatedPNG.fix('*');</script>
<style type="text/css"> 
body{behavior:url("{pigcms{$static_path}css/csshover.htc");}
.category_list li:hover .bmbox {filter:alpha(opacity=50);}
.gd_box{display: none;}
</style>
<![endif]-->
</head>
<body>
<include file="Public:header_top"/>
<div class="body pg-buy-process"> 
	<article>
		<div class="menu cf">
			<div class="menu_left hide">
				<div class="menu_left_top">全部分类</div>
				<div class="list">
					<ul>
						<volist name="all_category_list" id="vo" key="k">
						<li>
							<div class="li_top cf">
								<if condition="$vo['cat_pic']"><div class="icon"><img src="{pigcms{$vo.cat_pic}" /></div></if>
								<div class="li_txt"><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></div>
							</div>
							<if condition="$vo['cat_count'] gt 1">
								<div class="li_bottom">
								<volist name="vo['category_list']" id="voo" offset="0" length="3" key="j">
									<span><a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a></span>
								</volist>
								</div>
							</if>
						</li>
						</volist>
					</ul>
				</div>
			</div>
			<div class="menu_right cf">
				<div class="menu_right_top">
					<ul>
					<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
					<li class="ctur">
						<a href="{pigcms{$vo.url}">{pigcms{$vo.name}</a>
					</li>
					</pigcms:slider>
					</ul>
				</div>
			</div>
		</div>
	</article>
	<article>
		<div class="sysmsgw common-tip" id="sysmsg-error" style="display:none;"></div>
		<div id="bdw" class="bdw" style="min-height:700px;">
			<div id="bd" class="cf">
				<div id="content">
					<div>
						<div class="buy-process-bar-container">
							<ol class="buy-process-desc steps-desc">
								<li class="step step--current">1. 提交订单</li>
								<li class="step">2. 选择支付方式</li>
								<li class="step">3. 购买成功</li>
							</ol>
							<div class="progress">
								<div class="progress-bar" style="width:33.33%"></div>
							</div>
						</div>
					</div>
					<div class="mainbox cf">
						<div class="table-section summary-table">
							<table cellspacing="0" class="buy-table" id="menu_list">
								<tr class="order-table-head-row">
									<th class="desc">名称</th>
									<th class="unit-price">单价</th>
									<th class="amount">数量</th>
									<th class="col-total">总价</th>
								</tr>
								<volist name="goods" id="food">
								<tr>
									<td class="desc">{pigcms{$food['name']}<if condition="$food['str']">　<span style="color: gray;fond-size:10px">({pigcms{$food['str']})</span></if></td>
									<td class="money J-deal-buy-price">¥<span id="deal-buy-price">{pigcms{:floatval($food['price'])}<if condition="$food.extra_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$food.extra_price}{pigcms{$config.extra_price_alias_name}</if></span></td>
									<td class="deal-component-quantity ">{pigcms{$food['num']}</td>
									<td class="money total rightpadding col-total">¥<span id="J-deal-buy-total">{pigcms{$food['price'] * $food['num']|floatval}<if condition="$food.extra_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$food['extra_price']*$food['num']|floatval}{pigcms{$config.extra_price_alias_name}</if></span></td>
								</tr>
								</volist>
								
								<tr>
									<td></td>
									<td colspan="3" class="extra-fee total-fee rightpadding">
										<div><strong>商品实际总价</strong>：<span class="inline-block money">¥<strong id="basic_price">{pigcms{$basic_price|floatval}<if condition="$extra_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$extra_price}{pigcms{$config.extra_price_alias_name}</if></strong></span></div>
										<div><strong>折扣后的商品总价</strong>：<span class="inline-block money">¥<strong id="discount_price">{pigcms{$vip_discount_money|floatval}<if condition="$extra_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$extra_price}{pigcms{$config.extra_price_alias_name}</if></strong></span></div>
										<if condition="$sys_first_reduce gt 0">
										<div><strong>平台新单满减优惠金额</strong>：<span class="inline-block money">¥<strong id="sys_reduce_price">{pigcms{$sys_first_reduce|floatval}</strong></span></div>
										</if>
										<if condition="$sys_full_reduce gt 0">
										<div><strong>平台满减优惠金额</strong>：<span class="inline-block money">¥<strong id="sys_reduce_price">{pigcms{$sys_full_reduce|floatval}</strong></span></div>
										</if>
										<if condition="$sto_first_reduce gt 0">
										<div><strong>店铺新单满减优惠金额</strong>：<span class="inline-block money">¥<strong id="merchant_reduce_price">{pigcms{$sto_first_reduce|floatval}</strong></span></div>
										</if>
                                        <if condition="$sto_full_reduce gt 0">
                                        <div><strong>店铺满减优惠金额</strong>：<span class="inline-block money">¥<strong id="merchant_reduce_price">{pigcms{$sto_full_reduce|floatval}</strong></span></div>
                                        </if>
										<div><strong>优惠后商品总价</strong>：<span class="inline-block money">¥<strong id="goods_price">{pigcms{$price|floatval}<if condition="$extra_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$extra_price}{pigcms{$config.extra_price_alias_name}</if></strong></span></div>
										
										<if condition="$packing_charge">
										<div><strong>{pigcms{$store['pack_alias']|default='打包费'}</strong>：<span class="inline-block money">¥<strong id="packing_charge">{pigcms{$packing_charge|floatval}</strong></span></div>
										</if>
										
										
										<div id="show_delivery_fee" <if condition="!$delivery_fee">style="display:none"</if>><strong>{pigcms{$store['freight_alias']|default='配送费'}</strong>：<span class="inline-block money">¥<strong id="delivery_fee">{pigcms{$delivery_fee|floatval}</strong></span></div>
										
										<div><strong>合计总价</strong>：<span class="inline-block money">¥<strong id="price">{pigcms{$delivery_fee + $price + $packing_charge|floatval}<if condition="$extra_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$extra_price}{pigcms{$config.extra_price_alias_name}</if></strong></span></div>
									</td>
								</tr>
							</table>
						</div>
						<input id="J-deal-buy-cardcode" type="hidden" name="cardcode" maxlength="8" value=""/>
						
						<if condition="$user_session">
							<if condition="$delivery_type eq 2">
							<input type="checkbox" name="pick_in_store" id="pick_in_store" checked> <label for="pick_in_store">到店自提</label>
							<elseif condition="in_array($delivery_type, array(2, 3, 4))" />
							<input type="checkbox" name="pick_in_store" id="pick_in_store"> <label for="pick_in_store">到店自提</label>
							</if>
							<div id="deal-buy-delivery" class="blk-item delivery J-deal-buy-delivery">
								<h3 id="package" <if condition="in_array($delivery_type, array(2, 5))">style="display:none"</if>>收货地址<span><a target="_blank" href="{pigcms{:U('User/Adress/index')}">管理</a></span></h3>
								<h3 id="pick_addr" <if condition="$delivery_type neq 2">style="display:none"</if>>自提点地址</h3>
								<div id="adress_frame_div">
									
								</div>
								<input id="pick-address" type="hidden" name="pick_address" value=""/>
								<input id="buy-adress-id" type="hidden" name="adress_id" value=""/>
								<input id="buy-pick-id" type="hidden" name="pick_id" value=""/>
								<hr/>
								
								<h4 id="send_time_p" <if condition="$delivery_type neq 2">style="display:none"</if>>希望提货时间</h4>
								<h4 id="send_time_d" <if condition="$delivery_type eq 2">style="display:none"</if>>希望货物送达的时间 <if condition="$have_two_time AND $delivery_type neq 2 AND $delivery_type neq 5 AND empty($pick_addr_id)">(配送时间一：{pigcms{$time_select_1}，{pigcms{$store['freight_alias']|default='配送费'}：￥<b id="delivery_fee1">{pigcms{$delivery_fee}</b>；配送时间二：{pigcms{$time_select_2}，{pigcms{$store['freight_alias']|default='配送费'}：￥<b id="delivery_fee2">{pigcms{$delivery_fee2}</b>)</if></h4>
								<input type="text" class="f-text Wdate" id="oarrivalTime" onfocus="WdatePicker({dateFmt: 'yyyy-MM-dd HH:mm:ss', minDate: '{pigcms{$min_date}', maxDate: '{pigcms{$max_date}' })" value="{pigcms{$now_date}" readonly="readonly"/>
								<hr/>
								<input type="hidden" id="store_id" name="store_id" value="{pigcms{$store.store_id}">
								<h4>给店铺留言<span>（给店家留意提醒）</span></h4>
								<input class="f-text comment" type="text" id="desc" name="desc" />
								
								
								<span <if condition="$store['is_invoice'] AND $store['invoice_price'] elt $price">style="display:block"<else/>style="display:none"</if> >
								<hr/>
								<h4>索取发票<span>（填写您要开的发票抬头）</span></h4>
								<input class="f-text comment" type="text" id="invoice_head" name="invoice_head" />
								</span>
							</div>
						</if>
						<div class="blk-mobile" style="display: none">
							<p>您绑定的手机号码：<span class="mobile" style="color:#EE3968;">{pigcms{$pigcms_phone}</span></p>
						</div> 
						<div class="form-submit shopping-cart">
							<input type="submit" class="clear-cart btn btn-large btn-buy" id="confirmOrder" value="提交订单" >
						</div>
					</div>
				</div>
			</div>
			<!-- bd end -->
		</div>
	</article>
</div>
<script>
function change_adress_frame(frame_height){
	$('#adress_frame_div').height(frame_height).find('iframe').css({'opacity':'1','filter':'alpha(opacity=100)'});
}
function change_adress(adress_id,username,phone,province_txt,city_txt,area_txt,zipcode){
	$('#buy-adress-id').val(adress_id);
	$.post("{pigcms{:U('Meal/Shoporder/ajax_prices', array('store_id' => $store['store_id']))}", {'address_id':adress_id, 'oarrivalTime':$('#oarrivalTime').val()}, function(response){
		if (response.error_code) {
			alert(response.msg);
		} else {
			if(response.extra_price>0){
				$('#price').text(response.price+'+'+response.extra_price+'{pigcms{$config.extra_price_alias_name}');
			}else{				
				$('#price').text(response.price);
			}
			$('#delivery_fee').text(response.delivery_fee);
			$('#delivery_fee1').text(response.delivery_fee1);
			$('#delivery_fee2').text(response.delivery_fee2);
			if (response.delivery_fee) {
				$('#show_delivery_fee').show();
			} else {
				$('#show_delivery_fee').hide();
			}
			
		}
	}, 'json');
}

function change_pick_adress(adress_id,pick_name,phone,province,city,area){
	$('#pick-address').val(province+' '+city+' '+area+' '+pick_name+' ,自提点电话：'+phone );
	$('#buy-pick-id').val(adress_id);
	$.post("{pigcms{:U('Meal/Shoporder/ajax_prices', array('store_id' => $store['store_id']))}", {'type':1}, function(response){
		if (response.error_code) {
			alert(response.msg);
		} else {
			$('#price').text(response.price);
			$('#delivery_fee').text(response.delivery_fee);
			$('#delivery_fee1').text(response.delivery_fee1);
			$('#delivery_fee2').text(response.delivery_fee2);
			if (response.delivery_fee) {
				$('#show_delivery_fee').show();
			} else {
				$('#show_delivery_fee').hide();
			}
		}
	}, 'json');
	$('#show_delivery_fee').hide();
}

// window.onload = function() { 
// 	if($("#pick_in_store").is(':checked')==true){
// 		$('#pick_addr').css('display','block');
// 		$('#package').css('display','none');
// 		('#send_time_p').css('display','block');
// 		('#send_time_d').css('display','none');
// 		$('#adress_frame_div').html('<iframe src="{pigcms{:U('Index/Adress/pick_address',array('mer_id'=>$store['mer_id'], 'buy_type'=>'shop'))}"></iframe>');
// 	}
// }
$(document).ready(function(){
	if($("#pick_in_store").is(':checked')==true){
		$('#pick_addr, #send_time_p').css('display','block');
		$('#package, #send_time_d').css('display','none');
		$('#adress_frame_div').html('<iframe src="{pigcms{:U('Index/Adress/pick_address',array('mer_id'=>$store['mer_id'], 'buy_type'=>'shop'))}"></iframe>');
	} else {
		$('#pick_addr, #send_time_p').css('display','none');
		$('#package, #send_time_d').css('display','block');
		$('#adress_frame_div').html('<iframe src="{pigcms{:U('Index/Adress/frame')}"></iframe>');
	}

	$("#pick_in_store").bind("click", function () {
		if($("#pick_in_store").is(':checked')){
			$('#pick_addr, #send_time_p').css('display','block');
			$('#package, #send_time_d').css('display','none');
			$('#adress_frame_div').html('<iframe src="{pigcms{:U('Index/Adress/pick_address',array('mer_id'=>$store['mer_id'], 'buy_type'=>'shop'))}"></iframe>');
		}else{
			$('#pick_addr, #send_time_p').css('display','none');
			$('#package, #send_time_d').css('display','block');
			$('#adress_frame_div').html('<iframe src="{pigcms{:U('Index/Adress/frame')}"></iframe>');
		}
	});

	$("#confirmOrder").click(function(){
		if(is_login == false){
			art.dialog.open(login_url,{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'登录',
				padding: '30px',
				width: 438,
				height: 500,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				opacity:'0.4'
			});
			return false;
		}
		if(has_phone == false){
			art.dialog.open(phone_url,{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'绑定手机号码',
				padding: '30px',
				width: 438,
				height: 500,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				opacity:'0.4'
			});
			return false;
		}
	
		var address_id = $("#buy-adress-id").val();
		var pick_id = $("#buy-pick-id").val();
		var pick_address = $("#pick-address").val();
		var desc = $("#desc").val();
		var invoice_head = $("#invoice_head").val();
		var store_id = $('#store_id').val();
		var is_pick = $("#pick_in_store").is(':checked') ? 1 : 0;
		var oarrivalTime = $('#oarrivalTime').val();
		$.post("{pigcms{:U('Meal/Shoporder/saveorder')}", {'oarrivalTime':oarrivalTime, 'store_id':store_id, 'pick_address':pick_address, 'pick_id':pick_id, 'address_id':address_id, 'desc':desc, 'deliver_type':is_pick, 'invoice_head':invoice_head}, function(data){
			if (data.error_code == 1) {
				alert(data.msg);
			} else {
				window.sessionStorage.setItem("shop_cart_" + store_id, '');
// 				$.cookie("shop_cart_" + store_id, '', {expires:365, path:"/"});
				window.location.href = data.data;
			}
		}, 'json');
	});
});
</script>
<include file="Public:footer"/>
</body>
</html>

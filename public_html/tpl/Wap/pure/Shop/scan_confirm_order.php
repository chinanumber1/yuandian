<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/datePicker.css">
<script type="text/javascript" src="{pigcms{$static_path}shop/js/jquery1.8.3.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}shop/js/dialog.js"></script>

<title>{pigcms{$store['name']|default="快店"}</title>
<meta content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport">
<meta name="Keywords" content="">
<meta name="Description" content="">
<meta content="telephone=no, address=no" name="format-detection">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/main.css" media="all"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/confirm_order.css" media="all"/>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
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
</style>
</head>
<body onselectstart="return true;" ondragstart="return false;">
<div class="container">
	<form name="cart_confirm_form" action="{pigcms{:U('Shop/save_order',array('store_id'=> $store['store_id'], 'mer_id' => $store['mer_id'], 'deliverExtraPrice' => $_GET['deliverExtraPrice'], 'frm' => $_GET['frm'], 'village_id'=>$village_id))}" method="post">
	<section class="menu_wrap pay_wrap">
		<ul class="box deliver_pick">
			<li class="delivery_type">
				<a class="ico">配送方式：</a>&nbsp;&nbsp;
				<a class="btn_express pick_in_store" >到店扫码购物</a>
				
			</li>
		
		</ul>
	
		<if condition="!empty($goods)">
			
				<volist name="goods" id="fditem">
					<if condition="!empty($fditem['name'])">
					<ul class="menu_list box coupon_list" >
					<li><div><h3><strong style="display: inline;font-size:14px;">{pigcms{$fditem['name']}</strong></h3></div></li>
					</ul>
					</if>
					<ul class="menu_list order_list" id="orderList">
					<volist name="fditem['list']" id="ditem">
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
								<strong>￥<span class="unit_price">{pigcms{$ditem['price']}<if condition="$ditem.extra_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$ditem['extra_price']|floatval}{pigcms{$config.extra_price_alias_name}</if></span> <span style="color: gray; font-size:10px">({pigcms{$ditem['num']}{pigcms{$ditem['unit']})</span></strong>
							</div>
						</div>
					</li>
					</volist>
					</ul>
				</volist>
			<ul class="menu_list box coupon_list">
				<li>
					<div>
						<h3>折扣后商品总价：<strong style="display: inline;font-size:14px;">￥{pigcms{$vip_discount_money|floatval}<if condition="$extra_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$extra_price|floatval}{pigcms{$config.extra_price_alias_name}</if></strong>元</h3>
					</div>
				</li>
			
				
			
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
						
							</if>
						</li>
					</volist>
				</if>
                <if condition="$noDiscountList">
                    <volist name="noDiscountList" id="row">
                        <li>
                            <if condition="$row['type'] eq 3">
                                <div>
                                    <h3>商家首单满￥{pigcms{$row['money']|floatval}元,减<strong style="display: inline;font-size:14px;">￥{pigcms{$row['minus']|floatval}</strong>元活动与限时优惠、店铺/分类折扣、会员优惠不同享</h3>
                                </div>
                            <elseif condition="$row['type'] eq 4" />
                                <div>
                                    <h3>商家满￥{pigcms{$row['money']|floatval}元,减<strong style="display: inline;font-size:14px;">￥{pigcms{$row['minus']|floatval}</strong>元活动与限时优惠、店铺/分类折扣、会员优惠不同享</h3>
                                </div>
                            </if>
                        </li>
                    </volist>
                </if>
			</ul>
		</if>

	
	</section>
	<div style="display:none;">
	  <input class="hidden" id="order_id" name="order_id" value="{pigcms{$order_id}">
	  <input class="hidden" id="order_id" name="cartid" value="{pigcms{$cartid}">
	  <input class="hidden" id="is_pick_in_store" name="is_pick_in_store" value="2">


	  <input class="hidden" id="omark" name="omark" value="">
	  <input class="hidden" id="invoice_head" name="invoice_head" value="">
	  <input class="hidden" id="deliver_type" name="deliver_type" value="9">

      <input class="hidden" id="count_price" data-price="{pigcms{$price|floatval}" data-deliver="{pigcms{$dates.0.date_list.0.delivery_fee|floatval}" data-extprice="{pigcms{$extraPrice|floatval}" data-packing_charge="{pigcms{$packing_charge|floatval}" />
      <input class="hidden" id="minus_price" data-price="{pigcms{$this_discount_price|floatval}" data-deliver="{pigcms{$delivery_fee_reduce|floatval}"/>
      

	</div>
	</form>
	<!--div class="addres_box" id="remarkBox">
		<ul>
			<li><textarea class="txt max" placeholder="请填写备注" id="userMark"></textarea></li>
			<li class="btns_wrap">
			<span><a href="javascript:void(0);" class="comm_btn higher disabled" id="cancleRemark">取消</a></span>
			<span><a href="javascript:void(0);" class="comm_btn higher" id="saveRemark">确认</a></span>
			</li>
		</ul>
	</div-->


</div>
<div class="fixed" style="min-height:50px;padding:14px;">
	<p class="fl">
		<div><strong style="color:#ff560c;">待支付：￥<span id="totalPrice_">{pigcms{:floatval(round($price, 2))}</span></strong> | <strong style="color: gray;font-size: 8px;">已优惠￥<span id="cartNum_">{pigcms{$this_discount_price|floatval}</span></strong></div>
	</p>
	<a href="javascript:;" class="comm_btn" id="submit_order" style="position:absolute;background:#06c1ae;color:white;right:0;top:0;height:50px;padding:0 30px;line-height:50px;text-align:center;">提交订单</a>
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
var needDeliverTip = '{pigcms{$dates.0.show_date}' != '今天' ? true : false;
if(needDeliverTip == true){
	needDeliverTip = {pigcms{$dates.0.date_list.0.timestamp} - 3600 > {pigcms{:time()} ? true :false;
}

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
	
	window.addEventListener("pageshow", function(){
		$('#submit_order').removeClass('disabled');
	},false);
	
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
	setTimeout(function(){
		$('div.fixed').css({'bottom':'1px','left':'0px','z-index':'1','opacity':'1'});
		$('div.fixed').css({'bottom':'0px','left':'0px','z-index':'1','opacity':'1'});
	},1000);
	
	$('.deliver_time_box .timer .day,.deliver_time_box .timer .time').height($(window).height()*0.7-51);
	
	
;
	

	$('#post_package').click(function(){
		$(this).removeClass('pick_in_store_click').addClass('pick_in_store').siblings('.btn_express').removeClass('pick_in_store').addClass('pick_in_store_click');
		$('#li_pick').css('display', 'none');
		$('#li_delivery, #show_arrive_date, #show_arrive_time, #show_delivery_fee, #two_time_select, #deliverExtraPrice').show();
		$('#deliver_type').val(0);
		$('#totalPrice_').html($('#count_price').data('price') + $('#count_price').data('deliver') + $('#count_price').data('extprice') + $('#count_price').data('packing_charge'));
		$('#cartNum_').html($('#minus_price').data('price') + $('#minus_price').data('deliver'));
	});
	$('#post_package_express').click(function(){
		$(this).removeClass('pick_in_store_click').addClass('pick_in_store').siblings('.btn_express').removeClass('pick_in_store').addClass('pick_in_store_click');
		$('#li_delivery').css('display', 'block');
		$('#li_pick, #show_arrive_date, #show_arrive_time, #show_delivery_fee, #two_time_select, #deliverExtraPrice').hide();
		$('#deliver_type').val(0);
		$('#totalPrice_').html($('#count_price').data('price') + $('#count_price').data('deliver') + $('#count_price').data('extprice') + $('#count_price').data('packing_charge'));
		$('#cartNum_').html($('#minus_price').data('price') + $('#minus_price').data('deliver'));
	});
	$('#pick_in_store').click(function(){
		$(this).removeClass('pick_in_store_click').addClass('pick_in_store').siblings('.btn_express').removeClass('pick_in_store').addClass('pick_in_store_click');
		$('#li_pick').show();
		$('#li_delivery, #show_arrive_date, #show_arrive_time, #show_delivery_fee, #two_time_select, #deliverExtraPrice').css('display', 'none');
		$('#deliver_type').val(1);
		$('#totalPrice_').html($('#count_price').data('price') + $('#count_price').data('packing_charge'));
		$('#cartNum_').html($('#minus_price').data('price'));
	});


	$('#cancleRemark').bind('click', function(){
		$('#remarkBox').dialog('close');
	});

	$('#saveRemark').bind('click', function(){
		$('#remarkTxt').text($('#userMark').val());
		$('#userMark').val('');
		$('#remarkBox').dialog('close');
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
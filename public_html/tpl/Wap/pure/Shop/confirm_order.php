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
	<form name="cart_confirm_form" id="cart_confirm_form" action="{pigcms{:U('Shop/save_order',array('store_id'=> $store['store_id'], 'mer_id' => $store['mer_id'], 'deliverExtraPrice' => $_GET['deliverExtraPrice'], 'frm' => $_GET['frm'], 'village_id'=>$village_id))}" method="post">
	<section class="menu_wrap pay_wrap">
		<ul class="box deliver_pick">
			<li class="delivery_type">
				<a class="ico">配送方式：</a>&nbsp;&nbsp;
				<if condition="in_array($delivery_type, array(0, 3))">
					<a class="btn_express <if condition="$pick_addr_id">pick_in_store_click<else />pick_in_store</if>" id="post_package">{pigcms{$config['deliver_name']}</a>
				</if>
				<if condition="in_array($delivery_type, array(1, 4))">
					<a class="btn_express <if condition="$pick_addr_id">pick_in_store_click<else />pick_in_store</if>" id="post_package">商家配送</a>
				</if>
				<if condition="$delivery_type eq 5">
					<a class="btn_express <if condition="$pick_addr_id">pick_in_store_click<else />pick_in_store</if>" id="post_package_express">快递配送</a>
				</if>
				<if condition="in_array($delivery_type, array(2, 3, 4))">
					<a class="btn_express <if condition="$pick_addr_id">pick_in_store<elseif condition="$delivery_type neq 2" />pick_in_store_click<else />pick_in_store</if>" id="pick_in_store">到店自提</a>
				</if>
			</li>
			<if condition="$delivery_type neq 2">
				<li id="li_delivery" <if condition="$pick_addr_id">style="display:none"</if>>
					<a href="{pigcms{:U('My/adress',array('buy_type' => 'shop', 'store_id'=>$store['store_id'], 'village_id'=>$village_id, 'mer_id' => $store['mer_id'], 'deliverExtraPrice' => $_GET['deliverExtraPrice'], 'frm' => $_GET['frm'], 'current_id'=>$user_adress['adress_id'], 'order_id' => $order_id, 'cartid' => $cartid))}">
						<strong>
							<span id="showAddres"><if condition="$user_adress['adress_id']">{pigcms{$user_adress['province_txt']} {pigcms{$user_adress['city_txt']} {pigcms{$user_adress['area_txt']} {pigcms{$user_adress['adress']} {pigcms{$user_adress['detail']}<else/>请点击添加送货地址<php>if($address_count > 0){</php><br/><span style="font-size: 8px;color: #bbb5b5">已有地址均不在配送范围内</span><php>}</php></if></span><br>
							<span id="showName">{pigcms{$user_adress['name']}</span>
							<span id="showTel">{pigcms{$user_adress['phone']}</span>
						</strong>
						<div><i class="ico_arrow"></i></div>
					</a>
				</li>
			</if>
			<if condition="in_array($delivery_type, array(2, 3, 4))">
				<li id="li_pick" <if condition="$delivery_type neq 2 AND empty($pick_addr_id)">style="display:none"</if>>
					<a href="{pigcms{:U('My/pick_address',array('buy_type' => 'shop', 'store_id'=>$store['store_id'], 'village_id'=>$village_id, 'frm' => $_GET['frm'], 'deliverExtraPrice' => $_GET['deliverExtraPrice'], 'mer_id' => $store['mer_id'],'pick_addr_id' => $pick_address['pick_addr_id'], 'order_id' => $order_id, 'cartid' => $cartid))}">
						<strong>
							<span id="showTel">省市区：{pigcms{$pick_address['area_info']['province']} {pigcms{$pick_address['area_info']['city']} {pigcms{$pick_address['area_info']['area']}</span><br>
							<span id="showName">电话：{pigcms{$pick_address['phone']}</span><br>
							<span id="showAddres">地址：{pigcms{$pick_address['name']}</span>
							<br><span id="showName" style="color:green">距离：{pigcms{$pick_address['distance']}</span>
						</strong>
						<div><i class="ico_arrow"></i></div>
					</a>
				</li>
			</if>
		</ul>
		<ul class="box pay_box deliver_time">
			<li id="show_arrive_time" <if condition="$delivery_type eq 2 OR $delivery_type eq 5 OR $pick_addr_id">style="display:none"</if>>
				<a href="javascript:void(0);" id="timeBtn" class="time">
					<strong>送达时间</strong>
                    <php>if (!empty($dates)) {</php>
					<span id="arriveTime">{pigcms{$dates.0.show_date} {pigcms{$dates.0.date_list.0.hour_minute}</span>
                    <php> } else { </php>
                    <span id="arriveTime">抱歉,当前暂无可配送的预计送达时间</span>
                    <php> } </php>
					<div><i class="ico_arrow"></i></div>
				</a>
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
				<if condition="$packing_charge">
					<li>
						<div>
							<h3>{pigcms{$store['pack_alias']|default='打包费'}：<strong style="display: inline;font-size:14px;">￥{pigcms{$packing_charge|floatval}</strong>元</h3>
						</div>
					</li>
				</if>
				<li id="show_delivery_fee" <if condition="$delivery_type eq 2 OR $pick_addr_id OR $now_time_value eq 2">style="display:none"</if>>
					<div>
						<h3>{pigcms{$store['freight_alias']|default='配送费'}：<strong style="display: inline;font-size:14px;">￥{pigcms{$dates.0.date_list.0.delivery_fee_old}</strong>元</h3>
					</div>
				</li>
				
				<if condition="$extraPrice gt 0 AND $deliverExtraPrice eq 1">
					<li id="deliverExtraPrice" <php>if ($delivery_type == 2 || $pick_addr_id || $now_time_value == 2) { echo 'style="display:none"';}</php>>
						<div>
							<h3>不满起送价附加费：<strong style="display: inline;font-size:14px;">￥{pigcms{$extraPrice|floatval}</strong>元(满{pigcms{$store['basic_price']}元起送)</h3>
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
							<elseif condition="$row['discount_type'] eq 5"  />
							     <div class="delivery_fee" <if condition="$delivery_type eq 2 OR $pick_addr_id OR $now_time_value eq 2">style="display:none"</if>>
                                    <h3>{pigcms{$config['deliver_name']}优惠：商品满￥{pigcms{$row['money']|floatval}元,减<strong style="display: inline;font-size:14px;">￥{pigcms{$row['minus']|floatval}</strong>元配送费</h3>
                                </div>
							</if>
						</li>
					</volist>
				</if>
                <if condition="$noDiscountList">
                    <volist name="noDiscountList" id="row">
                        <li>
                            <if condition="$row['type'] eq 1">
                                <div>
                                    <h3>平台首单满￥{pigcms{$row['money']|floatval}元,减<strong style="display: inline;font-size:14px;">￥{pigcms{$row['minus']|floatval}</strong>元活动与限时优惠、店铺/分类折扣、会员优惠不同享</h3>
                                </div>
                            <elseif condition="$row['type'] eq 2" />
                                <div>
                                    <h3>平台满￥{pigcms{$row['money']|floatval}元,减<strong style="display: inline;font-size:14px;">￥{pigcms{$row['minus']|floatval}</strong>元活动与限时优惠、店铺/分类折扣、会员优惠不同享</h3>
                                </div>
                            <elseif condition="$row['type'] eq 3" />
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
                <if condition="!empty($noDiscountGoods)">
                <li><div><h3>{pigcms{$noDiscountGoods}不参与店铺/分类折扣优惠</h3></div></li>
                </if>
                <if condition="$plat_discount lt 10">
                <li><div><h3>商家对总价的折扣率：<strong style="display: inline;font-size:14px;">{pigcms{$plat_discount}折</strong></h3></div></li>
                </if>
			</ul>
		</if>
		<ul class="box pay_box markbox">
			<li>
				<a href="javascript:void(0);" id="remarkBtn">
					<strong>订单备注</strong>
					<span id="remarkTxt">点击添加订单备注</span>
					<div><i class="ico_arrow"></i></div>
				</a>
			</li>
			<li <if condition="$store['is_invoice'] AND $store['invoice_price'] elt $price">style="display:block"<else/>style="display:none"</if>>
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
	</section>
	<div style="display:none;">
	  <input class="hidden" id="order_id" name="order_id" value="{pigcms{$order_id}">
	  <input class="hidden" id="order_id" name="cartid" value="{pigcms{$cartid}">
	  <input class="hidden" id="ouserName" name="ouserName" value="{pigcms{$user_adress['name']}">
	  <input class="hidden" id="ouserTel" name="ouserTel" value="{pigcms{$user_adress['phone']}">
	  <input class="hidden" id="ouserAddres" name="ouserAddres" value="{pigcms{$user_adress['adress']} {pigcms{$user_adress['detail']}">
	  <input class="hidden" id="address_id" name="address_id" value="{pigcms{$user_adress['adress_id']}">
	  <input type="hidden" name="pick_address" value="{pigcms{$pick_address['area_info']['province']} {pigcms{$pick_address['area_info']['city']} {pigcms{$pick_address['area_info']['area']} {pigcms{$pick_address['name']} 电话：{pigcms{$pick_address['phone']}"/>
	  <input type="hidden" name="pick_id" value="{pigcms{$pick_address['pick_addr_id']}"/>
	  <input class="hidden" id="oarrivalDate" name="oarrivalDate" value="{pigcms{$dates.0.ymd}">
	  <input class="hidden" id="oarrivalTime" name="oarrivalTime" value="{pigcms{$dates.0.date_list.0.hour_minute}">
	  <input class="hidden" id="omark" name="omark" value="">
	  <input class="hidden" id="invoice_head" name="invoice_head" value="">
	  <input class="hidden" id="deliver_type" name="deliver_type" value="<if condition="$delivery_type eq 2 OR $pick_addr_id">1<else />0</if>"/>
      
      
      <input class="hidden" id="count_price" data-price="{pigcms{$price|floatval}" data-percent="{pigcms{$plat_discount|floatval}" data-deliver="{pigcms{$dates.0.date_list.0.delivery_fee|floatval}" data-extprice="{pigcms{$extraPrice|floatval}" data-packing_charge="{pigcms{$packing_charge|floatval}" />
      <input class="hidden" id="minus_price" data-price="{pigcms{$this_discount_price|floatval}" data-deliver="{pigcms{$delivery_fee_reduce|floatval}"/>
      
      
      
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
<div class="fixed" style="min-height:50px;padding:14px;">
    <if condition="$delivery_type eq 2 OR $pick_addr_id OR $now_time_value eq 2">
	<p class="fl">
		<div><strong style="color:#ff560c;">待支付：￥<span id="totalPrice_">{pigcms{:floatval(round(($price+$extraPrice+$packing_charge) * $plat_discount * 0.1, 2))}<if condition="$extra_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$extra_price|floatval}{pigcms{$config.extra_price_alias_name}</if></span></strong> | <strong style="color: gray;font-size: 8px;">已优惠￥<span id="cartNum_">{pigcms{:floatval(round($this_discount_price + ($price+$extraPrice+$packing_charge) * (1 - $plat_discount * 0.1), 2))}</span></strong></div>
	</p>
    <else />
    <p class="fl">
        <div><strong style="color:#ff560c;">待支付：￥<span id="totalPrice_">{pigcms{:floatval(round(($price+$dates[0]['date_list'][0]['delivery_fee']+$extraPrice+$packing_charge) * $plat_discount * 0.1, 2))}<if condition="$extra_price gt 0 AND $config.open_extra_price eq 1">+{pigcms{$extra_price|floatval}{pigcms{$config.extra_price_alias_name}</if></span></strong> | <strong style="color: gray;font-size: 8px;">已优惠￥<span id="cartNum_">{pigcms{:floatval(round($this_discount_price+$delivery_fee_reduce + (($price+$dates[0]['date_list'][0]['delivery_fee']+$extraPrice+$packing_charge) * (1 - ($plat_discount * 0.1))), 2))}</span></strong></div>
    </p>
    </if>
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
<div class="deliver_time_box">
	<div class="mask"></div>
	<div class="timer">
		<div class="header">期望送达</div>
		<div class="content">
			<div class="day">
				<volist name="dates" id="vo">
					<div class="item <if condition="$i eq 1">active</if>" data-date="{pigcms{$vo.ymd}">{pigcms{$vo.show_date}</div>
				</volist>
			</div>
			<div class="time" style="-webkit-overflow-scrolling:touch;">
				<volist name="dates" id="vo">
					<div class="item_box timer-{pigcms{$vo.ymd}" <if condition="$i neq 1">style="display:none;"</if>>
						<volist name="vo['date_list']" id="voo" key="k">
							<div class="item <if condition="$i eq 1 && $k eq 1">active</if>" data-show_date="{pigcms{$vo.show_date}" data-ymd="{pigcms{$vo.ymd}" data-time="{pigcms{$voo.hour_minute}" data-delivery_fee="{pigcms{$voo.delivery_fee_old}">{pigcms{$voo.hour_minute}<span class="price">({pigcms{$voo.delivery_fee_old}元配送费)</span></div>
						</volist>
					</div>
				</volist>
			</div>
		</div>
	</div>
</div>
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
	var payPrice = parseFloat($('#totalPrice_').html());
	if(payPrice < 0){
		$('#totalPrice_').html('0');
	}
	
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
	
	if($('#show_arrive_time').css('display') != 'none' && needDeliverTip){
		layer.open({title:'请注意：',content:'最近的配送时间是：'+$('#arriveTime').html(),btn:['确定'],shadeClose:false});
	}

	$('#show_arrive_time').click(function(){
		$('.deliver_time_box .mask').removeClass('hidden');
		$('.deliver_time_box').show();
	});
	$('.deliver_time_box .mask').click(function(){
		$('.deliver_time_box').hide();
	});
	
	$('.deliver_time_box .day .item').click(function(){
		$(this).addClass('active').siblings().removeClass('active');
		$('.timer-'+$(this).data('date')).show().siblings().hide();
		$('.deliver_time_box .time').scrollTop(0);
	});
	$('.deliver_time_box .time .item').click(function(){
		$('.deliver_time_box .time .item').removeClass('active');
		$(this).addClass('active');
		$('#arriveTime').html($(this).data('show_date')+' '+$(this).data('time'));
		
		$('#oarrivalDate').val($(this).data('ymd'));
		$('#oarrivalTime').val($(this).data('time'));
		$('#show_delivery_fee strong').html('￥'+$(this).data('delivery_fee'));

		$('#count_price').data('deliver', $(this).data('delivery_fee'));
        
        var percent = parseFloat($('#count_price').data('percent'));
        var payPrice = parseFloat(($('#count_price').data('price') + $(this).data('delivery_fee') + $('#count_price').data('extprice') + $('#count_price').data('packing_charge')).toFixed(2));
        var discountPrice = parseFloat(($('#minus_price').data('price') + $('#minus_price').data('deliver')).toFixed(2));
        if (percent < 10) {
            discountPrice = parseFloat((discountPrice + payPrice * (1 - percent * 0.1)).toFixed(2));
            payPrice = parseFloat((payPrice * percent * 0.1).toFixed(2));
        }
		if(payPrice < 0){
			payPrice = 0;
		}
        $('#totalPrice_').html(payPrice);
        $('#cartNum_').html(discountPrice);
        
//         $('#totalPrice_').html();
        //TODO
		setTimeout(function(){
			$('.deliver_time_box').hide();
		},150);
	});
	

	$('#post_package').click(function(){
		$(this).removeClass('pick_in_store_click').addClass('pick_in_store').siblings('.btn_express').removeClass('pick_in_store').addClass('pick_in_store_click');
		$('#li_pick').css('display', 'none');
		$('#li_delivery, #show_arrive_date, #show_arrive_time, #show_delivery_fee, #two_time_select, #deliverExtraPrice, .delivery_fee').show();
		$('#deliver_type').val(0);
        var payPrice = parseFloat(($('#count_price').data('price') + $('#count_price').data('deliver') + $('#count_price').data('extprice') + $('#count_price').data('packing_charge')).toFixed(2));
        var percent = parseFloat($('#count_price').data('percent'));
        var discountPrice = parseFloat(($('#minus_price').data('price') + $('#minus_price').data('deliver')).toFixed(2));
        if (percent < 10) {
            discountPrice = parseFloat((discountPrice + payPrice * (1 - percent * 0.1)).toFixed(2));
            payPrice = parseFloat((payPrice * percent * 0.1).toFixed(2));
        }
		if(payPrice < 0){
			payPrice = 0;
		}
		$('#totalPrice_').html(payPrice);
		$('#cartNum_').html(discountPrice);
	});
	$('#post_package_express').click(function(){
		$(this).removeClass('pick_in_store_click').addClass('pick_in_store').siblings('.btn_express').removeClass('pick_in_store').addClass('pick_in_store_click');
		$('#li_delivery').css('display', 'block');
		$('#li_pick, #show_arrive_date, #show_arrive_time, #show_delivery_fee, #two_time_select, #deliverExtraPrice, .delivery_fee').hide();
		$('#deliver_type').val(0);
		var percent = parseFloat($('#count_price').data('percent'));
        var payPrice = parseFloat(($('#count_price').data('price') + $('#count_price').data('deliver') + $('#count_price').data('extprice') + $('#count_price').data('packing_charge')).toFixed(2));
        var discountPrice = parseFloat(($('#minus_price').data('price') + $('#minus_price').data('deliver')).toFixed(2));
        if (percent < 10) {
            discountPrice = parseFloat((discountPrice + payPrice * (1 - percent * 0.1)).toFixed(2));
            payPrice = parseFloat((payPrice * percent * 0.1).toFixed(2));
        }
		if(payPrice < 0){
			payPrice = 0;
		}
        $('#totalPrice_').html(payPrice);
        $('#cartNum_').html(discountPrice);
	});
	$('#pick_in_store').click(function(){
		$(this).removeClass('pick_in_store_click').addClass('pick_in_store').siblings('.btn_express').removeClass('pick_in_store').addClass('pick_in_store_click');
		$('#li_pick').show();
		$('#li_delivery, #show_arrive_date, #show_arrive_time, #show_delivery_fee, #two_time_select, #deliverExtraPrice, .delivery_fee').css('display', 'none');
		$('#deliver_type').val(1);
        var percent = parseFloat($('#count_price').data('percent'));
        var payPrice = parseFloat(($('#count_price').data('price') + $('#count_price').data('packing_charge')).toFixed(2));
        var discountPrice = parseFloat(($('#minus_price').data('price')).toFixed(2));
        if (percent < 10) {
            discountPrice = parseFloat((discountPrice + payPrice * (1 - percent * 0.1)).toFixed(2));
            payPrice = parseFloat((payPrice * percent * 0.1).toFixed(2));
        }
		if(payPrice < 0){
			payPrice = 0;
		}
        $('#totalPrice_').html(payPrice);
        $('#cartNum_').html(discountPrice);
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
	
	if(sessionStorage.getItem('userMark')){
		$('#remarkTxt').text(sessionStorage.getItem('userMark'));
	}
	$('#saveRemark').bind('click', function(){
		sessionStorage.setItem('userMark',$('#userMark').val());
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
			
			$.post($('#cart_confirm_form').attr('action'), $('#cart_confirm_form').serialize(), function(response){
				if (response.status) {
					var order_id = $(this).data('order_id');
					if(typeof wx !=='undefined' && window.__wxjs_environment === 'miniprogram'){
						wx.miniProgram.navigateTo({
							url:'/pages/pay/index?order_id='+ response.info.order_id +'&type=shop'
							,complete:function(){
								$("#submit_order").removeClass('disabled');
							}
						});
					}else{
						$("#submit_order").removeClass('disabled');
						location.href = "{pigcms{:U('Pay/check',array('type'=>'shop'))}&order_id="+response.info.order_id;
					}
				} else {
					alert(response.info);
					$("#submit_order").removeClass('disabled');
				}
			});
			return false;
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
						$('body').append('<iframe src="pigcmso2o://getUserAddress/'+address_id+'/{pigcms{$store['store_id']}" style="display:none;"></iframe>');
						return false;
					});
				}else{
					$('#li_delivery a').click(function(){
						var address_id = $('#address_id').val() == '' ? '0' : $('#address_id').val();
						window.lifepasslogin.getUserAddress(address_id, {pigcms{$store['store_id']});
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
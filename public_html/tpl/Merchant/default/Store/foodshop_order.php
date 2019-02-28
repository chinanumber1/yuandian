<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/laytpl.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>

		<script type="text/javascript" src="{pigcms{$static_path}js/swiper-3.3.1.jquery.min.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/swiper-3.3.1.min.css"/>

		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaff_foodshop.css"/>
		<style>
			.remarkBg{
				position: fixed;
				top: 0;left: 0;
				right: 0;bottom: 0;
				background:rgba(0,0,0,.7);
				z-index: 10000;
				display: none;
			}
			.remarkContent{
				position: fixed;
				top: 50%;
				left: 50%;
				width: 400px;
		    	margin-left: -200px;
				height: 300px;
				margin-top:-150px;
				z-index: 10001;
				background: #fff;
				border-radius: 3px;
			}
			.itemTop{
				width: 100%;
				height: 40px;
				background: #2ECC71;
				color: #fff;
				line-height: 40px;
				text-align: center;
			}
			.itemContent textarea{
				width: 80%;
			    margin: 10px 7%;
			    border: 1px solid #f1f1f1;
			    resize: none;
			    padding: 8px 3%;
			}
			.itemBottom{
				margin-top: 10px;
				width: 100%;
				text-align: center;
			}
			.itemBottom span{
				display: inline-block;
				width: 40%;
			    height: 40px;
			    border-radius: 3px;
			    color: #fff;
			    line-height: 40px;
			}
			.itemBottom span:first-child{
				background: #999;
			}
			.itemBottom span:last-child{
				background: #2ECC71;
			}
			.addBei{
				position: absolute;
				right: 154px;
		    	margin-top: -23px;
				border: 1px solid #FF6000;
			    padding: 2px 6px;
			    border-radius: 3px;
			    color: #FF6000;
			    z-index: 10000;
			}
			.addRemark{
				color: #999;
				width: 100%;
				overflow: hidden;
				text-overflow:ellipsis;
				white-space: nowrap;
				font-size: 14px;
			}
		</style>
		<script type="text/javascript">
			var getFoodMenuUrl = "{pigcms{:U('foodshop_getmenu')}";
			var getFoodGroupDetailUrl = "{pigcms{:U('foodshop_getgroup_detail')}";
			var foodshopSaveOrder = "{pigcms{:U('foodshop_save_order')}";
			var foodshopEditOrder = "{pigcms{:U('foodshop_edit_order',array('order_id'=>$_GET['order_id']))}";
			var foodshopPrintOrder = "{pigcms{:U('foodshop_print_order',array('order_id'=>$_GET['order_id']))}";
			var foodshopChangeOrder = "{pigcms{:U('foodshop_change_order',array('order_id'=>$_GET['order_id']))}";
			var foodshopChangeOrderNote = "{pigcms{:U('foodshop_change_order_note',array('order_id'=>$_GET['order_id']))}";
			var foodshopPayOrder = "{pigcms{:U('store_arrival_add',array('business_type'=>'foodshop','business_id'=>$_GET['order_id']))}";
			var open_extra_price  = Number("{pigcms{$config.open_extra_price}");
			var extra_price_name  = "{pigcms{$config.extra_price_alias_name}";
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaff_foodshop_cart.js?t=1"></script>
	</head>
	<body class="foodshop_order">
		<div class="food_cart">
			<input type="hidden" id="order_id" value="{pigcms{$_GET.order_id}"/>
			<div id="cartBox">
				<div class="food_cart_header">
					购物车<span>(已点<span id="cartCount">0</span>)</span>
				</div>
				<div class="food_cart_list">
					<ul></ul>
				</div>
				<div style="margin-left:10px;color:green;font-size:14px;height:40px;overflow-y:auto;">客户留言：{pigcms{$order['note']|default='无'}</div>
				<div class="food_cart_footer">
					<div class="changeCart">已点菜品</div>
					<div class="checkCart">保存购物车</div>
				</div>
			</div>
			<div id="buyBox" style="display:none;">
				<div class="food_buy_header">
					已点菜品<span>(已点<span id="buyCount">0</span>)</span>
				</div>
				<div class="food_buy_list">
					<ul></ul>
				</div>
				<div style="margin-left:10px;color:green;font-size:14px;height:40px;overflow-y:auto;">客户留言：{pigcms{$order['note']|default='无'}</div>
				<div class="food_buy_footer">
					<div class="changeBuy">购物车</div>
					<span style="margin-top:50px;margin-right:10px;float:left;color:green;font-size:14px;">
					<p style="margin-bottom:5px" id="total_price"><b>订单总价：</b>{pigcms{$total_price|floatval}<if condition="$config.open_extra_price AND $extra_price gt 0">+{pigcms{$extra_price}{pigcms{$config.extra_price_alias_name}</if></p>
					<p style="margin-bottom:5px" id="book_price"><b>已付订金：</b>{pigcms{$order['price']|floatval}</p>
					<p id="unpaid_price"><b>还应支付：</b>{pigcms{$price|floatval}<if condition="$config.open_extra_price AND $extra_price gt 0">+{pigcms{$extra_price}{pigcms{$config.extra_price_alias_name}</if></p>
					</span>
				</div>
			</div>
		</div>
		<div class="food_menu">
			<div class="swiper-button-prev"></div>
			<div class="food_cat swiper-container">
				<ul class="swiper-wrapper"></ul>
			</div>
			<div class="swiper-button-next"></div>
			<div class="food_body"></div>
			<div class="foot_tool">
				<div class="food_tool_left">
					<ul>
						<if condition="$goods_package">
						<li id="use_group">使用套餐</li>
						</if>
						<li id="edit_order">编辑订单</li>
						<li id="print_order">打印订单</li>
					</ul>
				</div>
				<div class="food_tool_right" id="pay_order">
					结算
				</div>
			</div>
		</div>
		<div class="food_spec_box"></div>
		<div class="food_group_box">
			<ul></ul>
		</div>
		<div class="remarkBg">
			<div class="remarkContent">
				<div class="itemTop">添加备注</div>
				<div class="itemContent">
                    <input type="hidden" name="detail_id" id="detail_id" />
                    <input type="hidden" name="detail_key" id="detail_key" />
					<textarea name="note" id="note" cols="5" rows="8" placeholder="请输入备注内容"></textarea>
				</div>
				<div class="itemBottom">
					<span  class="qu">取消</span>
					<span class="que">确认</span>
				</div>
			</div>
		</div>
		<script id="foodGroupDetailTpl" type="text/html">
			<table>
				{{# for(var i in d){  }}
					<tr class="group_info group_info_{{ d[i].id }}" data-group_id="{{ d[i].id }}" data-maxnum="{{ d[i].goods_list.length }}" data-selectnum="{{ d[i].num }}">
						<td rowspan="{{ d[i].goods_list.length }}" class="left">
							{{ d[i].goods_list.length }} 选 {{ d[i].num }}
						</td>
						<td class="right group_info_row_{{ d[i].id }}" data-group_row_id="{{ d[i].id }}" data-row_id="{{ d[i].goods_list[0].goods_id }}">
							<div class="group_info_txt">{{ d[i].goods_list[0].name }}</div>
							<div class="group_info_checkbox"></div>
						</td>
					</tr>
					{{# for(var j in d[i].goods_list){ }}
						{{# if(j > 0){ }}
							<tr>
								<td class="right group_info_row_{{ d[i].id }}" data-group_row_id="{{ d[i].id }}" data-row_id="{{ d[i].goods_list[j].goods_id }}">
									<div class="group_info_txt">{{ d[i].goods_list[j].name }}</div>
									<div class="group_info_checkbox"></div>
								</td>
							</tr>
						{{# } }}
					{{# } }}
				{{# } }}
			</table>
		</script>
		<script id="foodGroupTpl" type="text/html">
			{{# for(var i in d){ }}
				<li>
					<div class="checkRadio" data-group_id="{{ d[i].id }}"></div>
					<div class="textInfo">
						<div class="name">{{ d[i].name }}</div>
						<div class="addRemark"></div>
						<div class="price">￥{{ d[i].price }}</div>

					</div>
					<div class="group_info_box"></div>
				</li>
			{{# } }}
		</script>
		<script id="foodSortTpl" type="text/html">
			{{# for(var i in d){ }}
				<li data-sort_id="{{ d[i].sort_id }}" class="swiper-slide">{{ d[i].sort_name }}</li>
			{{# } }}
		</script>
		<script id="foodBuyTpl" type="text/html">
			<li class="productBuyKey_{{ d.productKey }} buy_{{d.uid}}" data-uid="{{d.uid}}" data-detail_id="{{ d.detail_id }}" data-key="{{ d.productKey }}">
				<div class="name">
					{{ d.productName }} {{# if (parseInt(d.package_id) > 0) { }}(套餐){{# } }}
					<span>
						{{# if(d.productParam.length > 0){
								var tmpNameArr = [];
								for(var i in d.productParam){
									if(d.productParam[i].type == 'spec'){
										tmpNameArr.push(d.productParam[i].name);
									}else{
										for(var j in d.productParam[i].data){
											tmpNameArr.push(d.productParam[i].data[j].name);
										}
									}
								}
								}}
									{{ tmpNameArr.join(' ') }}
								{{#
							}else if(d.productLabel){
								}}
									{{ d.productLabel }}
								{{#
							}
						}}
					</span>
				</div>
				<div class="addRemark">{{ d.note }}</div>
				<div class="price">￥{{ d.productPrice }}{{# if(d.extra_price>0&&open_extra_price==1){ }}<font size="1">+{{ d.extra_price }}{{ extra_price_name }}</font> {{# } }}<span>/{{ d.productUnit }}</span></div>

                
				{{# if(d.is_must == 0 && parseInt(d.package_id) < 1){ }}
				<div class="cart_btn">
					<div class="plus">+</div>
					<div class="number"><input value="{{ d.count }}"/></div>
					<div class="min">-</div>
				</div>
				{{# } else { }}
				<div class="cart_btn">
					<div class="number"><input value="{{ d.count }}" readonly/></div>
				</div>
				{{# } }}
			</li>
		</script>
		<script id="foodCartTpl" type="text/html">
			<li class="productCartKey_{{ d.productKey }} {{# if(d.isTmpOrder){ }}tmp_order{{# } }} tmp_{{d.uid}}" data-uid="{{d.uid}}" data-key="{{ d.productKey }}">
				<div class="name">
					{{ d.productName }} {{# if (parseInt(d.package_id) > 0) { }}(套餐){{# } }}
					<span>
						{{# if(d.productParam.length > 0){
								var tmpNameArr = [];
								for(var i in d.productParam){
									if(d.productParam[i].type == 'spec'){
										tmpNameArr.push(d.productParam[i].name);
									}else{
										for(var j in d.productParam[i].data){
											tmpNameArr.push(d.productParam[i].data[j].name);
										}
									}
								}
								}}
									{{ tmpNameArr.join(' ') }}
								{{#
							}else if(d.productLabel){
								}}
									{{ d.productLabel }}
								{{#
							}
						}}
					</span>
				</div>
                <div class="addRemark">{{ d.note }}</div>
				<div class="price">￥{{ d.productPrice }}{{# if(d.extra_price>0&&open_extra_price==1){ }}<font size="1">+{{ d.extra_price }}{{ extra_price_name }}</font> {{# } }}<span>/{{ d.productUnit }}</span></div>
				{{# if (d.note.length > 0) { }}
                <div class="addBei" data-note="{{ d.note }}"  data-key="{{ d.productKey }}">修改备注</div>
                {{# } else { }}
                <div class="addBei" data-note="{{ d.note }}"  data-key="{{ d.productKey }}">加备注</div>
                {{# } }}
                {{# if (parseInt(d.package_id) > 0) { }}
                <div class="cart_btn">
					<div class="number"><input value="{{ d.count }}" readonly/></div>
				</div>
                {{# } else { }}
                <div class="cart_btn">
					<div class="plus">+</div>
					<div class="number"><input value="{{ d.count }}"/></div>
					<div class="min">-</div>
				</div>
                {{# } }}
			</li>
		</script>
		<script id="foodTpl" type="text/html">
			{{# for(var i in d){ }}
				<ul class="sort_ul_{{ d[i].sort_id }}">
					{{# for(var j in d[i].goods_list){ }}
						<li class="sort_{{ d[i].sort_id }} food_{{ d[i].goods_list[j].goods_id }} {{# if(d[i].goods_list[j].spec_list || d[i].goods_list[j].properties_list){ }}hasSpec{{# } }}" data-id="{{ d[i].goods_list[j].goods_id }}" data-sort_id="{{ d[i].sort_id }}" data-name="{{ d[i].goods_list[j].name }}" data-price="{{ d[i].goods_list[j].price }}" data-unit="{{ d[i].goods_list[j].unit }}" data-stock_num="{{ d[i].goods_list[j].stock_num }}"  data-extra_price_name="{{ d[i].goods_list[j].extra_price_name }}" data-extra_price="{{ d[i].goods_list[j].extra_pay_price }}">
							<div class="food_img">
								<img src="{{ d[i].goods_list[j].pic_arr[0].url.s_image }}"/>
							</div>
							<div class="food_text">
								{{ d[i].goods_list[j].name }}
								{{# if(d[i].goods_list[j].spec_list){ }}
									<div>多规格</div>
								{{# }else{ }}

									<div><span>{{ d[i].goods_list[j].price }}</span>元 {{# if(d[i].goods_list[j].extra_pay_price>0){ }}+{{ d[i].goods_list[j].extra_pay_price }}{{ d[i].goods_list[j].extra_price_name }} {{# } }}/{{ d[i].goods_list[j].unit }}</div>
								{{# } }}
							</div>
						</li>
					{{# } }}
				</ul>
			{{# } }}
		</script>
		<script id="foodSpecTpl" type="text/html">
			<div class="spec_title">
				<div class="name">{{ d.name }}</div>
				<div class="price">￥<span id="specPrice">{{ d.price }}</span><span id="specStock" style="display:none;"></span></div>
				<div class="close_spec"></div>
			</div>
			<div class="spec_pro_box">
				<div class="spec_content">
					{{# for(var i in d.spec_list){ }}
						<div class="row clearfix">
							<div class="spec_name">{{ d.spec_list[i].name }}：</div>
							<div class="spec_box">
								<ul>
									{{# var k = 0; for(var j in d.spec_list[i].list){ }}
										<li class="{{# if(k == 0){ }}active{{# } }}" data-spec_list_id="{{ d.spec_list[i].list[j].id }}"  data-spec_id="{{ d.spec_list[i].list[j].sid}}">{{ d.spec_list[i].list[j].name }}</li>
									{{#  k++; } }}
								</ul>
							</div>
						</div>
					{{# } }}
				</div>
				<div class="properties_content">
					{{# for(var i in d.properties_list){ }}
						<div class="row clearfix productProperties_{{ d.properties_list[i].id }}" data-num="{{ d.properties_list[i].num }}">
							<div class="properties_name">{{ d.properties_list[i].name }}：</div>
							<div class="properties_box">
								<ul>
									{{# var k = 0; for(var j in d.properties_list[i].val){ }}
										<li class="{{# if(d.properties_list[i].num == 1 && k == 0){ }}active{{# } }}" data-label_list_id="{{ i }}" data-label_id="{{ j }}">{{ d.properties_list[i].val[j] }}</li>
									{{#  k++; } }}
								</ul>
							</div>
						</div>
					{{# } }}
				</div>
			</div>
			<div class="spec_btn">加入购物车</div>
		</script>
	</body>
</html>
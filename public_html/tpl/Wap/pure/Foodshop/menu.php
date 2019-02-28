<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$store['name']}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/css_whir.css"/>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/foodshopmenu.js" charset="utf-8"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
<script>var store_id = '{pigcms{$store["store_id"]}', all_goods = '{pigcms{$all_goods}', submit_url = '{pigcms{:U("Foodshop/order_detail", array("store_id" => $order["store_id"], "order_id" => $order["order_id"]))}', cookie_index = 'foodshop_cart_{pigcms{$store["store_id"]}_order_{pigcms{$order["order_id"]}';
var  open_extra_price =Number('{pigcms{$config.open_extra_price}');
var saveGoods = '{pigcms{:U("Foodshop/saveGoods", array("orderid" => $order["real_orderid"]))}';
</script>
<style>
	.mask {
			position: fixed;
			left: 0;
		    top: 0;
		    width: 100%;
		    height: 100%;
		    background-color: rgba(0, 0, 0, .2);
		    z-index: 100;
		}
		.hidden{display: none;}
		.mask div{

			width: 100%;
			height: 250px;
			position: fixed;
			top:50%;
			margin-top: -125px;
			background: #fff;
		}
		.mask img{
			width: 100%;
			height: 250px;
		}
		.rg{float:right;}
		.ft{float:left;}
		.after:after{content:" ";display:block;clear:both;}
		.newAdd p{font-size: 16px;color: #333;}
		.newAdd .user_size{
			width: 97%;
			margin:0 3% 0 0;
			font-size: 12px;
			color: #999;
			padding: 2px 0;
			display: -webkit-box;
			-webkit-box-orient: vertical;
			-webkit-line-clamp: 2;
			overflow: hidden;
		}
		.xuan_food .ft{
			color: #666;
			font-size: 14px;
		}
		.xuan_food .ft span{
			color:#FF6000;
			font-size:16px;
		}
		.xuan_food .rg{
			margin-right: 3%;
			font-size:13px;
			margin-top: 3px;
		}
		.xuan_food .rg span{
			padding: 2px 6px;
			background: #FF6000;
			border-radius:30px;
			color: #fff;

		}
		.Tcancel{ display: none; width: 80%;   background-color: #fff;  position: fixed; top: 0px; left: 0px; height: 350px; margin:10%; border-radius: 5px; top: 50%; margin-top: -175px; z-index: 10001;}
		.Tcancel .Tcancel_top{  font-size: 16px; padding-left: 106px;  padding-top:55px; height: 27px; color: #999999; }
		.TcancelT{ background: #fff ;  }
		.TcancelT_top{ padding: 8px 15px 0 15px; height: 55px;  background: #e7ebee; border-radius: 5px 5px 0 0;  }
		.TcancelT_top .TcancelT_topL{ float: left;  }
		.TcancelT_top a{background: url({pigcms{$static_path}foodshop/img/xx_06.png) right center no-repeat; background-size: 27px; display: block; float: right; width: 27px; height: 27px; margin-top: 10px;}
		.TcancelT_top h2{ font-size: 16px; margin-bottom: 8px; }
		.TcancelT_top span{ color: #ff6000; font-size: 18px; height: 20px; line-height: 20px; }
		.TcancelT_top span i{ font-size: 12px; }
		.TcancelT_zh{ padding: 20px 15px; height: 130px;  overflow-y: auto;  }
		.Tcancel .join{ padding:15px; }
		.Tcancel .join input{ line-height: 40px; background: #2ecc71; color: #ffffff; font-size: 16px; width: 100%; border-radius: 5px; }

		.setmenu{ height: 400px; margin-top: -200px;  }
		.setmenu_list{ padding: 5px; height: 250px; overflow-y: auto; }
		.setmenu_list dd{border: #e6e6e6 1px solid; border-bottom: none;}
		.setmenu_list .condition{  width: 60px; height: 100%; text-align: center; font-size: 14px; color: #ff6000; display: table; }
		.setmenu_list .condition span{ display: table-cell; vertical-align: middle; }
		.setmenu_list .set_list{ overflow: hidden; border-left: #e6e6e6 1px solid;    }
		.setmenu_list .set_list li{ padding: 8px 10px; border-bottom: #e6e6e6 1px solid; padding-right: 25px; line-height: 18px; position: relative; }
		.setmenu_list .set_list li:last-child{ border-bottom: none; }
		.setmenu_list .set_list li:after{ content: ' ';position: absolute; top: 10px; right: 10px; display: block; width: 15px; height: 15px; background: url({pigcms{$static_path}foodshop/img/ad1.png) center no-repeat; background-size: 15px;  }
		.setmenu_list .set_list li.on:after{ background: url({pigcms{$static_path}foodshop/img/ad2.png) center no-repeat; background-size: 15px; }
		.setmenu_list .set_list li h2{ font-size: 12px; color: #666666; }
		.setmenu_list .set_list li p{  color: #ff6000; font-size: 11px; }
		/*.set_list>ul,.condition.fl{
			    border-bottom: #e6e6e6 1px solid;
		}*/
		.mask122{
			width: 100%;
		    background: url(../images/bj_10.png);
		    position: fixed;
		    top: 0px;
		    left: 0px;
		    bottom:0;
		    right: 0;
		    z-index: 10000;
		    display:none;
		    background: rgba(0,0,0,.8);
		}
		.motify{
			z-index: 10002;
		}
        .stock{font-size: 12px !important;color: #999 !important;margin-left: 20px !important;}
</style>
</head>
<body>
	<section class="foodleft">
		<!--div class="search">
			<input type="text" placeholder="搜索您想吃的" class="sr">
			<a href="#">搜索</a>
		</div-->
		<div class="foodnav">
			<ul>
				<volist name="goods_list" id="sort" key="i">
				<li><a href="javascript:void(0)" data-cat_id="{pigcms{$sort['sort_id']}" <if condition="$i eq 1">class="on"</if>>{pigcms{$sort['sort_name']}</a></li>
				</volist>
			</ul>
		</div>
	</section>
	<section class="foodright">
		<volist name="goods_list" id="rowset">
		<dl data-cat_id="{pigcms{$rowset['sort_id']}" class="foodright-{pigcms{$rowset['sort_id']}">
			<dt>{pigcms{$rowset['sort_name']}</dt>
			<volist name="rowset['goods_list']" id="goods">
            <php>if ($goods['goods_id'] == 0) {</php>
            <dd class="goods_ newAdd">
                <p class="packName">{pigcms{$goods['name']}</p>
                <div class="user_size">{pigcms{$goods['note']}</div>
                <div class="xuan_food after">
                    <p class="ft"><span>￥<span class="pacMon">{pigcms{$goods['price']}</span></span>/份</p>
                    <p class="rg packageSpeci" data-id="{pigcms{$goods['id']}" style="cursor: pointer;"><span>选菜品</span></p>
                </div>
            </dd>
            <php> } else { </php>
			<dd class="goods_{pigcms{$goods['goods_id']}">
				<div class="foodr_img">
					<img src="{pigcms{$goods['pic_arr'][0]['url']['s_image']}">
				</div>
				<div class="food_right">
					<h2>{pigcms{$goods['name']}</h2>
					<div class="MenuPrice">
						<i>￥</i>{pigcms{$goods['price']}<if condition="$goods.extra_pay_price gt 0 AND $config.open_extra_price eq 1">+<em style="font-size:12px;color:#f03c3c">{pigcms{$goods.extra_pay_price}{pigcms{$config.extra_price_alias_name}</em></if><em>/{pigcms{$goods['unit']}</em>
                        <php>if ($goods['stock_num'] < 10 && $goods['stock_num'] != -1) { </php>
                        <em>还剩{pigcms{$goods['stock_num']}{pigcms{$goods['unit']}</em>
                        <php> } </php>
					</div>
					<if condition="$goods['spec_list'] OR $goods['properties_list']">
					<div class="Addsub">
						<span class="Speci">选规格</span>
					</div>
					<else />
	                <div class="Addsub">
	                    <a href="javascript:void(0)" class="jian" data-stock_num="{pigcms{$goods['stock_num']|intval}" data-price="{pigcms{$goods['price']|floatval}" data-id="{pigcms{$goods['goods_id']}" data-index="{pigcms{$goods['goods_id']}" data-name="{pigcms{$goods['name']}" data-extra_pay_price="{pigcms{$goods.extra_pay_price}" data-extra_price_name = "{pigcms{$config.extra_price_alias_name}"></a>
						<input type="text" value="0" readOnly="true" class="num">
	                    <a href="javascript:void(0)" class="jia" data-stock_num="{pigcms{$goods['stock_num']|intval}" data-price="{pigcms{$goods['price']|floatval}" data-id="{pigcms{$goods['goods_id']}" data-index="{pigcms{$goods['goods_id']}" data-name="{pigcms{$goods['name']}" data-extra_pay_price="{pigcms{$goods.extra_pay_price}" data-extra_price_name = "{pigcms{$config.extra_price_alias_name}"></a>
					</div>
					</if>
				</div>
				<if condition="$goods['spec_list'] OR $goods['properties_list']">
					<section class="Tcancel TcancelT">
						<div class="TcancelT_top clr">
							<div class="TcancelT_topL">
								<h2>{pigcms{$goods['name']}</h2>
								<span class="price"><i>￥</i>{pigcms{$goods['price']}</span><span class="stock"><php>if ($goods['stock_num'] < 10 && $goods['stock_num'] >= 0) { </php>剩余：{pigcms{$goods['stock_num']|intval}<php>}</php></span>
							</div>
							<a href="javascript:void(0)" class="gb"></a>
						</div>
						<div class="TcancelT_zh">
							<div class="TcancelT_n">
								<volist name="goods['spec_list']" id="spec_r">
								<div class="TcancelT_list">
									<h2>{pigcms{$spec_r['name']}</h2>
									<div class="fications" data-id="{pigcms{$spec_r['id']}" data-num="1" data-name="{pigcms{$spec_r['name']}" data-type="spec">
										<ul class="clr" >
											<?php foreach ($spec_r['list'] as $srow) {?>
											<li data-id="{pigcms{$srow['id']}" data-name="{pigcms{$srow['name']}" data-type="spec" data-goods_id="{pigcms{$goods['goods_id']}">{pigcms{$srow['name']}</li>
											<?php }?>
										</ul>
									</div>
								</div>
								</volist>
								<volist name="goods['properties_list']" id="pro_r">
								<div class="TcancelT_list">
									<h2>{pigcms{$pro_r['name']}</h2>
									<div class="fications" data-id="{pigcms{$pro_r['id']}" data-name="{pigcms{$pro_r['name']}" data-num="{pigcms{$pro_r['num']}" data-type="properties" id="properties_{pigcms{$pro_r['id']}">
										<ul class="clr" >
											<?php foreach ($pro_r['val'] as $k => $val) {?>
											<li data-id="{pigcms{$k}" data-name="{pigcms{$val}" data-type="properties" data-goods_id="{pigcms{$goods['goods_id']}">{pigcms{$val}</li>
											<?php }?>
										</ul>
									</div>
								</div>
								</volist>
							</div>
						</div>
						<div class="Selected">
							已选：<span></span>
						</div>
						<div class="join" data-stock_num="{pigcms{$goods['stock_num']}" data-goods_id="{pigcms{$goods['goods_id']}" data-name="{pigcms{$goods['name']}" data-price="{pigcms{$goods['price']}">
							<input type="button" value="加入菜单">
						</div>
					</section>
				</if>
			</dd>
            <php>}</php>
			</volist>
		</dl>
		</volist>
	</section>
	<div class="Mask"></div>
	<section class="floor clr">
		<div class="trolley"></div>
		<div class="qty">0</div>
		<div class="prix">￥<i id="total_price">0</i></div>
<!-- 		<form name="cart_confirm_form" action="{pigcms{:U('Foodshop/order_detail', array('order_id' => $order['order_id']))}" method="post"> -->
		<input type="hidden" name="store_id" value="{pigcms{$store['store_id']}" />
		<input type="hidden" name="order_id" value="{pigcms{$order['order_id']}" />
		<input type="button" class="next" value="下一步">
<!-- 		</form> -->
		<!--a href="javascirpt:void(0);" class="next">下一步</a-->
	</section>
	<section class="Cart">
		<div class="Cart_top clr">
			<h2>购物车</h2>
			<span>清空</span>
		</div>
		<div class="Cart_list"><ul></ul></div>
	</section>
	<div class="mask hidden mask_img">
		<div>
			<img src="" alt="" class="mask_img">
		</div>		
	</div>
    <section class="Tcancel setmenu"></section>

    <script id="groupDetailTpl" type="text/html">
    <div class="TcancelT_top clr">
        <div class="TcancelT_topL">
            <h2>{{d.name}}</h2>
            <span class="clr"> <i class="fl">￥</i> <em class="fl">{{d.price}}</em>
            </span>
        </div>
        <a href="javascript:void(0)" class="gb"></a>
    </div>
    <div class="setmenu_list">
        <dl>
            {{# for (var i in d.goods_detail) { }}
            <dd>
                <div class="condition fl"><span>{{d.goods_detail[i].goods_list.length}}选{{d.goods_detail[i].num}}</span></div>
                <div class="set_list">
                    <ul data-num="{{d.goods_detail[i].num}}">
                        {{# for (var ii in d.goods_detail[i].goods_list) { }}
                        {{# if (d.goods_detail[i].num == 1 && ii == 0) { }}
                        <li class="on" style="cursor: pointer;" data-goods_id="{{d.goods_detail[i].goods_list[ii].goods_id}}" data-unit="{{d.goods_detail[i].goods_list[ii].unit}}" data-price="{{d.goods_detail[i].goods_list[ii].price}}" data-name="{{d.goods_detail[i].goods_list[ii].name}}">
                            <h2>{{d.goods_detail[i].goods_list[ii].name}}</h2>
                        </li>
                        {{# } else { }}
                        <li style="cursor: pointer;" data-goods_id="{{d.goods_detail[i].goods_list[ii].goods_id}}" data-unit="{{d.goods_detail[i].goods_list[ii].unit}}" data-price="{{d.goods_detail[i].goods_list[ii].price}}" data-name="{{d.goods_detail[i].goods_list[ii].name}}">
                            <h2>{{d.goods_detail[i].goods_list[ii].name}}</h2>
                        </li>
                        {{# } }}
                        {{# } }}
                    </ul>
                </div>
            </dd>
            {{# } }}
        </dl>
    </div>
    <div class="join" data-id="{{d.id}}" data-price="{{d.price}}" data-name="{{d.name}}">
        <input type="submit" value="加入菜单" >
    </div>
    </script>
</body>
</html>
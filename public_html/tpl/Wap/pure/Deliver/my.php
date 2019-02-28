<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>配送列表</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<link href="{pigcms{$static_path}css/deliver.css" rel="stylesheet"/>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
	<script src="https://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/report_location.js" charset="utf-8"></script>
    <style>
        .acc span{
            position: relative;
            top:-40px;
        }
        .f14.c9.acc{
            height: 55px;
            padding-top: 5px;
        }
        .acc img{
            width:50px;
            height: 50px;
            margin:0 3px;
        }
        .mask{
            position: fixed;
            z-index: 1000;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            background-color: rgba(0, 0, 0, .3);
             
        }
        .add{
            display: table;
            font-family: Helvetica, arial, sans-serif;
            pointer-events: none;
            position: fixed;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
        }
        .hidden{
            display: none;
        }
         .section {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
    </style>
</head>
<body>
<section class="Navigation">
	<ul class="clr">
		<a href="{pigcms{:U('Deliver/pick')}"><li class="on">待取货</li></a>
		<a href="{pigcms{:U('Deliver/send')}"><li class="on1">待配送</li></a>
		<a href="{pigcms{:U('Deliver/my')}"><li class="on2" style="border-bottom:#19caad 2px solid;color:#19caad">配送中</li></a>
	</ul>
</section>
<section class="nav_end clr">
	<div class="Dgrab" id="Dgrab1">
		<if condition="$list">
		<volist name="list" id="row">
		<section class="robbed supply_{pigcms{$row['supply_id']} go_detail" data-id="{pigcms{$row.supply_id}">
			<div class="Online c9 p10 f14" data-id="{pigcms{$row.supply_id}">
				<if condition="$row['fetch_number']">
				<span>取单编号: <strong style="color: red">{pigcms{$row['fetch_number']}</strong></span>
				<else />
				<span>订单编号: {pigcms{$row['real_orderid']}</span>
				</if>
				<if condition="$row['pay_method'] eq 1">
				<a href="javascript:;" class="fr cd p10">在线支付</a>
				<else />
				<a href="javascript:;" class="fr cd p10 on">货到付款</a>
				</if>
			</div>
			<php> if($row['item'] == 3) { </php>
				<div class="Title m10 go_detail" data-id="{pigcms{$row.supply_id}">
                    <if condition="$row['server_type'] eq 1">
                    <p class="f14 c9">商品类型：{pigcms{$row['goods_name']}</p>
                    <p class="f14 c9">商品重量：{pigcms{$row['goods_weight']}千克</p>
                    <p class="f14 c9">商品价格：{pigcms{$row['goods_price']}</p>
                    <php>if ($row['image']) {</php>
                    <p class="f14 c9 acc"><span>商品图片:</span>
                        <php> foreach($row['image'] as $img) {</php>
                        <img class="img_click" src="{pigcms{$img}" alt="" >
                        <php> } </php>
                    </p>
                    <php> } </php>
                    <div class="leaflets">帮送</div>
                    <else />
                    <p class="f14 c9">商品名称：{pigcms{$row['goods_name']}</p>
                    <p class="f14 c9">商品估价：{pigcms{$row['goods_price']}元</p>
                    <php>if ($row['image']) {</php>
                    <p class="f14 c9 acc"><span>商品图片:</span>
                        <php> foreach($row['image'] as $img) {</php>
                        <img class="img_click" src="{pigcms{$img}" alt="" >
                        <php> } </php>
                    </p>
                    <php> } </php>
                    <div class="leaflets">帮买</div>
                    </if>
            		
            	</div>
            	<div class="delivery m10">
                    <php> if (!empty($row['from_site'])) { </php>
            		<p class="f14 c6 on">
            			<a href="javascript:;" class="clr">
            				<span class="fl">取</span>
            				<em class="fl">{pigcms{$row['from_site']}</em>
            			</a>
            		</p>
                    <php> } </php>
            		<p class="f14 c6 on1">
    					<a href="{pigcms{$row['map_url']}" class="clr">
    						<span class="fl">送</span>
    						<em class="fl">{pigcms{$row['aim_site']}</em>
    						<i class="cd f14 fl">查看路线</i>
    					</a>
            		</p>
            	</div>
            	<div class="Namelist p10 f14">
            		<h2 class="f15 c3">{pigcms{$row['name']} <span class="c6"><a href="tel:{pigcms{$row['phone']}">{pigcms{$row['phone']}</a></span></h2>
                    <php> if ($row['server_type'] == 1) { </php>
            		<p class="c9">取货时间：{pigcms{$row['server_time']}</p>
                    <php> } else { </php>
                    <p class="c9">期望送达：{pigcms{$row['appoint_time']}</p>
                    <php> } </php>
            		<p class="red">额外小费：<i>{pigcms{$row['tip_price']|floatval}</i>元</p>
            		<p class="red">配送距离{pigcms{$row['distance']}公里，配送费{pigcms{$row['freight_charge']}元</p>
    				<if condition="$row['get_type'] eq 2">
    				<div class="Order">订单来源于{pigcms{$row['change_name']}配送员</div>
    				</if>
            	</div>
			<php> } else { </php>
			
			<div class="Title m10 go_detail" data-id="{pigcms{$row.supply_id}">
				<h2 class="f16 c3">{pigcms{$row['store_name']}</h2>
				<p class="f14 c9">下单时间：{pigcms{$row['order_time']}</p>
				<if condition="$row['get_type'] eq 1">
				<div class="leaflets">系统派单</div>
				</if>
			</div>
			<div class="delivery m10">
				<p class="f14 c6 on">
					<a href="javascript:;" class="clr">
						<span class="fl">取</span>
						<em class="fl">{pigcms{$row['from_site']}</em>   
					</a>
				</p>
				<p class="f14 c6 on1">
					<a href="{pigcms{$row['map_url']}" class="clr">
						<span class="fl">送</span>
						<em class="fl">{pigcms{$row['aim_site']}</em>
						<i class="cd f14 fl">查看路线</i>
					</a>    
				</p>
			</div>
			<div class="Namelist p10 f14">
				<h2 class="f15 c3">{pigcms{$row['name']} <span class="c6"><a href="tel:{pigcms{$row['phone']}">{pigcms{$row['phone']}</a></span></h2> 
				<p class="c9">期望送达：{pigcms{$row['appoint_time']}</p>
				<if condition="$row['note']">
				<p class="c9">客户备注：{pigcms{$row['note']}</p>
				</if>
				<p class="red">应收现金：<i>{pigcms{$row['deliver_cash']}</i>元</p>
				<p class="red">配送距离{pigcms{$row['distance']}公里，配送费{pigcms{$row['freight_charge']}元</p>
				<if condition="$row['get_type'] eq 2">
				<div class="Order">订单来源于{pigcms{$row['change_name']}配送员</div>
				</if>
			</div>
			<php>}</php>
			<div class="sign_bottom">
				<a href="javascript:;" class="service" data-id="{pigcms{$row['supply_id']}">送达</a>
			</div>
		</section>
		</volist>
		<else />
			<div class="psnone">
				<img src="{pigcms{$static_path}images/qdz_02.jpg">
			</div>
		</if>
	</div>
    <div class="mask hidden img_close">
        <div class="add">
            <div class="section">
                <img class="img_close" src="" alt="" style="width: 100%;">
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
$('body').off('click','.img_click').on('click','.img_click',function(e){
    e.stopPropagation();
    var sic=$(this).prop('src');
    $('.mask').removeClass('hidden');
    $('.mask img').prop('src',sic);
    $('.img_close').click(function(e){
        $('.mask').addClass('hidden');
    });
});
$(function(){
	$(".delivery p em").each(function(){
		$(this).width($(window).width() - $(this).siblings("i").width() - 55); 
	});
	$(".Dgrab").css({"margin-top":"40px"});
	$(".nav_end .Dgrab").width($(window).width());

	var DeliverListUrl = "{pigcms{:U('Deliver/my')}";
	var mark = 0;

	function grab(e) {
		if (mark) {
			return false;
		}
		mark = 1;
		e.stopPropagation();
		var supply_id = $(this).attr("data-id");
		$.post(DeliverListUrl, "supply_id="+supply_id, function(json){
			mark = 0;
			if (json.status) {
				layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:'配送成功~',btn: ['确定'],end:function(){}});
			} else {
				layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:'系统出错~',btn: ['确定'],end:function(){}});
			}
			$(".supply_"+supply_id).remove();
		});
	}

	$(".service").bind("click", grab);
	$(document).on("click", '.go_detail', function(e){
        e.stopPropagation();
        location.href = "{pigcms{:U('Wap/Deliver/detail')}&supply_id=" + $(this).attr("data-id");
    });
});
</script>
<include file="menu"/>
</body>
</html>
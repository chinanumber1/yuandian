<!DOCTYPE html>
 <html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
	<meta name="format-detection" content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<title>{pigcms{$storeName.name}</title>
	<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
	<meta name="description" content="{pigcms{$config.seo_description}" />
</head>

<body class=" hIphone" style="padding-bottom: initial;background: #ecedf1;">
<div id="fis_elm__0"></div>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/lib_3a812b5.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/style_dd39d16.css">
<!-- <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/orderhistory_c6670c7.css"> -->
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}shop/css/order_4bc7e9e.css">
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<div id="fis_elm__1"></div>
<img src="{pigcms{$static_path}shop/images/hm.gif" width="0" height="0" style="display:block">
<div id="wrapper" class="">
    <div id="fis_elm__2">
        <div id="common-widget-nav" class="common-widget-nav ">
            <div class="left-slogan"> <a class="left-arrow icon-arrow-left2" data-node="navBack" href="javascript:history.go(-1);"></a> </div>
            <div class="center-title"> <a href="javascript:void(0)">{pigcms{$storeName.name}</a> </div>
            <div class="right-slogan "> <a class="tel-btn" href="tel:{pigcms{$storeName.phone}"><i class="icon-phone"></i></a> </div>
        </div>
    </div>
    <div id="fis_elm__3">
        <div id="common-widget-tab" class="common-widget-tab">
            <ul class="order-tab">
                <li  class="active"><a href="javaScript:void(0);">订单状态</a></li>
                <li><a href="{pigcms{:U('Mall/order_detail', array('order_id' => $order_id))}">订单详情</a></li>
            </ul>
        </div>
    </div>
    <div id="fis_elm__4">
        <div id="order-widget-orderhistory" class="order-widget-orderhistory">
            <div data-node="timeLine" class="timeline" style="height:<?php if($statusCount >= 2): ?><?php echo $statusCount*10.83; ?><?php else : ?><?php echo '0'; ?><?php endif; ?>%"></div>
            <div class="relative-wrapper">
            <volist name="status" id="vo">
                <div class="item">
                    <div class="status-icon">
                    	<span class="-mark">
                    		<if condition="$vo['status'] eq 0"><img src="{pigcms{$static_path}shop/images/3.png">
	                        <elseif condition="$vo['status'] eq 1"/><img src="{pigcms{$static_path}shop/images/3.png">
	                        <elseif condition="$vo['status'] eq 2"/><img src="{pigcms{$static_path}shop/images/1.png">
	                        <elseif condition="$vo['status'] eq 3"/><img src="{pigcms{$static_path}shop/images/4.png">
	                        <elseif condition="$vo['status'] eq 4"/><img src="{pigcms{$static_path}shop/images/5.png">
	                        <elseif condition="$vo['status'] eq 5"/><img src="{pigcms{$static_path}shop/images/5.png">
	                        <elseif condition="$vo['status'] eq 6"/><img src="{pigcms{$static_path}shop/images/5.png">
	                        <elseif condition="$vo['status'] eq 7"/><img src="{pigcms{$static_path}shop/images/4.png">
	                        <elseif condition="$vo['status'] eq 8"/><img src="{pigcms{$static_path}shop/images/3.png">
	                        <elseif condition="$vo['status'] eq 9"/><img src="{pigcms{$static_path}shop/images/3.png">
	                        <elseif condition="$vo['status'] eq 10"/><img src="{pigcms{$static_path}shop/images/3.png">
	                        <elseif condition="$vo['status'] eq 11"/><img src="{pigcms{$static_path}shop/images/5.png">
	                        <elseif condition="$vo['status'] eq 12"/><img src="{pigcms{$static_path}shop/images/5.png">
	                        <elseif condition="$vo['status'] eq 13"/><img src="{pigcms{$static_path}shop/images/5.png">
	                        <elseif condition="$vo['status'] eq 14"/><img src="{pigcms{$static_path}shop/images/5.png">
	                        <elseif condition="$vo['status'] eq 15"/><img src="{pigcms{$static_path}shop/images/3.png">
	                        <elseif condition="$vo['status'] eq 30"/><img src="{pigcms{$static_path}shop/images/3.png">
	                       	</if>
                   		</span>
                  	</div>
                    <div class="status-card">
                        <div class="card-arrow"></div>
                        <div class="card-content">
                            <p class="big">
                            	<if condition="$vo['status'] eq 0"> 订单生成成功<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 1"/> 订单支付成功<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 2"/> 店员接单<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 3"/> 配送员接单<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 4"/> 配送员取货<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 5"/> 配送员配送中<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 6"/> 配送结束<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 7"/>
                            	<if condition="$order['is_pick_in_store'] eq 3">
                            	店员已发货<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<else />
                            	 店员验证消费<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	 </if>
                            	<elseif condition="$vo['status'] eq 8"/> 完成评论<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 9"/> 已完成退款<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 10"/> 已取消订单<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 11"/> 商家分配自提点<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 12"/> 商家发货到自提点<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 13"/> 自提点已接货<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 14"/> 自提点已发货<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 15"/> 您在自提点取货<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	<elseif condition="$vo['status'] eq 30"/> 店员为您修改了价格<span>{pigcms{$vo.dateline|date="Y-m-d H:i",###}</span>
                            	</if>
                            </p>
                            <p class="small"> 
          						<if condition="$vo['status'] eq 0"> <span>订单编号：{pigcms{$order.real_orderid}</span>
          						<elseif condition="$vo['status'] eq 1"/> <span>订单编号：{pigcms{$order.real_orderid}</span>
                            	<elseif condition="$vo['status'] eq 2"/> <span>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>正在为您准备商品</span>
                            	<elseif condition="$vo['status'] eq 3"/> <span>配送员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>正在赶往店铺取货</span>
                            	<elseif condition="$vo['status'] eq 4"/> <span>配送员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>已取货，准备配送，请耐心等待</span>
                            	<elseif condition="$vo['status'] eq 5"/> <span>配送员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>正快速向您靠拢，请耐心等待！</span>
                            	<elseif condition="$vo['status'] eq 6"/> <span>配送员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>已完成配送，欢迎下次光临！</span>
                            	<elseif condition="$vo['status'] eq 7"/> 
                            	<if condition="$order['is_pick_in_store'] eq 3">
                            	<span>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已发货给快递公司<strong style="color:red">【{pigcms{$order['express_name']}】</strong>，快递单号:<strong style="color:green">{pigcms{$order['express_number']}</strong></span>
                            	<else />
                            	<span>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>将订单改成已消费</span>
                            	</if>
                            	<elseif condition="$vo['status'] eq 8"/> <span>您已完成评论，谢谢您提出宝贵意见！</span>
                            	<elseif condition="$vo['status'] eq 9"/> <span>您已完成退款</span>
                            	<elseif condition="$vo['status'] eq 10"/> <span>您已经取消订单</span>
                            	<elseif condition="$vo['status'] eq 11"/> <span>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>给您分配</span>
                            	<elseif condition="$vo['status'] eq 12"/> <span>店员:<if condition="$vo['name']"><strong style="color:red">【{pigcms{$vo.name}】</strong></if> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a>已经给您发货到配送点</span>
                            	<elseif condition="$vo['status'] eq 13"/> <span>自提点<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已经接到您的货物了</span>
                            	<elseif condition="$vo['status'] eq 14"/> <span>自提点<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已经给您发货了</span>
                            	<elseif condition="$vo['status'] eq 15"/> <span>您在自提点<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已经把您的货提走了！</span>
                            	<elseif condition="$vo['status'] eq 30"/> <span>店员<strong style="color:red">【{pigcms{$vo.name}】</strong> <a class="tel-btn" href="tel:{pigcms{$vo.phone}">{pigcms{$vo.phone}</a> 已将订单的总价修改成{pigcms{$vo.note}</span>
                            	</if>                  
                            </p>
                            <if condition="$vo['status'] eq 4">
                            <div id="map" style="height:150px;display:none"></div>
                            </if>
                        </div>
                    </div>
                </div>
            </volist>
            </div>
            <div class="time-btm"> <img src="{pigcms{$static_path}shop/images/timer_2132249.png">
                <div class="right-btn">
                    <div class="title none"> <a class="cui-btn active" href="{pigcms{:U('Mall/store', array('store_id' => $order['store_id']))}">随便逛逛</a> </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="fis_elm__6">
    <div id="common-widget-profile" class="common-widget-profile hide">
        <div class="popover">
              <ul class="list-group">
                    <li> <i class="icon-menu"></i> <a href="##">我的订单</a> </li>
                    <li> <i class="icon-location"></i> <a href="##">送货地址管理</a> </li>
                    <li> <i class="icon-favorite"></i> <a href="##">收藏夹</a> </li>
                    <li> <i class="icon-phone"></i> <a href="##">客服电话&nbsp;4000-117-777</a> </li>
                    <li> <i class="icon-coupon"></i> <a href="##">我的代金券</a> </li>
                    <li> <i class="icon-refund"></i> <a href="##">我的退款</a> </li>
                </ul>
        </div>
    </div>
</div>
<div class="global-mask layout"></div>
<if condition="$center">
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script>
var order_id = {pigcms{$order['order_id']};
var status = {pigcms{$order['status']};
var order_status = {pigcms{$order['order_status']};
$(document).ready(function(){
	setInterval("ostatus()", 5000);//1000为1秒钟
});

function ostatus()
{
	$.get('wap.php?c=Shop&a=orderstatus', {'order_id':order_id}, function(response){
		if (response.error_code == false) {
			var data = response.data;
			if (status < data.status) {
				if (data.status == 1) {
					order_status = data.order_status;
					status = data.status;
					tips('店员已经接单了！');
				}
			} else if (order_status < data.order_status) {
				if (data.order_status == 2) {
					order_status = data.order_status;
					status = data.status;
					tips('配送员已接单！');
				} else if (data.order_status == 3) {
					order_status = data.order_status;
					status = data.status;
					tips('配送员已经取货！');
				} else if (data.order_status == 4) {
					order_status = data.order_status;
					status = data.status;
					tips('配送员已经将您的货物送达！');
				} else if (data.order_status == 5) {
					order_status = data.order_status;
					status = data.status;
					tips('您已经确认收货！');
				}
			}
		}
	}, 'json');
}


function tips(msg)
{
	layer.open({
		title: [
		        '状态修改提示',
		        'background-color:#8DCE16; color:#fff;'
		    ],
	    content: msg,
	    btn: ['确认'],
	    shadeClose: false,
	    yes: function(){
	        location.reload();
	    }
	});
}
function jump(e) {
    location.href = "{pigcms{:U('Shop/map', array('order_id'=>$order_id))}";
}
$(function(){
    // 百度地图API功能
    var map = new BMap.Map("map");
    map.centerAndZoom(new BMap.Point({pigcms{$center['lng']}, {pigcms{$center['lat']}), 15);
    map.enableScrollWheelZoom();

    var polyline = new BMap.Polyline([
        new BMap.Point({pigcms{$point['from_site']['lng']},{pigcms{$point['from_site']['lat']}),
        <if condition="$lines">
        <volist name="lines" id="vo">
        new BMap.Point({pigcms{$vo['lng']}, {pigcms{$vo['lat']}),
        </volist>
        </if>
        <if condition="$supply['status'] eq 5">
        new BMap.Point({pigcms{$point['aim_site']['lng']},{pigcms{$point['aim_site']['lat']}),
        </if>
    ], {strokeColor:"red", strokeWeight:5, strokeOpacity:0.8});   //创建折线
    map.addOverlay(polyline);   //增加折线

    //我的图标
    var pt1 = new BMap.Point({pigcms{$point['aim_site']['lng']},{pigcms{$point['aim_site']['lat']});
    var myIcon = new BMap.Icon("{pigcms{$static_path}shop/images/map/my_pos.png", new BMap.Size(60,60));
    var marker1 = new BMap.Marker(pt1,{icon:myIcon});  // 创建标注
    map.addOverlay(marker1);
    //店铺图标
    var pt2 = new BMap.Point({pigcms{$point['from_site']['lng']},{pigcms{$point['from_site']['lat']});
    var storeIcon = new BMap.Icon("{pigcms{$static_path}shop/images/map/store_pos.png", new BMap.Size(22,60));
    var marker2 = new BMap.Marker(pt2,{icon:storeIcon});  // 创建标注
    map.addOverlay(marker2);

    //配送员图标
    <?php
        $temp = $lines;
        $deliver_pos = array_pop($temp);
        if (! $deliver_pos) {
            $deliver_pos = array('lng'=>$point['from_site']['lng'], 'lat'=>$point['from_site']['lat']);
        }
    ?>
    var pt2 = new BMap.Point({pigcms{$deliver_pos['lng']},{pigcms{$deliver_pos['lat']});
    var storeIcon = new BMap.Icon("{pigcms{$static_path}shop/images/map/deliver_pos.png", new BMap.Size(22,60));
    var marker2 = new BMap.Marker(pt2,{icon:storeIcon});  // 创建标注
    map.addOverlay(marker2);

    map.setViewport([
        new BMap.Point({pigcms{$point['from_site']['lng']},{pigcms{$point['from_site']['lat']}),
        <if condition="$lines">
        <volist name="lines" id="vo">
        new BMap.Point({pigcms{$vo['lng']}, {pigcms{$vo['lat']}),
        </volist>
        </if>
        new BMap.Point({pigcms{$point['aim_site']['lng']},{pigcms{$point['aim_site']['lat']}),
    ]);
    map.disableScrollWheelZoom();
    map.disableContinuousZoom();

    map.addEventListener("click", jump);
});

</script>
</if>
</body>
</html>
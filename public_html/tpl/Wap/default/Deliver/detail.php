<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no,minimal-ui">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="keywords" content="百度外卖，百度地图外卖，百度外卖网，外卖订餐，网上订餐外卖，快餐外卖，外卖网，北京外卖，外卖；">
    <meta name="description" content="百度地图外卖是由百度打造的一个专业外卖服务平台，覆盖众多优质外卖商家，提供方便快捷的网上外卖订餐服务。">

    <title>配送详情</title>

</head>
<body class=" hIphone" style="padding-bottom: initial;">
<div id="fis_elm__0"></div>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/lib_3a812b5.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_dd39d16.css">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/order_4bc7e9e.css">
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
<script src="{pigcms{$static_path}js/convertor.js"></script>
<script src="{pigcms{$static_public}js/laytpl.js"></script>
<script src="{pigcms{$static_path}layer/layer.m.js"></script>
<div id="fis_elm__1"></div>
<div id="wrapper" class="">
    <div id="fis_elm__2">
        <div id="common-widget-nav" class="common-widget-nav ">
            <div class="left-slogan"> <a class="left-arrow icon-arrow-left2"  href="{pigcms{$_SERVER['HTTP_REFERER']}"></a> </div>
            <div class="center-title"> <a href="javascript:void(0)">{pigcms{$supply['name']}-{pigcms{:mb_substr($supply['aim_site'], 0, 9, 'UTF-8')}..</a> </div>
            <div class="right-slogan "> <a class="tel-btn icon-road-image" href="javascript:" id="road"></a> </div>
        </div>
    </div>
    <!--<div id="fis_elm__3">
        <div id="common-widget-tab" class="common-widget-tab">
            <ul class="order-tab">
                <li><a href="order_zhuangtai.html">订单状态</a></li>
                <li class="active"><a href="order_info.html">订单详情</a></li>
            </ul>
        </div>
    </div>-->
    <div id="fis_elm__4">
        <div id="order-widget-orderdetail" class="order-widget-orderdetail" style="padding-bottom: 100px">
            <div id="fis_elm__5">
                <div id="widget-order-detail-menu-detail">
                    <ul class="order-info list-group">
                        <li> <span class="left-span title">订单详情</span> <span class="right-value total">总计：￥{pigcms{$order['discount_price']}</span> </li>
                        <volist name="goods" id="vo">
                            <php>$tools_money = $tools_money+$vo['tools_money']*$vo['num'];</php>
                        <li> <span class="left-span">{pigcms{$vo['name']}</span>
                            <div class="right-value"> <span>x{pigcms{$vo['num']}&nbsp;&nbsp;</span> <span>￥<?=$vo['price']*$vo['num']?></span> </div>
                        </li>
                        </volist>
                        <if condition="$store['tools_money_have'] eq 1">
                        <li> <span class="left-span">餐盒费</span> <span class="right-value">￥{pigcms{:floatval($store['tools_money'])}</span> </li>
                        </if>
                        <if condition="$store['send_money'] gt 0">
                        <li> <span class="left-span">配送费</span> <span class="right-value" data-node="mian-price">￥{pigcms{:floatval($store['send_money'])}</span> </li>
                        </if>
                        <!--li> <span class="left-span">收货码</span> <span class="right-value total" data-node="mian-price">{pigcms{$order['code']}</span> </li-->
                        <if condition="$couponInfo">
                        <li> <span class="left-span">红包</span> <span class="right-value total" data-node="mian-price">-{pigcms{:floatval($couponInfo['money'])}</span> </li>
                        </if>
                        <if condition="discountInfo">
                        <volist name="discountInfo" id="vo">
                        <li> <span class="left-span">【优惠】{pigcms{$vo['desc']}</span> <span class="right-value total" data-node="mian-price">-{pigcms{:floatval($vo['discount_money'])}</span> </li>
                        </volist>
                        </if>
                        <li> <span class="left-span">支付方式</span> <span class="right-value total" data-node="mian-price">{pigcms{$order['pay_type']}</span> </li>
                        <if condition="$order['deliver_cash']">
                        <li> <span class="left-span">收取现金</span> <span class="right-value total" data-node="mian-price">{pigcms{$order['deliver_cash']|floatval}</span> </li>
                        </if>
                    </ul>
                </div>
            </div>
            <div id="fis_elm__5">
                <div id="widget-order-detail-menu-detail">
                    <ul class="order-info list-group">
                        <li> <span class="left-span title">用户信息</span></li>
                        <li> <span class="left-span">用户姓名</span>
                            <div class="right-value"> <span>{pigcms{$supply['name']}</span> </div>
                        </li>
                        <li> <span class="left-span">手机号</span>
                            <div class="right-value"> <span><a href="tel:{pigcms{$supply['phone']}">{pigcms{$supply['phone']}</a></span> </div>
                        </li>
                        <li style="white-space: normal;height: auto;overflow: auto;max-height: none;line-height: 23px;"> <span class="left-span">配送地址</span>
                            <div class="right-value" style="text-align: left;margin-left: 80px;"> <span>{pigcms{$supply['aim_site']}</span> </div>
                        </li>
                        <if condition="$supply['end_time']">
                        <li> <span class="left-span">送达时间</span>
                            <div class="right-value"> <span>{pigcms{$supply['end_time']|date="Y-m-d H:i:s",###}</span> </div>
                        </li>
                        </if>
                    </ul>
                </div>
            </div>
            <div id="fis_elm__5">
                <div id="widget-order-detail-menu-detail">
                    <ul class="order-info list-group">
                        <li> <span class="left-span title">商家信息</span></li>
                        <li> <span class="left-span">店铺名称</span>
                            <div class="right-value"> <span>{pigcms{$store['name']}</span> </div>
                        </li>
                        <li> <span class="left-span">店铺电话</span>
                            <div class="right-value"> <span><a href="tel:{pigcms{$store['phone']}">{pigcms{$store['phone']}</a></span> </div>
                        </li>
                        <li> <span class="left-span">店铺地址</span>
                            <div class="right-value"> <span>{pigcms{$supply['from_site']}</span> </div>
                        </li>
                    </ul>
                </div>
            </div>
            <if condition="$order['cue_field']">
            <div id="fis_elm__5">
                <div id="widget-order-detail-menu-detail">
                    <ul class="order-info list-group">
                        <li> <span class="left-span title">分类填写字段</span></li>
                        <volist name="order['cue_field']" id="cue">
                        <li style="white-space: normal;height: auto;overflow: auto;max-height: none;line-height: 23px;"> <span class="left-span">{pigcms{$cue['title']}</span>
                            <div class="right-value" style="text-align: left;margin-left: 80px;"> <span>{pigcms{$cue['txt']}</span> </div>
                        </li>
                        </volist>
                    </ul>
                </div>
            </div>
            </if>
            <div id="fis_elm__2" <php>if($supply[status] != 5):</php>style="margin-top:5px;position: fixed;bottom: 0;width: 100%;height: 70px;border-top: 1px solid #d4d5d8;background: #fff;padding: 5px 0 10px;"<php>endif;</php> style="margin-top:15px;">
                <div id="common-widget-nav" class="common-widget-nav " <php>if($supply[status] != 5):</php> onclick="operate()" style="background-color: #EE3968;margin-top: 5px;border-radius: 8px;width: 98%;margin: 0 auto;margin-top: 12px;"<php>endif;</php> >
                    <div class="center-title">
                        <a href="javascript:void(0)" <php>if($supply[status] != 5):</php>style="color: #fff;font-weight: bold;"<php>endif;</php>>
                            <php>
                                switch($supply['status']) {
                                    case 1: echo '抢单';break;
                                    case 2: echo '取货';break;
                                    case 3: echo '配送';break;
                                    case 4: echo '完成';break;
                                    case 5: echo '配送已完成';break;
                                }
                            </php>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var mark = 0;
    <?php if($supply['status'] == 1): ?>
        var DeliverListUrl = "{pigcms{:U('Deliver/grab')}";
    <?php elseif($supply['status'] == 2):?>
        var DeliverListUrl = "{pigcms{:U('Deliver/pick')}";
    <?php elseif($supply['status'] == 3):?>
        var DeliverListUrl = "{pigcms{:U('Deliver/send')}";
    <?php elseif($supply['status'] == 4):?>
        var DeliverListUrl = "{pigcms{:U('Deliver/my')}";
    <?php endif;?>
    function operate() {
        if (mark) {
            return false;
        }
        mark = 1;
        var supply_id = {pigcms{$supply['supply_id']};
        $.post(DeliverListUrl, "supply_id="+supply_id, function(json){
            mark = 0
            if (json.status) {
                layer.open({title:['抢单提示：','background-color:#FF658E;color:#fff;'],content:'修改成功~',btn: ['确定'],end:function(){location.href = location.href;}});
            } else {
                layer.open({
                    title: ['抢单提示：', 'background-color:#FF658E;color:#fff;'],
                    content: '操作失败~',
                    btn: ['确定'],
                    end: function () {
                        location.href = location.href;
                    }
                });
            }
        });
    }
</script>
<script>
    //上传配送员位置
    var HAVE_SEND = <?php if($have_send){ echo $have_send;}else{ echo 0;}?>;
    var UPLOCATION_URL = "{pigcms{:U('Deliver/location')}";
    if (HAVE_SEND) {
        layer.open({title:['配送提示：','background-color:#FF658E;color:#fff;'],content:'订单正在配送，请保持页面长亮！',btn: ['确定'],end:function(){}});
        $(window).bind('beforeunload',function(){return '您有正在配送的订单，关闭后用户将看不到您的位置？';});
        if (navigator.geolocation){
            setInterval(upLocation, 60000);
        }else{
            clearInterval(timer);
            alert("定位失败,用户浏览器不支持或已禁用位置获取权限");
        }
    }

    function upLocation() {
        navigator.geolocation.getCurrentPosition(function(position){
            var lng = position.coords.longitude;
            var lat = position.coords.latitude;
            var point = {};
            point.lng = lng;
            point.lat = lat;
            BMap.Convertor.translate(point, 0, function(Bpoint){
                var Blng = Bpoint.lng;
                var Blat = Bpoint.lat;
                $.post(UPLOCATION_URL, "lng="+Blng+"&lat="+Blat, function(json){});
            });
        });
    }

    $("#road").click(function(){
        location.href = "{pigcms{:U('Deliver/map', array('supply_id'=>$supply['supply_id']))}";
    });
</script>
<div class="global-mask layout"></div>

<div id="fis_elm__8"></div>
<div id="fis_elm__9"></div>

</body>
</html>
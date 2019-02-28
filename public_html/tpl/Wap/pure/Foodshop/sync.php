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
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>

<script type="text/javascript">var STATIC_PATH = '{pigcms{$static_path}', open_extra_price = '{pigcms{$config.open_extra_price}', extra_price_alias_name = '{pigcms{$config.extra_price_alias_name}';</script>
<script type="text/javascript">
var real_orderid = '{pigcms{$order["real_orderid"]}', status = '{pigcms{{pigcms{$order["status"]}}', order_id = '{pigcms{{pigcms{$order["order_id"]}}';
var call_store_url = "{pigcms{:U('Foodshop/call_store', array('order_id' => $order['real_orderid']))}";
var call_server_url = "{pigcms{:U('Foodshop/callServer', array('order_id' => $order['real_orderid']))}";
var check_status_url = "{pigcms{:U('Foodshop/check_status', array('order_id' => $order['order_id']))}";
var get_data_info_url = "{pigcms{:U('Foodshop/getDataInfo', array('real_orderid' => $order['real_orderid']))}";
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/foodshop_sync.js" charset="utf-8"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>
<body>

<section class="Cart CartReserve">
    <if condition="($old_goods_list OR $package_list OR $must_list)">
    <div class="CartReserve_top"><span>已预订菜品</span></div>
    <php> if ($order['status'] < 3) { </php>
    <div style='text-align:center;color:#fff;line-height:35px;height:35px; margin: 8px 0px;' id="share">
        <span style='  padding: 7px 14px;background: #2BC7A2;'>邀请好友一起点餐</span>
    </div>
    <php>} </php>
    <if condition="$old_goods_list OR $package_list OR $must_list">
    <div class="Cart_list">
        <ul id="data">
        <volist name="old_goods_list" id="rowset">
            <php>if (isset($userList[$key])) { </php> 
            <li class="clr" style='paddong:0px'>
                <div class="Clist_left">
                    <img src="<php>if (empty($userList[$key]['avatar'])) {echo $static_path . 'images/user_header.png';} else { echo $userList[$key]['avatar'];}</php>" style='border-radius: 50%; width: 30px;  height: 30px;'>
                </div>
                <div style='padding-left: 10px;padding-top: 5px;  float: left;'>
                    <span style='font-weight:bold;color:#000000'>{pigcms{$userList[$key]['nickname']}</span>
                    <php> if ($key == $order['uid']) { </php>
                    <span style="padding: 5px 10px;background: #bb0b0b;margin-left: 10px;color: #fff;">下单人</span>
                    <php> } </php>
                </div>
                <!--div class="Clist_right" style='color: #d0d0d0; margin-top: -14px; border: 1px solid #bbb; padding: 1px 10px;  box-shadow: 0px 1px 1px #bbbbbb;'>
                        <span>删除ta</span>
                </div-->
            </li>
            <php>}</php>
            <volist name="rowset['goodsList']" id="goods">
            <li class="clr packages">
                <div class="Clist_left">
                    <h2>{pigcms{$goods['name']}</h2>
                    <if condition="$goods['spec']">
                    <span>({pigcms{$goods['spec']})</span>
                    </if>
                </div>
                <php>if ($goods['isNew'] == 1) { </php>
                <div class="phpo">
                    <img src="{pigcms{$static_path}images/dengdai.png">
                </div>
                <php>}</php>
                <div class="Clist_right">
                    <div class="MenuPrice">
                        <i>￥</i>{pigcms{$goods['price']|floatval}<if condition="$config.open_extra_price AND $goods.extra_price gt 0">+{pigcms{$goods['extra_price']|floatval}{pigcms{$config.extra_price_alias_name}</if>
                    </div>
                    <div class="Addsub">
                        <span>{pigcms{$goods['num']|floatval}{pigcms{$goods['unit']}</span>
                        <!--a href="javascript:void(0)" class="jian"></a-->
                        <!--a href="javascript:void(0)" class="jia"></a-->
                    </div>
                </div>
            </li>
            </volist>
            </volist>
            <volist name="package_list" key="uid" id="plist">
            <php>if (isset($userList[$key])) { </php> 
            <li class="clr" style='paddong:0px'>
                <div class="Clist_left">
                    <img src="<php>if (empty($userList[$key]['avatar'])) {echo $static_path . 'images/user_header.png';} else { echo $userList[$key]['avatar'];}</php>" style='border-radius: 50%; width: 30px;  height: 30px;'>
                </div>
                <div style='padding-left: 10px;padding-top: 5px;  float: left;'>
                    <span style='font-weight:bold;color:#000000'>{pigcms{$userList[$key]['nickname']}</span>
                </div>
                <!--div class="Clist_right" style='color: #d0d0d0; margin-top: -14px; border: 1px solid #bbb; padding: 1px 10px;  box-shadow: 0px 1px 1px #bbbbbb;'>
                        <span>删除ta</span>
                </div-->
            </li>
            <php>}</php>
            <php> foreach($plist['goodsList'] as $packList) {</php>
            <li class="clr on">
                <div class="clr Package">
                    <div class="Clist_left">
                        <h2 style="color: #29c7a2;">{pigcms{$packList['name']}</h2>
                    </div>
                    <if condition="isset($packList['isNew']) AND $packList['isNew'] eq 1">
                    <div class="phpo">
                        <img src="{pigcms{$static_path}images/dengdai.png">
                    </div>
                    </if>
                    <div class="Clist_right">
                        <div class="MenuPrice">
                            <i>￥</i>{pigcms{$packList['price']|floatval}    
                        </div>
                        <div class="Addsub">
                            <span>{pigcms{$packList['num']|floatval}份</span>
                        </div>
                    </div>
                </div>
                <div class="Package_end">
                    <dl>
                        <php> foreach ($packList['list'] as $row) {</php>
                        <dd>
                            {pigcms{$row['name']} <if condition="$row['spec']"><i>({pigcms{$row['spec']})</i></if>
                            <div class="fr"><span class="on">￥{pigcms{$row['price']|floatval}</span> <span>{pigcms{$row['num']|floatval}/{pigcms{$row['unit']}</span></div> 
                        </dd>
                        <php>}</php>
                    </dl>
                    <a href="javascript:void(0)" class="more"></a>
                </div>
                <div class="bottom"></div>
            </li>
            <php>}</php>
            </volist>
            <volist name="must_list" id="must">
            <li class="clr">
                <div class="Clist_left">
                    <h2>{pigcms{$must['name']} <i>必点</i></h2>
                </div>
                <div class="Clist_right">
                    <div class="MenuPrice">
                        <i>￥</i>{pigcms{$must['price']|floatval}    
                    </div>
                    <div class="Addsub">
                        <span>{pigcms{$must['num']|floatval}{pigcms{$must['unit']}</span>
                    </div>
                </div>
            </li>
            </volist>
            <!--  新增html -->
        </ul>
    </div>
    </if>

    </if>
    <div class="Reservation" style="margin-top: 1px;">
        <div class="textarea" style="margin-bottom: 5px;">
            <textarea placeholder="如有附加要求，可填写，我们会尽量安排" name="note" id="note">{pigcms{$order['note']}</textarea>
        </div>
    </div>
    <div class="Serving">
        <dl>
            <!-- <dt>总计：{pigcms{$price|floatval}元</dt> -->
            <dd class="vegetables clr">
                <a href="{pigcms{:U('Foodshop/menu', array('order_id' => $order['real_orderid'], 'store_id' => $order['store_id']))}" class="add" <if condition="$is_add_menu eq 0">style="display:none"</if>>加菜</a>
                <a href="javascript:void(0)" class="notice" <if condition="$is_call_store eq 0">style="display:none"</if>>通知上菜</a>
                <a href="javascript:void(0)" class="call" <if condition="$order['status'] gt 2">style="display:none"</if>>呼叫服务</a>
            </dd>
        </dl>
    </div>
</section>


<if condition="$order['book_time']">
<if condition="empty($old_goods_list) AND empty($goods_list)">
<section class="Success"><span>座位详情！</span></section>
</if>
<section class="Sudetails">
    <ul>
        <li class="Su_zh">
            <dl>
                <dd>{pigcms{$order['book_time_show']}</dd>
                <dd>{pigcms{$order['book_num']}人 | {pigcms{$order['table_type_name']}  <span class="Su_sit"><if condition="$order['status'] eq 0"><b style="color:red">未付</b><else/>已付</if>定金:￥{pigcms{$order['book_price']|floatval}</span></dd>
                <dd>{pigcms{$order['name']} <if condition="$order['sex'] eq 1">先生<else />女士</if> {pigcms{$order['phone']}</dd>
            </dl>
        </li>
    </ul>
</section>
</if>

<!--  新增html --> 
<section class="cartetails">
    <div class="cartetails_list">
        <h1>订单详情</h1>
        <ul>
		    <if condition="$tablesDiscount">
                <li class="clr">
                    <i>桌次折扣</i>
                    <span class="fr"><span style="color: red">{pigcms{$tablesDiscount.mer_discount|floatval}折</span> (部分商品不参加折扣)</span>
                </li>
            </if>
			<if condition="$coupon_discount.discount_value gt 0">
				<li class="clr">
					<i>平台折扣券</i>
					<span class="fr"><span style="color: red">{pigcms{$coupon_discount.discount_value|floatval}折</span> (部分商品不参加折扣)</span>
				</li>
            </if>
            <if condition="$plat_discount lt 10">
            <li class="clr">
                <i>商家折扣</i>
                <span class="fr"><span style="color: red">{pigcms{$plat_discount}折</span> (部分商品不参加折扣)</span>
            </li>
            </if>
            <li class="clr">
                <i>订单编号</i>
                <span class="fr">{pigcms{$order['real_orderid']}</span>
            </li>
            <li class="clr">
                <i>餐台类型</i>
                <span class="fr">{pigcms{$order['table_type_name']}</span>
            </li>
            <li class="clr">
                <i>餐台名称</i>
                <span class="fr">{pigcms{$order['table_name']}</span>
            </li>
            <li class="clr">
                <i>下单时间</i>
                <span class="fr">{pigcms{$order['create_time']|date='Y-m-d H:i', ###}</span>
            </li>
            <if condition="$order['book_pay_time']">
            <li class="clr">
                <i>预订支付时间</i>
                <span class="fr">{pigcms{$order['book_pay_time']|date='Y-m-d H:i', ###}</span>
            </li>
            </if>
            <if condition="$order['book_pay_type']">
            <li class="clr">
                <i>预订支付方式</i>
                <span class="fr">{pigcms{$order['book_pay_type']}</span>
            </li>
            </if>
            <if condition="$order['pay_time']">
            <li class="clr">
                <i>买单时间</i>
                <span class="fr">{pigcms{$order['pay_time']|date='Y-m-d H:i', ###}</span>
            </li>
            </if>
            <if condition="$order['pay_type']">
            <li class="clr">
                <i>买单支付方式</i>
                <span class="fr">{pigcms{$order['pay_type']}</span>
            </li>
            </if>
        </ul>
    </div>
</section>
<div style="padding-bottom: 65px;"></div>
 <!--  新增html -->
<div class="Total clr pay" <if condition="$is_pay eq 0"> style="display:none" </if>>
    <div class="Total_left">总计<span>￥<i>{pigcms{$price|floatval}<if condition="$config.open_extra_price AND $extra_price GT 0">+{pigcms{$extra_price}{pigcms{$config.extra_price_alias_name}</if></i></span></div>
    <a href="{pigcms{:U('Foodshop/pay', array('order_id' => $order['real_orderid']))}" class="Check">去买单</a>
</div>

<div class="Total clr comment" <if condition="$order['status'] eq 3 AND $order['uid'] eq $userid"> <else />style="display:none" </if>>
    <div class="Total_left">已买单</div>
    <a href="{pigcms{:U('My/foodshop_feedback', array('order_id' => $order['order_id']))}" class="Check">去评价</a>
</div>
<div id="cover"></div>
<div id="guide"><img src="{pigcms{$static_path}images/guide1.png"></div>

<script id="allData" type="text/html">
{{# for (var uid in d.goodsList) { }}
    {{# for (var u in d.userList) { }}
    {{# if (uid == 'a_' + u) { }}
    <li class="clr" style='paddong:0px'>
    <div class="Clist_left">
        {{# if (d.userList[u].avatar.length>0) { }}
        <img src="{{ d.userList[u].avatar }}" style="border-radius: 50%; width: 30px;  height: 30px;">
        {{# } else { }}
        <img src="{{ STATIC_PATH }}images/user_header.png" style="border-radius: 50%; width: 30px;  height: 30px;">
        {{# } }}
    </div>
    <div style="padding-left: 10px;padding-top: 5px;  float: left;">
        <span style="font-weight:bold;color:#000000">{{ d.userList[u].nickname }}</span>
        {{# if ('a_' + d.orderUid == uid) { }}
        <span style="padding: 5px 10px;background: #bb0b0b;margin-left: 10px;color: #fff;">下单人</span>
        {{# } }}
    </div>
    </li>
    {{# } }}
    {{# } }}
    {{# for (var i in d.goodsList[uid].goodsList) { }}
    <li class="clr packages">
        <div class="Clist_left">
            <h2>{{ d.goodsList[uid].goodsList[i].name }}</h2>
            {{# if (d.goodsList[uid].goodsList[i].spec.length > 0) { }}
            <span>({{ d.goodsList[uid].goodsList[i].spec }})</span>
            {{# } }}
        </div>
        {{# if (d.goodsList[uid].goodsList[i].isNew == 1) { }}
        <div class="phpo">
            <img src="{{ STATIC_PATH }}images/dengdai.png">
        </div>
        {{# } }}
        <div class="Clist_right">
            <div class="MenuPrice">
                <i>￥</i>{{ parseFloat(d.goodsList[uid].goodsList[i].price) }} 
                {{# if (open_extra_price > 0 && d.goodsList[uid].goodsList[i].extra_price > 0) { }}
                +{{ parseFloat(d.goodsList[uid].goodsList[i].extra_price) }}{{ extra_price_alias_name }}
                {{# } }}
            </div>
            <div class="Addsub">
                <span>{{ d.goodsList[uid].goodsList[i].num }}{{ d.goodsList[uid].goodsList[i].unit }}</span>
            </div>
        </div>
    </li>
{{# } }}
{{# } }}


{{# for (var uid in d.packageList) { }}
    {{# for (var u in d.userList) { }}
    {{# if (uid == 'a_' + u) { }}
    <li class="clr" style='paddong:0px'>
    <div class="Clist_left">
        {{# if (d.userList[u].avatar.length>0) { }}
        <img src="{{ d.userList[u].avatar }}" style="border-radius: 50%; width: 30px;  height: 30px;">
        {{# } else { }}
        <img src="{{ STATIC_PATH }}images/user_header.png" style="border-radius: 50%; width: 30px;  height: 30px;">
        {{# } }}
    </div>
    <div style="padding-left: 10px;padding-top: 5px;  float: left;">
        <span style="font-weight:bold;color:#000000">{{ d.userList[u].nickname }}</span>
    </div>
    </li>
    {{# } }}
    {{# } }}
    {{# for (var i in d.packageList[uid].goodsList) { }}
    <li class="clr on">
        <div class="clr Package">
            <div class="Clist_left">
                <h2 style="color: #29c7a2;">{{ d.packageList[uid].goodsList[i].name }}</h2>
            </div>
            {{# if (d.packageList[uid].goodsList[i].isNew == 1) { }}
            <div class="phpo">
                <img src="{{ STATIC_PATH }}images/dengdai.png">
            </div>
            {{# } }}
            <div class="Clist_right">
                <div class="MenuPrice">
                    <i>￥</i>{{ parseFloat(d.packageList[uid].goodsList[i].price) }}
                </div>
                <div class="Addsub">
                    <span>{{ d.packageList[uid].goodsList[i].num }}份</span>
                </div>
            </div>
        </div>
        <div class="Package_end">
            <dl>
                {{# for (var l in d.packageList[uid].goodsList[i].list) { }}
                <dd>
                    {{ d.packageList[uid].goodsList[i].list[l].name }}
                    {{# if (d.packageList[uid].goodsList[i].list[l].spec.length > 0) { }}
                    <i>({{ d.packageList[uid].goodsList[i].list[l].spec }})</i>
                    {{# } }}
                    <div class="fr"><span class="on">￥{{ parseFloat(d.packageList[uid].goodsList[i].list[l].price) }}</span> <span>{{ d.packageList[uid].goodsList[i].list[l].num }}/{{ d.packageList[uid].goodsList[i].list[l].unit }}</span></div> 
                </dd>
                {{# } }}
            </dl>
            <a href="javascript:void(0)" class="more"></a>
        </div>
        <div class="bottom"></div>
    </li>
{{# } }}
{{# } }}

{{# for (var m in d.mustList) { }}
<li class="clr">
    <div class="Clist_left">
        <h2>{{ d.mustList[m].name }}<i>必点</i></h2>
    </div>
    <div class="Clist_right">
        <div class="MenuPrice">
            <i>￥</i>{{ parseFloat(d.mustList[m].price) }}
        </div>
        <div class="Addsub">
            <span>{{ parseFloat(d.mustList[m].num) }}{{ d.mustList[m].unit }}</span>
        </div>
    </div>
</li>
{{# } }}
</script>
<script>
window.shareData = {
    "moduleName":"Foodshop",
    "moduleID":"0",
    "imgUrl": "{pigcms{$store['image']}", 
    "sendFriendLink": "{pigcms{$share_url}",
    "tTitle": "一起点餐吧",
    "tContent": "我正在【{pigcms{$store['name']}】就餐，邀您来点餐"
};
</script>

<if condition="$is_app_browser">
	<script type="text/javascript">
		window.lifepasslogin.shareLifePass("一起点餐吧", "我正在【{pigcms{$store['name']}】就餐，邀您来点餐", "{pigcms{$store['image']}", "{pigcms{$share_url}");
	</script>
</if>
{pigcms{$shareScript}
</body>
</html>
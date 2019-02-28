<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>{pigcms{$config.shop_alias_name}_{pigcms{$now_city.area_name}_{pigcms{$config.seo_title}</title>
    <if condition="$now_area">
    	<meta name="keywords" content="{pigcms{$now_area.area_name},{pigcms{$now_circle.area_name},{pigcms{$config.seo_keywords}" />
    <else />
    	<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
    </if>
	<meta name="description" content="{pigcms{$config.seo_description}" />
	<meta charset="utf-8">
	<link href="{pigcms{$static_path}css/shop_pc.css" rel="stylesheet"/>
    <script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
	<script src="{pigcms{$static_path}js/common.js"></script>
	<script type="text/javascript">var store_id = '{pigcms{$store['id']}', store_long = '{pigcms{$store.long}',store_lat = '{pigcms{$store.lat}',static_path = "{pigcms{$static_path}";</script>
	<!--[if lte IE 9]>
	<script src="{pigcms{$static_path}js/jquery-1.9.1.min.js"></script>
	<script src="{pigcms{$static_path}js/html5shiv.min.js"></script>
	<![endif]-->
</head>
    <body style="background: #f5f5f5;">
        <section class="shoptop">
            <div class="shopto_top">
                <div class="w1200 clr">
                    <div class="fl clr">
                        <span class="fl">{pigcms{$shop_select_address}</span>
                        <a href="/shop/change.html" class="fl">[切换地址]</a> 
                    </div>
                    <if condition="empty($user_session)">
                    <div class="fr">
                    	<span><a href="{pigcms{:UU('Index/Login/index')}">登录</a> | <a href="{pigcms{:UU('Index/Login/reg')}">注册</a></span>
                    </div>
                    <else />
                    <div class="fr">
                        <span><a href="{pigcms{:UU('User/Index/index')}">{pigcms{$user_session.nickname}</a> | <a href="{pigcms{:UU('Index/Login/logout')}">退出</a></span>
                    </div>
                    </if>
                </div>
            </div>
            <div class="shopto_end shopto_end2">
                <div class="w1200 clr">
                    <div class="fl img">
                        <a href="/"><img src="{pigcms{$config.site_logo}" width="163" height="51"></a>
                    </div>
                    <div class="link fl">
                        <a href="/shop.html" class="on">首页</a><span>|</span><a href="{pigcms{:UU('User/Index/index')}">我的订单</a>
                    </div>
                    <div class="fr">
                        <input type="text" placeholder="搜索美食" id="keyword">
                        <button id="search" style="cursor: pointer;">搜索</button>
                    </div>    
                </div>
            </div>
        </section>
        <section class="details">
            <div class="w1200 clr">
                <div class="fl parent">
                    <div class="img fl">
                        <img src="{pigcms{$store['image']}">
                    </div>
                    <div class="pl15 clr">
                        <div class="title clr">
                            <h2>{pigcms{$store['name']}</h2>
                            <if condition="$store['is_close']">
								<span class="no">未营业</span>
                            <else />
								<span class="yes">营业中</span>
                            </if>
						<if condition="$store['isverify']">
							<img src="/static/images/sjxq_rec.png" style="float: right;margin-left: 5px;" >
						</if>
                        </div>
                        <div class="score clr">
                            <div class="fl">
                                <div class="atar_Show">
                                    <p></p>
                                </div>
                                <span class="Fraction"><i>{pigcms{$store['star']}</i>分</span>
                            </div>
                            <span class="fl">月售{pigcms{$store['month_sale_count']}单</span>
                        </div>
                        <div class="time">接单时间：{pigcms{$store['time']}</div>
                    </div>
                    <div class="trans">
                        <div class="trans_n">
                            <ul>
                                <li>
                                    <span class="fl">店铺地址：</span>
                                    <div class="p62">{pigcms{$store['adress']}</div>     
                                </li>
                                <li>
                                    <span class="fl">店铺电话：</span>
                                    <div class="p62">{pigcms{$store['phone']}</div>     
                                </li>
                                <li>
                                    <span class="fl">配送服务：</span>
                                    <div class="p62">{pigcms{$store['deliver_name']}</div>     
                                </li>
                            </ul>
                              
                        </div>
                    </div>
                </div>
                <div class="fr give">
                    <ul class="clr">
                        <li>
                            <h2>￥{pigcms{$store['delivery_price']|floatval}</h2>
                            <p>起送价</p>
                        </li>
                        <li>
                            <h2>￥{pigcms{$store['delivery_money']|floatval}</h2>
                            <p>配送费</p>
                        </li>
                        <li>
                            <h2>{pigcms{$store['delivery_time']}分钟</h2>
                            <p>送达时间</p>
                        </li>
                    </ul>
                </div>
            </div>
        </section>
        
        <section class="variety">
            <div class="w1200 clr">
                     <div class="fl vleft">
                    <div class="vlefttop">
                        <div class="clr change">
                            <a href="/shop/{pigcms{$store['id']}.html">商品</a>
                            <a href="/shop/comment/{pigcms{$store['id']}.html" class="on">评价</a>
							<if condition="$config['store_shop_auth']">
								<a href="/shop/auth/{pigcms{$store['id']}.html">资质证照</a>
							</if>
                        </div>
                    </div>
                    
                    
                    <div class="comment" >
                        <div class="comment_top">
                            <dl class="clr">
                                <a href="/shop/comment/{pigcms{$store['id']}"><dd <if condition="$tab eq ''">class="on"</if>>全部 ({pigcms{$all_count})</dd></a>
                                <a href="/shop/comment/{pigcms{$store['id']}/high"><dd <if condition="$tab eq 'high'">class="on"</if>>满意 ({pigcms{$good_count})</dd></a>
                                <a href="/shop/comment/{pigcms{$store['id']}/wrong"><dd <if condition="$tab eq 'wrong'">class="on"</if>>不满意 ({pigcms{$wrong_count})</dd></a>
                            </dl>
                        </div>
                        <div class="comment_end">
                            <ul>
                            	<volist name="list" id="vo">
                                <li class="clr">
                                    <div class="commentpho clr">
                                        <div class="commentpho_n fl">
                                            <div class="img fl">
                                                <img src="{pigcms{$vo['avatar']}">
                                            </div>
                                            <div class="p70">
                                                <h2>{pigcms{$vo['nickname']}</h2>
                                                <div class="score">
                                                    <div class="atar_Show">
                                                        <p></p>
                                                    </div>
                                                    <span class="Fraction"><i>{pigcms{$vo['score']}</i>分</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="fr time">{pigcms{$vo['add_time_hi']}</div>     
                                    </div>
                                    <if condition="$vo['goods']">
                                    <div class="Recommend">点赞菜 : <volist name="vo['goods']" id="name"><span>{pigcms{$name}</span></volist></div>
                                    </if>
                                    <div class="text">{pigcms{$vo['comment']}</div>
                                    <if condition="$vo['merchant_reply_content']">
                                    <div class="Reply">餐厅回复 : {pigcms{$vo['merchant_reply_content']}</div>
                                    </if>
                                </li>
                                </volist>
                            </ul>
                            {pigcms{$page}
                        </div>
                    </div> 
                </div>
                <div class="fr vright">
                    <div class="vright_top">
                       <h2>商家公告</h2>
                       <div class="text">{pigcms{$store['store_notice']}</div> 
                    </div>
                    <div class="vright_middle">
                        <div class="activity">
                            <dl>
					
                            	<if condition="isset($store['coupon_list']['system_newuser']) AND $store['coupon_list']['system_newuser']">
                                <dd>
                                    <span class="fl platform">首</span>
                                    <div class="a_text">平台首单
                                    <volist name="store['coupon_list']['system_newuser']" id="vo">
                                    	满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
                                    </volist>
                                    </div>
                                </dd>
                                </if>
                                <if condition="isset($store['coupon_list']['system_minus']) AND $store['coupon_list']['system_minus']">
                                <dd>
                                    <span class="fl reduce">减</span>
                                    <div class="a_text">平台
                                    <volist name="store['coupon_list']['system_minus']" id="vo">
                                    	满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
                                    </volist>
                                    </div>
                                </dd>
                                </if>
                                <if condition="isset($store['coupon_list']['delivery']) AND $store['coupon_list']['delivery']">
                                <dd>
                                    <span class="fl red">惠</span>
                                    <div class="a_text">配送费
                                    <volist name="store['coupon_list']['delivery']" id="vo">
                                    	满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
                                    </volist>
                                    </div>
                                </dd>
                                </if>
                                <if condition="isset($store['coupon_list']['discount']) AND $store['coupon_list']['discount']">
                                <dd>
                                    <span class="fl zhe">折</span>
                                    <div class="a_text">店内全场{pigcms{$store['coupon_list']['discount']}折</div>
                                </dd>
                                </if>
                                <if condition="isset($store['coupon_list']['newuser']) AND $store['coupon_list']['newuser']">
                                <dd>
                                    <span class="fl business">首</span>
                                    <div class="a_text">店铺首单
                                    <volist name="store['coupon_list']['newuser']" id="vo">
                                    	满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
                                    </volist>
                                    </div>
                                </dd>
                                </if>
                                <if condition="isset($store['coupon_list']['minus']) AND $store['coupon_list']['minus']">
                                <dd>
                                    <span class="fl ticket">减</span>
                                    <div class="a_text">店铺
                                    <volist name="store['coupon_list']['minus']" id="vo">
                                    	满{pigcms{$vo['money']}元减{pigcms{$vo['minus']}元,
                                    </volist>
                                    </div>
                                </dd>
                                </if>
                            </dl>
                        </div>
                    </div>
                    <div class="vright_end" id="biz-map">
                    </div>  
                </div>
            </div>    
        </section>

		<include file="Public:footer"/>
        <!-- 导航 -->
        <section class="scan">
            <ul>
                <li class="code">
                    <div class="display">
                        <h2>扫描二维码</h2>
                        <p>关注微信 下单优惠更多</p>
                        <img src="{pigcms{:U('Index/Recognition/see_qrcode',array('type'=>'shop','id'=>$store['id']))}" width=122 height=122>
                    </div>
                </li>
                <li class="Return"></li>
            </ul>
        </section>
        <if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
            <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={pigcms{$config.google_map_ak}"></script>
            <script type="text/javascript">var is_google_map = "{pigcms{$config.google_map_ak}"</script>
            <else />
		<script src="http://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2"></script>
        </if>
		<script type="text/javascript">
		$(function(){
            if(typeof(is_google_map)!="undefined"){
                //谷歌地图
                var position = new Object();

                position.lng = $.cookie('shop_select_lng');
                position.lat = $.cookie('shop_select_lat');

                if (position.lng != null && position.lat != null) {
                    var map = new google.maps.Map(document.getElementById('biz-map'), {
                        disableDefaultUI:true,
                        mapTypeControl:false,
                        zoom: 10,
                        center: {lng:parseFloat(position.lng),lat:parseFloat(position.lat)}
                    });
                    var polygonArr = [];
                    polygonArr.push({lng:parseFloat(position.lng), lat:parseFloat(position.lat)});
                    polygonArr.push({lng:parseFloat(store_long), lat:parseFloat(store_lat)});
                    var flightPath = new google.maps.Polyline({
                        path: polygonArr,
                        geodesic: true,
                        strokeColor: 'red',
                        strokeOpacity: 0.8,
                        strokeWeight: 5
                    });
                    flightPath.setMap(map);

                    //我的图标
                    var userIcon =  new google.maps.Marker({
                        position: {lng:parseFloat(position.lng),lat: parseFloat(position.lat)},
                        map:map,
                        icon: {url:static_path+"images/mysite.png", anchor: new google.maps.Point(16, 16)}
                    });
                    //店铺图标
                    var storeIcon =  new google.maps.Marker({
                        position: {lng:parseFloat(store_long),lat:parseFloat(store_lat)},
                        map:map,
                        icon: {
                            url: static_path+"images/storesite.png",
                            anchor: new google.maps.Point(16, 16)
                        }
                    });
                    var bounds = new google.maps.LatLngBounds();
                    for (var i = 0; i < polygonArr.length; i++) {
                        bounds.extend(polygonArr[i]);
                    }
                    map.fitBounds(bounds);

                    var s = GetDistance(position.lng,position.lat,store_long,store_lat);

                    var infowindow = new google.maps.InfoWindow({
                        content:"距离 " + s + " 米"
                    });
                    infowindow.open(map, storeIcon);
                    storeIcon.addListener('click', function() {
                        infowindow.open(map, storeIcon);
                    });

                    /* 得到经纬度之间的距离 */
                    function Rad(d){
                        return d * Math.PI / 180.0;//经纬度转换成三角函数中度分表形式。
                    }
                    //输出为米
                    function GetDistance(lng1,lat1,lng2,lat2){
                        var radLat1 = Rad(lat1);
                        var radLat2 = Rad(lat2);
                        var a = radLat1 - radLat2;
                        var  b = Rad(lng1) - Rad(lng2);
                        var s = 2 * Math.asin(Math.sqrt(Math.pow(Math.sin(a/2),2) +
                            Math.cos(radLat1)*Math.cos(radLat2)*Math.pow(Math.sin(b/2),2)));
                        s = s *6378.137 ;// EARTH_RADIUS;
                        s = Math.round(s * 10000) / 10; //输出为公里
                        return s;
                    }
                } else {
                    var map = new google.maps.Map(document.getElementById('biz-map'), {
                        mapTypeControl:false,
                        zoom: 10,
                        center: {lng:parseFloat(store_long),lat:parseFloat(store_lat)}
                    });
                    var storeIcon =  new google.maps.Marker({
                        position: {lng:parseFloat(store_long),lat:parseFloat(store_lat)},
                        map:map,
                        icon:  {url:static_path+"images/storesite.png", anchor: new google.maps.Point(16, 16)}
                    });
                }
            }else {
                //百度地图
                var position = new Object();
                position.lng = $.cookie('shop_select_lng');
                position.lat = $.cookie('shop_select_lat');

                var map = new BMap.Map("biz-map");
                map.centerAndZoom(new BMap.Point(position.lng, position.lat), 15);
                map.enableScrollWheelZoom();
                var polyline = new BMap.Polyline([
                    new BMap.Point(position.lng, position.lat),
                    new BMap.Point(store_long, store_lat)
                ], {strokeColor: "red", strokeWeight: 5, strokeOpacity: 0.8});   //创建折线
                map.addOverlay(polyline);   //增加折线

                //我的图标
                var pt1 = new BMap.Point(position.lng, position.lat);
                var myIcon = new BMap.Icon(static_path + "images/mysite.png", new BMap.Size(32, 32));
                var marker1 = new BMap.Marker(pt1, {icon: myIcon});  // 创建标注
                map.addOverlay(marker1);
                //店铺图标
                var pt2 = new BMap.Point(store_long, store_lat);
                var storeIcon = new BMap.Icon(static_path + "images/storesite.png", new BMap.Size(32, 32));
                var marker2 = new BMap.Marker(pt2, {icon: storeIcon});  // 创建标注
                map.addOverlay(marker2);

                function _e() {
                    this.defaultAnchor = BMAP_ANCHOR_TOP_RIGHT,
                        this.defaultOffset = new BMap.Size(10, 10)
                }

                var n = map.getDistance(pt1, pt2).toFixed(0);
                "NaN" == n && (n = 0),
                    _e.prototype = new BMap.Control,
                    _e.prototype.initialize = function (e) {
                        var obj = document.createElement("div");
                        return obj.appendChild(document.createTextNode("距离 " + n + " 米")),
                            obj.className = "mapTopCtrl",
                            e.getContainer().appendChild(obj),
                            obj
                    };
                var o = new _e;
                map.addControl(o);
                map.setViewport([pt1, pt2]);
                map.enableScrollWheelZoom();
                map.enableContinuousZoom();
            }
			 // 显示分数
			 $(".score").each(function() {
			 	$(this).find("p").css("width", $(this).find("i").text() * 15);
			 });
			
			 $('#search').click(function(){
			 	var keyword = $('#keyword').val();
			 	if (keyword.length < 1) return false;
			 	location.href = '/shop/{pigcms{$store["id"]}.html?keyword=' + keyword;
			 });
			 /*底部返回顶部*/  
			 $(window).scroll(function(){  
			 	if ($(window).scrollTop() > 200) {
			 		$(".Return").fadeIn();
			 	} else {
			 		$(".Return").fadeOut(500);
			 	}
			 });
			 $(".Return").click(function() {
			 	$('body,html').animate({scrollTop: 0}, 500);
			 	return false;
			 });
			
			 //清除边框
			 $(".give li:last-child").css("border-right",0);  
			 $(".vlefttop li:last-child").css("background","none");
		});
		</script>
</body>
</html>
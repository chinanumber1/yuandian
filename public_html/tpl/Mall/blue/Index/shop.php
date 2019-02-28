<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<if condition="$config['site_favicon']">
<link rel="shortcut icon" href="{pigcms{$config.site_favicon}" />
</if>
<title>{pigcms{$config.seo_title}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<meta charset="utf-8">
<link href="{pigcms{$static_path}css/bootstrap.min.mall.css" rel="stylesheet" />
<link href="{pigcms{$static_path}css/mallheader.css" rel="stylesheet" />
<link href="{pigcms{$static_path}css/mallshop.css" rel="stylesheet" />
<script src="{pigcms{$static_path}js/jquery-1.9.1.min.js"></script>
<script src="{pigcms{$static_public}js/laytpl.js"></script>
<script src="{pigcms{$static_path}js/bootstrap.js" type="text/javascript" charset="utf-8"></script>
<script src="https://api.map.baidu.com/api?v=2.0&ak=4c1bb2055e24296bbaef36574877b4e2&s=1"></script>
<script>var store_long = '{pigcms{$store.long}',store_lat = '{pigcms{$store.lat}',static_path = "{pigcms{$static_path}";</script>
<script src="{pigcms{$static_path}js/mallshop.js"></script>
<script src="{pigcms{$static_path}js/mallcommon.js"></script>
</head>
<include file="Public:header_top" />
<setion class="containers">
    <!-- 头部分类列表-->
    <div class="navList">
        <div class="classList clearfix">
            <div class="item pull-left">
                <p>
                    <i class="glyphicon glyphicon-th-list"></i>全部分类
                </p>
                <ul>
                    <volist name="categoryList" id="category">
                    <if condition="$i lt 8">
                    <li class="item0">
                        <dl>
                            <dt>
                                <a href="/mall/{pigcms{$category['id']}">{pigcms{$category['name']}</a>
                            </dt>
                            <dd>
                                <volist name="category['son_list']" id="row" key="ii">
                                <if condition="$ii lt 4">
                                <a href="/mall/{pigcms{$category['id']}/{pigcms{$row['id']}"> {pigcms{$row['name']}</a>
                                </if>
                                </volist>
                            </dd>
                        </dl>
                        <i class="glyphicon glyphicon-menu-right"></i>
                        <div class="ziClass">
                            <div class="ziClass_1">
                                <h4><a href="/mall/{pigcms{$category['id']}">{pigcms{$category['name']}</a></h4>
                                <ul class="clearfix">
                                    <volist name="category['son_list']" id="row" key="si">
                                    <li class="pull-left">
                                        <a href="/mall/{pigcms{$category['id']}/{pigcms{$row['id']}">{pigcms{$row['name']}</a>
                                    </li>
                                    </volist>
                                </ul>
                            </div>
                        </div>
                    </li>
                    </if>
                    </volist>
                </ul>
            </div>
            <div class="itemRight pull-left">
                <ul class="clearfix">
                <pigcms:slider cat_key="web_mall_slider" limit="7" var_name="web_index_slider">
                    <li class="pull-left">
                        <a href="{pigcms{$vo.url}">{pigcms{$vo.name}</a>
                    </li>
                </pigcms:slider>
                </ul>
            </div>
        </div>
        <hr/>
    </div>
    <!-- 小导航头-->
    <div class="towNav">
        <ul class="clearfix">
            <li class="pull-left"><a href="/mall/">首页 > </a></li>
            <li class="pull-left">{pigcms{$store['name']}</li>
        </ul>
    </div>
    <!-- 店铺信息-->
    <div class="shopMassage clearfix">
        <div class="shopMassageLeft pull-left">
            <img src="{pigcms{$store['image']}" style="width: 130px;height:130px;border-radius:50%;padding-right:0; margin-right:20px;"/>
            <ul>
                <li><h3>{pigcms{$store['name']}</h3></li>
                <li><i class="glyphicon glyphicon-time"></i> <span>营业时间 : {pigcms{$store['time']}</span></li>
                <li><i class="glyphicon glyphicon-earphone"></i> <span>联系方式 : {pigcms{$store['phone']}</span></li>
                <li><i class="glyphicon glyphicon-map-marker"></i> <span>店铺地址 : {pigcms{$store['adress']}</span></li>
            </ul>
        </div>
        <div class="shopMassageRight pull-right">
            <ul class="clearfix">
                <li class="pull-left">
                    <dl>
                        <dt>{pigcms{$store['star']}</dt>
                        <dd>店铺评分</dd>
                    </dl>
                </li>
                <li class="pull-left">
                    <dl>
                        <dt>{pigcms{$store['month_sale_count']}</dt>
                        <dd>已售</dd>
                    </dl>
                </li>
                <li class="pull-left">
                    <dl>
                        <dt>{pigcms{$store['goods_count']}</dt>
                        <dd>全部商品</dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>
    <div class="contents clearfix">
        <div class="contentLeft pull-left">
            <!-- 商品与评价-->
            <div class="goodUser">

                <div class="goodUserheader clearfix">
                    <div class="goodUserLeft pull-left ">
                        <ul class="clearfix">
                            <li class="pull-left te active">商品</li>
                            <li class="pull-left divider"></li>
                            <a href="/mall/scomment/{pigcms{$store['id']}"><li class="pull-left te ">评价</li></a>
                        </ul>
                    </div>
                    <div class="goodUserRight pull-right goodShow">
                        <ul class="clearfix">
                            <if condition="empty($sort)">
                            <li class="pull-left te active">默认排序</li>
                            <else />
                            <a href="{pigcms{$url}"><li class="pull-left te">默认排序</li></a>
                            </if>
                            <li class="pull-left divider"></li>
                            <if condition="$sort eq 'dsell'">
                            <a href="{pigcms{$url}?order=asell"><li class="pull-left te active">销量 <i class="glyphicon glyphicon-arrow-up"></i></li></a>
                            <elseif condition="$sort eq 'asell'" />
                            <a href="{pigcms{$url}?order=dsell"><li class="pull-left te active">销量 <i class="glyphicon glyphicon-arrow-down"></i></li></a>
                            <else />
                            <a href="{pigcms{$url}?order=dsell"><li class="pull-left te">销量</li></a>
                            </if>
                            <li class="pull-left divider"></li>
                            
                            <if condition="$sort eq 'dprice'">
                            <a href="{pigcms{$url}?order=aprice"><li class="pull-left te active">价格 <i class="glyphicon glyphicon-arrow-up"></i></li></a>
                            <elseif condition="$sort eq 'aprice'" />
                            <a href="{pigcms{$url}?order=dprice"><li class="pull-left te active">价格 <i class="glyphicon glyphicon-arrow-down"></i></li></a>
                            <else />
                            <a href="{pigcms{$url}?order=aprice"><li class="pull-left te">价格</li></a>
                            </if>
                            
                        </ul>
                    </div>
                    <hr/>
                </div>

                <!-- 分类-->
                <div class="goodUserClass goodShow">
                    <ul class="clearfix">
                    <volist name="sort_list" id="sort">
                    <li class="pull-left <php> if (in_array($sort['sort_id'], $select_ids)) { </php>active<php>}</php>"><a href="/mall/shop/{pigcms{$store['id']}/{pigcms{$sort['sort_id']}">{pigcms{$sort['sort_name']}</a></li>
                    </volist>
                    </ul>
                </div>
                <!-- 二级类-->
                <if condition="!empty($secondSort)">
                <div class="goodUserTwo goodShow">
                    <ul class="clearfix yi">
                        <volist name="secondSort" id="second">
                        <li class="pull-left <php> if (in_array($second['sort_id'], $select_ids)) { </php>active<php>}</php>"><a href="/mall/shop/{pigcms{$store['id']}/{pigcms{$second['sort_id']}">{pigcms{$second['sort_name']}</a><php>if ($second['sort_discount']>0) {</php><span>{pigcms{$second['sort_discount']|floatval}折 <i>折扣不同享</i></span><php>}</php></li>
                        </volist>
<!--                         <li class="pull-left"><a href="javascript:void(0);">小米电脑</a> <span>7折 <i>折扣不同享</i></span></li> -->
                    </ul>
                    <php> if (!empty($threeSort)) { </php>
                    <ul class="two clearfix">
                        <volist name="threeSort" id="three">
                        <li class="pull-left <php> if (in_array($three['sort_id'], $select_ids)) { </php>active<php>}</php>"><a href="/mall/shop/{pigcms{$store['id']}/{pigcms{$three['sort_id']}">{pigcms{$three['sort_name']}</a></li>
                        </volist>
                    </ul>
                    <php> } </php>
                </div>
                </if>
            </div>
            <!-- 商品-->
            <div class="goodShow">
                <volist name="goodsList" id="goods">
                <div class="variousClass">
                    <div class="variousClassHeader clearfix">
                        <h3 class="pull-left">{pigcms{$goods['cat_name']} <if condition="$goods['sort_discount'] gt 0"><span>{pigcms{$goods['sort_discount']|floatval}折</span></if></h3>
                    </div>
                    <div class="variousClassContent">
                        <ul class="variousFoods clearfix">
                            <volist name="goods['product_list']" id="product">
                            
                            <li class="pull-left">
                                <a href="/mall/goods/{pigcms{$product['product_id']}">
                                <img src="{pigcms{$product['product_image']}"/>
                                <!--div class="variousJian">
                                    <img src="img/c1_03.png" alt=""/>
                                    <img src="img/c1_05.png" alt=""/>
                                    <img src="img/c1_07.png" alt=""/>
                                </div-->
                                <h4>{pigcms{$product['product_name']}</h4>
                                <div class="priceDis clearfix">
                                    <ul class="pull-left clearfix ">
                                        <li class="pull-left">￥{pigcms{$product['product_price']|floatval}</li>
                                        <li class="pull-left">￥{pigcms{$product['o_price']|floatval} <i></i></li>
                                    </ul>
                                    <p class="pull-right">已售 <span>{pigcms{$product['product_sale']|intval}</span></p>
                                </div>
                                <if condition="!empty($product['is_seckill_price'])">
                                <span class="deadline"><i>限时优惠</i></span>
                                </if>
                                </a>
                            </li>
                            </volist>
                        </ul>
                    </div>
                </div>
                </volist>
            </div>
        </div>
        <div class="contentRight pull-right">
            <!-- 商家公告-->
            <div class="shopShow">
                <p class="shopShowHeader">商家公告</p>
                <p>{pigcms{$store['store_notice']}</p>
            </div>
            <if condition="$store['coupon_list']">
            <div class="shopDiscount">
                <if condition="isset($store['coupon_list']['invoice']) AND $store['coupon_list']['invoice']">
                <p><i class="piao">票</i> 满{pigcms{$store['coupon_list']['invoice']}元支持开发票，请在下单时填写发票抬头</p>
                </if>
                <volist name="store['coupon_list']['system_newuser']" id="sn">
                <p><i class="shou">首</i> 平台首单满{pigcms{$sn['money']}元减{pigcms{$sn['minus']}元</p>
                </volist>
                
                <volist name="store['coupon_list']['system_minus']" id="sm">
                <p><i class="jian1">减</i>平台优惠满{pigcms{$sm['money']}元减{pigcms{$sm['minus']}元</p>
                </volist>
                
                <if condition="$store['coupon_list']['discount']">
                <p><i class="zhe">折</i> 商家{pigcms{$store['coupon_list']['discount']}折优惠</p>
                </if>
                
                <volist name="store['coupon_list']['newuser']" id="n">
                <p><i class="shou2">首</i> 商家首单满{pigcms{$n['money']}元减{pigcms{$n['minus']}元</p>
                </volist>
                <volist name="store['coupon_list']['minus']" id="m">
                <p><i class="hui">惠</i> 商家优惠满{pigcms{$m['money']}元减{pigcms{$m['minus']}元</p>
                </volist>
            </div>
            </if>
            
            <!-- 地图组件-->
            <div class="vright_end" id="biz-map"></div>
        </div>
    </div>
<include file="Public:footer" />
</body>
<script>
$('.details>ul>li').click(function(e){
	$(this).addClass('active').siblings('li').removeClass('active');
	var imgSrc=$(this).find('img').attr('src');
	$(this).parent().prev().attr('src',imgSrc);
});
</script>
</html>
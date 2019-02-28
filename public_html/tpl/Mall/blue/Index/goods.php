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
<link href="{pigcms{$static_path}css/mallgoods.css" rel="stylesheet" />
<script src="{pigcms{$static_path}js/jquery-1.9.1.min.js"></script>
<script src="{pigcms{$static_public}js/layer/layer.js"></script>
<script src="{pigcms{$static_path}js/bootstrap.js" type="text/javascript" charset="utf-8"></script>
<script>var goodsDetail = '{pigcms{$goods_detail}', cartGoods = JSON.parse('{pigcms{$cartGoods}');</script>
<script> if (goodsDetail != '') {goodsDetail = JSON.parse(goodsDetail);}</script>
<script src="{pigcms{$static_path}js/mallcommon.js"></script>
<script src="{pigcms{$static_path}js/mallgoods.js"></script>
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
            <li class="pull-left"><a href="/mall">首页 > </a></li>
            <if condition="!empty($fcate)">
            <li class="pull-left"><a href="/mall/{pigcms{$fcate['id']}">{pigcms{$fcate['name']} > </a></li>
            </if>
            <if condition="!empty($scate)">
            <li class="pull-left"><a href="/mall/{pigcms{$fcate['id']}/{pigcms{$scate['id']}">{pigcms{$scate['name']} > </a></li>
            </if>
            <li class="pull-left">{pigcms{$now_goods['name']}</li>
        </ul>
    </div>
        <!-- 商品详情-->
    <div class="details clearfix">
        <img src="{pigcms{$now_goods['pic_arr'][0]['url']}" class="pull-left" alt=""/>
        <ul class="pull-left">
            <volist name="now_goods['pic_arr']" id="pic">
            <li <if condition="$i eq 1">class="active"</if>><img src="{pigcms{$pic['url']}"/></li>
            </volist>
        </ul>
        <div class="detailsMassage pull-left">
            <h3>{pigcms{$now_goods['name']}</h3>
            <div class="mas1 clearfix">
                <dl class="pull-left">
                    <dt >
                    <ul class="clearfix">
                        <li class="mlft1 pull-left">售价</li>
                        <li class="mlft2 pull-left">￥<b id="price">{pigcms{$now_goods['price']|floatval}</b></li>
                        <if condition="$now_goods['is_seckill_price']">
                        <li class="mlft3 pull-left">原价 ￥<b id="oprice">{pigcms{$now_goods['old_price']|floatval}</b><i></i></li>
                        <li class="mlft4 pull-left">限时优惠<php>if ($now_goods['max_num'] > 0) { echo " ,限" . $now_goods['max_num'] . "份优惠";}</php></li>
                        <elseif condition="$now_goods['max_num'] gt 0" />
                        <li class="mlft4 pull-left">限购{pigcms{$now_goods['max_num']}份</li>
                        </if>
                    </ul>
                </dt>
                    <dd>
                        <ul class="clearfix">
                            <li class="pull-left maseChild1"><i></i>剩余<b id="stock"><if condition="$now_goods['stock_num'] eq -1">充足<else />{pigcms{$now_goods['stock_num']}</if></b></li>
                            <li class="pull-left maseChild2"><i></i>运费{pigcms{$now_goods['deliver_fee']}元</li>
                            <li class="pull-left maseChild3"><i></i>{pigcms{$store['now_city_name']}</li>
                        </ul>
                    </dd>
                </dl>
                <ul class="pull-right mright">
                    <li>{pigcms{$store['reply_count']|intval}</li>
                    <li>用户评价</li>
                </ul>
            </div>
            <!-- 优惠-->
            
            <if condition="$store['coupon_list']">
            <div class="masDis clearfix">
                <span class="pull-left">优惠</span>
                <ul class="pull-left clearfix">
                    <volist name="store['coupon_list']['system_newuser']" id="sn">
                    <li class="pull-left"><i class="shou">首</i> 平台首单满{pigcms{$sn['money']}元减{pigcms{$sn['minus']}元</li>
                    </volist>
                    
                    <volist name="store['coupon_list']['system_minus']" id="sm">
                    <li class="pull-left"><i class="jian1">减</i>平台优惠满{pigcms{$sm['money']}元减{pigcms{$sm['minus']}元</li>
                    </volist>
                    
                    <if condition="$store['coupon_list']['discount']">
                    <li class="pull-left"><i class="zhe">折</i> 商家{pigcms{$store['coupon_list']['discount']}折优惠</li>
                    </if>
                    
                    <volist name="store['coupon_list']['newuser']" id="n">
                    <li class="pull-left"><i class="shou2">首</i> 商家首单满{pigcms{$n['money']}元减{pigcms{$n['minus']}元</li>
                    </volist>
                    <volist name="store['coupon_list']['minus']" id="m">
                    <li class="pull-left"><i class="hui">惠</i> 商家优惠满{pigcms{$m['money']}元减{pigcms{$m['minus']}元</li>
                    </volist>
                </ul>
            </div>
            </if>
            <!-- 颜色-->
                
            <volist name="now_goods['spec_list']" id="spec">
            <div class="masColor clearfix spec" data-goods_id="{pigcms{$now_goods['goods_id']}">
                <span class="pull-left">{pigcms{$spec['name']}</span>
                <ul class="clearfix spec_ul" data-id="{pigcms{$spec['id']}" data-name="{pigcms{$spec['name']}" data-num="1" data-type="spec">
                    <volist name="spec['list']" id="vo" key="s">
                    <li class="pull-left <if condition="$s eq 1">active</if>" data-id="{pigcms{$vo['id']}" data-name="{pigcms{$vo['name']}">{pigcms{$vo['name']}</li>
                    </volist>
                </ul>
            </div>
            </volist>
            <volist name="now_goods['properties_list']" id="property">
            <div class="masColor clearfix property">
                <span class="pull-left">{pigcms{$property['name']}</span>
                <ul class="clearfix properties_ul" data-id="{pigcms{$property['id']}" data-name="{pigcms{$property['name']}" data-num="{pigcms{$property['num']}" data-type="properties">
                    <volist name="property['val']" id="po" key="pi">
                    <li class="pull-left <if condition="$pi eq 1">active</if>" data-id="{pigcms{$pi}" data-name="{pigcms{$po}">{pigcms{$po}</li>
                    </volist>
                </ul>
            </div>
            </volist>
            <!-- 数量-->
            <div class="masNum clearfix">
                <span class="pull-left">数量</span>
                <div class="changeNum pull-left">
                   <button class="less active">-</button>
                    <input type="number" value="1" id="nums"/>
                    <button class=" adds active">+</button>
                </div>
            </div>
            <div class="masbtns">
                <button type="button" id="nowBuy">立即购买</button>
                <button type="button" id="addCart">加入购物车</button>
            </div>
        </div>
    </div>
    
        <div class="contents clearfix">
        <div class="contentLeft pull-left">
            <!-- 商品与评价-->
            <div class="goodUserheader clearfix">
                <div class=" pull-left ">
                    <ul class="clearfix">
                        <li class="pull-left te active">商品</li>
                        <li class="pull-left te "><a href="/mall/comment/{pigcms{$now_goods['goods_id']}">评价</a></li>
                    </ul>
                </div>
            </div>
            <div class="goodUser">
                <div class="goodShow goodDetail">{pigcms{$now_goods['des']}</div>
            </div>
        </div>
        <div class="contentRight pull-right">
            <!-- 店铺公告-->
            <div class="shopText">
                <p class="shopTextHeader">店铺介绍</p>
                <div class="shopTextCont">
                    <img src="{pigcms{$store['image']}" alt=""/>
                    <h5 style="color: #333;margin: 0;">{pigcms{$store['name']}</h5>
                    <h5 style="color: #333;font-size: 16px;margin-top: 18px;margin-bottom: 2px;">{pigcms{$store['goods_count']}</h5>
                    <h5 style="margin-top: 0;margin-bottom: 11px;">全部商品</h5>
                    <p style="margin-bottom: 5px;">营业时间 : {pigcms{$store['time']}</p>
                    <div class="phones">
                        <span>联系方式 : </span>
                        <ul>
                            <volist name="store['phone']" id="phone">
                            <li>{pigcms{$phone}</li>
                            </volist>
                        </ul>
                    </div>
                    <a href="/mall/shop/{pigcms{$store['store_id']}">进店看看</a>
                </div>
            </div>
            <if condition="$goodsList">
            <div class="variousClass">
                <div class="variousClassContent">
                    <ul class="variousFoods">
                        <volist name="goodsList" id="rgoods">
                        <li onclick="location.href='/mall/goods/{pigcms{$rgoods['goods_id']}'">
                            <img src="{pigcms{$rgoods['image']}"/>
                            <h4>{pigcms{$rgoods['name']}</h4>
                            <div class="priceDis clearfix">
                                <ul class="pull-left clearfix ">
                                    <li class="pull-left">￥{pigcms{$rgoods['price']|floatval}</li>
                                    <li class="pull-left">￥{pigcms{$rgoods['o_price']|floatval} <i></i></li>
                                </ul>
                                <p class="pull-right">已售 <span>{pigcms{$rgoods['sell_count']|intval}</span></p>
                            </div>
                            <if condition="$rgoods['is_seckill_price']">
                            <span class="deadline"><i>限时优惠</i></span>
                            </if>
                        </li>
                        </volist>
                    </ul>
                </div>
            </div>
            </if>
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
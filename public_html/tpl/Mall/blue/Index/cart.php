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
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/awesome-bootstrap-checkbox.css"/>
<link rel="stylesheet" href="{pigcms{$static_path}css/font-awesome.css"/>
<link href="{pigcms{$static_path}css/mallheader.css" rel="stylesheet" />
<link href="{pigcms{$static_path}css/mallcart.css" rel="stylesheet" />
<script src="{pigcms{$static_path}js/jquery-1.9.1.min.js"></script>
<script src="{pigcms{$static_public}js/layer/layer.js"></script>
<script src="{pigcms{$static_path}js/bootstrap.js" type="text/javascript" charset="utf-8"></script>
<script src="{pigcms{$static_path}js/mallcommon.js"></script>
<script src="{pigcms{$static_path}js/mallcart.js"></script>
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

<div class="foodsCont">
    <div class="towNav">
        <b>我的购物车
            <span></span>
        </b>
    </div>
    <div class="payGoodsHeader">
        <ul class="clearfix">
            <li class="pull-left">
                <div class=" alls">
                    <label for="awardLimit1">操作</label>
                </div>
            </li>
            <li class="pull-left">商品信息</li>
            <li class="pull-left">单价( 元 )</li>
            <li class="pull-left">数量</li>
            <li class="pull-left">小计( 元 )</li>
            <li class="pull-left">操作</li>
        </ul>
    </div>
    <div class="payGoodsContent"></div>
    <div class="payGoodsFoot">
        <div class="f1">
        </div>
        <div class="f2" id="clearCart">
            <a href="javascript:void(0);">清空购物车</a>
        </div>
        <div class="f3">已选商品
            <span id="selectCount">0</span>件
        </div>
        <div class="f4">商品总价 :
            <span id="selectPrice">￥0</span>
        </div>
        <div class="f5 active" id="nowBuy">去结算</div>
    </div>
</div>
<div class="nothing">
    <ul>
        <li>
            <img src="{pigcms{$static_path}images/emptyCart.png" alt="" />
        </li>
        <li>购物车空空如也</li>
        <li>
            <a href="/mall/"><span>去逛逛</span></a>
        </li>
    </ul>
</div>
<include file="Public:footer" />
</body>
</html>
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
<link href="{pigcms{$static_path}css/mallcategory.css" rel="stylesheet" />
<script src="{pigcms{$static_path}js/jquery-1.9.1.min.js"></script>
<script src="{pigcms{$static_public}js/laytpl.js"></script>
<script src="{pigcms{$static_path}js/bootstrap.js" type="text/javascript" charset="utf-8"></script>
<script>var catefid = '{pigcms{$fid}', cateid = '{pigcms{$cid}';</script>
<script src="{pigcms{$static_path}js/mallcatedetail.js"></script>
<script src="{pigcms{$static_path}js/mallcommon.js"></script>
<style>
.Load {
    line-height: 55px;
    font-size: 18px;
    color: #666666;
    display: block;
    text-align: center;
    background: #ebebeb;
}
</style>
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
    <!-- 二级分类-->
    <div class="twoClass">
        <ul class="clearfix">
            <li class="pull-left"><a href="/mall/">首页 > </a></li>
            <li class="pull-left">
                <span>{pigcms{$fcategory['name']} <i class="glyphicon glyphicon-triangle-bottom"></i> <i class="glyphicon glyphicon-triangle-top"></i></span>
                <dl class="clearfix lastClass">
                    <dd class="pull-left"><a href="/mall">全部</a><span>|</span></dd>
                    <volist name="categoryList" id="cate">
                    <dd class="pull-left <if condition="$cate['id'] eq $fcategory['id']">active</if>"><a href="/mall/{pigcms{$cate['id']}">{pigcms{$cate['name']}</a><span>|</span></dd>
                    </volist>
                </dl>
            </li>
        </ul>
    </div>
    <!-- 轮播图-->
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
    <ol class="carousel-indicators">
        <volist name="banners" id="banner">
            <li data-target="#carousel-example-generic" data-slide-to="{pigcms{$i- 1}" <if condition="$i eq 1">class="active"</if>></li>
        </volist>
    </ol>
    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <volist name="banners" id="banner">
        <div class="item <if  condition="$i eq 1">active</if>">
            <a href="{pigcms{$banner['url']}" target="_blank">
                <img src="{pigcms{$banner['image']}">
                <div class="carousel-caption"></div>
            </a>
        </div>
        </volist>
    </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <!--<span class="sr-only">Previous</span>-->
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <!--<span class="sr-only">Next</span>-->
        </a>
    </div>

    <!--分类 -->
    <div class="classify">
        <div class="classifyItem clearfix">
            <p class="pull-left">分类</p>
            <ul class="clearfix">
                <li class="pull-left <if condition="$cid eq 0">active</if>"><a href="/mall/{pigcms{$fid}">全部</a></li>
                <volist name="sonCategorys" id="son">
                <li class="pull-left <if condition="$cid eq $son['id']">active</if>"><a href="/mall/{pigcms{$fid}/{pigcms{$son['id']}">{pigcms{$son['name']}</a></li>
                </volist>
            </ul>
        </div>
        <volist name="pdata" id="plist">
        <div class="classifyItem clearfix">
            <p class="pull-left">{pigcms{$plist['name']}</p>
            <ul class="clearfix">
<!--                 <li class="pull-left active"><a href="javascript:;">全部</a></li> -->
                <volist name="plist['value']" id="rval">
                <li class="pull-left">
                    <div class="checkbox checkbox-success checkbox-inline allItem" >
                        <input id="pval_{pigcms{$rval['id']}" class="awardLimit" type="checkbox" value="{pigcms{$rval['id']}">
                        <label for="pval_{pigcms{$rval['id']}"><span style="position:relative;top: -2px;">{pigcms{$rval['name']}</span></label>
                    </div>
                </li>
                </volist>
            </ul>
        </div>
        </volist>
        <div class="classifyItem clearfix sort">
            <p class="pull-left">排序</p>
            <ul class="clearfix">
                <li class="pull-left active "><a href="javascript:;">综合</a></li>
                <li class="pull-left sortItem">
                    <a href="javascript:;">按销量排序</a>
                    <dl>
                        <dt style="line-height: 0.7;"><i class="glyphicon glyphicon-triangle-top "></i></dt>
                        <dd style="line-height: 0.7;"><i class="glyphicon glyphicon-triangle-bottom"></i></dd>
                    </dl>
                </li>
                <li class="pull-left sortItem">
                    <a href="javascript:;">按价格排序</a>
                    <dl>
                        <dt style="line-height: 0.7;"><i class="glyphicon glyphicon-triangle-top "></i></dt>
                        <dd style="line-height: 0.7;"><i class="glyphicon glyphicon-triangle-bottom"></i></dd>
                    </dl>
                </li>
            </ul>
        </div>
    </div>
    <!-- 各种分类下商品-->
    <div class="variousClass">
        <div class="variousClassContent">
            <ul class="variousFoods clearfix"></ul>
        </div>
    </div>
    <a href="javascript:void(0)" class="Load" data-page="2" style="display: none">点击加载更多商品...</a>
<script id="mallListBoxTpl" type="text/html">
{{# for(var i in d){ }}
    <li class="pull-left">
    <a href="{{ d[i].pcmallurl }}">
    <img src="{{ d[i].image }}"/>
    <div class="variousJian"></div>
    <h4>{{ d[i].name }}</h4>
    <div class="priceDis clearfix">
    <ul class="pull-left clearfix ">
    <li class="pull-left">￥{{ d[i].price }}</li>
    <li class="pull-left">￥{{ d[i].old_price }} <i></i></li>
    </ul>
    <p class="pull-right">已售 <span>{{ d[i].sell_count }}</span></p>
    </div>
    {{# if (d[i].is_seckill_price == true) { }}
    <span class="deadline"><i>限时优惠</i></span>
    {{# } }}
    </a>
    </li>
{{# } }}
</script>
<include file="Public:footer" />
</body>
</html>
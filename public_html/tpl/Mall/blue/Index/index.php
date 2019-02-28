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
<link href="{pigcms{$static_path}css/mallindex.css" rel="stylesheet" />
<script src="{pigcms{$static_path}js/jquery-1.9.1.min.js"></script>
<script src="{pigcms{$static_public}js/laytpl.js"></script>
<script src="{pigcms{$static_path}js/bootstrap.js"></script>
<script src="{pigcms{$static_path}js/mallcommon.js"></script>
<script src="{pigcms{$static_path}js/mallindex.js"></script>
</head>
<include file="Public:header_top" />
<setion class="containers"> <!-- 头部分类列表-->
<div class="navList">
    <div class="classList clearfix">
        <div class="item pull-left">
            <p><i class="glyphicon glyphicon-th-list"></i>全部分类</p>
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
    <hr />
</div>
<!-- 轮播图-->
<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
        <pigcms:adver cat_key="web_mall_banner" limit="6" var_name="index_today_fav">
            <li data-target="#carousel-example-generic" data-slide-to="{pigcms{$i - 1}" <if condition="$i eq 1">class="active"</if>></li>
        </pigcms:adver>
    </ol>
    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
        <pigcms:adver cat_key="web_mall_banner" limit="6" var_name="index_today_fav">
            <div class="item <if  condition="$i eq 1">active</if>">
            <a href="{pigcms{$vo['url']}" target="_blank">
                <img src="{pigcms{$vo.pic}" alt="{pigcms{$vo.name}">
                <div class="carousel-caption"></div>
            </a>
            </div>
        </pigcms:adver>
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
<!-- 手风琴效果-->
<if condition="$count gt 4">
<div class="accordion">
    <ul class="clearfix accImg">
        <pigcms:adver cat_key="web_mall_middle" limit="6" var_name="index_today_fav">
            <li class="press a{pigcms{$i} pull-left" style="background: url('{pigcms{$vo.pic}') no-repeat;background-size:cover; ">
                <a target="_blank" href="{pigcms{$vo.url}">
                    <i></i>
                    <!-- <img src="{pigcms{$vo.pic}" alt="{pigcms{$vo.name}"> -->
                </a>
            </li>
        </pigcms:adver>
    </ul>
</div>
</if>
<!-- 各种分类下商品-->
<div id="contentList"></div>
<script id="mallCatBoxTpl" type="text/html">
{{# for(var i in d){ }}
    <div class="variousClass">
        <div class="variousClassHeader clearfix">
            <h3 class="pull-left">{{ d[i].name }}</h3>
            <ul class="clearfix pull-left">
                {{# for (var ii in d[i].son_list) { }}
                {{# if (ii == 0) { }}
                {{# mallGoods(d[i].son_list[ii].id); }}
                <li class="pull-left active" data-id="{{ d[i].son_list[ii].id }}">{{ d[i].son_list[ii].name }}</li>
                {{# } else { }}
                <li class="pull-left" data-id="{{ d[i].son_list[ii].id }}">{{ d[i].son_list[ii].name }}</li>
                {{# } }}
                {{# } }}
            </ul>
            {{# if (d[i].son_list.length > 5) { }}
            <a href="/mall/{{ d[i].id }}" class="pull-right">查看更多></a>
            {{# } }}
        </div>
        <div class="variousClassContent" {{# if (d[i].image.length < 1) { }}style="margin-top: 20px"{{# } }}>
            {{# if (d[i].image.length > 0) { }}
            <a href="{{ d[i].url}}"><img src="{{ d[i].image}}" class="imgs" /></a>
            {{# } }}
            <ul class="variousFoods clearfix" id="goods_{{ d[i].id }}"></ul>
        </div>
    </div>
{{# } }}
</script>

<script id="mallListBoxTpl" type="text/html">
{{# for(var i in d){ }}
    <li class="pull-left">
        <a href="{{ d[i].pcmallurl }}">
        <img src="{{ d[i].image }}" />
        <!--div class="variousJian">
        <span class="hui">惠</span>
        <span class="jian1">减</span>
        <span class="zhe">折</span>
        </div-->
        <h4>{{ d[i].name }}</h4>
        <p>￥{{ d[i].price }}</p>
        {{# if (d[i].is_seckill_price == true) { }}
        <span class="deadline">
            <i>限时优惠</i>
        </span>
        {{# } }}
        </a>
    </li>
{{# } }}
</script>
<include file="Public:footer" />
</body>
<script>
$('.accordion').off('mouseover','.accImg li').on('mouseover','.accImg li',function(e){
    var me=this;
    $(this).width(680).css('transition','width 0.1s').siblings('li').width(130).css('transition','width 0.1s');
    $(this).find('i').hide().parents('li').siblings('li').find('i').show();
    var index=$(this).index();
    $(me).mouseout(function(e){
        $(this).width(680).css('transition','width 0.1s').siblings('li').width(130).css('transition','width 0.1s');
        $(this).find('i').hide().parents('li').siblings('li').find('i').show();
    });
});
</script>
</html>
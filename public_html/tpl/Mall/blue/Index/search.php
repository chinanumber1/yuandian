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
<link href="{pigcms{$static_path}css/mallsearch.css" rel="stylesheet" />
<script src="{pigcms{$static_path}js/jquery-1.9.1.min.js"></script>
<script src="{pigcms{$static_public}js/laytpl.js"></script>
<script src="{pigcms{$static_path}js/bootstrap.js" type="text/javascript" charset="utf-8"></script>
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
    <if condition="$return['total'] gt 0">
	<div class="towNav">
		<ul class="clearfix">
			<li class="pull-left alldian">全部<if condition="$search_type eq  1">店铺<else />商品</if> &gt;</li>
			<li class="pull-left">为您搜索到 <span>{pigcms{$return['total']}</span> 个结果</li>
		</ul>
	</div>
	<!-- 各种分类下商品-->
    <php> if (isset($return['goods_list'])) { </php>
	<div class="variousClass">
		<div class="variousClassContent">
			<ul class="variousFoods clearfix">
                <volist name="return['goods_list']" id="goods">
                
				<li class="pull-left">
                <a href="{pigcms{$goods['pcmallurl']}">
					<img src="{pigcms{$goods['image']}"/>
					<!--div class="variousJian">
						<img src="img/c1_03.png" alt=""/>
						<img src="img/c1_05.png" alt=""/>
						<img src="img/c1_07.png" alt=""/>
					</div-->
					<h4>{pigcms{$goods['name']}</h4>
					<div class="priceDis clearfix">
						<ul class="pull-left clearfix ">
							<li class="pull-left">￥{pigcms{$goods['price']}</li>
                            <if condition="$goods['is_seckill_price']">
							<li class="pull-left">￥{pigcms{$goods['old_price']} <i></i></li>
                            <else />
                            <li class="pull-left"><i></i></li>
                            </if>
						</ul>
						<p class="pull-right">已售 <span>{pigcms{$goods['sell_count']}</span></p>
					</div>
                    </a>
				</li>
                </volist>
			</ul>
		</div>
	</div>
    <php> } elseif ($return['store_list']) { </php>
	<!-- 店铺-->
	<div class="variousClass">
		<div class="variousClassContent">
			<ul class="variousFoods clearfix">
                <volist name="return['store_list']" id="store">
				<li class="pull-left">
                <a href="/mall/shop/{pigcms{$store['store_id']}">
					<img src="{pigcms{$store['image']}"/>
					<h4>{pigcms{$store['name']}</h4>
					<div class="ratings">
						<span><b><i style="width: {pigcms{$store['score_mean'] * 15}px"></i></b>{pigcms{$store['score_mean']}分</span>
						<p>共{pigcms{$store['goods_count']}件商品</p>
					</div>
					<!--div class="variousJian1">
						<img src="img/c1_03.png" alt=""/>
						<img src="img/c1_05.png" alt=""/>
						<img src="img/c1_07.png" alt=""/>
					</div-->
                    </a>
				</li>
                </volist>
			</ul>
		</div>
	</div>
    <php> } </php>
    <else />
	<div class="nothings" style="display: block;">
		<img src="{pigcms{$static_path}images/128.png"/>
		<p>暂未搜索到相关内容</p>
	</div>
    </if>
<include file="Public:footer" />
</body>
<script>
$('.sortItem ').click(function(e){
	if($(this).find('dt i').is('.active')){
		$(this).find('dt i').removeClass('active').parents('li').find('dd i').addClass('active');
	}else{
		$(this).find('dd i').removeClass('active').parents('li').find('dt i').addClass('active');
	}
});
$('.logosRight ul li').click(function(e){
    $(this).addClass('active').siblings().removeClass('active');
});
</script>
</html>
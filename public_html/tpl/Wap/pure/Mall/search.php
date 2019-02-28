<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>搜索</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mall.css"/>
<!-- <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script> -->
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script type="text/javascript">var noAnimate = true;</script>
<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
<script>var ajax_url = '{pigcms{:U("Mall/ajax_list")}';var open_extra_price =Number("{pigcms{$config.open_extra_price}");
var extra_price_name ="{pigcms{$config.extra_price_alias_name}";</script>
<script type="text/javascript" src="{pigcms{$static_path}js/mallsearch.js"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>
<body>
<section class="search searcht">
	<div class="searcht_n">
		<if condition="$store_id eq 0">
		<div class="cond">
			<span class="on">商品</span>
			<div class="cond_list">
				<span class="sp">商品</span>
				<span class="dp">店铺</span>
			</div>
		</div>
		<input type="text" placeholder="" class="se_input" value="{pigcms{$keyword}" />
		<else />
		<input type="text" placeholder="" class="se_input" value="{pigcms{$keyword}" style="padding-left: 30px;" />
		</if>
		<input type="hidden" id="store_id" value="{pigcms{$store_id}" />
		<a href="javascript:void(0)" id="search">搜索</a>
	</div>
</section>

<section class="hot">
	<!-- 收索没有结果图 -->
	<div class="psnone" style="display: none;">
		<img src="{pigcms{$static_path}images/s_08.png">
	</div>
	
	<!--  收索结果列表 -->
	<div class="bd_a clr search_list" >
	</div>
	<div class="navBox_list search_list">
	</div>
</section>
</body>
<script id="storeListBoxTpl" type="text/html">
{{# for(var i = 0, len = d.store_list.length; i < len; i++){ }}
	<a href="{{ d.store_list[i].url }}">
		<div class="Menulink clr">
			<div class="Menulink_img fl">
				<img class="on" src="{{ d.store_list[i].image }}">
				<span class="MenuGroup"></span>
			</div>
			<div class="Menulink_right">
				<h2>{{ d.store_list[i].name }}</h2>
				<div class="MenuPrice">
					<span class="PriceF">综合评分<i>{{ d.store_list[i].score_mean }}</i></span>
					<span class="PriceS">共<i>{{ d.store_list[i].goods_count }}</i>件商品</span>
				</div>
			</div>
		</div> 
	</a>
{{# } }}
</script>
<script id="goodsListBoxTpl" type="text/html">
{{# for(var i = 0, len = d.goods_list.length; i < len; i++){ }}
	<a href="{{ d.goods_list[i].url }}">
		<div class="bd_img">
			<img src="{{ d.goods_list[i].image }}" width="100%"/>
		</div>
		<div class="bd_text">
			<h2>{{ d.goods_list[i].name }}</h2>
			<div class="Price clr">
				<div class="fl">
					<span>￥<i>{{ d.goods_list[i].price }}{{# if(open_extra_price&&d.goods_list[i].extra_pay_price>0){ }}+{{ d.goods_list[i].extra_pay_price}} {{ extra_price_name }} {{# } }}</i></span>
					{{# if (d.goods_list[i].is_seckill_price){}}
					<del>原价{{ d.goods_list[i].old_price }}</del>
					{{# } }}
				</div>
				{{# if (d.goods_list[i].sell_count > 0){}}
				<div class="fr">已售{{ d.goods_list[i].sell_count }}单</div>
				{{# } else if(d.goods_list[i].is_new == 1) { }}
				<div class="fr">新品上架</div>
				{{# } }}
			</div>
		</div>
		{{# if (d.goods_list[i].is_seckill_price){}}
		<div class="discount">限时优惠</div>
		{{# } }}
	</a>
{{# } }}
</script>
</html>
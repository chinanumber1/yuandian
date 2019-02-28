<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta name="format-detection" content="telephone=no"/>
<meta charset="utf-8">
<title>{pigcms{$title}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mall.css"/>
<!-- <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script> -->
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script type="text/javascript">var noAnimate = true;</script>
<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
<script>var cat_fid = '{pigcms{$cat_fid}', cat_id = '{pigcms{$cat_id}', names = '{pigcms{$names}', ajax_url = '{pigcms{:U("Mall/ajax_list")}';
var open_extra_price =Number("{pigcms{$config.open_extra_price}");
var extra_price_name ="{pigcms{$config.extra_price_alias_name}";
</script>
<!--[if lte IE 9]>
<script src="{pigcms{$static_path}js/html5shiv.min.js"></script>
<![endif]-->
<script type="text/javascript" src="{pigcms{$static_path}js/mallgoodslist.js" charset="utf-8"></script>
</head>
<body>
	<section class="search">
		<input type="text" placeholder="搜索商品名称" class="se_input">
		<a href="javascript:void(0)" id="search">搜索</a>
	</section>
	<section class="dityhome">
		<div class="dity_top">
			<ul class="clr">
				<li class="ou updown" data-sort="1">
					<span>综合</span>
				</li>
				<li data-sort="2">
					<span>销量</span>
				</li>
				<li data-sort="3">
					<span>价格</span>
				</li>
			</ul>
		</div>
		<div class="dity_end">
			<ul class="clr">
				<volist name="properties" id="row">
				<li>
					<span>{pigcms{$row['name']}</span>
				</li>
				</volist>
			</ul>

			<!-- 下拉菜单 -->
			<volist name="properties" id="rowset">
			<div class="drop">
				<div class="drop_top drop_top{pigcms{$key}">
					<dl class="clr">
						<volist name="rowset['value_list']" id="vo">
						<dd data-pid="{pigcms{$vo['id']}">
							<span>{pigcms{$vo['name']}</span>
						</dd>
						</volist>
					</dl>
				</div>
				<div class="confirm clr">
					<a href="javascript:void(0)" class="fl">重置</a>
					<a href="javascript:void(0)" class="fr">确定</a>
				</div>
			</div>
			</volist>
		</div>
		<div class="psnone" style="display: none;">
			<img src="{pigcms{$static_path}images/ksjt.png">
		</div>
 		<div class="bd_a clr">
		</div>
	</section>
	<adiv class="mask maskt"></adiv>
</body>
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
					<span>￥<i>{{ d.goods_list[i].price }}{{# if(open_extra_price==1&&d.goods_list[i].extra_pay_price>0){ }}+{{ d.goods_list[i].extra_pay_price }}{{ extra_price_name }}{{# } }}</i></span>
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
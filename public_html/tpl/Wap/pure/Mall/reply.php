<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta name="format-detection" content="telephone=no"/>
<meta charset="utf-8">
<title>{pigcms{$store['name']}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mall.css"/>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/swiper.min.css"/>
<!-- <script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script> -->
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script type="text/javascript">var noAnimate = true;</script>
<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
<script>var shopReplyUrl = "{pigcms{$config.site_url}/index.php?g=Index&c=Reply&a=ajax_get_list&order_type=3&parent_id={pigcms{$store['store_id']}";</script>
<script type="text/javascript" src="{pigcms{$static_path}js/mallreply.js" charset="utf-8"></script>
</head>
<body>
<section class="details_comment">
	<div class="details_comment_n clr">
		<div class="details_comment_top">
			<div class="atar_Show">
				<p></p>
			</div>
			<span>{pigcms{$store['score_mean']}</span>
		</div>
		<div class="details_comment_end">{pigcms{$store['reply_count']}人评价</div>
	</div> 
</section>
<section class="evaluate evaluates evaluate2s">
	<dl></dl>
</section>
</body>
<script id="shopReplyTpl" type="text/html">
{{# for(var i = 0, len = d.length; i < len; i++){ }}
<dd>
	<div class="title clr">
		<h2 class="fl clr">
			<img src="{{# if(d[i].avatar!= ''){}}{{ d[i].avatar }}{{# }else{ }}/static/images/portrait.jpg{{# } }}" width=20 height=20 class="fl">
			<span class="fl">{{ d[i].nickname }}</span>
		</h2>
		<div class="atar_Show fr">
			<p tip="{{ d[i].score }}"></p>
		</div>
	</div>
	<div class="content">{{ d[i].comment }}</div>
	{{# if(d[i].goods){ }}
		{{# var tmpGoods = d[i].goods; }}
		<div class="attr">点赞商品：
		{{# for(var k in tmpGoods){ }}
			{{ tmpGoods[k] }} 
		{{# } }}
		</div>
	{{# } }}
	<div class="attr clr">
		<span>发表日期：{{ d[i].add_time_hi }}</span>
	</div>
	{{# if(d[i].merchant_reply_time != '0'){ }}
	<div class="date">
		<div class="data_n">
			<div class="data_top clr">
				<h2 class="fl">商家回复</h2>
				<span class="fr">{{ d[i].merchant_reply_time_hi }}</span>
			</div>
			<p>{{ d[i].merchant_reply_content }}</p>
		</div>
	</div>
	{{# } }}
</dd>
{{# } }}
</script>
</html>
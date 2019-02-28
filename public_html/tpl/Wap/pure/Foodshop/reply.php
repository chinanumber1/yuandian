<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$config.meal_alias_name}-{pigcms{$foodshop['name']}评论列表</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/css_whir.css"/>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
<script type="text/javascript">
	var location_url = "{pigcms{:U('Foodshop/replyList', array('store_id' => $foodshop['store_id']))}";
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/replylist.js?210" charset="utf-8"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>
<body style="background-color: #fff">
	<section class="details_comment">
		<div class="details_comment_n">
			<div class="details_comment_top">
				<div class="atar_Show"><p></p></div>
				<span>{pigcms{$foodshop['score_mean']|floatval}</span>
			</div>
			<div class="details_comment_end">{pigcms{$foodshop['reply_count']}人评价</div>
		</div> 
	</section>
	<div id="container">
		<div id="scroller">
			<div id="pullDown">
				<span class="pullDownIcon"></span><span class="pullDownLabel">下拉可以刷新</span>
			</div>
			<section class="details_evaluate m43 listBox">
				<ul style="margin-top:0"></ul>
			</section>
			<div id="pullUp">
				<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
			</div>
		</div>
	</div>
</body>
<script id="replyListBoxTpl" type="text/html">
{{# for(var i = 0, len = d.list.length; i < len; i++){ }}
<li>
	<div class="details_evaluate_top clr">
		<div class="evaluate_left">
			<h3>{{ d.list[i].nickname }}</h3>
			<span>{{ d.list[i].add_time }}</span>
		</div>
		<div class="evaluate_right">
			<div class="atar_Show">
				<p tip="{{ d.list[i].score }}"></p>
			</div>
		</div>  
	</div>
	<div class="details_evaluate_end" data-hide="0">{{ d.list[i].comment }}</div>
	{{# if(d.list[i].merchant_reply_content){ }}
		<div style="font-size:12px;background:#F8F8F8;padding:10px;line-height:24px;color:#333333;text-align:justify;"><span style="color:#FF532A;">商家回复：</span><span>{{ d.list[i].merchant_reply_content }}</span></div>
	{{# } }}
	<a href="javascript:void(0)" class="more"></a>
</li>
{{# } }}
</script>
</html>
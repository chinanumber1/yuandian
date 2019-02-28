<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>我的{pigcms{$config['score_name']} | {pigcms{$config.site_name}</title>
    <!--[if IE 6]>
		<script src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a-min.v86c6ab94.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
		<script src="{pigcms{$static_path}js/html5shiv.min-min.v01cbd8f0.js"></script>
    <![endif]-->
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.v113ea197.css" />
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/base.v492b572b.css" />
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/search-box.v6656b683.css" />
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/cate-nav.v4299f875.css" />
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/filter.ved243bd9.css" />
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/deallist.v49c087a6.css" />
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/side.v4cfd6eb1.css" />
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/qrcode.v74a11a81.css" />
    <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/banner-index.v8c9e126d.css" />
	<script type="text/javascript">
	   var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	</script>	
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_path}js/common.js"></script>
	<script src="{pigcms{$static_path}js/category.js"></script>
</head>
<body id="credit" class="has-order-nav" style="position:static;">
	<div id="doc" class="bg-for-new-index">
		<header id="site-mast" class="site-mast">
			<include file="Public:header_top"/>
		</header>
		<div id="bdw" class="bdw">
			<div id="bd" class="cf">
				<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/order-nav.v0efd44e8.css" />
				<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/account.v1a41925d.css" />
				<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/table-section.v538886b7.css" />
				<div class="component-order-nav mt-component--booted">
					<div class="side-nav J-order-nav">
						<div class="J-side-nav__user side-nav__user cf">
							<a href="javascript:void(0);" title="帐户设置" class="J-user item user">
								<img src="<if condition="$now_user['avatar']">{pigcms{$now_user.avatar}<else/>{pigcms{$static_path}images/user-default-avatar.png</if>" width="30" height="30" alt="{pigcms{$now_user.nickname}头像"/>
							</a>
							<div class="item info">
								<div class="info__name" style="height:36px;line-height:36px;">{pigcms{$now_user.nickname}</div>
							</div>
						</div>
						<div class="side-nav__account cf">
							<a class="item item--first" href="{pigcms{:U('Credit/index')}" title="{pigcms{$now_user.now_money}">{pigcms{$now_user.now_money}<span>余额</span></a>
							<a class="item" href="{pigcms{:U('Point/index')}" title="{pigcms{$now_user.score_count}">{pigcms{$now_user.score_count}<span>{pigcms{$config['score_name']}</span></a>
						</div>
						<dl class="side-nav__list">
							<dt class="first-item"><strong>我的订单</strong></dt>
							<dd>
								<ul class="item-list">
									<li><a href="{pigcms{:U('Index/index')}">{pigcms{$config.group_alias_name}订单</a></li>
									<li><a href="{pigcms{:U('Index/meal_list')}">{pigcms{$config.meal_alias_name}订单</a></li>
									<li><a href="{pigcms{:U('Collect/index')}">我的收藏</a></li>
								</ul>
							</dd>
							<dt><strong>我的评价</strong></dt>
							<dd>
								<ul class="item-list">
									<li><a href="{pigcms{:U('Rates/index')}">待评价</a></li>
									<li><a href="{pigcms{:U('Rates/rated')}">已评价</a></li>
								</ul>
							</dd>
							<dt><strong>我的账户</strong></dt>
							<dd class="last">
								<ul class="item-list">
									<li class="current"><a href="{pigcms{:U('Point/index')}">我的{pigcms{$config['score_name']}</a></li>
									<li><a href="{pigcms{:U('Credit/index')}">我的余额</a></li>
									<li><a href="{pigcms{:U('Adress/index')}">收货地址</a></li>
								</ul>
							</dd>
						</dl>
					</div>
				</div>
				<div id="content" class="coupons-box">
					<div class="mainbox mine">
						<div class="balance">您当前的{pigcms{$config['score_name']}： <strong>{pigcms{$now_user.score_count}</strong></div>
						<ul class="filter cf">
							<li class="current"><a href="{pigcms{:U('Point/index')}">{pigcms{$config['score_name']}记录</a></li>
						</ul>
						<div class="table-section">
							<table cellspacing="0" cellpadding="0" border="0">
								<tr>
									<th width="130">时间</th>
									<th width="auto">详情</th>
									<th width="110">{pigcms{$config['score_name']}</th>
								</tr>
								<volist name="score_list" id="vo">
									<tr>
										<td>{pigcms{$vo.time|date='Y-m-d H:i:s',###}</td>
										<td class="detail">{pigcms{$vo.desc}</td>
										<if condition="$vo['type'] eq 1">
											<td class="income">+{pigcms{$vo.score}</td>
										<else/>
											<td class="expense">-{pigcms{$vo.score}</td>
										</if>
									</tr>
								</volist>
							</table>
						</div>
						{pigcms{$pagebar}
                    </div>
				</div>
			</div> <!-- bd end -->
		</div>
	</div>
	<include file="Public:footer"/>
</body>
</html>

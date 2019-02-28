<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<title>我的帖子 | {pigcms{$config.site_name}</title> 
	<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
	<meta name="description" content="{pigcms{$config.seo_description}" />
	<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
	<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
	<link href="{pigcms{$static_path}css/meal_order_list.css"  rel="stylesheet"  type="text/css" />
	<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
	<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
	<script type="text/javascript">
	   var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	   var levelToupdateUrl="{pigcms{$config['site_url']}{pigcms{:U('User/Level/levelUpdate')}"
	</script>
	<script src="{pigcms{$static_path}js/common.js"></script>

	<!--[if IE 6]>
	<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
	<script type="text/javascript">DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');</script>
	<script type="text/javascript">DD_belatedPNG.fix('*');</script>
	<style type="text/css"> 
body{behavior:url("{pigcms{$static_path}css/csshover.htc");}
.category_list li:hover .bmbox {filter:alpha(opacity=50);}
.gd_box{display: none;}
</style>
	<![endif]-->
	<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
	<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
</head>
<body id="credit" class="has-order-nav" style="position:static;">
	<include file="Public:header_top"/>
	<div class="body pg-buy-process">
		<div id="doc" class="bg-for-new-index">
			<article>
				<div class="menu cf">
					<div class="menu_left hide">
						<div class="menu_left_top">全部分类</div>
						<div class="list">
							<ul>
								<volist name="all_category_list" id="vo" key="k">
									<li>
										<div class="li_top cf">
											<if condition="$vo['cat_pic']">
												<div class="icon">
													<img src="{pigcms{$vo.cat_pic}" />
												</div>
											</if>
											<div class="li_txt">
												<a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a>
											</div>
										</div>
										<if condition="$vo['cat_count'] gt 1">
											<div class="li_bottom">
												<volist name="vo['category_list']" id="voo" offset="0" length="3" key="j">
													<span>
														<a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a>
													</span>
												</volist>
											</div>
										</if>
									</li>
								</volist>
							</ul>
						</div>
					</div>
					<div class="menu_right cf">
						<div class="menu_right_top">
							<ul>
								<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
									<li class="ctur">
										<a href="{pigcms{$vo.url}">{pigcms{$vo.name}</a>
									</li>
								</pigcms:slider>
							</ul>
						</div>
					</div>
				</div>
			</article>
			<include file="Public:scroll_msg"/>
			<div id="bdw" class="bdw">
				<div id="bd" class="cf">
					<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/order-nav.v0efd44e8.css" />
					<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/account.v1a41925d.css" />
					<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/table-section.v538886b7.css" />
					<include file="Public:sidebar"/>
					<div id="content" class="coupons-box">
						<div class="mainbox mine">
							<div class="balance">
								您当前的帖子总数： <strong>{pigcms{$count}</strong>&nbsp;条
							</div>
							<div class="table-section">
								<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<th width="100">所属板块</th>
										<th width="400">帖子标题</th>
										<th width="auto">发布时间</th>
										<th width="auto">回复时间</th>
										<th width="auto">操作</th>
									</tr>
									

									<if condition="is_array($tiebaList)">
										<volist name="tiebaList" id="vo">
											<tr>
												<td>{pigcms{$vo.plate_name}</td>
												<td>{pigcms{$vo.title}</td>
												<td>{pigcms{$vo.add_time|date="Y-m-d H:i:s",###} </td>
												<td>{pigcms{$vo.last_time|date="Y-m-d H:i:s",###}</td>
												<td><a href="{pigcms{$config.site_url}/portal.php?g=Portal&c=Tieba&a=detail&tie_id={pigcms{$vo.tie_id}" target="_blank">查看</a></td>
											</tr>
										</volist>
									<else />
										<tr>
											<td colspan="5">
													您暂时没有任何可以查看的帖子！
											</td>
										</tr>
									</if>
								</table>
								
								
							</div>
							{pigcms{$pagebar}
						</div>
					</div>
				</div>
				<!-- bd end -->
			</div>
		</div>
		<include file="Public:footer"/>
</body>
	</html>
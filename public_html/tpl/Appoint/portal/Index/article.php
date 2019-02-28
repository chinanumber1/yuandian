<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<title>{pigcms{$config.appoint_seo_title}</title>
		<meta name="keywords" content="{pigcms{$config.appoint_seo_keywords}" />
		<meta name="description" content="{pigcms{$config.appoint_seo_description}" />
		<link href="{pigcms{$static_path}css/style.css" type="text/css" rel="stylesheet" />
		<link href="{pigcms{$static_path}css/home.css" type="text/css" rel="stylesheet"/>
	</head>
	<body>
		<div class="header">
			<include file="Public:header_top"/>
		</div>
		<div class="wrapper">
			<div class="article">
				<div class="breadcrumb">
					<p class="c_c">
						<i class="breadcrumb-icon"></i>
						<a href="{pigcms{$config.appoint_site_url}">首页</a>&gt;
						<a href="javascript:void(0)">新闻</a>
					</p>
				</div>
				<h2>{pigcms{$detail.title}</h2>
				<p class="address">
				  <span class="pubtime">{pigcms{$detail.publish_time|date='Y-m-d H:i:s',###}</span>
				</p>
				<div class="content">
				  {pigcms{$detail.content}
				</div>
			</div>
			<include file="Public:footer"/>
		</div>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/jquery.flexslider.js"></script>
		<script src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script src="{pigcms{$static_path}js/common.js"></script>
		<script src="{pigcms{$static_path}js/home.js"></script>
	</body>
</html>

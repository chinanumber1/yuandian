<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<title>选择城市 - {pigcms{$config.site_name}</title>
		<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
		<meta name="description" content="{pigcms{$config.seo_description}" />
		<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
		<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
		<link href="{pigcms{$static_path}css/changecity.css"  rel="stylesheet"  type="text/css" />
		<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
		<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
		<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
		<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
		<script src="{pigcms{$static_path}js/common.js"></script>
		<script>var city_action="{pigcms{:U('Changecity/ajaxGetCitysByPro')}",top_domain="{pigcms{$config.many_city_top_domain}",request="{pigcms{$_GET.request}";</script>
		<script src="{pigcms{$static_path}js/changecity.js"></script>
		<!--[if IE 6]>
		<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
		<script type="text/javascript">
		   /* EXAMPLE */
		   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');

		   /* string argument can be any CSS selector */
		   /* .png_bg example is unnecessary */
		   /* change it to what suits you! */
		</script>
		<script type="text/javascript">DD_belatedPNG.fix('*');</script>
		<style type="text/css"> 
			body{behavior:url("{pigcms{$static_path}css/csshover.htc");}
			.category_list li:hover .bmbox {filter:alpha(opacity=50);}
			.gd_box{display:none;}
		</style>
		<![endif]-->
	</head>
	<body>
		<include file="Public:changecity_header_top"/>
        <div class="body">
			<div class="hd">
				<div class="hd_left">
					<form action="">
						<a href="http://{pigcms{$now_city.area_url}.{pigcms{$config.many_city_top_domain}{pigcms{$_GET.request}" class="site_enter">点击进入{pigcms{$now_city.area_name}站&gt;&gt;</a>
						<span class="fm_label">按省份选择：</span>
						<select name="" id="site_sel_pro" class="fm_sel">
							<option value="0">请选择</option>
							<volist name="all_province" id="vo">
								<option value="{pigcms{$vo.area_id}">{pigcms{$vo.area_name}</option>
							</volist>
						</select>
						<select name="" id="site_sel_city" class="fm_sel">
							<option value="">请选择</option>
						</select>
						<input type="submit" value="确定" class="fm_submit"/>
					</form>
				</div>
			</div>
			<div class="had_city">推荐城市：<volist name="hot_city" id="vo"><a href="http://{pigcms{$vo.area_url}.{pigcms{$config.many_city_top_domain}{pigcms{$_GET.request}">{pigcms{$vo.area_name}</a></volist></div>
			<div class="abc_filter" id="city_set_0">
				<p>按城市拼音首字母选择：<volist name="all_city" id="vo"><a href="#city_set_{pigcms{$key}">{pigcms{$key}</a></volist></p>
				<i class="arrow"></i>
			</div>
			<ul class="city_set">
				<volist name="all_city" id="vo">
					<li id="city_set_{pigcms{$key}">
						<span class="abc_star">{pigcms{$key}</span>
						<volist name="vo" id="voo">
							<a href="http://{pigcms{$voo.area_url}.{pigcms{$config.many_city_top_domain}{pigcms{$_GET.request}" title="{pigcms{$voo.area_name}" <if condition="$voo['is_hot']">class="col_hs"</if>>{pigcms{$voo.area_name}</a>
						</volist>
					</li>
				</volist>
			</ul>
        </div>
		<include file="Public:footer"/>
	</body>
</html>

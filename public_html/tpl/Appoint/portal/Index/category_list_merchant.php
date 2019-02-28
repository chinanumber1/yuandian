<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<title>{pigcms{$config.appoint_seo_title}</title>
	<meta name="keywords" content="{pigcms{$config.appoint_seo_keywords}" />
	<meta name="description" content="{pigcms{$config.appoint_seo_description}" />
	<link href="{pigcms{$static_path}css/style.css" type="text/css" rel="stylesheet" />
	<link href="{pigcms{$static_path}css/category.css" type="text/css" rel="stylesheet" />
</head>
<body>
	<div class="header"> <include file="Public:header_top"/>
		<div class="breadcrumb">
			<p class="c_c"> <i class="breadcrumb-icon"></i> <a href="{pigcms{$config.appoint_site_url}">首页</a> &gt;<a href="{pigcms{$config.appoint_list_url}"> 分类列表</a> &gt;<a href="{pigcms{$config.appoint_list_url}#{pigcms{$top_category.cat_url}">{pigcms{$top_category.cat_name}</a> &gt;<a href="{pigcms{$now_category.url}">{pigcms{$now_category.cat_name}</a> </p>
		</div>
	</div>
	<div class="jz-home" <if condition="$config['appoint_son_category_bgimg']">style="background-image:url({pigcms{$config.appoint_son_category_bgimg});"</if>>
		<div class="main" style="padding-top:0px;">
			<if condition='$now_category'>
				<div class="lead">
					<h1>{pigcms{$now_category.cat_name}</h1>
					<if condition='$now_category["desc"]'><div>{pigcms{$now_category.desc}</div></if>
				</div>
			</if>
			<div class="category-filter-box">
				<div class="category-filter-wrapper clearfix">
					<div class="label">区域：</div>
					<ul class="filter-sect-list">
						<li <if condition='($_GET["area_url"] eq "all") OR (!$_GET["area_url"])'>class="current"</if>><a href="{pigcms{$group_category_all}">全部</a></li>
						<volist name='area_list' id='vo'>
							<li <if condition='$now_area["area_id"] eq $vo["area_id"]'>class="current"</if>><a href="{pigcms{$vo.url}">{pigcms{$vo.area_name}</a></li>
						</volist>
					</ul>
				</div>
				<if condition="$circle_list">
					<div class="category-filter-wrapper clearfix">
						<div class="label">商圈：</div>
						<ul class="filter-sect-list"><li <if condition='(stripos($now_area["url"],__SELF__) neq false) OR (stripos($group_category_all,__SELF__) OR (!$now_area["url"]))'>class="current"</if>><a href="{pigcms{$now_area['url']}">全部商圈</a></li>
						<volist name='circle_list' id='vo'>
							<li <if condition='$vo["area_url"] eq $_GET["area_url"]'>class="current"</if>><a href="{pigcms{$vo.url}">{pigcms{$vo.area_name}</a></li>
						</volist>
					   </ul>
					</div>
				</if>
			</div>	
			<div class="container clearfix">
				<div style="float:left;">
					<if condition='$group_list'>
					  <div class="baoj-service">
						<ul class="clearfix">
							<volist name="group_list" id="vo">
								<li class="jz-h-l ofh"> 
									<a href="{pigcms{$vo.url}" class="jz-icon"><img src="{pigcms{$vo.list_pic}"></a>
									<h3>{pigcms{$vo.appoint_name|msubstr=###,0,10}</h3>
									<p class="des">{pigcms{$vo.appoint_content|msubstr=###,0,30}</p>
									<a href="{pigcms{$vo.url}" class="check-xq">查看详情</a> 
								</li>
							</volist>
							<if condition="count($group_list)%4 neq 0">
								<for start="0" end="4-count($group_list)%4">
									<li class="jz-h-qd ofh">
										<p class="s-more">
											更多服务<br>敬请期待
										</p>
									</li>
								</for>
							</if>
						</ul>
					  </div>
					  <else />
					  <div class="baoj-service">
						<ul class="clearfix">
							<for start="0" end="4">
								<li class="jz-h-qd ofh">
									<p class="s-more">
										更多服务<br>敬请期待
									</p>
								</li>
							</for>
						</ul>
					  </div>
					  </if>
					</div>
				<div class="pin-wrapper">
				  <div id="elevator" class="elevator stuckMenu"> </div>
				</div>
			</div>
		</div>
<include file="Public:footer"/>
</div>
<script src="{pigcms{:C('JQUERY_FILE')}"></script> 
<script src="{pigcms{$static_path}js/jquery.flexslider.js"></script> 
<script src="{pigcms{$static_public}js/layer/layer.js"></script> 
<script src="{pigcms{$static_path}js/common.js"></script> 
<script src="{pigcms{$static_path}js/pin.js"></script> 
<script src="{pigcms{$static_path}js/category.js"></script>
</body>
</html>

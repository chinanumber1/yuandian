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
		<div class="header">
			<include file="Public:header_top"/>
			<div class="breadcrumb">
				<p class="c_c">
					<i class="breadcrumb-icon"></i>
					<a href="{pigcms{$config.appoint_site_url}">首页</a> 
	      	        &gt;<a href="{pigcms{$config.appoint_list_url}"> 分类列表</a> 
	        	</p>
			</div>
		</div>
		<div class="jz-home" <if condition="$config['appoint_category_bgimg']">style="background-image:url({pigcms{$config.appoint_category_bgimg});"</if>>
			<div class="main" <if condition="!$config['appoint_category_bgimg']">style="padding-top:0px;"</if>>
				<div class="container clearfix">
                    <div style="float:left;">
						<volist name="all_category_list" id="vo">
							<div class="baoj-service m-top50" id="{pigcms{$vo.cat_url}">
								<div class="title">{pigcms{$vo.cat_name}</div>
								<ul class="clearfix">
									<volist name="vo['category_list']" id="voo" key="k">
										<li class="jz-h-l ofh" <if condition="$k%4 eq 0">style="margin-right:0px;"</if>>
											<a href="{pigcms{$voo.url}" class="jz-icon"><img src="{pigcms{$voo.cat_big_pic}"/></a>
											<h3>{pigcms{$voo.cat_name}</h3>
											<p class="des">{pigcms{$voo.desc|nl2br=###}</p> 
											<a href="{pigcms{$voo.url}" class="check-xq">查看详情</a>
										</li>
									</volist>
									<if condition="$vo['cat_count']%4 neq 0">
										<for start="$vo['cat_count']%4" end="4" name="j">
											<li class="jz-h-qd ofh" <if condition="$j eq 3">style="margin-right:0px;"</if>>
												<p class="s-more">
													更多服务<br>敬请期待
												</p>
											</li>
										</for>
									</if>
								</ul>
							</div>
						</volist>
                    </div>
					<div class="pin-wrapper">
						<div id="elevator" class="elevator stuckMenu">
							<ul>
								<volist name="all_category_list" id="vo">
									<li class="menuItem"><a class="etitle" href="#{pigcms{$vo.cat_url}">{pigcms{$vo.cat_name}</a></li>
								</volist>
								<li><a id="upward" href="javascript:void(0)"></a></li>
							</ul>
						</div>
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

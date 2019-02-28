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
	
	</div>
	<div class="jz-home" <if condition="$config['appoint_son_category_bgimg']">style="background-image:url({pigcms{$config.appoint_son_category_bgimg});"</if>>
		<div class="main" style="padding-top:0px;">
			
			<div class="category-filter-box">
				<div class="category-filter-wrapper clearfix">
						<if condition="$keywords"><div class="label">找到 <font color="#f76120">“{pigcms{$keywords}”</font> 相关预约  <font color="#f76120">{pigcms{$group_count}</font> 个</div></if>
						
				</div>
			
			</div>	
			<div class="container clearfix">
				<div style="float:left;width: 100%;text-align:center">
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
							
						</ul>
					  </div>
					  <else />
					  <div class="baoj-service">
						<p >没有搜索到内容</p>
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

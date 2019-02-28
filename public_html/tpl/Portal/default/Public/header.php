<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>{pigcms{$portal_name}-{pigcms{$config.portal_seo_title}</title>
	<meta name="keywords" content="{pigcms{$config.portal_seo_keywords}" />
	<meta name="description" content="{pigcms{$config.portal_seo_description}" />
	<if condition="$config['site_favicon']">
		<link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
    </if>
	<link href="{pigcms{$static_path}article/css/zixun2015.css" type="text/css" rel="stylesheet" />
	<link href="{pigcms{$static_path}article/css/g2013.css?tc=112538" type="text/css" rel="stylesheet" />
	<link href="{pigcms{$static_path}article/css/common2013.css?tc=112538" type="text/css" rel="stylesheet" />
	<link href="{pigcms{$static_path}article/css/head_8.css?tc=112538" type="text/css" rel="stylesheet" />
	<script src="{pigcms{$static_path}article/js/jquery-1.10.2.min.js?tc=112538"></script>
	<script src="{pigcms{$static_path}article/js/common2013.js?tc=112538"></script>
	<script src="{pigcms{$static_path}article/js/head8.js?tc=112538"></script>
</head>
<body>
	<div class="wrapper head8">
		<div class="public-top-layout">
			<div class="topBar w-1200">
		
				<div class="quick-menu login_info" id="login_info">
					<if condition="$user_session['uid']">
						<p class="login_success">
							<span class="username">{pigcms{$user_session['nickname']}</span> ，您好！
							<a href="{pigcms{$config['site_url']}/index.php?g=User" target="_blank">管理</a>
							<a href="{pigcms{$config['site_url']}/index.php?g=Index&c=Login&a=logout">退出</a>
						</p>
					<else/>
						<p class="login_success">
							<a href="{pigcms{$config['site_url']}/index.php?g=Index&c=Login&a=index" style="color:red;">登录</a>&nbsp;&nbsp;|&nbsp;
							<a href="{pigcms{$config['site_url']}/index.php?g=Index&c=Login&a=reg">注册</a>
						</p>
					</if>
					
				</div>
			</div>
		</div>
		<!--header-->
		<div class="header">
			<div class="clearfix w-1200">
				<div class="logo fl">
					<a href="{pigcms{:U('Index/index')}"><img src="{pigcms{$config.portal_site_logo}"/></a>
				</div>
				<div class="search fl">
						<div class="nav_bbs" id="nav" style="width: 100px;">
							<p class="set"><if condition="$_GET['search']">贴吧<else/>资讯</if></p>
							<ul class="keyword_new">
								<li data-val="1">资讯</li>
								<li data-val="2">贴吧</li>
							</ul>
						</div>
						<input type="hidden" id="keyword_c" value="1">
						<input type="text" name="keyword" id="seach_val"  value="<if condition="$_GET['search']">{pigcms{$_GET['search']}<else/>{pigcms{$_GET['keyword']}</if>" class="search_text" x-webkit-speech placeholder="请输入关键字" />
						<input type="submit" onclick="seach()" value="搜索" class="search_sub" />
				</div>
			</div>
			<div class="navWrap clearfix">
				<div class="nav w-1200" id="">
					<ul>
						<li> <a href="{pigcms{:U('Index/index')}" class="bold0 <if condition="MODULE_NAME eq 'Index'">select</if>" style="color:;">首页</a> </li>
						<li> <a href="{pigcms{:U('Article/index')}" class="bold0 <if condition="MODULE_NAME eq 'Article'">select</if>" style="color:;">资讯</a> </li>
						<li> <a href="{pigcms{:U('Activity/index')}" class="bold0 <if condition="MODULE_NAME eq 'Activity'">select</if>" style="color:;">活动</a> </li>
						<li> <a href="{pigcms{:U('Company/index')}" class="bold0 <if condition="MODULE_NAME eq 'Company'">select</if>" style="color:;">商家</a> </li>
						<li> <a href="{pigcms{:U('Tieba/index')}" class="bold0 <if condition="MODULE_NAME eq 'Tieba'">select</if>" style="color:;">贴吧</a> </li>
						<li> <a href="{pigcms{:U('Yellow/index')}" class="bold0 <if condition="MODULE_NAME eq 'Yellow'">select</if>" style="color:;">黄页</a> </li>
						<li> <a href="{pigcms{$config.site_url}/category/all" target="_blank" class="bold0" style="color:;">{pigcms{$config.group_alias_name}</a> </li>
						<li> <a href="{pigcms{$config.site_url}/shop.html" target="_blank" class="bold0" style="color:;">{pigcms{$config.shop_alias_name}</a> </li>
						<li> <a href="{pigcms{$config.site_url}/appoint/" target="_blank" class="bold0" style="color:;">{pigcms{$config.appoint_alias_name}</a> </li>
						<li> <a href="{pigcms{$config.site_url}/classify/" target="_blank" class="bold0" style="color:;">{pigcms{$config.classify_name}</a> </li>
					</ul>
				</div>
			</div>
		</div>
		<script>
			function seach(){
				var seach_val = $("#seach_val").val();
				var keyword_c = $("#keyword_c").val();
				if(keyword_c == 1){
		            location.href = "{pigcms{:U('Article/lists')}"+"&keyword="+seach_val;
				}else if(keyword_c == 2){
		            location.href = "{pigcms{:U('Tieba/index')}"+"&search="+seach_val;
				}
			}
		</script>
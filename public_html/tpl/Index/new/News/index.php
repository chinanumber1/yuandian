<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<title><if condition="$news">{pigcms{$news.title}<elseif condition="$now_cat"/>{pigcms{$now_cat.name}<else />平台快报</if> - {pigcms{$config.site_name}</title>
		<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
		<meta name="description" content="{pigcms{$config.seo_description}" />
		<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
		<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
		<link href="{pigcms{$static_path}css/intro.css"  rel="stylesheet"  type="text/css" />
		<style>
			
			.content .mt{height:25px;border:1px solid #d0e4c2;margin-bottom:10px;padding:5px 10px;background:#fcfffa;}
			.content h2{float:left;width:60px;height:22px;padding:0;color:#333;line-height:22px;font-size:14px;font-weight:normal;}
			.content .mt .extra{float:right;width:6px;height:28px;}
			.content .mt .form{float:left;width:220px;height:28px;}
			.content .mt .text{width:110px; height:18px;line-height:18px;padding:2px;margin:1px 10px 0 20px;border:1px solid; border-color:#ccc;}
			.content .mt .btn-search{width:53px;height:25px;border:0;line-height:22px;text-align:center;background:url(//misc.360buyimg.com/product/skin/2012/i/newicon20130422.png?ver=20130423) no-repeat -103px -112px;cursor:pointer;}
			.content .mc{border:1px solid #EBEBEB;margin-bottom:10px;}
			.content h5{height:30px;line-height:30px;padding-left:10px;background:#f7f7f7;color:#666;}
			.content h5 span{float:right;width:135px;margin-right:10px;text-align:right;}
			.content ul{padding:15px 10px 20px;}
			.content li{padding:3px 0;height:24px;line-height:24px;overflow:hidden;zoom:1;border-bottom:1px dotted #ddd;}
			.content li a{color:#005ea7;}
			.content li div{float:left;margin-right:5px;}
			.content li span{float:right;margin-left:5px;color:#999;font-family:Verdana;}
			.content li .line{float:none;height:10px;overflow:hidden;margin-top:11px;}
			.summary{text-align:center;color:#999;margin-bottom:15px;}
			
		</style>
		<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
		<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
		<script type="text/javascript">
	      //var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	    </script>
		<script src="{pigcms{$static_path}js/common.js"></script>
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
				body{behavior:url("{pigcms{$static_path}css/csshover.htc"); 
				}
				.category_list li:hover .bmbox {
		filter:alpha(opacity=50);
			 
					}
		  .gd_box{	display: none;}
		</style>
		<![endif]-->
	</head>
	<body>
		<include file="Public:header_top"/>
		<div class="body"> 
		
			<div class="w main">
				<div id="Position" class="margin_b6">
					<a href="{pigcms{$config.site_url}">首页</a><span>&gt;</span>&nbsp;<a href="/news/" style="font-size:12px;">平台快报</a><if condition="$now_cat"><span>&gt;</span>&nbsp;<a href="/news/cat-{pigcms{$now_cat.id}.html" style="font-size:12px;">{pigcms{$now_cat.name}</a></if><if condition="$news"><span>&gt;</span>&nbsp;<a href="/news/{pigcms{$news.id}.html" style="font-size:12px;">{pigcms{$news.title}</a></if></div>
					<div class="left">
						<h2>平台快报</h2>
						<ul class="conact_side">
							<volist name="news_cat" id="vo">
								<li><a href="/news/cat-{pigcms{$vo.id}.html" >{pigcms{$vo.name}</a></li>
							</volist>
						</ul>
						<div class="borderlr"></div>
						<div class="corner_b">
							<div class="corner_bl"></div>
							<div class="corner_br"></div>
						</div>
					</div>
					<div class="right">
						<div class="corner_t">
							<div class="corner_tl"></div>
							<div class="corner_tr"></div>
						</div>
						<div class="corner_c"></div>
						<div class="content">
						<if condition="$news">	
							<h1 class="tit" style="margin-bottom:5px;">{pigcms{$news.title}</h1>
							<div class="summary">时间:&nbsp;{pigcms{$news.add_time|date='Y-m-d H:i:s',###}</div>
							{pigcms{$news['content']}
							
						<else />
							<h5><span>发表时间</span>标题</h5>
							<ul>
							<volist name="news_title" id="vo">
								<li>
									<div>
										<a href="/news/{pigcms{$vo.id}.html"><font class="skcolor_ljg"></font>【{pigcms{$now_cat.name}】{pigcms{$vo.title} </a>
									</div>
									<span>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</span>
									<div class="line"></div>
								</li>
							</volist>
							
							</ul>
							<div class="paginator-wrapper">{pigcms{$pagebar}</div>
						</if>
						</div>
						
						<!--[if !ie]>内容 结束<![endif]-->
						<div class="corner_b"><div class="corner_bl"></div><div class="corner_br"></div></div>
						<!--[if !ie]>help_tips 开始<![endif]-->
						<!--[if !ie]>help_tips 结束<![endif]-->
					</div>
					<!--[if !ie]>right 结束<![endif]-->
				</div>
        </div>
		<include file="Public:footer"/>
	</body>
</html>

<!DOCTYPE html PUBLIC "-/W3C/DTD XHTML 1.0 Transitional/EN" "http:/www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http:/www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>{pigcms{$res.title}-{pigcms{$tpl.wxname}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" />
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<meta name="format-detection" content="telephone=no" />
<link href="{pigcms{$static_path}css/yl/news.css" rel="stylesheet" type="text/css" />
<script src="{pigcms{$static_path}js/yl/audio.min.js" type="text/javascript"></script>
<script>
      audiojs.events.ready(function() {
        audiojs.createAll();
      });
</script>
</head>
<script>
window.onload = function (){
	var oWin = document.getElementById("win");
	var oLay = document.getElementById("overlay");	
	var oBtn = document.getElementById("popmenu");
	var oClose = document.getElementById("close");
	oBtn.onclick = function (){
		oLay.style.display = "block";
		oWin.style.display = "block"	
	};
	oLay.onclick = function (){
		oLay.style.display = "none";
		oWin.style.display = "none"	
	}
};
</script>
<body id="news">
	<div id="ui-header">
		<div class="fixed">
			<a class="ui-title" id="popmenu">选择分类</a> 
			<a class="ui-btn-left_pre" href="javascript:history.go(-1)"></a> 
			<a class="ui-btn-right_home" href="{pigcms{:U('Index/index',array('token'=>$tpl['token']))}"></a>
		</div>
	</div>
	<div id="overlay"></div>
	<div id="win">
		<ul class="dropdown">
			<volist name="info" id="vo">
			<li><a
				href="{pigcms{:U('Index/lists',array('token'=>$vo['token'],'classid'=>$vo['id']))}"><span>{pigcms{$vo.name}</span></a></li>
			</volist>
			<div class="clr"></div>
		</ul>
	</div>
	<div class="Listpage">
		<div class="top46"></div>
		<div class="page-bizinfo">
			<div class="header" style="position: relative;">
				<h1 id="activity-name">{pigcms{$res.title}</h1>
				<span id="post-date">{pigcms{$res.createtime|date="y-m-d",###}</span>
			</div>
			<if condition="!empty($tpl['weixin'])">
			<a id="biz-link" class="btn" href="{pigcms{:U('Index/index',array('token'=>$res['token']))}" data-transition="slide">
				<div class="arrow">
					<div class="icons arrow-r"></div>
				</div>
				<div class="logo">
					<div class="circle"></div>
					<img id="img" src="{pigcms{$tpl.headerpic}">
				</div>
				<div id="nickname">{pigcms{$tpl.wxname}</div>
				<div id="weixinid">微信号:{pigcms{$tpl.weixin}</div>
			</a>
			</if>
			<eq name="res.showpic" value="1">
			<div class="showpic">
				<img src="{pigcms{$res.pic}" />
			</div>
			</eq>
			<div class="text" id="content">{pigcms{$res.info|htmlspecialchars_decode}</div>
<script>
function dourl(url){
	location.href= url;
}
</script>

		</div>

		<div class="list">
			<div id="olload">
				<span>往期回顾</span>
			</div>

			<div id="oldlist">
				<ul>
					<volist name="lists" id="lo">
					<li class="newsmore">
						<!--在整合列表页和分类也的时候，这里修改过模板--> <a
						href="{pigcms{:U('Index/content',array('token'=>$lo['token'],'id'=>$lo['id'],'classid'=>intval($_GET['classid'])))}">
							<div class="olditem">
								<div class="title">{pigcms{$lo.title}</div>
							</div>
					</a>
					</li>
					</volist>
				</ul>
				<a class="more"
					href="{pigcms{:U('Index/lists',array('token'=>$res['token'],'classid'=>$res['classid']))}">更多精彩内容</a>
			</div>
		</div>
		<a class="footer" href="#news" target="_self"><span class="top">返回顶部</span></a>

	</div>

	<div style="display: none">{pigcms{$res.tongji|htmlspecialchars_decode}</div>
	<if condition="$homeInfo['copyright']">
	<div class="copyright">{pigcms{$homeInfo.copyright}</div>
	</if>
	<include file="Index:styleInclude" />
	<include file="$cateMenuFileName" />
	<!-- share -->
	<include file="Index:content_share" />
</body>
</html>
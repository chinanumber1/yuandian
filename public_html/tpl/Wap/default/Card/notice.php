<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>{pigcms{$thisCard.cardname}</title>
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="{pigcms{$static_path}card/style/style.css" rel="stylesheet" type="text/css">
<script src="/static/js/jquery.min.js" type="text/javascript"></script>
<script src="{pigcms{$static_path}card/js/accordian.pack.js" type="text/javascript"></script>
<style type="text/css">
#cardintegral .cardexplain{ margin-top:10px;}
</style>
</head>
<body id="cardnews" onLoad="new Accordian('basic-accordian',5,'header_highlight');" class="mode_webapp">
<!--div class="qiandaobanner"><a href="javascript:history.go(-1);"><img src="{pigcms{$thisCard.Lastmsg}" ></a> </div-->

<div id="basic-accordian">
<volist name="notices" id="item">
<div id="test{pigcms{$item.id}-header" class="accordion_headings <?php if ($item['id']==$firstItemID){?>header_highlight<?php } ?>">
<div class="tab new">
<span class="title">{pigcms{$item.title}<p>{pigcms{$item.time|date='Y年m月d日',###}</p></span>
</div>
<div id="test{pigcms{$item.id}-content" style=" display: block; overflow: hidden; opacity: 1; ">
<div class="accordion_child">

<p class="xiangqing">{pigcms{$item.content}</p>

</div>
</div>
</div>
</volist> 
</div>
<include file="Card:cardFooter"/>
<include file="Card:share"/>
</body>
</html>

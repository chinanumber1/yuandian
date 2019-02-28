<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>选择粉丝</title>
<meta http-equiv="MSThemeCompatible" content="Yes" />
<link rel="stylesheet" type="text/css" href="/tpl/Merchant/default/static/css/style_2_common.css" />
<link href="/tpl/Merchant/default/static/css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="/tpl/Merchant/default/static/css/cymain.css" />

<script src="/tpl/Merchant/default/static/js/common.js" type="text/javascript"></script>
<script src="/tpl/Merchant/default/static/js/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>

<style>
body{line-height:180%;}
ul.modules li{padding:4px 10px;margin:4px;background:#efefef;float:left;width:27%;}
ul.modules li div.mleft{float:left;width:40%}
ul.modules li div.mright{float:right;width:55%;text-align:right;}
img{
	width:30px;
	height:30px;
}
</style>
</head>
<body style="background:#fff;padding:20px 20px;">

<form action="{pigcms{:U('Send/select_user')}" method="post">
	<input type="hidden" name="send_id" value="<if condition="isset($send_id)">{pigcms{$send_id}<else />{pigcms{$_GET.id}</if>"/>
	查询用户：<input type="text" placeholder="请输入用户名或手机号" value="" class="px" name="search">
	<button class="btnGrayS" style="height: 29px;">搜索</button>
	<div class="tips" id="tips" style="color:red; float: right;"></div>
</form>
<table class="ListProduct" border="0" cellSpacing="0" cellPadding="0" width="100%">
<thead>
<tr>
<th>昵称</th>
<th style=" width:80px;">操作 <span class="tooltips" ><span>
<p>点击“选中”即可</p>
</span></span></th>
</tr>
</thead>
<volist name="list" id="m">
<tr class="user" data-uid="{pigcms{$m.uid}"><td><img src="{pigcms{$m.avatar}"  />{pigcms{$m.nickname}</td><td class="norightborder"><a href="###" onclick="send('{pigcms{$m.openid}')">发送</a></td></tr>
</volist>
</table>
<div class="footactions" style="padding-left:10px">
  <div class="pages">{pigcms{$page}</div>
</div>
<script>
function send(openid){
	send_id = $('input[name="send_id"]').val();
	$.post("{pigcms{:U('Send/preview_send')}", {openid:openid,send_id:send_id}, function(data, textStatus, xhr) {
		
		if(!data.status){
			window.top.msg(0,data.info,true,3);
		}else{
			window.top.msg(1,data.info,true,3);
		}
		
	},'json');
}
</script>
</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>实体卡选择</title>
<meta http-equiv="MSThemeCompatible" content="Yes" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_2_common.css" />
<link href="{pigcms{$static_path}css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/cymain.css" />

<script src="{pigcms{$static_path}js/common.js" type="text/javascript"></script>
<script src="{pigcms{$static_path}js/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<style>
body{line-height:180%;}
ul.modules li{padding:4px 10px;margin:4px;background:#efefef;float:left;width:27%;}
ul.modules li div.mleft{float:left;width:40%}
ul.modules li div.mright{float:right;width:55%;text-align:right;}
</style>
</head>
<body style="background:#fff;padding:20px 20px;">
	<form action="#" method="post">
	<input type="text" placeholder="请输入名称搜索词" value="" class="px" name="search">
	<button class="btnGrayS" style="height: 29px;">搜索</button>
	</form>
	<table class="ListProduct" border="0" cellSpacing="0" cellPadding="0" width="100%">
		<thead>
			<tr>
				<th>卡号</th>
				<th>实体卡余额</th>
				<th style=" width:80px;">操作 </th>
			</tr>
		</thead>
		<volist name="list" id="m">
		<tr>
		<td>{pigcms{$m['cardid']}</td>
		<td style="width:70px;">￥{pigcms{$m['balance_money']}</td>
		<td class="norightborder"><a href="javascript:void(0)" onclick="returnHomepage('{pigcms{$m.cardid}')">选中</a></td>
		</tr>
		</volist>
	</table>
	<div class="footactions" style="padding-left:10px">
		<div class="pages">{pigcms{$page}</div>
	</div>
	<script>
	var domid = art.dialog.data('domid');
	// 返回数据到主页面
	function returnHomepage(url){
		var origin = artDialog.open.origin;
		var dom = origin.document.getElementById(domid);
		dom.value = url;
		setTimeout("art.dialog.close()", 100 )
	}
	</script>
</body>
</html>
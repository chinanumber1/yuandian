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
</style>
</head>
<body style="background:#fff;padding:20px 20px;">

	<form action="#" method="post">
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
<tr class="user" data-uid="{pigcms{$m.uid}"><td>{pigcms{$m.nickname}|{pigcms{$m.phone}</td><td class="norightborder"><a href="###" onclick="returnHomepage('{pigcms{$m.uid}','{pigcms{$m.nickname}','{pigcms{$m.phone}')">选中</a></td></tr>
</volist>
</table>
<div class="footactions" style="padding-left:10px">
  <div class="pages">{pigcms{$page}</div>
</div>
<script>
var domid=art.dialog.data('domid');
var origin = artDialog.open.origin;
var name_add = origin.document.getElementById('form-field-tags')
var name_div = origin.document.getElementById('tags')
// 返回数据到主页面
function returnHomepage(id,name,phone){
	// var dom = origin.document.getElementById(domid);
	// dom.value=url;
	//setTimeout("art.dialog.close()", 100 )
	var a={};
	a.uid = id;
	a.name = name;
	a.phone = phone;
	addTag(a)
	
}

function addTag(obj) {
	var tag = obj.name;
	if (tag != '') {
		var i = 0;
		var tag_arr = $(name_div).children(".tag");
		$(tag_arr).each(function() {
			if ($(this).text() == tag + "×") {
				//$(this).addClass("tag-warning");
				$('#tips').html('已经添加了')
				setTimeout("removeWarning()", 800);
				i++;
			}
		})
		// obj.val('');
		if (i > 0) { //说明有重复
			return false;
		}
		var uids_tmp = $(name_div).find('#uids').val();
		$(name_div).find('#uids').val(uids_tmp+obj.uid+',')
		$(name_add).before("<span class='tag'>" + tag + "<button class='close' data-uid='"+uids_tmp+"' type='button'>×</button></span>"); //添加标签
	}
}

function removeWarning() {
	$('#tips').html('')
}
</script>
</body>
</html>
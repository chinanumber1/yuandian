<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>微信公众平台源码,微信机器人源码,微信自动回复源码 PigCms多用户微信营销系统</title>
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
	<input type="text" placeholder="请输入名称搜索词" value="" class="px" name="name">
	<button class="btnGrayS" style="height: 29px;">搜索</button>
	</form>
</div>
<h4>商家列表</h4>
<table class="ListProduct" border="0" cellSpacing="0" cellPadding="0" width="100%">
<thead>
<tr>
<th>商家名称</th>
<th style=" width:80px;">操作 <span class="tooltips" ><span>
<p>点击“选中”即可</p>
</span></span></th>
</tr>
</thead>
<volist name="mer_list" id="m">
<tr><td>{pigcms{$m.name}</td><td class="norightborder"><input type="checkbox" name="mer_id" value="{pigcms{$m.mer_id}" <if condition="$m.select eq 1">checked</if>>选中</td></tr>
</volist>
</table>
<div class="footactions" style="padding-left:10px">
  <div class="pages">{pigcms{$page}</div>
</div>
<button class="btnGrayS" style="float:right" onclick="fun()">确定</button>
<script>

		var mer_list = art.dialog.data('mer_list')
		var mer_arr = [];
		if(mer_list=='0'){
			mer_list = '';
		}else{
			mer_arr = mer_list.split(',');
		}
		$('input').each(function(index,val){
			if($.inArray($(this).val(), mer_arr)>-1){
				$(this).attr('checked',true);
			}
		})
		$('input').click(function(){		
			if($(this).is(":checked")){
				mer_arr.push($(this).val())
			}else{
				mer_arr.splice($.inArray($(this).val(), mer_arr), 1);
			}
			mer_list = mer_arr.join(',')
			art.dialog.data('mer_list',mer_list)
		})
		
		function fun(){
			top.art.dialog({id : 'part_mer'}).close();  
		}
</script>
</body>
</html>
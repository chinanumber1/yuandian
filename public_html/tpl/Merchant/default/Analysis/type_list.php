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

<h4>菜品类型列表</h4>
<table class="ListProduct" border="0" cellSpacing="0" cellPadding="0" width="100%">
<thead>
<tr>
<th>菜品名称</th>
<th style=" width:80px;">操作 <span class="tooltips" ><span>
<p>点击“选中”即可</p>
</span></span></th>
</tr>
</thead>
<volist name="sort_list" id="m">
<tr><td>{pigcms{$m.sort_name}</td><td class="norightborder"><input type="checkbox" name="sort_id" value="{pigcms{$m.sort_id}" <if condition="$m.select eq 1">checked</if>>选中</td></tr>
</volist>
</table>
<div class="footactions" style="padding-left:10px">
  <div class="pages">{pigcms{$page}</div>
</div>
<button class="btnGrayS" style="float:right" onclick="fun()">确定</button>
<script>

		var type_list = art.dialog.data('type_list')
		var type_arr = [];
		if(type_list=='0'){
			type_list = '';
		}else{
			type_arr = type_list.split(',');
		}
		$('input').each(function(index,val){
			if($.inArray($(this).val(), type_arr)>-1){
				$(this).attr('checked',true);
			}
		})
		$('input').click(function(){		
			if($(this).is(":checked")){
				type_arr.push($(this).val())
			}else{
				type_arr.splice($.inArray($(this).val(), type_arr), 1);
			}
			type_list = type_arr.join(',')
			art.dialog.data('type_list',type_list)
		})
		
		function fun(){
			top.art.dialog({id : 'part_type'}).close();  
		}
</script>
</body>
</html>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<meta http-equiv="MSThemeCompatible" content="Yes" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_2_common.css" />
<link href="{pigcms{$static_path}css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/cymain.css" />
<script type="text/javascript" src="{pigcms{$static_path}js/common.js"></script>
<script type="text/javascript" src="/static/js/jquery.min.js"></script>
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
	<div style="background:#fefbe4;border:1px solid #f3ecb9;color:#993300;padding:10px;margin-bottom:5px;">使用方法：点击对应内容后面的“选中”即可。</div>
	<h4>列表</h4>
	<table class="ListProduct" border="0" cellSpacing="0" cellPadding="0" width="100%">
		<thead>
			<tr>
				<th>菜品名称</th>
				<th>菜品单价</th>
				<th style=" width:80px;">操作</th>
			</tr>
		</thead>
		<if condition="$goods_list">
			<volist name="goods_list" id="m">
			<tr>
				<td>{pigcms{$m.name}</td>
				<td>{pigcms{$m.price|floatval}</td>
				<td class="norightborder"><a href="###" onclick="returnHomepage('{pigcms{$m.goods_id}', '{pigcms{$m.name}', '{pigcms{$m.price|floatval}')">选中</a></td>
			</tr>
			</volist>
		<else/>
			<tr><td colspan="3" align="center">没有可供选择的菜品</td></tr>
		</if>
	</table>
	<div class="footactions" style="padding-left:10px">
		<div class="pages">{pigcms{$pagebar}</div>
	</div>
<script>
var html_tr = art.dialog.data('html_tr'), html_table = art.dialog.data('html_table'), index_i = art.dialog.data('index_i');
console.log(typeof(html_tr));
console.log(typeof(html_table));
if (typeof(index_i) == 'undefined') {
	index_i = 0;
	console.log('111111111111111111111');
}
console.log(index_i);

// var imgids=art.dialog.data('imgids');
// 返回数据到主页面
function returnHomepage(goods_id, name, price)
{
	if (typeof(index_i) == 'undefined') {
		index_i = 0;
	}
	var t = '<tr>';
		t += '<td>' + name + '<input type="hidden" name="goods_ids[' + index_i + '][]" value="' + goods_id + '" /></td>';
		t += '<td>' + price + '</td>';
// 		t += '<td></td>';
		t += '<td><a title="删除" class="red" style="padding-right:8px;" href="javascript:;">';
		t += '<i class="ace-icon fa fa-trash-o bigger-130"></i>';
		t += '</a></td>';
		t += '</tr>';

	if (typeof(html_tr) == 'undefined') {
		html_table.prepend('<table class="table table-striped table-bordered table-hover"><tr><td>菜品名称</td><td>菜品价格</td><!--td>规格</td--><td>操作</td></tr>' + t + '</table>');
	} else {
		html_tr.append(t);
	}





	
// 	var imgidsdom = origin.document.getElementById(imgids);
// 	var multinews= origin.document.getElementById(art.dialog.data('multinews'));
// 	var singlenews= origin.document.getElementById(art.dialog.data('singlenews'));
// 	var multione= origin.document.getElementById(art.dialog.data('multione'));
// 	var js_appmsg_preview= origin.document.getElementById(art.dialog.data('js_appmsg_preview'));
// 	//dom.value+=','+url;
// 	imgCount=imgidsdom.value.split(',').length-1;
// 	//
	
// 	if(multione.innerHTML==''){
// 		singlenews.style.display="";
// 		multinews.style.display="none";
		
// 		multione.innerHTML=' <h4 class="appmsg_title"><a href="javascript:void(0);" onClick="return false;" target="_blank">'+title+'</a></h4><div class="appmsg_thumb_wrp"><img style="border:1px solid #ddd" class="js_appmsg_thumb appmsg_thumb" src="'+pic+'"><i class="appmsg_thumb default" style="background:url('+pic+');background-size:100% 100%">&nbsp;</i></div>';
		
// 	}else{
// 		singlenews.style.display="none";
// 		multinews.style.display="";
// 		js_appmsg_preview.innerHTML=js_appmsg_preview.innerHTML+'<div id="appmsgItem4" data-fileid="" data-id="4" class="appmsg_item js_appmsg_item "><img class="js_appmsg_thumb appmsg_thumb" src="'+pic+'"><i class="appmsg_thumb default" style="background:url('+pic+');background-size:100% 100%">&nbsp;</i><h4 class="appmsg_title"><a onClick="return false;" href="javascript:void(0);" target="_blank">'+title+'</a></h4></div>';
// 	}
// 	dom.style.display="";
// 	if (imgidsdom.value == '') {
// 		imgidsdom.value = id;
// 	} else {
// 		imgidsdom.value += ',' + id;
// 	}
	
	setTimeout("art.dialog.close()", 100 )
}
</script>
</body>
</html>
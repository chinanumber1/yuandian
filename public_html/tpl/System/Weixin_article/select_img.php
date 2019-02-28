<!DOCTYPE html PUBLIC "-//W3C//Dtd XHTML 1.0 transitional//EN" "http://www.w3.org/tr/xhtml1/Dtd/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<meta http-equiv="MSthemeCompatible" content="Yes" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_2_common.css" />
<script src="{pigcms{$static_path}js/common.js" type="text/javascript"></script>
<link href="{pigcms{$static_path}css/style.css" rel="stylesheet" type="text/css" />
<script src="/static/js/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/cymain.css" />
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<style>
body{line-height:180%;}
ul.modules li{padding:4px 10px;margin:4px;background:#efefef;float:left;width:27%;}
ul.modules li div.mleft{float:left;width:40%}
ul.modules li div.mright{float:right;width:55%;text-align:right;}
.ListProduct {
	border-top: #d3d3d3 1px solid;
	margin-top: 5px;
	width: 100%;
	margin-bottom: 5px;_border-collapse: collapse;

}
.ListProduct thead th {
	border-bottom: #d3d3d3 1px solid; border-bottom: 5px; background-color: #f1f1f1; padding-left: 5px; padding-right: 5px; color: #666; font-size: 14px; border-top: #e3e3e3 1px solid; font-weight: normal; border-right: #ddd 1px solid; padding-top: 5px; color:#000000; font-weight:bold
}
.ListProduct tbody td {
	border-bottom: #eee 1px solid; border-bottom: 10px; padding-left: 5px; padding-right: 5px; border-right: #eee 1px solid; padding-top: 10px;
	font-size:12px;_empty-cells:show;word-break: break-all;
}
.ListProduct tbody tr:nth-child(2n+1) {
    background-color:#FCFCFC;
}
.ListProduct tbody tr:hover {
    background-color:#F1FCEA;
}
.ListProduct tbody td p{
	padding: 0;font-size:12px;_empty-cells:show;word-break: break-all;
}
.ListProduct tfoot td {
	border-bottom: #eee 1px solid; border-bottom: 10px; padding-left: 5px; padding-right: 5px; border-right: #eee 1px solid; padding-top: 10px; background-color:#f9f9f9;
	font-size:12px;_empty-cells:show;word-break: break-all;
}
.ListProduct thead th.norightborder {
	border-right: 0;
}
.ListProduct tbody td.norightborder {
	border-right: 0;
}
.ListProduct .select{
	width: 30px;
}
.ListProduct .keywords{width: 150px;
}
.ListProduct .answer{
	width: 375px;
}
.ListProduct .answer_text{
	 width: 360px; overflow:hidden;white-space:nowrap;text-overflow:ellipsis; height:16px
}
.answer_text img{
	 margin-right: 5px; float:left;
}
.ListProduct .category{
	width: 70px;
}
.ListProduct .time{
	width: 70px;
}


.ListProduct .edit{
	width: 120px;
}
.pages{padding:3px;margin:3px;text-align:center;}
.pages a{border:#eee 1px solid;padding:2px 5px;margin:2px;color:#036cb4;text-decoration:none;}
.pages a:hover{border:#999 1px solid;color:#666;}
.pages a:active{border:#999 1px solid;color:#666;}
.pages .current{border:#036cb4 1px solid;padding:2px 5px;font-weight:bold;margin:2px;color:#fff;background-color:#036cb4;}
.pages .disabled{border:#eee 1px solid;padding:2px 5px;margin:2px;color:#ddd;}

</style>
</head>
<body style="background:#fff;padding:20px 20px;">
<div style="background:#fefbe4;border:1px solid #f3ecb9;color:#993300;padding:10px;margin-bottom:5px;">使用方法：点击对应内容后面的“选中”即可。</div>
<h4>列表</h4>
<table class="ListProduct" border="0" cellSpacing="0" cellPadding="0" width="100%">
<thead>
<tr>
<th>标题</th>
<th style=" width:80px;">操作

</th>
</tr>
</thead>
<if condition="$list">
<volist name="list" id="m">
<tr><td>{pigcms{$m.title}</td><td class="norightborder"><a href="###" onclick="returnHomepage({pigcms{$m.pigcms_id},'{pigcms{$m.title}','{pigcms{$m.cover_pic}','{pigcms{$m.digest}')">选中</a></td></tr>
</volist>
<else/>
<tr><td colspan="2" align="center"><a href="{pigcms{:U('Weixin_article/one')}" target="_blank" style="color:#369">还没有图文消息，请点击这里添加图文消息</a></td></tr>
</if>
</table>
<div class="footactions" style="padding-left:10px">
  <div class="pages">{pigcms{$page}</div>
</div>

<script>
var titledom=art.dialog.data('titledom');
var imgids=art.dialog.data('imgids');
// 返回数据到主页面
function returnHomepage(id,title,pic,info){
	var origin = artDialog.open.origin;
	var dom = origin.document.getElementById(titledom);
	var imgidsdom = origin.document.getElementById(imgids);
	var multinews= origin.document.getElementById(art.dialog.data('multinews'));
	var singlenews= origin.document.getElementById(art.dialog.data('singlenews'));
	var multione= origin.document.getElementById(art.dialog.data('multione'));
	var js_appmsg_preview= origin.document.getElementById(art.dialog.data('js_appmsg_preview'));
	//dom.value+=','+url;
	imgCount=imgidsdom.value.split(',').length-1;
	//
	dom.innerHTML='<div class="mediaPanel"><div class="mediaHead"><span class="title" id="zbt">'+title+'</span><span class="time"><?php echo date('Y-m-d',time());?></span><div class="clr"></div></div><div class="mediaImg"><img id="suicaipic1" src="'+pic+'"></div><div class="mediaContent mediaContentP"><p id="zinfo">'+info+'</p></div><div class="mediaFooter"><span class="mesgIcon right"></span><span style="line-height:50px;" class="left">查看全文</span><div class="clr"></div></div></div>';
	
	if(multione.innerHTML==''){
		singlenews.style.display="";
		multinews.style.display="none";
		
		multione.innerHTML=' <h4 class="appmsg_title"><a href="javascript:void(0);" onClick="return false;" target="_blank">'+title+'</a></h4><div class="appmsg_thumb_wrp"><img style="border:1px solid #ddd" class="js_appmsg_thumb appmsg_thumb" src="'+pic+'"><i class="appmsg_thumb default" style="background:url('+pic+');background-size:100% 100%">&nbsp;</i></div>';
		
	}else{
		singlenews.style.display="none";
		multinews.style.display="";
		js_appmsg_preview.innerHTML=js_appmsg_preview.innerHTML+'<div id="appmsgItem4" data-fileid="" data-id="4" class="appmsg_item js_appmsg_item "><img class="js_appmsg_thumb appmsg_thumb" src="'+pic+'"><i class="appmsg_thumb default" style="background:url('+pic+');background-size:100% 100%">&nbsp;</i><h4 class="appmsg_title"><a onClick="return false;" href="javascript:void(0);" target="_blank">'+title+'</a></h4></div>';
	}
	dom.style.display="";
	if (imgidsdom.value == '') {
		imgidsdom.value = id;
	} else {
		imgidsdom.value += ',' + id;
	}
	
	setTimeout("art.dialog.close()", 100 )
}
</script>

</body>
</html>
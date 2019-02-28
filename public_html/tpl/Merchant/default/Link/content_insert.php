<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>插入链接</title>
<meta http-equiv="MSThemeCompatible" content="Yes" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style_2_common.css" />
<link href="{pigcms{$static_path}css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/cymain.css" />
	<script type="text/javascript">
	   var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	</script>
<script src="{pigcms{$static_path}js/common.js" type="text/javascript"></script>
<script src="{pigcms{$static_path}js/jquery.min.js" type="text/javascript"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<style>
body{line-height:180%;}
ul.modules li{padding:4px 10px;margin:4px;background:#efefef;float:left;width:27%;}
ul.modules li div.mleft{float:left;width:40%}
ul.modules li div.mright{float:right;width:55%;text-align:right;}
</style>
</head>
<body style="background:#fff;padding:20px 20px;">
<div style="background:#fefbe4;border:1px solid #f3ecb9;color:#993300;padding:10px;margin-bottom:5px;">使用方法：点击“选中”直接返回对应模块外链代码，或者点击“详细”选择具体的内容外链</div>
<h4>请选择模块：</h4>
<ul class="modules">
<volist name="modules" id="m">
<?php if (!intval($_GET['iskeyword']) || (intval($_GET['iskeyword'])&&$m['askeyword'])){?>
<li>
<div class="mleft">{pigcms{$m.name}</div>
<div class="mright">
<if condition="$m['sub']"><a href="<?php if (!$m['linkurl']){?>?g=Merchant&c=Link&a={pigcms{$m.module}&iskeyword=<?php echo intval($_GET['iskeyword']);?><?php }else{echo $m['linkurl'];}?>">详细</a></if>
<if condition="$m['canselected']"><a href="###" onclick="returnHomepage('{pigcms{$m.linkcode}','{pigcms{$m.keyword}','{pigcms{$m.name}')" style="margin-left:14px;">选中</a></if>
</div>
<div style="clear:both"></div>
</li>
<?php }?>
</volist>
<div style="clear:both"></div>
</ul>
<script>
var domid=art.dialog.data('domid');
// 返回数据到主页面
function returnHomepage(url,name,moduleName){
	var origin = artDialog.open.origin;
	var dom = origin.document.getElementById(domid);
	
	
	
	var title = origin.document.getElementById(domid+'-title');
	var info = origin.document.getElementById(domid+'-info');
	var img = origin.document.getElementById(domid+'-img');
	var img_show = origin.document.getElementById(domid+'-img-show');
	var url_show = origin.document.getElementById(domid+'-show');
	url_show.href=url;
	img_show.src='{pigcms{$mer_pic}';
	
	info.value=name;
	title.value='['+moduleName+']';
	img.value='{pigcms{$mer_pic}';
	dom.value=url;
	
	
	setTimeout("art.dialog.close()", 100 )
}
</script>
</body>
</html>
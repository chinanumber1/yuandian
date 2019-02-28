<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>选择图片</title>
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
	<form id="selectimg">
		<table class="ListProduct" border="0" cellSpacing="0" cellPadding="0" width="100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>图片</th>
					<th>备注</th>
					<th style=" width:80px;">操作 </th>
				</tr>
			</thead>
			<volist name="list" id="m">
			<tr>
				<td>{pigcms{$m['pigcms_id']}</td>
				<td><img src="{pigcms{$m['pic']}" style="width:70px;"/><br/></td>
				<td><input type="text" name="img_remark"  value="{pigcms{$m['img_remark']}" style="border:1px solid #999;"></td>
				<td class="norightborder"><input type="checkbox" name="pic" value="{pigcms{$m.pic}">选中|<a href="javascript:void(0)" onclick="deleteImg(this)">删除</a></td>
			</tr>
			</volist>
		</table>
		
	</form>
	<div class="footactions" style="padding-left:10px">
		<div class="pages">{pigcms{$page}</div>
	</div>
	<button class="btnGrayS" style="float:right" onclick="fun()">确定</button>
	<script>
	var images='';
	var domid = art.dialog.data('domid');
	var type = art.dialog.data('type');
	$(document).ready(function() {
		$("input[name='img_remark']").change(function(event) {
			$.post('{pigcms{:U(\'ImageSelect/img_option\')}', {id:$(this).parents().prev().prev().html(),text: $(this).val()}, function(data, textStatus, xhr) {});
		});
	});		
	function fun(){
		// 返回数据到主页面
		var origin = artDialog.open.origin;
		var dom = origin.document.getElementById(domid);
		var http = "http://<?php echo $_SERVER['HTTP_HOST'];?>" ;
		var ids=0;
		var image_path=new Array();
		$("input[name='pic']:checkbox").each(function(index,val){ 
			if(val.checked==true){
				var pos=val.value.lastIndexOf("/");
				image_bgs = val.value;
				image_path.push(val.value.substring(0,pos)+','+val.value.substring(pos+1));
				image_meal = '<img style="width:200px;height:200px" src="'+val.value+'"/>'+'<input type="hidden" name="image_select" value="'+val.value.substring(0,pos)+','+val.value.substring(pos+1)+'" >';
				image = '<img style="width:200px;height:200px" src="'+val.value+'"/>'+'<input type="hidden" name="image_select" value="'+val.value+'" >';
				images+='<li class="upload_pic_li"><img src="'+val.value+'"><input type="hidden" name="pic[]" value="'+val.value.substring(0,pos)+','+val.value.substring(pos+1)+'"><br><a href="#" onclick="deleteImage(\''+val.value+'\',this);return false;">[ 删除 ]</a></li>';
				ids++;
			}
		});
		if(domid=='upload_pic_ul'){
			var img_count=dom.getElementsByTagName("li").length;
			if(ids>5){
				alert('不能超过5个');
				$("input[name='pic']:checkbox").attr('checked','false');
			}
		
			if(img_count+ids>5){
				alert("图片数量不能超过5个");
			}else{
				$.post('{pigcms{:U(\'ImageSelect/check_thumb_exist\')}', {image_path:image_path,type:type}, function(data, textStatus, xhr) {setTimeout("art.dialog.close()", 100 )});
				dom.innerHTML+= images;
			}
		}else if(domid=='image_preview_box'){
			if(ids>1){
				alert('只能选一个');
				$("input[name='pic']:checkbox").attr('checked','false');
			}else{
				$.post('{pigcms{:U(\'ImageSelect/check_thumb_exist\')}', {image_path:image_path,type:type}, function(data, textStatus, xhr) {setTimeout("art.dialog.close()", 100 )});
				dom.innerHTML= image_meal;
			}
		}else if(domid=='gallery'+dom.name){
			$.post('{pigcms{:U(\'Frontmanag/insert_gallery\')}', {cyid:dom.name,image_path:image_path}, function(data, textStatus, xhr) {setTimeout("art.dialog.close()", 100 )});
		}else if(domid == 'bgs'){
			if(ids>1){
				alert('只能选一个');
				$("input[name='pic']:checkbox").attr('checked','false');
			}else{
				dom.value=image_bgs;
			}
			setTimeout("art.dialog.close()", 100 )
		}else if(type=='chanel'){
			if(ids>1){
				alert('只能选一个');
				$("input[name='pic']:checkbox").attr('checked','false');
			}else{
				dom.value=http+image_bgs;
			}
			setTimeout("art.dialog.close()", 100 )
		}else{
			if(ids>1){
				alert('只能选一个');
				$("input[name='pic']:checkbox").attr('checked','false');
			}else{
				dom.innerHTML= image;
			}
			setTimeout("art.dialog.close()", 100 )
		}
		images = '';
		
	}
	
	function deleteImg(obj){
		if(confirm('确定要删除图片吗？')){
			$.post('{pigcms{:U(\'ImageSelect/img_option\')}', {id:$(obj).parents().prev().prev().prev().html(),op: 'del'}, function(data, textStatus, xhr) { window.location.reload();});
		}
	}
	
	</script>
</body>
</html>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 品牌故事</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<style>
			a:hover,a:visited{color:#666;}
		</style>
	</head>
	<body>
	<form id="myform" method="post" action="{pigcms{:U('shop_fitment_history',array('store_id'=>$_GET['store_id']))}" autocomplete="off">
		<input type="hidden" name="store_id" value="{pigcms{$_GET.store_id}"/>
		<table>
			<tr>
				<th width="20%" colspan="4">可以选择任意店铺的自定义页面。</th>
			</tr>
			<tr>
				<th width="20%">选择自定义页面</th>
				<td width="80%" colspan="3">
					<select name="shop_brand_weipage" id="shop_brand_weipage" style="width:150px;">
						<option value="0">不展示品牌故事</option>
						<volist name="diypage_list" id="vo">
							<option value="{pigcms{$vo.page_id}" <if condition="$now_store['shop_brand_weipage'] eq $vo['page_id']">selected="selected"</if>>{pigcms{$vo.page_name}</option>
						</volist>
					</select>
					<div style="float:right;">
						<a href="{pigcms{:U('Config/store')}" target="_blank" style="color:blue;">新建</a><span style="margin:0px 10px;">|</span><a href="{pigcms{:U('shop_fitment_history',array('store_id'=>$_GET['store_id']))}" style="color:blue;float:right;margin-right:20px;">刷新</a>
					</div>
				</td>
			</tr>
		</table>
		<div class="btn">
			<button type="submit" id="submit">保存</button>
		</div>
		</form>
		<script>
			$(function(){
				$('#myform').submit(function(){
					$('#submit').prop('disabled',true).html('保存中...');
					$.post($('#myform').attr('action'),$('#myform').serialize(),function(result){
						if(result.status == 1){
							parent.layer.alert(result.info,{
								end:function(){
									window.parent.layer.closeAll();
								}
							});
						}else{
							parent.layer.alert(result.info);
							$('#submit').prop('disabled',false).html('保存');
						}
					});
					return false;
				});
			});
		</script>
	</body>
</html>
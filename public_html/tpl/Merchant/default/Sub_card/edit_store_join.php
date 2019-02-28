<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 会员卡编辑</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
		<style>
			a:hover,a:visited{color:#666;}
			.cb-enable, .cb-disable, .cb-enable span, .cb-disable span {
background: url(tpl/System/Static/css/img/form_onoff.png) repeat-x;
display: block;
float: left;
cursor: pointer;
}
.cb-enable .selected {
background-position: 0 -48px;
}
.cb-enable span, .cb-disable span {
font-weight: bold;
line-height: 24px;
background-repeat: no-repeat;
display: block;
}
.cb-enable span {
background-position: left -72px;
padding: 0 10px;
}
.cb-enable .selected span {
background-position: left -120px;
color: #fff;
}
.cb-enable input, .cb-disable input {
display: none;
}
.cb-disable span {
background-position: right -144px;
padding: 0 10px;
}
.cb-disable .selected {
background-position: 0 -24px;
}
.cb-disable .selected span {
background-position: right -168px;
color: #fff;
}
		</style>
	</head>
	<body>
	<form id="myform" method="post" action="{pigcms{:U('Sub_card/edit_store_join')}" frame="true" refresh="true" autocomplete="off" onsubmit="return false;" >
		<table>
		
			<tr>
				<th>套餐名称</th>
				<th>店铺名称</th>
				<th>库存</th>
				<th>是否预约</th>
				<th>描述</th>
			</tr>
			<input type="hidden" name="id" value="{pigcms{$_GET.id}"	>
			
				<tr>
					<th>
						<b>{pigcms{$package_name}</b>
					</th>
					<th>
						<b>{pigcms{$store_name}</b>
					</th>
					<th>
						<input type="text" name="sku" id="sku" value="{pigcms{$store_join.sku}"	onkeyup="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}"
onafterpaste="if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'0')}else{this.value=this.value.replace(/\D/g,'')}" >
					</th>
					<th>
						<span class="cb-enable"><label class="cb-enable <if condition="$store_join['appoint'] eq 1">selected</if>"><span>是</span><input type="radio" name="appoint" value="1" <if condition="$store_join['appoint'] eq 1">checked="checked"</if>/></label></span>
						<span class="cb-disable"><label class="cb-disable <if condition="$store_join['appoint'] eq 0">selected</if>"><span>否</span><input type="radio" name="appoint" value="0" <if condition="$store_join['appoint'] eq 0">checked="checked"</if>/></label></span>
					</th>
					<th>
						<textarea name="desc" cols="20" rows="2"  >{pigcms{$store_join.desc}</textarea>
					</th>
				</tr>
		
			
			
		</table>
		
	
		<div class="btn">
			<button id="submit" type="submit">确定</button>
			<button id="reset" type="reset">取消</button>
		</div>
		</form>
		<script>
			$(function(){
				
				// $("#sku").bind("input", function () {
					// console.log(parseFloat($(this).val()))
					// if (isNaN(parseFloat($(this).val())) || parseFloat($(this).val()) <= 0) $(this).val(1);
				// })
				
				$('.handle_btn').live('click',function(){
					art.dialog.open($(this).attr('href'),{
						init: function(){
							var iframe = this.iframe.contentWindow;
							window.top.art.dialog.data('iframe_handle',iframe);
						},
						id: 'handle',
						title:'编辑',
						padding: 0,
						width: 800,
						height: 520,
						lock: true,
						resize: false,
						background:'black',
						button: null,
						fixed: false,
						close: null,
						left: '50%',
						top: '38.2%',
						opacity:'0.4'
					});
					return false;
				});
				
				$('#group_id').change(function(){
					$('#frmselect').submit();
				});
				$('#submit').click(function(){
					$.ajax({
						url: '{pigcms{:U('Sub_card/edit_store_join')}',
						type: 'POST',
						dataType: 'json',
						data: $('#myform').serialize(),
						success:function(date){
							if(date.status){
								alert(date.info);
								parent.location.reload();   
							}else{
								alert(date.info);
								window.location.reload();
							}
						}
					});
				});
				
				$('.cb-enable').click(function(){
					$(this).find('label').addClass('selected');
					$(this).find('label').find('input').prop('checked',true);
					$(this).next('.cb-disable').find('label').find('input').prop('checked',false);
					$(this).next('.cb-disable').find('label').removeClass('selected');
				});
				$('.cb-disable').click(function(){
					$(this).find('label').addClass('selected');
					$(this).find('label').find('input').prop('checked',true);
					$(this).prev('.cb-enable').find('label').find('input').prop('checked',false);
					$(this).prev('.cb-enable').find('label').removeClass('selected');
				});
				
				$('#reset').click(function(){
				 parent.location.reload();   

				});
			});
		</script>
	</body>
</html>
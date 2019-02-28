<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <!--[if IE 6]>
		<script src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a-min.v86c6ab94.js"></script>
    <![endif]-->
    <!--[if lt IE 9]>
		<script src="{pigcms{$static_path}js/html5shiv.min-min.v01cbd8f0.js"></script>
    <![endif]-->
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.v113ea197.css" />
	<style>
		html{background-color:white;}
		body{font-size:12px;background-color:white;}
		.erro_tips{margin-top:40px;text-align:center;}
		.address-list label{margin-left:3px;line-height:36px;zoom:1;}
		.address-list .selected{background:#FEF5E7;}
		.address-list li{padding-left:10px;}
	</style>
</head>
<body>
	<if condition="$error_msg">
		<div class="erro_tips">{pigcms{$error_msg}</div>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script>
			$(function(){
				window.parent.change_adress_frame($('#pick-address-list').height());
				});
		</script>	
	<else/>
		<ul class="address-list" id="pick-address-list">
			<volist name="adress_list" id="vo">
				<if condition="($vo.phone neq '') AND ($vo.name neq '')">
				<li <if condition="$i eq 1">class="selected"</if>>
					<input class="select-radio" type="radio" name="pick_in_store" autocomplete="off" id="address_{pigcms{$vo.pick_addr_id}" value="{pigcms{$vo.pick_addr_id}" <if condition="$i eq 1">checked="checked"</if> />
					<label class="detail" for="address_{pigcms{$vo.pick_addr_id}" pick_addr_id="{pigcms{$vo.pick_addr_id}" pick_name="{pigcms{$vo.name}" phone="{pigcms{$vo.phone}" province="{pigcms{$vo.area_info.province}" city="{pigcms{$vo.area_info.city}" area="{pigcms{$vo.area_info.area}">{pigcms{$vo.area_info.province} {pigcms{$vo.area_info.city} {pigcms{$vo.area_info.area}， {pigcms{$vo.name} 电话：{pigcms{$vo.phone}<if condition="$vo.addr_type eq 1"><font color="red">店铺</font></if></label>
				</li>
				</if>
			</volist>
		</ul>
		<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script>
			$(function(){
				window.parent.change_adress_frame($('#pick-address-list').height());
				
				var first_obj = $('#pick-address-list li label').eq(0);
				window.parent.change_pick_adress(first_obj.attr('adress_id'),first_obj.attr('pick_name'),first_obj.attr('phone'),first_obj.attr('province'),first_obj.attr('city'),first_obj.attr('area'));
				
				$('#pick-address-list li label').click(function(){
					if(!$(this).closest('li').hasClass('selected')){
						$(this).closest('li').addClass('selected').siblings('li').removeClass('selected');
						window.parent.change_pick_adress($(this).attr('pick_addr_id'),$(this).attr('pick_name'),$(this).attr('phone'),$(this).attr('province'),$(this).attr('city'),$(this).attr('area'));
					}
				});
			});
		</script>
	</if>
</body>
</html>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/styles.css">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.ba-bbq.min.js"></script>
<if condition="$config['site_favicon']">
	<link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
</if>
<title>{pigcms{$config.site_name} - 社区中心</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" href="{pigcms{$static_path}css/bootstrap.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/font-awesome.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-fonts.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace.min.css" id="main-ace-style">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-skins.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-rtl.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/global.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui-timepicker-addon.css">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.ba-bbq.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/ace-extra.min.js"></script>


<script type="text/javascript" src="{pigcms{$static_path}js/bootstrap.min.js"></script>

<!-- page specific plugin scripts -->
<script type="text/javascript" src="{pigcms{$static_path}js/bootbox.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.ui.touch-punch.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.easypiechart.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.sparkline.min.js"></script>

<!-- ace scripts -->
<script type="text/javascript" src="{pigcms{$static_path}js/ace-elements.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/ace.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.yiigridview.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui-i18n.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui-timepicker-addon.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/echarts.min.js"></script>
<link rel="stylesheet" href="{pigcms{$static_path}layer/css/layui.css"  media="all">
<script src="{pigcms{$static_path}layer/layer.js"></script>
<style type="text/css">
.jqstooltip {
	position: absolute;
	left: 0px;
	top: 0px;
	visibility: hidden;
	background: rgb(0, 0, 0) transparent;
	background-color: rgba(0, 0, 0, 0.6);
	filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000,endColorstr=#99000000);
	-ms-filter:"progid:DXImageTransform.Microsoft.gradient(startColorstr=#99000000, endColorstr=#99000000)";
	color: white;
	font: 10px arial, san serif;
	text-align: left;
	white-space: nowrap;
	padding: 5px;
	border: 1px solid white;
	z-index: 10000;
}

.jqsfield {
	color: white;
	font: 10px arial, san serif;
	text-align: left;
}

.statusSwitch, .orderValidSwitch, .unitShowSwitch, .authTypeSwitch {
	display: none;
}

#shopList .shopNameInput, #shopList .tagInput, #shopList .orderPrefixInput
	{
	font-size: 12px;
	color: black;
	display: none;
	width: 100%;
}
.btn.disabled,.btn[disabled],fieldset[disabled] .btn {
	pointer-events:auto;
}
.fl{ float:left;}
.fr{ float:right;}
</style>
<script type="text/javascript">
	try{ace.settings.check('navbar' , 'fixed')}catch(e){}
	try{ace.settings.check('main-container' , 'fixed')}catch(e){}
	try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
	try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
</script>
	
</head>

<body class="no-skin">
	<include file="Public:nav"/>
	<script>
		function payment_reminder(){
			var url = "{pigcms{:U('Unit/payment_reminder')}";
			$.post(url,{},function(data){
				if(data.error == 1){
					// layer.confirm(data.msg, {icon: 3, title:'提示',btn: ['查看','关闭']}, function(index){
				 //  		location.href = "{pigcms{:U('Unit/pay_order')}";
					// });

					layer.open({
						type: 1,
						shade: false,
						area: ['300px','150px'],
						maxmin: true,
						content: '<div style="text-align;center; margin-left: 77px; margin-top: 20px;">'+data.msg+'</div>',
						zIndex: layer.zIndex, //重点1
						success: function(layero){
						layer.setTop(layero); //重点2
						},
						btn: ['查看', '关闭'],
						yes: function(index, layero){
						location.href = "{pigcms{:U('Unit/pay_order')}";
						}
						,btn2: function(index, layero){

						},
					}); 
				}
			},'json');
		}
	 	window.setInterval(payment_reminder,5000);

		function repair_reminder(){
			var url = "{pigcms{:U('Repair/repair_reminder')}";
			$.post(url,{},function(data){
				if(data.error == 1){
					layer.open({
						type: 1,
						shade: false,
						area: ['300px','150px'],
						maxmin: true,
						content: '<div style="text-align;center; margin-left: 77px; margin-top: 20px;">'+data.msg+'</div>',
						zIndex: layer.zIndex, //重点1
						success: function(layero){
						layer.setTop(layero); //重点2
						},
						btn: ['查看', '关闭'],
						yes: function(index, layero){
						location.href = "{pigcms{:U('Repair/index')}";
						}
						,btn2: function(index, layero){

						},
					}); 
				}
			},'json');
		}
	 	window.setInterval(repair_reminder,5000);

		function suggest_reminder(){
			var url = "{pigcms{:U('Repair/suggest_reminder')}";
			$.post(url,{},function(data){
				if(data.error == 1){
					layer.open({
						type: 1,
						shade: false,
						area: ['300px','150px'],
						maxmin: true,
						content:  '<div style="text-align;center; margin-left: 77px; margin-top: 20px;">'+data.msg+'</div>',
						zIndex: layer.zIndex, //重点1
						success: function(layero){
						layer.setTop(layero); //重点2
						},
						btn: ['查看', '关闭'],
						yes: function(index, layero){
						location.href = "{pigcms{:U('Repair/village_suggest')}";
						}
						,btn2: function(index, layero){

						},
					}); 
				}
			},'json');
		}
	 	window.setInterval(suggest_reminder,5000);
	</script>
	<div class="main-container" id="main-container">
	<include file="Public:left"/>

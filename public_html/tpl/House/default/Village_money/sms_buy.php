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
<script src="{pigcms{$static_public}js/layer/layer.js"></script>

<script type="text/javascript">
	try{ace.settings.check('navbar' , 'fixed')}catch(e){}
	try{ace.settings.check('main-container' , 'fixed')}catch(e){}
	try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
	try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
</script>

</head>

<body class="no-skin" style="background-color: #FFFFFF;">
	<div style="text-align: center;">
		<form enctype="multipart/form-data" class="form-horizontal" id="smsBuySub" method="get" action="{pigcms{:U(Village_money/sms_buy)}">
			<input type="hidden" name="c" value="Village_money"/>
			<input type="hidden" name="a" value="sms_buy"/>
			<div style="text-align: left; padding-left: 35%; margin-top: 50px; font-size: 16px;">
				<div style="margin-top: 150px;">
					短信价格：<input type="text" name="price" value="{pigcms{$config.sms_price}" readonly="readonly" >&nbsp;&nbsp;( 分/条 )
				</div>
				<div style="margin-top: 15px;">
					购买数量：<input type="text" name="sms_number" id="sms_number" onkeyup="value=value.replace(/[^\d]/g,'')" value="">&nbsp;&nbsp;条 (&nbsp;{pigcms{$config.sms_min_number}&nbsp;条起订)
				</div>
			</div>
			<div style="margin-top: 15px;">
				<button class="btn btn-info" type="submit"> <i class="ace-icon fa fa-check bigger-110"></i> 购买 </button>
			</div>
		</form>
	</div>
<script>

	$("#smsBuySub").submit(function(){

		var sms_min_number = "{pigcms{$config.sms_min_number}";
		var sms_number = $("#sms_number").val();
		if(parseInt(sms_number) < parseInt(sms_min_number)){
			layer.msg('购买数量不能小于'+sms_min_number+'条');
			return false;
		}
	})
	
</script>
<include file="Public:footer"/>
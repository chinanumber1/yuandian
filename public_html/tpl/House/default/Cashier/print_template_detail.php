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
.fl{ float:left;}
.fr{ float:right;}
body{
	background-color: #fff;
}
</style>
<script type="text/javascript">
	try{ace.settings.check('navbar' , 'fixed')}catch(e){}
	try{ace.settings.check('main-container' , 'fixed')}catch(e){}
	try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
	try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
</script>
	
</head>

<body class="no-skin">

<div class="main-content">
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div id="print_table" class="tab-pane active ">
				<div id="div_1">
				<if condition="$print_template.custom">
					<volist name="print_template['custom']" id="vo">
						<if condition="$vo.type eq '1'">
							<if condition="$vo.title eq '标题'">
								<div id="right_{pigcms{$vo.id}" style="width: 100%;float: left;text-align: center;font-size: 23px;margin-bottom: 4px;">{pigcms{$print_template.title}</div>
							<else/>
								<div id="right_{pigcms{$vo.id}" style="width: 30%;float: left;margin-bottom: 2px;"><strong>{pigcms{$vo.title}：</strong>【{pigcms{$vo.title}】</div>
							</if>
						</if>
					</volist>												
				</if>
				</div>
				<div id="div_2">
				<table class="table  table-bordered" width="80%">
					<tbody id="body">
					<tr>
						<volist name="print_template['custom']" id="vo">
							<if condition="$vo.type eq '2'">
								<th id="right_{pigcms{$vo.id}" style="text-align: center;">{pigcms{$vo.title}</th>
							</if>
						</volist>	
					</tr>
						<tr>
							<volist name="print_template['custom']" id="vo">
								<if condition="$vo.type eq '2'">
									<td class="tdright_{pigcms{$vo.id}"></td>
								</if>
							</volist>
						</tr>
						<tr>
							<volist name="print_template['custom']" id="vo">
								<if condition="$vo.type eq '2'">
									<td class="tdright_{pigcms{$vo.id}"></td>
								</if>
							</volist>
						</tr>
						<tr>
							<volist name="print_template['custom']" id="vo">
								<if condition="$vo.type eq '2'">
									<td class="tdright_{pigcms{$vo.id}"></td>
								</if>
							</volist>
						</tr>
						<tr>
							<volist name="print_template['custom']" id="vo">
								<if condition="$vo.type eq '2'">
									<td class="tdright_{pigcms{$vo.id}"></td>
								</if>
							</volist>
						</tr>
					</tbody>
				</table>
				</div>
				<div id="div_3">
				<if condition="$print_template.custom">
					<volist name="print_template['custom']" id="vo">
						<if condition="$vo.type eq '3'">
							<if condition="$vo.title eq '说明'">
								<div id="right_{pigcms{$vo.id}" style="width: 100%;float: left;margin-bottom: 2px;"><strong>{pigcms{$vo.title}：</strong>【{pigcms{$print_template.desc}】</div>
							<elseif condition="$vo.title eq '收款备注'  || $vo.title eq '合计'" />
								<div id="right_{pigcms{$vo.id}" style="width: 100%;float: left;margin-bottom: 2px;"><strong>{pigcms{$vo.title}：</strong>【{pigcms{$vo.title}】</div>
							<else/>
								<div id="right_{pigcms{$vo.id}" style="width: 30%;float: left;margin-bottom: 2px;"><strong>{pigcms{$vo.title}：</strong>【{pigcms{$vo.title}】</div>
							</if>
						</if>
					</volist>												
				</if>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>
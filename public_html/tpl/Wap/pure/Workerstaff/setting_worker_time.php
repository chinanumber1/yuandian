<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>修改营业时间</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll.2.13.2.css"/>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll.2.13.2.js"></script>
	<style type="text/css">
	.btn{ background-color:#66cccc}
	dl.list dd>.input-weak{ width:50%; float:right}
	dl.list dd span{display:block; float:left;color:#868686}
	.btn:active{ background-color:#66cccc}
	</style>
</head>
<body id="index">
        <div id="tips" class="tips"></div>
        <form id="form" method="post" action="__SELF__">
		    <dl class="list">
		        <dd class="dd-padding">
					<span>营业时间段开始时间:</span>
					<input placeholder="营业时间段开始时间" class="input-weak" type="text" name="office_start_time" value="{pigcms{$now_worker['office_time']['open']}">
		        </dd>
				
				<dd class="dd-padding">
					<span>营业时间段结束时间:</span>
		            <input placeholder="营业时间段结束时间" class="input-weak" type="text" name="office_stop_time" value="{pigcms{$now_worker['office_time']['close']}">
		        </dd>
				
				<dd class="dd-padding">
					<span>预约时间间隔:</span>
		            <input placeholder="单位分钟，必须是10的倍数" class="input-weak" type="text" name="time_gap" value="{pigcms{$now_worker['time_gap']}">
		        </dd>
		    </dl>
		    <div class="btn-wrapper"><button type="submit" class="btn btn-block btn-larger">修改</button></div>
		</form>
		<script>
			$(function(){
				var begin = {};
				begin.date = {preset : 'time'};
				begin.default = {
						theme: 'android-ics light', //皮肤样式
						mode: 'scroller', //日期选择模式
						display: 'bottom', //显示方式
				};
				var enddate = {};
				enddate.date = {preset : 'time'};
				enddate.default = {
						theme: 'android-ics light', //皮肤样式
						mode: 'scroller', //日期选择模式
						display: 'bottom', //显示方式
						onSelect: function (valueText, inst) {
							if ($("#appDate").val() != '') {
								var url = "{pigcms{:U('ajax_set_worker_time')}";
								var begin_time = $("#appDate").val();
								var end_time = valueText;
								$.post(url , {'office_start_time':begin_time,'office_stop_time':end_time},function(data){
								},'json')
							}
						}
				};
				$("input[name='office_start_time']").mobiscroll($.extend(begin['date'], begin['default']));
				$("input[name='office_stop_time']").mobiscroll($.extend(enddate['date'], enddate['default']));
			});
		</script>
{pigcms{$hideScript}
</body>
</html>
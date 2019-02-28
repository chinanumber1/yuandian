<!doctype html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<title>{pigcms{$now_merchant.name}的会员资料</title>
	<link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/style_bai.css"/>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}my_card/css/LCalendar.css">
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
	
</head>
<body>
	<form action="" method="post">
	<div id="bigbox">
		<div class="jtxx">
			<p class="jtxx_l left">姓名：</p>
			<input class="jtxx_r xb left" type="text" value="{pigcms{$now_user.truename}" id="true_name" name="truename">
		</div>
		
		<div class="jtxx">
			<p class="jtxx_l left">生日：</p>
			<input class="lizi" type="text" name="birthday" id="end_date" value="{pigcms{$now_user.birthday}" placeholder="选择开始日期" readonly="readonly">
			<!-- <input style="width: 76%;" class="xb left rili" type="text" value="{pigcms{$now_user.birthday}" id="birthday1" name="birthday1"> -->
			<input type="hidden" value="{pigcms{$_GET.mer_id}"  name="mer_id">
		</div>  
	</div>
	<button class="btn" id="commitBtn">完成</button>
	</form>
	<div class="cover hide"></div>
	<div class="sure hide">
		<p class="sure_text">该手机号对应多张卡，您确定去并卡账户吗？</p>
		<div class="btn_box">
		<input class="anniu btn_sure" type="button" value="确定">
		<input class="anniu margin_l btn_cancel" type="button" value="取消"></div>
	</div>
	<script src="{pigcms{$static_path}my_card/js/LCalendar.js" type="text/javascript"></script>
	<script>
		function p(s) {
		    return s < 10 ? '0' + s: s;
		}
		var val=$('#end_date').val();
		if(val=='0000-00-00'){
			//获取当前时间
			var myDate = new Date();
			//获取当前年
			var year=myDate.getFullYear();
			//获取当前月
			var month=myDate.getMonth()+1;
			//获取当前日
			var date=myDate.getDate(); 
			var now=year+'-'+p(month)+"-"+p(date);
			$('#end_date').val(now)
		}


		var calendar = new LCalendar();
		calendar.init({
			'trigger': '#end_date', //标签id
			'type': 'date', //date 调出日期选择 datetime 调出日期时间选择 time 调出时间选择 ym 调出年月选择,
			'minDate': (new Date().getFullYear()-100) + '-' + 1 + '-' + 1, //最小日期
			'maxDate': (new Date().getFullYear()) + '-' + 12 + '-' + 31 //最大日期
		});
		$('.lizi').click(function(e){
			document.activeElement.blur(); 
		})
		$('body').off('click','.gearDate').on('click','.gearDate',function(e){
			$(this).remove();
		});
	</script>
</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>服务认证</title>
<link href="{pigcms{$static_path}yuedan/css/css_whir.css" rel="stylesheet"/>
<script src="{pigcms{$static_path}yuedan/js/jquery-1.9.1.min.js" type="text/javascript" charset="utf-8"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>
<body>
<header>
	<a href="{pigcms{:U('Service/index')}" class="ft"><i></i></a>
	<span>商户认证</span>
</header>
<div class="Guide_end" style="margin-top: 0px;">
	<if condition="$authentication_info['authentication_status'] eq 1">
		<div class="Guide_top Guide_shz">
			<h5>&nbsp;<span>审核中</span></h5>
		</div>
	<elseif condition="$authentication_info['authentication_status'] eq 3"/>
		<div class="Guide_top">
			<h5>&nbsp;<span>未通过</span></h5>
		</div>
	<elseif condition="$authentication_info['authentication_status'] eq 2"/>
		<div class="Guide_top Guide_ytg">
			<h5>&nbsp;<span>已通过</span></h5>
		</div>
	</if>
	
	<volist name="authentication_info['authentication_field']" id="vo">
		<if condition="$vo['type'] eq 1">
			<div class="Guide_n">
				<p><span style="width: 100px;">{pigcms{$vo.title}：</span>{pigcms{$vo.value}</p>
			</div>
		<elseif condition="$vo['type'] eq 2"/>
			<div class="Correctid">
				<img src="{pigcms{$vo.value}" width=100% height=0>
				<p class="jszt">{pigcms{$vo.title}</p>
			</div>
		</if>
	</volist>
</div>
</body>
	<script type="text/javascript">
		$(".Correctid img").each(function(){
			$(this).height($(this).width()*0.6)
		});
		$('#car_apply').on('click',function(){
			location.href =	"{pigcms{:U('car_apply')}";
		});
	</script>
</html>
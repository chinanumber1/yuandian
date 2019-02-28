<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>车主认证</title>
<link href="{pigcms{$static_path}car_owner/css/css_whir.css" rel="stylesheet"/>
<script src="{pigcms{$static_path}car_owner/js/jquery-1.8.3.min.js"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
</head>
<body>
<div class="Guide_end">
	<if condition="$find['status'] eq 0">
		<div class="Guide_top Guide_shz">
			<h5>{pigcms{$find['name']}<span>审核中</span></h5>
		</div>
	<elseif condition="$find['status'] eq 2"/>
		<div class="Guide_top">
			<h5>{pigcms{$find['name']}<span>未通过</span></h5>
		</div>
	<elseif condition="$find['status'] eq 1"/>
		<div class="Guide_top Guide_ytg">
			<h5>{pigcms{$find['name']}<span>已通过</span></h5>
		</div>
	</if>
	<div class="Guide_n">
		<p><span>车牌号：</span>{pigcms{$find['front']}</p>
		<p><span>身份证号：</span>{pigcms{$find['user_id_number']}</p>
	</div>
	<div class="Correctid">
		<dl class="scwj clr">
			<dd>
				<ul class="clearfix">
					<li>
						<img src="{pigcms{$find['authentication_img']}" />
					</li>
				</ul>
				<span>身份证正面</span>
			</dd>
			<dd>
				<ul class="clearfix">
					<li>
						<img src="{pigcms{$find['authentication_back_img']}" />
					</li>
				</ul>
				<span>身份证背面</span>
			</dd>
		</dl>
	</div>
	<div class="Correctid">
		<img src="{pigcms{$find['drivers_license']}" width=100% height=0>
		<p class="jszt">驾驶证</p>
	</div>
</div>
	<if condition="$find['status'] eq 2">
		<div class="Incon">
			<p>{pigcms{$find['examine_remarks']}</p>
		</div>
		<div id="car_apply" class="dysb_d">
			<a href="#" class="dysub">重新提交审核</a>
		</div>
	</if>
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
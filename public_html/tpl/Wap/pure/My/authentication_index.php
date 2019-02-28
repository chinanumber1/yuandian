<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
		<meta http-equiv="Expires" content="-1">
		<meta http-equiv="Cache-Control" content="no-cache">
		<meta http-equiv="Pragma" content="no-cache">
		<meta charset="utf-8">
		<title>实名认证</title>
		<link href="{pigcms{$static_path}scenic/css/css_whir.css" rel="stylesheet"/>
		<script src="{pigcms{$static_path}scenic/js/jquery-1.8.3.min.js"></script>
		<!--[if lte IE 9]>
		<script src="scripts/html5shiv.min.js"></script>
		<![endif]-->
	</head>
	<style type="text/css">
		.Guide_n p span{ text-align: left;width: initial; }
		dl.scwj dd{ margin-top: 0px; }
	</style>
	<body>
		<!-- <div class="brief Guide">
			<h2>我的向导发布</h2>
		</div> -->
		<div class="Guide_end">
			<if condition="$authentication['authentication_status'] eq 0">
				<div class="Guide_top Guide_shz">
					<h5>{pigcms{$authentication['user_truename']}<span>审核中</span></h5>
				</div>
			<elseif condition="$authentication['authentication_status'] eq 2"/>
				<div class="Guide_top">
					<h5>{pigcms{$authentication['user_truename']}<span>未通过</span></h5>
				</div>
			<elseif condition="$authentication['authentication_status'] eq 1"/>
				<div class="Guide_top Guide_ytg">
					<h5>{pigcms{$authentication['user_truename']}<span>已通过</span></h5>
				</div>
			</if>
			<div class="Guide_n">
				<p class="clr"><span>身份证号</span>：{pigcms{$authentication['user_id_number']}</p>
			</div>
			<div class="Correctid">
				<dl class="scwj clr">
					<dd>
						<ul class="clearfix">
							<li>
								<if condition="$authentication['authentication_img']">
									<img src="{pigcms{$authentication['authentication_img']}" />
								<else/>
									<img src="{pigcms{$static_path}scenic/images/smrztu_03.png" />
								</if>
							</li>
						</ul>
						<span>身份证正面</span>
					</dd>
					<dd>
						<ul class=" clearfix">
							<li>
								<if condition="$authentication['authentication_back_img']">
									<img src="{pigcms{$authentication['authentication_back_img']}" />
								<else/>
									<img src="{pigcms{$static_path}scenic/images/smrztu_05.png" />
								</if>
							</li>
						</ul>
						<span>身份证背面</span>
					</dd>
				</dl>
			</div>
		</div>
		<!--重新申请按钮-->
		<if condition="$authentication['authentication_status'] eq 2">
		    <div class="Incon">
		    	<p class="">
		    		<if condition="$authentication['examine_remarks'] eq 1">您上传的身份证照片模糊，请重新上传审核！
					<elseif condition="$authentication['examine_remarks'] eq 2"/>您上传的身份证照片与真实姓名不符，请重新上传审核！
					<elseif condition="$authentication['examine_remarks'] eq 3"/>经审核，您上传的身份证已经过期，请重新上传审核！
					<else/>审核通过
		    		</if>
		    	</p>
		    </div>
			<div class="dysb_d">
				<a href="{pigcms{:U('authentication')}" class="dysub">重新申请</a>
			</div>
		</if>
	</body>
</html>
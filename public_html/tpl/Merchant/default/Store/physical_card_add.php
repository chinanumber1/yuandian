<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>
		<script type="text/javascript" src=".{pigcms{$static_public}js/date/WdatePicker.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
	</head>
	<body>
		<div class="mainBox">
			<div class="rightMain">
				<div class="grid-view">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="form_add" autocomplete="off">
						<input type="hidden" name="business_type" value="{pigcms{$_GET.business_type}"/>
						<input type="hidden" name="business_id" value="{pigcms{$_GET.business_id}"/>
						<input type="hidden" name="pay_title" value="{pigcms{$pay_title}"/>
						<div class="form-group">
							<label class="col-sm-2"><label for="total_price">实体卡卡号</label></label>
							<input class="col-sm-4" size="10" name="cardid" id="total_price" type="text" value=""/>
							<span class="form_tips">请输入实体卡卡号(请用户出示)</span>
						</div>
						<div class="form-group">
							<label class="col-sm-2"><label for="user_phone">用户手机号</label></label>
							<input class="col-sm-4" size="10" name="phone" id="user_phone" type="text" value="{pigcms{$user_phone}"/>
							<span class="form_tips">该手机号码需要在平台注册,没有手机号码无法绑定</span>
						</div>
						
						
						<div class="clearfix form-actions">
							<div class="col-md-offset-3 col-md-9">
								<button class="btn btn-info" type="submit" id="submit_btn">
									绑定
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
<script>
$(function(){
	
});

</script>
</html>
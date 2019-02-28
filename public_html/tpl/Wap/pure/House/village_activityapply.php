<!DOCTYPE html>
<html lang="zh">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0" />
	<meta name="format-detection"content="telephone=no">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta name="apple-mobile-web-app-status-bar-style" content="black" />
	<meta name="description" content="" />
	<meta name="keywords" content="" />
	<title>活动报名</title>
	<link href="{pigcms{$static_path}css/appoint_form.css?07" rel="stylesheet"/>
    <style>
	.yxc-attr-list li{ padding-left:3px;}
	</style>
</head>

<body>
	<section id="main">
		<div class="yxc-body-bg index-section">
			<form action="" method="post" id="main_form">
				<div class="yxc-space space-six border-t-no"></div>
				<ul class="yxc-attr-list appoint-time">
					<li data-role="chooseTime">
						<p class="cover no-arrow">
							<input class="ipt-attr" type="text" name="name" placeholder="报名姓名" />
						</p>
					</li>
					<div class="yxc-space space-six border-t-no"></div>
					<li>
						<p class="cover no-arrow">
							<input class="ipt-attr" type="text" name="phone" placeholder="手机号码(必须为小区业主手机号码)" />
						</p>
					</li>
					
					<div class="yxc-space space-six border-t-no"></div>
					<li>
						<p class="cover no-arrow">
							<input class="ipt-attr" type="text" name="apply_num" placeholder="报名人数" />
						</p>
					</li>
					<div class="yxc-space space-six border-t-no"></div>
					<li>
						<p class="cover no-arrow">
							<input class="ipt-attr" type="text" name="memo" placeholder="备注(可选)" />
						</p>
					</li>
				</ul>
				
				<em class="tip-add-money">
					<input type="hidden" name="activity_id" value="{pigcms{$_GET['activity_id']}" />
					<div class="foot-index">
						<a class="bt-sub-order" data-role="submit">
							立即报名
						</a>
					</div>
				</em>
			</form>
		</div>
	</section>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
	<script>
	$('.bt-sub-order').click(function(){
		var nowDom = $(this);
		var slA = $('#main_form').serializeArray();
		var url = "{pigcms{:U('House/village_activity',array('village_id'=>$_SESSION['now_village_bind']['village_id'],'id'=>$_GET['activity_id']))}"

		nowDom.addClass('disabled').html('提交中...');
		$.post(window.location.href,$('#main_form').serialize(),function(result){
			if(result.status == 1){
				motify.log('报名成功，正在跳转...');
				window.location.href = url;
			}else{
				nowDom.removeClass('disabled').html('立即报名');
				motify.log(result.msg);
				return false;
			}
		},'json');
		return false;
	});
	
	var motify = {
	timer:null,
	log:function(msg){
		$('.motify').hide();
		if(motify.timer) clearTimeout(motify.timer);
		if($('.motify').size() > 0){
			$('.motify').show().find('.motify-inner').html(msg);
		}else{
			$('body').append('<div class="motify" style="display:block;"><div class="motify-inner">'+msg+'</div></div>');
		}
		motify.timer = setTimeout(function(){
			$('.motify').hide();
		},3000);
	}
};
	</script>
</body>
</html>
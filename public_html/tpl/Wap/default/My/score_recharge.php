<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>{pigcms{$config['score_name']}兑换余额</title>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/>
	
</head>
<body id="index">
        <if condition="$error">
        	<div id="tips" class="tips tips-err" style="display:block;">{pigcms{$error}</div>
        <else/>
        	<div id="tips" class="tips"></div>
        </if>
        <form id="form" method="post" action="{pigcms{:U('My/score_recharge')}">
			<input type="hidden" name="label" value="{pigcms{$_GET.label}"/>
		    <dl class="list">
		        <dd class="dd-padding">
		            <input id="score" placeholder="请填写兑换{pigcms{$config['score_name']}数量" class="input-weak" type="text" name="score" value="{pigcms{$score_count}" <if condition="$_GET['label'] && $_GET['money']">readonly="readonly" onclick="$('#tips').html('订单兑换时无法修改金额！').show();"</if>/>
		        </dd>
		    </dl>
		    <p class="btn-wrapper">{pigcms{$config['score_name']}可兑换数为：{pigcms{$score_count} 个</p>
		    <p class="btn-wrapper">可以兑换余额为：{pigcms{$score_deducte} 元 （<font color="red">注意：这部分余额不能提现</font>）</p>
			
		    <div class="btn-wrapper"><button type="button" class="btn btn-block btn-larger" <if condition="$score_deducte eq 0">disabled="disabled"</if>onclick="bio_verify({submit:'#form',twice:twice_verify});">兑换</button></div>
		</form>

    	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script src="{pigcms{$static_path}layer/layer.m.js"></script>

		<script>
		<if condition="$config['twice_verify']">var twice_verify = true;<else />var twice_verify = false;</if>
		<if condition="$_SESSION['user']['verify_end_time']">var verify_end_time = {pigcms{$_SESSION['user']['verify_end_time']};</if>
		</script>
		{pigcms{$BioAuthticMethod}
		<script>
			$(function(){
				$('#form').on('submit', function(e){
					$('#tips').removeClass('tips-err').hide();
					var score = parseFloat($('#score').val());
					$('button').attr('disabled','disabled');
					$('button').html('正在处理...');
					if(isNaN(score)){
						$('#tips').html('请输入合法的金额！').addClass('tips-err').show();
			            e.preventDefault();
						location.reload();
						return false;
					}else if(score > {pigcms{$score_count} ){
						$('#tips').html('单次兑换金额最高不能超过{pigcms{$score_count} 万元').addClass('tips-err').show();
			            e.preventDefault();
						location.reload();
						return false;
					}else if(score < 1){
						$('#tips').html('单次兑换金额最低不能低于 1 个').addClass('tips-err').show();
			            e.preventDefault();
						location.reload();
						return false;
					}
			    });		
				
			});
		</script>
		<include file="Public:footer"/>
{pigcms{$hideScript}
</body>
</html>
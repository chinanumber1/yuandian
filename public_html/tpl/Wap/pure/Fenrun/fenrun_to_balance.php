<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>分润转余额</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}fenrun/css/fenrun.css?215"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		
    <style>
	
	</style>
</head>

 <body>
		<section class="balance">
            <div class="tit">分润转余额</div>
            <div class="whole bw">
                <div class="input">
                    <input type="number" placeholder="请输入金额" class="import" name="money"/>
                    <div class="click">全部转余额</div>
                </div>
                <p class="remind" data-num="{pigcms{$config.fenrun_to_balance_percent|floatval}">当前分润钱包余额￥{pigcms{$now_user.fenrun_money|floatval}，满￥{pigcms{$config.min_fenrun_to_balance_money|floatval}才可转到余额</p>
            </div>
            <div class="confirm">确认转换</div>
        </section>
        


        <script src="{pigcms{$static_path}fenrun/js/fenrun.js"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
			var min = Number('{pigcms{$config.min_fenrun_to_balance_money|floatval}');
			var fenrun = Number('{pigcms{$now_user.fenrun_money|floatval}');
			var flag = true;
			$(".import").bind('input', function(e){
				var key = $.trim($(this).val());
				var html='';
				if(key.length > 0 && key>=min ){
					$(".confirm").addClass("on");
					var discount=$(".remind").data("num");
					var jg = (key*discount/100).toFixed(2);
					html='额外扣除<i class="penny">￥'+jg+'</i>手续费，手续费率<i>'+ discount +'%</i>';
				}else{
					html='当前分润钱包余额￥'+fenrun+'，满￥'+min+'才可转到余额';
					$(".confirm").removeClass("on");
				}
				$(".remind").html(html);
			});
			
			$('.click').click(function(){
				$(".import").val($.trim(fenrun))
				var key = $.trim($('.import').val());
				var html='';
				if(key.length > 0 && key>=min ){
					$(".confirm").addClass("on");
					var discount=$(".remind").data("num");
					var jg = (key*discount/100).toFixed(2);
					html='额外扣除<i class="penny">￥'+jg+'</i>手续费，手续费率<i>'+ discount +'%</i>';
				}else{
					html='当前分润钱包余额￥'+fenrun+'，满￥'+min+'才可转到余额';
					$(".confirm").removeClass("on");
				}
				$(".remind").html(html);
			});
			
			$('.confirm').click(function(){
				if(!$(".confirm").hasClass('on')){
					return false;
				}
				var money = $(".import").val();
				if(money<=0){
					layer.open({
						content:'您没有可转金额',
						btn: ['我知道了']
						 ,yes: function(index){
						  window.location.reload();
						  layer.close(index);
						}
					});
				}
				if(flag){
					$.ajax({
						url: '{pigcms{:U('fenrun_to_balance')}',
						type: 'POST',
						dataType: 'json',
						data: {money: money},
						beforeSend:function(){
							flag = false;
						},
						success:function(data){
							layer.open({
								content:data.info,
								btn: ['我知道了']
								 ,yes: function(index){
								  window.location.reload();
								  layer.close(index);
								}
							});
						}
					});
				}
			});
		</script>
    </body>

</html>
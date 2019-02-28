<!doctype html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
	<title>{pigcms{$now_merchant.name}的交易数据</title>
	<link type="text/css" rel="stylesheet" href="{pigcms{$static_path}my_card/css/style_bai.css"/>
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
</head>
<body>
	<div id="jlbox">
		<div class="jy_title">交易记录</div>
		<if condition="$record">
			<volist  name="record" id="vo">
				<div class="jy_xq_box">
					<div class="jy jy_zong">
						<p class="left"> {pigcms{$vo.time|date="Y-m-d H:i:s",###}</p>
						<p class="right margin_r">{pigcms{$vo.desc}</p>
					</div>
					<div class="jy_xq hide">
						<if condition="$vo.money_add gt 0">
							<div class="jy_jl">
								<p class="left">余额增加：</p>
								<p class="right margin_r">{pigcms{$vo.money_add}元</p>
							</div>
						</if>
						<if condition="$vo.money_use gt 0">
							<div class="jy_jl">
							<p class="left">余额减少：</p>
							<p class="right margin_r">{pigcms{$vo.money_use}元</p>
							</div>
						</if>
						<if condition="$vo.score_add gt 0">
							<div class="jy_jl">
							<p class="left">{pigcms{$config['score_name']}增加：</p>
							<p class="right margin_r">{pigcms{$vo.score_add}分</p>
							</div>
						</if>
						<if condition="$vo.score_use gt 0">
							<div class="jy_jl">
							<p class="left">{pigcms{$config['score_name']}减少：</p>
							<p class="right margin_r">{pigcms{$vo.score_use}分</p>
							</div>
						</if>
						<if condition="$vo.coupon_add gt 0">
							<div class="jy_jl">
							<p class="left">优惠券增加：</p>
							<p class="right margin_r">{pigcms{$vo.coupon_add}元</p>
							</div>
						</if>
						<if condition="$vo.coupon_use gt 0">
							<div class="jy_jl">
							<p class="left">优惠券减少：</p>
							<p class="right margin_r">{pigcms{$vo.coupon_use}元</p>
							</div>
						</if>
					</div>
				</div>
			</volist>
		<else />
			<div class="jy_xq_box">
				<div class="jy jy_zong">
					<p class="left">无交易记录</p>
				</div>		
			</div>
		</if>	
	</div>
	<script>
		$('.jy').on('click',function(){
			if($(this).parent('.jy_xq_box').children('.jy_xq').is(':hidden')){
				$(this).parent('.jy_xq_box').children('.jy_xq').show();
				if($(this).parent('.jy_xq_box').siblings().children('.jy_xq').is(':visible')){
					$(this).parent('.jy_xq_box').siblings().children('.jy_xq').hide();
					$(this).parent('.jy_xq_box').siblings().children('.jy').addClass('jy_zong').removeClass('jy_zong_cur');
				}
				$(this).addClass('jy_zong_cur').removeClass('jy_zong');
			}else if($(this).parent('.jy_xq_box').children('.jy_xq').is(':visible')){
				$(this).parent('.jy_xq_box').children('.jy_xq').hide();
				$(this).addClass('jy_zong').removeClass('jy_zong_cur');
			}
		});
	</script>
</body>
</html>
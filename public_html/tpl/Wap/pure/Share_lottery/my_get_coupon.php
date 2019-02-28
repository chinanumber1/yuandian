<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>分享抢券</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/share_coupon.css?1232"/>
		<!-- <link rel="stylesheet" type="text/css" href="../static/css/share_coupon.css?1232"/> -->
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		
	</head>
	<body>

	
		
		<div id="share_success">
			<if condition="$share_coupon_adver ">
				<div class="share_success3" ><img style="width:100%" src = "{pigcms{$share_coupon_adver.pic}"></div>	
			<else />
				<div class="share_success3"><h1 style="font-style:normal">{pigcms{$config.share_friend_coupon_notice}</h1></div>
			</if>	
                <div class="share_success2">
                    <div class="share_success_juan">
                        <div>
                            <div class="share_bg">
                                <div class="share_bg_left">
                                	<ul>
                                		<li><span>￥</span><b>{pigcms{$coupon.discount|floatval}</b></li>
                                		<li><span>满{pigcms{$coupon.order_money|floatval}减{pigcms{$coupon.discount|floatval}元</span></li>
                                	</ul>
                                </div>
                                <div class="share_bg_right">
                                	<ul>
                                		<li><h3>{pigcms{$coupon.name}</h3></li>
										<li><span>使用平台:{pigcms{$coupon.platform}</span></li>
										<li><span>使用类别:{pigcms{$coupon.category_txt}</span></li>
										<li style="color:#f00;">{pigcms{$coupon.start_time|date="Y-m-d",###}至{pigcms{$coupon.end_time|date="Y-m-d",###}</li>
                                	</ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="share_success_zhi">
                        <h2>分享成功!</h2>
                        <p>奖励您{pigcms{$config.share_coupon_get_num}张平台优惠劵</p>
                        <span >已存入您的账户</span>
                        <a href="{pigcms{$coupon.coupon_url}" class="btn" >立即使用</a>
                    </div>
                </div>
         </div>
		<script type="text/javascript">
			
			$(function(){
				$('.open_coupon').click(function(){
					
					$.post('{pigcms{:U('ajax_get_coupon')}', {order_id: {pigcms{$_GET['order_id']},type:'{pigcms{$_GET['type']}'}, function(data, 	textStatus, xhr) {
						if(data.status){
							window.location.reload();
						}else{
							layer.open({content:data.info,btn: ['确定']});
						}
					});
				});
			});
			var width=$(window).width();
			var  juan=$	('.share_success_juan').width();

			var juan_a=(width-juan)/2;
			$('.share_success_juan').css('margin-left',juan_a);
			$('.share_success_zhi').css('margin-left',juan_a);
		</script>
		{pigcms{$hideScript}
	</body>
</html>
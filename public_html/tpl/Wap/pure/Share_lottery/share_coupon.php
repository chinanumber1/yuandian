<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<title>拼人品抢券</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/share_coupon.css?215"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
	</head>
	<body>

		<section class="grab_coupons">
			<if condition="$share_coupon_adver">
				<img style="width:100%" src = "{pigcms{$share_coupon_adver.pic}">
			<else />
			
			<h1>{pigcms{$config.share_friend_coupon_notice}</h1>
			</if>
			<p>抢来自{pigcms{:substr_replace($share_user['phone'],'****',3,4)}分享的优惠劵</p>
			<a href="javascript:void(0)" class="open_coupon">开</a>
			<span>{pigcms{$config.share_friend_coupon_des}</span>
		</section>
		<script type="text/javascript">
			<php>if($_GET['type'] == 'store'){</php>
				window.shareData = {
					"moduleName":"Store",
					"moduleID":"0",
					"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
					"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Share_lottery/share_coupon',array('order_id'=>$order['order_id'],'type'=>'store'))}",
					"tTitle": "{pigcms{$config.share_coupon_title}",
					"tContent": "{pigcms{$config.share_coupon_num}张优惠劵，快来抢啊！"
				};
					<php>}else if($_GET['type'] == 'shop'){</php>
				
				window.shareData = {
				"moduleName":"Shop",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Share_lottery/share_coupon',array('order_id'=>$order_details['order_id'],'type'=>'shop'))}",
				"tTitle": "{pigcms{$config.share_coupon_title}",
				"tContent": "{pigcms{$config.share_coupon_num}张优惠劵，快来抢啊！"
			};
			<php>}</php>
			$(function(){
				$('.open_coupon').click(function(){
					
					$.post('{pigcms{:U('ajax_get_coupon')}', {order_id: {pigcms{$_GET['order_id']},type:'{pigcms{$_GET['type']}'}, function(data, 	textStatus, xhr) {
						
						if(data.status==1){
							setTimeout(function () {
								window.location.href="{pigcms{:U('Share_lottery/share_coupon')}&order_id={pigcms{$_GET['order_id']}&type={pigcms{$_GET['type']}"
							},1000);
													
						}else{
							layer.open({content:data.info,btn: ['确定']});
						}
					});
				});
			});
			
			
		
		</script>
		
		{pigcms{$shareScript}
	</body>
</html>
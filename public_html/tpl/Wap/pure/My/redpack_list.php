<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
		<title>红包记录</title>
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
        <section class="wallet">
            <div class="operation">
                <div class="name">账户余额（元）</div>
                <div class="num">{pigcms{:number_format($redpack_money,2)}</div>

            </div>
            <div class="record"> 
				<div class="h2">余额记录</div>	
		
                <ul class="bw">
                  
                </ul>

                <!-- <div class="no_img">
                    <img src="images/wu_07.jpg">
                    <p>您当前还没有记录哟~</p>
                </div> -->
            </div>
        </section>
        


        <script src="{pigcms{$static_path}fenrun/js/fenrun.js"></script>
		<script src="{pigcms{$static_path}js/common_wap.js"></script>
		<script>
			var page = 1;
			list();
			$('#transaction').on('click',function(){
				location.href =	"{pigcms{:U('transaction')}";
			});
			$('#integral').on('click',function(){
				location.href =	"{pigcms{:U('integral')}";
			});
			
			$('#withdraw').on('click',function(){
				location.href =	"{pigcms{:U('Fenrun/withdraw')}";
			});
			$('#recharge').on('click',function(){
				location.href =	"{pigcms{:U('recharge')}";
			});
			$(window).scroll(function(){
				if($(window).scrollTop() == $(document).height() - $(window).height()){
					$('#mais').remove();
					var jia	=	'';
    				jia	+=	'<div id="jia" class="text-center m-t m-b">正在加载</div>';
    				$('.bw').append(jia);
					if($('.no_img').length < 1){
						destination	=	$('#destination').text();
						ride_price	=	$('#ride_price').text();
						remain_number	=	$('#remain_number').text();
						list();
					}else{
						$('#jia').remove();
					}
				}
			});
			function list(){
				$.ajax({
					type : "post",
					url : "{pigcms{:U('redpack_json')}",
					dataType : "json",
					data:{
						page	:	page,
					},
					async:false,
					success : function(result){
						var rideList	=	'';
						if(result){
							var	ride_list	=	result.money_list;
							if(ride_list){
								var	ride_list_length	=	ride_list.length;
								page++;
								for(var x=0;x<ride_list_length;x++){
									rideList	+=	' <li class="clr">';
									rideList	+=	' <div class="fl">';
						
									// rideList	+=	'	<p class="explain">'+ride_list[x].desc+'</p>';
									rideList	+=	'	<p class="explain">领取成功</p>';
									rideList	+=	'	<p>'+ride_list[x].time_s+'</p>';
									rideList	+=	'</div>';
									if(ride_list[x].type == 2){
										rideList	+=	'	<div class="fr c5b"> -'+ride_list[x].money+'</div>';
									}else{
										rideList	+=	'	<div class="fr cf1"> +'+ride_list[x].money+'</div>';
									}
									rideList	+=	'</li>';
								}
								if(ride_list_length <= 9){
									//rideList	+=	'<div class="no_img"><img src="{pigcms{$static_path}fenrun/images/wu_07.jpg"><p>您当前还没有记录哟~</p></div>';
								}else{
									rideList	+=	'<div id="mais" style="text-align:center;padding:10px 0;">上拉会有更多记录哦</div>';
								}
									$('.bw').append(rideList);
							}else{
								rideList	+=	'<div class="no_img"><img src="{pigcms{$static_path}fenrun/images/wu_07.jpg"><p>您当前还没有记录哟~</p></div>';
								$('.bw').after(rideList);
								//$('.bw').hide();
							}
						}else{
							rideList	+=	'<div class="no_img"><img src="{pigcms{$static_path}fenrun/images/wu_07.jpg"><p>您当前还没有记录哟~</p></div>';
								$('.bw').after(rideList);
								$('.bw').hide();
						}
						$('#jia').remove();
					
					},
					error:function(){
						alert('页面错误，请联系管理员');
					}
				})
			}
		</script>
		<script type="text/javascript">
			window.shareData = {
				"moduleName":"Home",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>",
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Home/index')}",
				"tTitle": "{pigcms{$config.site_name}",
				"tContent": "{pigcms{$config.seo_description}"
			};
		</script>
    </body>

</html>
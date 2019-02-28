<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>{pigcms{$now_village.village_name}</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name='apple-touch-fullscreen' content='yes' />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="address=no" />

		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/new_village.css" />
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
	</head>

	<body>
		<div id="container">
			<div id="scroller" class="village_my">
				<nav class="my_top">
					<div id="set_up" class="my-account">
						<div class="avatar_box">
							<img class="avater" src="<if condition="$now_user['avatar']">{pigcms{$now_user.avatar}<else/>{pigcms{$static_path}images/pic-default.png</if>" />
						</div>

						<div class="user-info">
							<p class="uname">业主名称：{pigcms{$now_user_info.name} <span><if condition='$now_user["sex"] eq 1'><img src="{pigcms{$static_path}images/man.png" width="15px" height="15px" /><elseif condition='$now_user["sex"] eq 2' /><img src="{pigcms{$static_path}images/woman.png" width="15px" height="15px" /></if></span></p>

                            <if condition="$now_user_info.type eq 4">
                                <p class="umoney phone">工作编号：{pigcms{$now_user_info.usernum}</p>
                                <else/>
                                <p class="umoney phone">物业编号：{pigcms{$now_user_info.usernum}</p>
                            </if>
							<if condition="$now_user_info['type'] neq 4">
								<p class="umoney phone">地址：{pigcms{$now_user_info.address}</p>
							<else/>
								<p class="umoney phone">{pigcms{$now_user_info.memo}</p>
							</if>
						</div>
						<!--div class="setting"><img src="{pigcms{$static_path}images/set_up.png" alt=""></div-->
					</div>
				</nav>

				<nav>
					<section class="link-url" data-url="{pigcms{:U('My/my_money')}"><span><img src="{pigcms{$static_path}images/money.png" /></span>
						<p>我的钱包</p>
					</section>
					<section class="person-info">
						<div class="fl">
							<p>{pigcms{$now_user.now_money}</p>
							<p>账户余额</p>
						</div>
						<div class="fl">
							<p>{pigcms{$now_user.score_count}</p>
							<p>账户{pigcms{$config['score_name']}</p>
						</div>
						<div class="fr">
							<p>VIP{pigcms{$now_user.level}</p>
							<p>账户等级</p>
						</div>
					</section>
				</nav>

				<nav>
					<section class="link-url" data-url="{pigcms{:U('House/my_village_list')}"><span><img src="{pigcms{$static_path}images/house.png" /></span>
						<p>我的小区</p>
					</section>
					
					<!--if condition="!$_SESSION['now_village_bind']['flag']">
					<section class="link-url" data-url="{pigcms{:U('House/village_my_bind_family_list',array('village_id'=>$now_village['village_id']))}"><span><img src="{pigcms{$static_path}images/merber.png" /></span>
						<p>成员管理</p>
					</section>
					</if-->
					<section class="link-url" data-url="{pigcms{:U('House/village_my_paylists',array('village_id'=>$now_village['village_id']))}"><span><img src="{pigcms{$static_path}images/fee.png" /></span>
						<p>生活缴费</p>
					</section>
				</nav>

				<nav>
					<section class="link-url" data-url="{pigcms{:U('House/order_list',array('village_id'=>$now_village['village_id']))}"><span><img src="{pigcms{$static_path}images/order.png" /></span>
						<p>我的订单</p>
					</section>
					<section class="link-url" data-url="{pigcms{:U('My/adress',array('village_id'=>$now_village['village_id']))}"><span><img src="{pigcms{$static_path}images/address.png" /></span>
						<p>收货地址</p>
					</section>
                    <section class="link-url" data-url="{pigcms{:U('Bbs/my_bbs_list',array('village_id'=>$now_village['village_id']))}"><span><img src="{pigcms{$static_path}images/bbs.png" /></span>
						<p>我的帖子</p>
					</section>
					<section class="link-url" data-url="{pigcms{:U('Gift/index',array('village_id'=>$now_village['village_id']))}"><span><img src="{pigcms{$static_path}images/gift.png" /></span>
						<p>{pigcms{$config['score_name']}商城</p>
					</section>
					<section class="link-url" data-url="{pigcms{:U('Library/express_service_list',array('village_id'=>$now_village['village_id']))}"><span><img src="{pigcms{$static_path}images/kuaidi.png" /></span>
						<p>快递代收</p>
					</section>

					<section class="link-url" data-url="{pigcms{:U('Library/express_send_list',array('village_id'=>$now_village['village_id']))}"><span><img src="{pigcms{$static_path}images/kuaidisend.png" /></span>
						<p>快递代发</p>
					</section>
					
					<section class="link-url" data-url="{pigcms{:U('Library/visitor_list',array('village_id'=>$now_village['village_id']))}"><span><img src="{pigcms{$static_path}images/visitor.png" /></span>
						<p>访客登记</p>
					</section>
					
					<section class="link-url" data-url="{pigcms{:U('House/village_my_repairlists',array('village_id'=>$now_village['village_id']))}"><span><img src="{pigcms{$static_path}images/baoxiu2.png" /></span>
						<p>物业报修</p>
					</section>
					
					
					<section class="link-url" data-url="{pigcms{:U('House/village_my_suggestlist',array('village_id'=>$now_village['village_id']))}"><span><img src="{pigcms{$static_path}images/tousu.png" /></span>
						<p>投诉建议</p>
					</section>

					<section class="link-url" data-url="{pigcms{:U('House/village_my_utilitieslists',array('village_id'=>$now_village['village_id']))}"><span><img src="{pigcms{$static_path}images/shangbao.png" /></span>
						<p>水电煤上报</p>
					</section>
				</nav>

				<nav>
					<section onclick="location.href='tel:{pigcms{:reset(explode(' ',$now_village['property_phone']))}'"><span><img src="{pigcms{$static_path}images/wuye.png" /></span>
						<p>联系物业</p>
					</section>
					<!--section class="link-url" data-url=""><span><img src="{pigcms{$static_path}images/kefu.png" /></span>
						<p>联系客服</p>
					</section-->
				</nav>
			</div>

			<div id="pullUp" style="bottom:-60px;">
				<img src="/static/logo.png" style="width:130px;height:40px;margin-top:10px" />
			</div>
		</div>
		<include file="House:footer"/>
		{pigcms{$shareScript}
		<script>
		var myScroll;
		var isApp = motify.checkApp();
		$(function(){
			$('#backBtn').click(function(){
				window.history.go(-1);
			});
			if($(".footerMenu").length){
				$('#scroller').css({'min-height':($(window).height()-100+1)+'px'});
			}else{
				$('#scroller').css({'min-height':($(window).height()-50+1)+'px'});
			}
			myScroll = new IScroll('#container', { probeType: 3,disableMouse:true,disablePointer:true,mouseWheel: false,scrollX: false, scrollY:true,click:iScrollClick(),scrollbars:false,useTransform:false,useTransition:false});
			if(isApp){
		        $('body').append('<style>::-webkit-scrollbar{width:0px;}</style>');
		        $('#container').css({'top':'-12px'});
		        $('#container,#scroller').css({'position':'static'});
		    }
			$('.avater').click(function(){
				var album_array = [];
				album_array[0] = $(this).attr('src');
				wx.previewImage({
					current:album_array[0],
					urls:album_array
				});
				return false;
			});
		});
		</script>
	</body>
</html>
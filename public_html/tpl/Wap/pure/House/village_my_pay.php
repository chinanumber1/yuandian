<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <if condition="!$is_app_browser">
		<title>{pigcms{$now_village.village_name}</title>
        <else/>
        <title>小区缴费</title>
        </if>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<!--script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script-->
		<script type="text/javascript"> var backUrl="{pigcms{:U('House/village',array(village_id=>$now_village['village_id']))}";</script>
		<style>
			#scroller {
			    position: absolute;
			    z-index: 1;
			    -webkit-tap-highlight-color: rgba(0,0,0,0);
			    width: 100%;
			    -webkit-transform: translateZ(0);
			    -moz-transform: translateZ(0);
			    -ms-transform: translateZ(0);
			    -o-transform: translateZ(0);
			    transform: translateZ(0);
			    -webkit-touch-callout: none;
			    -webkit-user-select: none;
			    -moz-user-select: none;
			    -ms-user-select: none;
			    user-select: none;
			    -webkit-text-size-adjust: none;
			    -moz-text-size-adjust: none;
			    -ms-text-size-adjust: none;
			    -o-text-size-adjust: none;
			    text-size-adjust: none;
			    transform: translate3d(0px,0px,0px);
			    overflow-y: scroll;
			}
			#pullUp1 {
			    height: 50px;
			    line-height: 50px;
			    text-align: center;
			    width: 100%;
			}
			.village_my nav{
				margin-bottom: 0;
			}
		</style>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>小区缴费</header>
    </if>
		<div id="container">
			<div id="scroller" class="village_my">
				<nav class="me_pay">
					<volist name="pay_list" id="vo">
						<section class="link-url" data-url="{pigcms{$vo.url}"><img src="{pigcms{$static_path}images/house/{pigcms{$vo.type}.png"/><p>{pigcms{$vo.name}</p><if condition="$vo['money'] gt 0"><em>(需缴费￥{pigcms{$vo.money})</em></if></section>
					</volist>

					<!-- <section class="link-url" data-url="http://up.7015.me/ccfront/entry/gateway/jhwy/usr_num={pigcms{$urlInfo['village_name']}&usr_id_elem1={pigcms{$urlInfo['floor_layer']}&usr_id_elem2={pigcms{$urlInfo['floor_name']}&usr_id_elem3={pigcms{$urlInfo['room_addrss']}"><img src="{pigcms{$static_path}images/house/property.png"/><p>盛京银行卡物业缴费</section> -->

					<volist name="payment_list" id="vo">
						<section class="link-url" data-url="{pigcms{:U('House/village_pay',array('village_id'=>$now_village['village_id'],'bind_id'=>$vo['bind_id'],'type'=>'custom_payment'))}"><img src="{pigcms{$vo.pay_icon}"/><p>{pigcms{$vo.payment_name}&nbsp;<if condition="$vo['remarks'] neq ''">({pigcms{$vo['remarks']})</if></p></section>
					</volist>

				</nav>
                <if condition="!$is_app_browser">
                    <div id="pullUp1" style="bottom:-60px;">
                        <img src="{pigcms{$config.site_logo}" style="width:130px;height:40px;margin-top:10px"/>
                    </div>
                </if>
				
			</div>
		</div>
		<script type="text/javascript" src="{pigcms{$static_path}js/new_village_my.js?210" charset="utf-8"></script>
		{pigcms{$shareScript}
		<script>
			$('#scroller').height($(document).height()-50);
		</script>
	</body>
</html>
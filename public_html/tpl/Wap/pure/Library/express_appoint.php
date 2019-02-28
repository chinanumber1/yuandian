
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <title>快递预约</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/mobiscroll_min.css" media="all">
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/mobiscroll_min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<style>
			.area_input{
				width: 100%;
			}
			.teshu{
				width: 73%!important;
			}
			.teshu_icon{
				display: inline-block;
				width: 17px;
				height: 17px;
				background: url({pigcms{$static_path}images/f1_time.png) center no-repeat;
				background-size: contain;
				vertical-align: sub;
			}
			.tishi{
			    padding: 10px 10px 10px 35px;
			    color: #999;
			}
			.tishi b{
				display: inline-block;
				width: 17px;
				height: 17px;
				background: url({pigcms{$static_path}images/f1_jie.png) center no-repeat;
				background-size: contain;
				vertical-align: sub;
			}
			input[type="text"]:disabled{
					background: #fff;
			}
			::-webkit-input-placeholder { /* WebKit browsers */
			　　color:#999;
			　　}
		</style>
	</head>
	<body>
    <header class="pageSliderHide"><div id="backBtn"></div>快递预约</header>
			<div id="container">
				<div id="scroller" class="village_repair">
					<form action="__SELF__" method="post" onsubmit="return chk_submit()">
						<!-- <section>
							<div class="area_input" style="margin-top:15px;">
								
								<span class="nametip">物业代送时间为 {pigcms{$express_config.start_time|date='H:i',###} 至 {pigcms{$express_config.end_time|date='H:i',###}</span>
							</div>
						</section> -->
						<section>
							<div class="area_input " style="margin-top:15px; width: 100%;">
								<span style="font-size: 16px;margin-left: 2%;color: #333;">送件时间:</span>
								<input style="" type="text" class="bind_family_txt datetime teshu" name="send_time" id="bind_family_name" placeholder="请选择符合范围的送件时间">
							</div>
						</section>
						<p class="tishi">
							<b></b>
							<span class="nametip">物业代送时间为 {pigcms{$express_config.start_time|date='H:i',###} 至 {pigcms{$express_config.end_time|date='H:i',###}</span>
							<if condition="$now_express.money gt 0"><span class="nametip" style="color: red;">代送费{pigcms{$now_express.money}元</span></if>
						</p>
						<div class="area_btn">
							<input type="hidden" name="express_id" value="{pigcms{$_GET['id']}" />
							<input type="hidden" name="express_collection_price" value="{pigcms{$now_express.money}" />
							<input type="submit" id="submit_btn" value="确定"/>
						</div>
					</form>
					
					
					<form id="recharge-form" method="post" action="{pigcms{:U('My/recharge')}" style="display:none;">
						<input id="recharge-money" name="money" value="{pigcms{$now_express.money}">
						<input id="label" name="label"/>
						<input id="village_id" name="village_id" value="{pigcms{$now_express.village_id}"/>
						<input type="submit" value="提交">
					</form>
				</div>
			</div>
		<script type="text/javascript" language="javascript">
				$(function(){
					$('.datetime').mobiscroll()["datetime"]({
						lang: 'zh',
						display: 'bottom',
						minWidth: 64,
					});
				})
				
				var ajax_express_appoint_url = "{pigcms{:U('ajax_express_appoint')}";
				function chk_submit(){
					$.post(ajax_express_appoint_url,$('form').serialize(),function(result){
						if(result.status && result.url){
							window.location.href=result.url;
						}else{
							layer.open({
								content: result.info,
								btn: ['确定'],
								shadeClose: false,
								yes:function(){									
									window.location.reload();
								}
							});
						}
						
					},'json')
					return false;
				}
		</script>
			</body>
</html>
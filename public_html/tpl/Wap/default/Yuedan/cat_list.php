<html lang="zh-CN"><head>
		<meta charset="utf-8">
		<title>分类</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		<link rel="stylesheet" type="text/css" href="http://hf.pigcms.com/tpl/Wap/pure/static/css/common.css?215">
		<link rel="stylesheet" type="text/css" href="http://hf.pigcms.com/tpl/Wap/pure/static/css/index.css?216">
		  <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}yuedan/css/cat_list.css"/>
		<script type="text/javascript" src="https://apps.bdimg.com/libs/jquery/1.9.0/jquery.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="http://hf.pigcms.com/tpl/Wap/pure/static/js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="http://hf.pigcms.com/tpl/Wap/pure/static/js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="http://hf.pigcms.com/tpl/Wap/pure/static/layer/layer.m.js" charset="utf-8"></script>
		<link href="http://hf.pigcms.com/tpl/Wap/pure/static/layer/need/layer.css" type="text/css" rel="styleSheet" id="layermcss">
		<style>
			
		</style>
	</head>
	<body style="zoom: 1;">
		<header class="hasManyCity after">
			<a href="{pigcms{:U('Yuedan/index')}"><i></i></a>
			<h2>全部分类</h2>
		</header>
		<div id="container1"  class="pageSliderHide after">
			<div class="leftBar">
				<ul class="scrollerBox" style="transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
					<volist name="catList" key="k" id="vo">
						<li class="<if condition="$k eq 1">cur</if>">{pigcms{$vo.cat_name}</li>
					</volist>
				</ul>
			</div>
			<div class="rightBar" style="width: 79%;height: 100%;">
				<div class="scrollerBox" style="width: 79%; transition-timing-function: cubic-bezier(0.1, 0.57, 0.1, 1); transition-duration: 0ms; transform: translate(0px, 0px) translateZ(0px);">
					<div class="tab_change">
						<volist name="catList" key="catkey" id="catvo">
							<dl id="right_1" class="clearfix" <if condition="$catkey eq 1">style="display: block;"</if>>
								<volist name="catvo['catList']" id="vovo">
									<a href="{pigcms{:U('service_list',array('cid'=>$vovo['cid']))}">
										<dd class="link-url">
											<div class="box">
												<div class="imgBox" style="height: 86.6667px;">
													<img src="{pigcms{$vovo.icon}">
												</div>
												<div class="catName">{pigcms{$vovo.cat_name}</div>
												<!-- <div class="remind third"></div> -->
											</div>
										</dd>
									</a>
								</volist>
							</dl>
						</volist>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript">
			$('.scrollerBox li').click(function(e){
				$(this).addClass('cur').siblings('li').removeClass('cur');
				var index=$(this).index();
				$('.tab_change dl:eq('+index+')').show().siblings('dl').hide();
			});
			$('.hasManyCity a').click(function(e){
				location.href="{pigcms{:U('index')}";
			});

			var height=$('#right_1').height();
			var window_height=$(window).height();
			if(height<=window_height){
				$('.leftBar').css('height',window_height-100);
			}else{
				$('.leftBar').css('height',height);
			}
			
		</script>	

		<style>
			
		</style>
</body>
</html>
<!doctype html>
<html>
<head>
	<include file="header"/>
</head>
<body>
	<div class="weui-pull-to-refresh__layer">
		<div class='weui-pull-to-refresh__arrow'></div>
		<div class='weui-pull-to-refresh__preloader'></div>
		<div class="down">下拉刷新</div>
		<div class="up">释放刷新</div>
		<div class="refresh">正在刷新</div>
	</div>
	<link href="{pigcms{$static_path}classifynew/css/mynew.css" rel="stylesheet"/>
	<div class="page__bd my_new_bd">
		<div class="my__head_new main_bg">
			<i class="header-annimate-element1"></i><i class="header-annimate-element4"></i>
			<i class="header-annimate-element5"></i><i class="header-annimate-element6"></i>
			<i class="header-annimate-element7"></i><i class="header-annimate-element8"></i>
			<i class="header-annimate-element9"></i>
			<div class="my__head_wap block">
				<div class="my__head_user z">
					<a href="" class="my__head_avatar z">
						<img src="{pigcms{$user_session.avatar|default='./static/images/user_avatar.jpg'}"/>
					</a>
					<div>
						<div class="my__head_nickname f16">{pigcms{$user_session.nickname}</div>
						<a class="qblink">UID : {pigcms{$user_session.uid}</a>
					</div>
				</div>
				<div class="weui-grids weui-grids-mini">
					<a href="javascript:;" class="weui-grid" style="width:33.333333%">
						<div class="tc">
							<span id="njum3" class="countup f16">{pigcms{$classify_count.content_count}</span>
						</div>
						<p class="weui-grid__label f13 ">总发布</p>
					</a>
					<a href="javascript:;" class="weui-grid" style="width:33.333333%">
						<div class="tc">
							<span id="njum1" class="countup f16 ">{pigcms{$classify_count.views_count}</span>
						</div>
						<p class="weui-grid__label f13 ">总浏览量</p>
					</a>
					<a href="javascript:;" class="weui-grid" style="width:33.333333%">
						<div class="tc">
							<span id="njum2" class="countup f16">{pigcms{$my_view_count}</span>
						</div>
						<p class="weui-grid__label f13 ">我的浏览量</p>
					</a>
				</div>
			</div>
		</div>

		<div class="float_nav weui-flex cl">
			<div class="swiper-container" id="newsSlider" data-autoplay="5000" data-speed="3000">
				<ul class="swiper-wrapper">
					<volist name="intro" id="vo">
						<li class="swiper-slide"> 
							<b class="main_color mr8 f14"><i class="iconfont icon-tongzhi f14 "></i>平台公告</b> 
							<a href="{pigcms{:U('about',array('id'=>$vo['id']))}" class="c9 f14">{pigcms{$vo.title}</a> 
						</li>
					</volist>
				</ul>
			</div>
		</div>



		<div class="weui-cells f15 before_none after_none">
			<div class="weui-grids">
				<a href="{pigcms{:U('my_fabu')}" class="weui-grid" style="width:25%">
					<div class="weui-grid__icon">
						<i class="iconfont icon-fabuhei main_color f24"></i>
					</div>
					<p class="weui-grid__label">我的发布</p>
					<em class="sub_label f10 c9">{pigcms{$my_count}条</em>
				</a>
				<a style="width:25%" href="{pigcms{:U('My/my_money')}" class="weui-grid">
					<div class="weui-grid__icon">
						<i class="iconfont icon-qianbao2 color-red f24"></i>
					</div>
					<p class="weui-grid__label">我的钱包</p>
				</a>


				<a class="weui-grid" style="width:25%" href="{pigcms{:U('member',array('uid'=>$user_session['uid']))}">
					<div class="weui-grid__icon">
						<i class="iconfont icon-fensiguanli color-purple2 f24"></i>
					</div>
					<p class="weui-grid__label">个人主页</p>
				</a>
				<if condition="$config['is_im']">
					<a style="width:25%" class="weui-grid" href="{pigcms{:U('My/go_im')}">
						<div class="weui-grid__icon">
							<i class="iconfont icon-sixin2 color-green f24"></i>
						</div>
						<p class="weui-grid__label">我的私信</p>
					</a>
				</if>
			</div>
		</div>

		<div class="weui-cells f15 before_none after_none">
			<div class="weui-cells__title weui_title mt0 f15 bold">我的订单</div>
			<div class="weui-grids">
				<a href="{pigcms{:U('My/classify_order_list')}" class="weui-grid">
					<div class="weui-grid__icon">
						<i class="iconfont icon-dingdan color-paypal f24"></i>
					</div>
					<p class="weui-grid__label ">信息订单</p>
				</a>
			</div>
		</div>
		<div class="weui-cells" style="font-size:14px;">
			<a class="weui-cell weui-cell_access" href="{pigcms{:U('about')}">
				<div class="weui-cell__hd"><i class="iconfont icon-guize color-forest"></i></div>
				<div class="weui-cell__bd">
					<p>帮助中心</p>
				</div>
				<div class="weui-cell__ft"> </div>
			</a>
			<a class="weui-cell weui-cell_access" href="{pigcms{$kefu_url}" target="_blank">
				<div class="weui-cell__hd"><i class="iconfont icon-kefu color-pink"></i></div>
				<div class="weui-cell__bd">
					<p>联系客服</p>
				</div>
				<div class="weui-cell__ft">有问题请找我</div>
			</a>
		</div>

		<div class="footer_fix"></div>
	</div>
	<div class="cl footer_fix"></div>
	<div class="weui-tabbar">
		<a href="{pigcms{:U('index')}" class="weui-tabbar__item">
			<i class="iconfont icon-index weui-tabbar__icon"></i>
			<p class="weui-tabbar__label">首页</p>
		</a>
        <a href="{pigcms{:U('hongbao')}" class="weui-tabbar__item">
			<i class="iconfont icon-hongbao2 weui-tabbar__icon"></i>
			<p class="weui-tabbar__label">红包</p>
		</a>
		<a href="{pigcms{:U('fabu')}" class="weui-tabbar__item weui-bar__item_on showpubfont">
			<div class="pub_circle"></div>
			<i class="iconfont icon-fabuhei weui-tabbar__icon"></i>
			<p class="weui-tabbar__label pub_circle_p" style="color:#777!important">发布</p>    
		</a>
        <a href="{pigcms{:U('collect')}" class="weui-tabbar__item ">
			<i class="iconfont icon-jieban weui-tabbar__icon"></i>
			<p class="weui-tabbar__label">收藏</p>
		</a>
		<a href="{pigcms{:U('my')}" class="weui-tabbar__item weui-bar__item_on">
			<span style="display: inline-block;position: relative;">
				<i class="iconfont icon-xiaolian2 weui-tabbar__icon"></i>
			</span>
			<p class="weui-tabbar__label">我的</p>
		</a>
	</div>
	<include file="footer"/>
	<script>
		var loadingurl = "{pigcms{:U('getList',array('uid'=>$nowUser['uid']))}&page=";
	</script>
</body>
</html>
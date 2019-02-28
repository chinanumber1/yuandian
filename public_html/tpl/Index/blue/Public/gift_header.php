<!DOCTYPE html>
<html lang="en">
<head>
    <title>{pigcms{$config.gift_alias_name}首页-{pigcms{$config.site_name}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <link rel="stylesheet" href="{pigcms{$static_path}gift/css/base.css">
    <link rel="stylesheet" href="{pigcms{$static_path}gift/css/font-awesome.min.css">
	<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
    <!--[if IE 7]>
    <link rel="stylesheet" href="{pigcms{$static_path}gift/css/font-awesome-ie7.min.css">
    <![endif]-->
    <link rel="stylesheet" href="{pigcms{$static_path}gift/css/jfStyle.css">
    <script type="text/javascript" src="{pigcms{$static_path}gift/js/html5.min.js"></script>
	<style type="text/css">
	.rightTagAndBtn{ width:400px;}
	</style>
	<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
	<script type="text/javascript">
	 
	   var  score_name = "{pigcms{$config.score_name}";
	</script>
</head>
<body>
<div class="header_top">
    <div class="hot cf">
        <div class="loginbar cf">
				<div class="span"><a href="/" style="font-size:14px;color:red;padding-right:12px;">返回首页</a></div>
				<div class="span" style="padding-right:10px;">|</div>
			<if condition="empty($user_session)">
				<div class="login"><a href="{pigcms{:UU('Index/Login/index')}" style="color:red;"> 登录 </a></div>
				<div class="regist"><a href="{pigcms{:UU('Index/Login/reg')}">注册 </a></div>
			<else/>
				<p class="user-info__name growth-info growth-info--nav">
					<span>
						<a rel="nofollow" href="{pigcms{:UU('User/Index/index')}" class="username">{pigcms{$user_session.nickname}</a>
					</span>
					<a class="user-info__logout" href="{pigcms{:UU('Index/Login/logout')}">退出</a>
				</p>
			</if>
			
			<if condition='!empty($now_user)'>
			<div class="span">|</div>
			<div class="weixin cf">
				<span>可用余额:<i>{pigcms{$now_user['now_money']}</i></span>
			</div>
			<div class="span">|</div>			
			<div class="weixin cf">
				<span>可用{pigcms{$config['score_name']}:<i>{pigcms{$now_user['score_count']}</i></span>
			</div>
			</if>
            <div class="span">|</div>
			<div class="weixin cf">
				<div class="weixin_txt"><a href="{pigcms{$config.config_site_url}/topic/weixin.html" target="_blank"> 微信版</a></div>
				<div class="weixin_icon"><p><span>|</span><a href="{pigcms{$config.config_site_url}/topic/weixin.html" target="_blank">访问微信版</a></p><img src="{pigcms{$config.wechat_qrcode}"/></div>
			</div>
            <div class="span">|</div>            
<!--             <div class="app cf">
                <div class="app_txt"><a href="{pigcms{$config.config_site_url}/topic/app.html" target="_blank"> APP版</a></div>
                <div class="app_icon"><p><span>|</span><a href="{pigcms{$config.config_site_url}/topic/app.html" target="_blank">访问APP版</a></p><img src="{pigcms{$config.site_url}/tpl/Static/blue/app/images/logo_08.png"/></div>
            </div> -->
        </div>
        <div class="list">

			<ul class="cf">
				<li>
					<div class="li_txt"><a href="{pigcms{:UU('User/Index/index')}">我的订单</a></div>
					<div class="span">|</div>
				</li>
				<li class="li_txt_info cf">
					<div class="li_txt_info_txt"><a href="{pigcms{:UU('User/Index/index')}">我的信息</a></div>
					<div class="li_txt_info_ul">
						<ul class="cf">
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Index/index')}">我的订单</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Rates/index')}">我的评价</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Collect/index')}">我的收藏</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Point/index')}">我的{pigcms{$config['score_name']}</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Credit/index')}">帐户余额</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:UU('User/Adress/index')}">收货地址</a></li>
						</ul>
					</div>
					<div class="span">|</div>
				</li>
				<li class="li_liulan">
					<div class="li_liulan_txt"><a href="#">最近浏览</a></div>	 
					<div class="history" id="J-my-history-menu"></div> 
					<div class="span">|</div>
				</li>
				<li class="li_shop">
					<div class="li_shop_txt"><a href="#">我是商家</a></div>
					<ul class="li_txt_info_ul cf">
						<li><a class="dropdown-menu__item first" rel="nofollow" href="{pigcms{$config.config_site_url}/merchant.php">商家中心</a></li>
						<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{$config.config_site_url}/merchant.php">我想合作</a></li>
					</ul>
				</li>
			</ul>
        </div>
    </div>
</div>

<header class="header">
    <div class="logoAndSearch">
        <div class="w1200 clearfix">
            <!--div class="topSearch fr">
                <p class="fl fresh">今日更新 <em>298</em> 款</p>
                <div class="inputWrap fl">
                    <input type="text" placeholder="请输入关键词"/>
                </div>
                <button class="searchBtn fl"></button>
                <div class="search_suggest" id="gov_search_suggest">
                    <ul>
                    </ul>
                </div>
            </div-->
            <div class="logo">
                <a href="{pigcms{$config.site_url}">
                    <img src="{pigcms{$config.site_logo}"/>
                </a>
            </div>
        </div>
    </div>
</header>
<nav class="nav">
    <div class="w1200 clearfix">
        <div class="bigNav fl">
            <ul>
                <li <if condition='ACTION_NAME EQ "index"'>class="on"</if>><a href="{pigcms{:U('Gift/index')}"><i class="fa fa-home"></i>首页</a></li>
                <li <if condition='(ACTION_NAME EQ "gift_list") && ($_GET["type"] eq "hot")'>class="on"</if>><a href="{pigcms{:U('gift_list',array('type'=>'hot'))}"><i class="fa fa-star-o"></i>我能兑换</a> </li>
            </ul>
        </div>
        <div class="subNav fl">
            <ul>
				<volist name='gift_category_list["list"]' id='gift' offset='0' length='12'>
					<li <if condition='($_GET["cat_id"] eq $gift["cat_id"]) || ($now_nav_gift_category["cat_fid"] eq $gift["cat_id"])'>class="on"</if>>
						<a href="{pigcms{:U('gift_list',array('cat_id'=>$gift['cat_id'],'exchange_type'=>2))}">
							{pigcms{$gift['cat_name']}
						</a>
					</li>
				</volist>
            </ul>
        </div>
    </div>
</nav>
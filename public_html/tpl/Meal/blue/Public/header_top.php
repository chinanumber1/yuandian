<div class="header_top">
    <div class="hot cf">
        <div class="loginbar cf">
			<if condition="$now_select_city">
				<div class="span" style="font-size:16px;color:red;padding-right:3px;cursor:default;">{pigcms{$now_select_city.area_name}</div>
				<div class="span" style="padding-right:10px;">[<a href="{pigcms{:UU('Index/Changecity/index')}">切换城市</a>]</div>
				<div class="span" style="padding-right:10px;">|</div>
			</if>
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
			<div class="span">|</div>
			<div class="weixin cf">
				<div class="weixin_txt"><a href="{pigcms{$config.config_site_url}/topic/weixin.html" target="_blank"> 微信版</a></div>
				<div class="weixin_icon"><p><span>|</span><a href="{pigcms{$config.config_site_url}/topic/weixin.html" target="_blank">访问微信版</a></p><img src="{pigcms{$config.wechat_qrcode}"/></div>
			</div>
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
<header class="header cf">
	<pigcms:one_adver cat_key="index_top">
		<div class="content">
			<div class="banner" style="background:{pigcms{$one_adver.bg_color}">
				<div class="hot"><a href="{pigcms{$one_adver.url}" title="{pigcms{$one_adver.name}"><img src="{pigcms{$one_adver.pic}" /></a></div>
			</div>
		</div>
	</pigcms:one_adver>
    <div class="nav cf">
		<div class="logo">
			<a href="{pigcms{$config.site_url}" title="{pigcms{$config.site_name}">
				<img  src="{pigcms{$config.site_logo}" />
			</a>
		</div>
		<div class="search">
			<form action="{pigcms{:U('Meal/Search/index')}" method="post" group_action="{pigcms{:U('Group/Search/index')}" meal_action="{pigcms{:U('Meal/Search/index')}">
				<div class="form_sec">
					<div class="form_sec_txt meal">{pigcms{$config.meal_alias_name}</div>
					<div class="form_sec_txt1 group">{pigcms{$config.group_alias_name}</div>
				</div>
				<input name="w" class="input" type="text" placeholder="请输入商品名称、地址等"/>
				<button value="" class="btnclick"><img src="{pigcms{$static_path}images/o2o1_20.png"  /></button>
			</form>
			<div class="search_txt">
				<volist name="search_hot_list" id="vo">
					<a href="{pigcms{$vo.url}"><span>{pigcms{$vo.name}</span></a>
				</volist>
			</div>
		</div>
		<div class="menu" <if condition="$config.top_three_logo_close eq 1">style="display:none"</if>>
			<div class="ment_left">
			  <div class="ment_left_img"><img src="{pigcms{$static_path}images/o2o1_13.png" /></div>
			  <div class="ment_left_txt">随时退</div>
			</div>
			<div class="ment_left">
			  <div class="ment_left_img"><img src="{pigcms{$static_path}images/o2o1_15.png" /></div>
			  <div class="ment_left_txt">不满意免单</div>
			</div>
			<div class="ment_left">
			  <div class="ment_left_img"><img src="{pigcms{$static_path}images/o2o1_17.png" /></div>
			  <div class="ment_left_txt">过期退</div>
			</div>
		</div>
    </div>
</header>
<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<?php if($config['site_favicon']): ?><link rel="shortcut icon" href="<?php echo ($config["site_favicon"]); ?>"/><?php endif; ?>
		<title><?php echo ($config["seo_title"]); ?></title>
		<meta name="keywords" content="<?php echo ($config["seo_keywords"]); ?>" />
		<meta name="description" content="<?php echo ($config["seo_description"]); ?>" />
		<link href="<?php echo ($static_path); ?>css/css.css" type="text/css"  rel="stylesheet" />
		<link href="<?php echo ($static_path); ?>css/new.css" type="text/css"  rel="stylesheet" />
		<link href="<?php echo ($static_path); ?>css/header.css"  rel="stylesheet"  type="text/css" />
		<link rel="stylesheet" type="text/css" href="<?php echo ($static_path); ?>css/ydyfx.css"/>
		<script src="<?php echo ($static_path); ?>js/jquery-1.7.2.js"></script>
		<script src="<?php echo ($static_public); ?>js/jquery.lazyload.js"></script>
		<script src="<?php echo ($static_path); ?>js/jquery.nav.js"></script>
		<script src="<?php echo ($static_path); ?>js/navfix.js"></script>
		<script src="<?php echo ($static_path); ?>js/common.js"></script>
		<script src="<?php echo ($static_path); ?>js/index.js"></script>
		<script src="<?php echo ($static_path); ?>js/index.activity.js"></script>
		<?php if($config['wap_redirect']): ?><script>
				if(/(iphone|ipod|android|windows phone)/.test(navigator.userAgent.toLowerCase())){
					<?php if($config['wap_redirect'] == 1): ?>window.location.href = './wap.php';
					<?php else: ?>
						if(confirm('系统检测到您可能正在使用手机访问，是否要跳转到手机版网站？')){
							window.location.href = './wap.php';
						}<?php endif; ?>
				}

			</script><?php endif; ?>
		<!--[if IE 6]>
		<script  src="<?php echo ($static_path); ?>js/DD_belatedPNG_0.0.8a.js" mce_src="<?php echo ($static_path); ?>js/DD_belatedPNG_0.0.8a.js"></script>
		<script type="text/javascript">
		   /* EXAMPLE */
		   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');

		   /* string argument can be any CSS selector */
		   /* .png_bg example is unnecessary */
		   /* change it to what suits you! */
		</script>
		<script type="text/javascript">DD_belatedPNG.fix('*');</script>
		<style type="text/css">
			body{behavior:url("<?php echo ($static_path); ?>css/csshover.htc");}
			.category_list li:hover .bmbox {filter:alpha(opacity=50);}
			.gd_box{display:none;}
		</style>
		<![endif]-->
	</head>
	<body>
		<?php $index_top_fold = D("Adver")->get_adver_by_key("index_top_fold","1");if(is_array($index_top_fold)): $i = 0;if(count($index_top_fold)==0) : echo "<div class='no_adver_list index_top_fold_empty'>列表为空</div>" ;else: foreach($index_top_fold as $key=>$vo): ++$i;?><div class="index_top_fold_box" style="background:url(<?php echo ($vo["pic"]); ?>) no-repeat center top <?php echo ($vo["bg_color"]); ?>;">
				<a href="<?php echo ($vo["url"]); ?>" target="_blank" class="link"></a>
			</div><?php endforeach; endif; else: echo "<div class='no_adver_list index_top_fold_empty'>列表为空</div>" ;endif; ?>
		<div class="header_top">
    <div class="hot cf">
        <div class="loginbar cf">
			<?php if($now_select_city): ?><div class="span" style="font-size:16px;color:red;padding-right:3px;cursor:default;"><?php echo ($now_select_city["area_name"]); ?></div>
				<div class="span" style="padding-right:10px;color:#7d7d7d;">[<a href="<?php echo UU('Index/Changecity/index');?>">切换城市</a>]</div>
				<div class="span" style="padding-right:10px;">|</div><?php endif; ?>
			<?php if(empty($user_session)): ?><div class="login"><a href="<?php echo UU('Index/Login/index');?>" style="color:red;"> 登录 </a></div>
				<div class="regist"><a href="<?php echo UU('Index/Login/reg');?>">注册 </a></div>
			<?php else: ?>
				<p class="user-info__name growth-info growth-info--nav">
					<span>
						<a rel="nofollow" href="<?php echo UU('User/Index/index');?>" class="username"><?php echo ($user_session["nickname"]); ?></a>
					</span>
					<a class="user-info__logout" href="<?php echo UU('Index/Login/logout');?>">退出</a>
				</p><?php endif; ?>
			<div class="span">|</div>
			<div class="weixin cf">
				<div class="weixin_txt"><a href="<?php echo ($config["config_site_url"]); ?>/topic/weixin.html" target="_blank"> 微信版</a></div>
				<div class="weixin_icon"><p><span>|</span><a href="<?php echo ($config["config_site_url"]); ?>/topic/weixin.html" target="_blank">访问微信版</a></p><img src="<?php echo ($config["wechat_qrcode"]); ?>"/></div>
			</div>
			<?php if($config['pcindex_show_appdown']): ?><div class="span">|</div>
				<div class="app cf">
					<div class="app_txt"><a href="<?php echo ($config["config_site_url"]); ?>/topic/app.html" target="_blank"> APP版</a></div>
					<div class="app_icon"><p><span>|</span><a href="<?php echo ($config["config_site_url"]); ?>/topic/app.html" target="_blank">访问APP版</a></p><img src="<?php echo U('Recognition/get_own_qrcode',array('qrCon'=>urlencode(C('config.site_url').'/topic/app_wap.html')));?>"/></div>
				</div><?php endif; ?>
        </div>
        <div class="list">

			<ul class="cf">
				<li>
					<div class="li_txt"><a href="<?php echo UU('User/Index/index');?>">我的订单</a></div>
					<div class="span">|</div>
				</li>
				<li class="li_txt_info cf">
					<div class="li_txt_info_txt"><a href="<?php echo UU('User/Index/index');?>">我的信息</a></div>
					<div class="li_txt_info_ul">
						<ul class="cf">
							<li><a class="dropdown-menu__item" rel="nofollow" href="<?php echo UU('User/Index/index');?>">我的订单</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="<?php echo UU('User/Rates/index');?>">我的评价</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="<?php echo UU('User/Collect/index');?>">我的收藏</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="<?php echo UU('User/Point/index');?>">我的<?php echo ($config['score_name']); ?></a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="<?php echo UU('User/Credit/index');?>">帐户余额</a></li>
							<li><a class="dropdown-menu__item" rel="nofollow" href="<?php echo UU('User/Adress/index');?>">收货地址</a></li>
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
						<li><a class="dropdown-menu__item first" rel="nofollow" href="<?php echo ($config["config_site_url"]); ?>/merchant.php">商家中心</a></li>
						<li><a class="dropdown-menu__item" rel="nofollow" href="<?php echo ($config["config_site_url"]); ?>/store.php">店员中心</a></li>
						<?php if($config['house_open']): ?><li><a class="dropdown-menu__item" rel="nofollow" href="<?php echo ($config["config_site_url"]); ?>/shequ.php">社区管理</a></li><?php endif; ?>
					</ul>
				</li>
			</ul>
        </div>
    </div>
</div>
<header class="header cf">
	<?php $one_adver = D("Adver")->get_one_adver("index_top"); if(is_array($one_adver)): ?><div class="content">
			<div class="banner" style="background:<?php echo ($one_adver["bg_color"]); ?>">
				<div class="hot"><a href="<?php echo ($one_adver["url"]); ?>" title="<?php echo ($one_adver["name"]); ?>"><img src="<?php echo ($one_adver["pic"]); ?>" /></a></div>
			</div>
		</div><?php endif; ?>
    <div class="nav cf">
		<div class="logo">
			<a href="<?php echo ($config["site_url"]); ?>" title="<?php echo ($config["site_name"]); ?>">
				<img  src="<?php echo ($config["site_logo"]); ?>" />
			</a>
			<div></div>
		</div>
		<div class="search">
			<form action="<?php echo U('Group/Search/index');?>" method="post" group_action="<?php echo U('Group/Search/index');?>" meal_action="<?php echo U('Meal/Search/index');?>">
				<div class="form_sec">
					<div class="form_sec_txt group"><?php echo ($config["group_alias_name"]); ?></div>
					<div class="form_sec_txt1 meal"><?php echo ($config["meal_alias_name"]); ?></div>
				</div>
				<input name="w" class="input" type="text" placeholder="请输入商品名称"/>
				<button value="" class="btnclick"><img src="<?php echo ($static_path); ?>images/o2o1_20.png" /></button>
			</form>
			<div class="search_txt">
				<?php if(is_array($search_hot_list)): $i = 0; $__LIST__ = $search_hot_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo ($vo["url"]); ?>"><span><?php echo ($vo["name"]); ?></span></a><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
		</div>
		<div class="menu" <?php if($config["top_three_logo_close"] == 1): ?>style="display:none"<?php endif; ?>>
			<div class="ment_left">
			  <div class="ment_left_img"><img src="<?php echo ($static_path); ?>images/o2o1_13.png" /></div>
			  <div class="ment_left_txt">随时退</div>
			</div>
			<div class="ment_left">
			  <div class="ment_left_img"><img src="<?php echo ($static_path); ?>images/o2o1_15.png" /></div>
			  <div class="ment_left_txt">不满意免单</div>
			</div>
			<div class="ment_left">
			  <div class="ment_left_img"><img src="<?php echo ($static_path); ?>images/o2o1_17.png" /></div>
			  <div class="ment_left_txt">过期退</div>
			</div>
		</div>
    </div>
</header>
		<div class="containr" style="position: relative; top:20px;">
			<div class="body" style="width:100%;">
				<div class="gd_box" style="top:1540px;">
					<div id="gd_box">
						<div id="gd_box1">
							<div id="nav">
								<ul>
									<?php $autoI = 0; ?>
									<?php if(is_array($index_group_list)): $i = 0; $__LIST__ = $index_group_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(!empty($vo['group_list']) && count($vo['group_list']) >= 4): ?><li <?php if($i == 1): ?>class="current"<?php endif; ?>>
												<a class="f<?php echo ($i); ?>" onClick="scrollToId('#f<?php echo ($i); ?>');"><img src="<?php echo ($vo["cat_pic"]); ?>" />
													<div class="scroll_<?php echo ($autoI%7+1); ?>"><?php echo ($vo["cat_name"]); ?></div>
												</a>
											</li>
											<?php $autoI++; endif; endforeach; endif; else: echo "" ;endif; ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div style=" width: 780px; height:50px; overflow: hidden; margin: 0 auto; position: absolute; top:50px; z-index: 9999; line-height: 50px;left: 50%;margin-left: -390px;">
				<?php if(!empty($scroll_msg)): ?><div  class="scroll_msg" style="">
						<div style="">
							<div class=""  id="scrollText" style="">
								<marquee  scrollamount="5" onmouseover = this.stop()  onmouseout=this.start() style="height:50px;margin: 0px; padding: 0px;background: rgba(255,255,255,0.9);" >
								<?php if(is_array($scroll_msg)): $i = 0; $__LIST__ = $scroll_msg;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div style="display:inline-block">
										<span style="padding-right:30px;">
											<i style="background:url(<?php echo ($static_path); ?>images/lbt_03.png) left center no-repeat; background-size: 15px; width: 20px; height: 20px; display:block; float: left; margin-top: 15px;"></i>
											<a style="color: #ff2c4d;;"><?php echo ($vo["content"]); ?></a>
										</span>
									</div><?php endforeach; endif; else: echo "" ;endif; ?>
								</marquee>
							</div>
						</div>
					</div>


					<style>
					#scrollText div a{ color: #a94442;}
					</style>
					<link rel="stylesheet" href="<?php echo ($static_public); ?>font-awesome/css/font-awesome.min.css"><?php endif; ?>
				</div>
				<!---menu left--->
				<div class="menu cf" style="position: relative;width: 1200px;">
					<div class="menu_left" style="margin-top:0px">
						<img class="category" src="<?php echo ($static_path); ?>images/category.png" /><div class="menu_left_top">全部分类</div>
						<div class="list">
							<ul>
								<?php if(is_array($all_category_list)): $k = 0; $__LIST__ = $all_category_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><li>
										<div class="li_top cf">
											<?php if($vo['cat_pic']): ?><div class="icon"><img src="<?php echo ($vo["cat_pic"]); ?>" /></div><?php endif; ?>
											<div class="li_txt"><a href="<?php echo ($vo["url"]); ?>" title="<?php echo ($vo["cat_name"]); ?>" target="_blank"><?php echo (mb_substr($vo["cat_name"],0,6,'utf-8')); ?></a></div>
										</div>
										<?php if($vo['cat_count'] > 1): ?><div class="rightIco"></div>
											<div class="li_bottom">
												<?php if(is_array($vo['category_list'])): $j = 0; $__LIST__ = array_slice($vo['category_list'],0,2,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($j % 2 );++$j;?><span><a href="<?php echo ($voo["url"]); ?>" title="<?php echo ($voo["cat_name"]); ?>" target="_blank"><?php echo (mb_substr($voo["cat_name"],0,4,'utf-8')); ?></a></span><?php endforeach; endif; else: echo "" ;endif; ?>
											</div>
											<div class="list_txt">
												<p><a href="<?php echo ($vo["url"]); ?>"><?php echo (mb_substr($vo["cat_name"],0,4,'utf-8')); ?></a></p>
												<?php if(is_array($vo['category_list'])): $j = 0; $__LIST__ = $vo['category_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($j % 2 );++$j;?><a class="<?php if($voo['is_hot']): ?>bribe<?php endif; ?>" href="<?php echo ($voo["url"]); ?>" title="<?php echo ($voo["cat_name"]); ?>" target="_blank"><?php echo (mb_substr($voo["cat_name"],0,4,'utf-8')); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
											</div><?php endif; ?>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>
							</ul>
						</div>
					</div>
					<!---menu right--->
					<div class="menu_right cf" style="margin-top:0px">
						<div class="menu_right_top">
							<ul>
								<?php $web_index_slider = D("Slider")->get_slider_by_key("web_slider","10");if(is_array($web_index_slider)): $i = 0;if(count($web_index_slider)==0): echo "列表为空" ;else: foreach($web_index_slider as $key=>$vo): ++$i;?><li class="ctur">
										<a href="<?php echo ($vo["url"]); ?>" <?php if($i == 1): ?>class="select"<?php endif; ?>><?php echo ($vo["name"]); ?></a>
									</li><?php endforeach; endif; else: echo "列表为空" ;endif; ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="menu_main">
				<div style=" position: relative;width: 980px;padding-left:220px">
					<div class="menu cf" >
						<div class="menu_right cf" style="margin-top:0px">
							<div class="menu_right_bottom cf">
								<div class="left cf">
									<div class="activityDiv">
											<ul>
												<?php $index_today_fav = D("Adver")->get_adver_by_key("index_today_fav","6");if(is_array($index_today_fav)): $i = 0;if(count($index_today_fav)==0) : echo "<div class='no_adver_list index_today_fav_empty'>列表为空</div>" ;else: foreach($index_today_fav as $key=>$vo): ++$i;?><li <?php if($i == 1): ?>class="mt-slider-current-trigger"<?php endif; ?> data-color="<?php echo ($vo["bg_color"]); ?>" data-name="<?php echo ($vo["name"]); ?>" data-subname="<?php echo ($vo["sub_name"]); ?>">
														<a href="<?php echo ($vo["url"]); ?>" target="_blank">
															<img src="<?php echo ($vo["pic"]); ?>" alt="<?php echo ($vo["name"]); ?>"/>
														</a>
													</li><?php endforeach; endif; else: echo "<div class='no_adver_list index_today_fav_empty'>列表为空</div>" ;endif; ?>
											</ul>
											<div class="activityDesc">
												<h1></h1>
												<div class="activityInfo"></div>
												<?php $index_today_fav = D("Adver")->get_adver_by_key("index_today_fav","6");if(is_array($index_today_fav)): $i = 0;if(count($index_today_fav)==0) : echo "<div class='no_adver_list index_today_fav_empty'>列表为空</div>" ;else: foreach($index_today_fav as $key=>$vo): ++$i;?><a id="point<?php echo ($i); ?>" href="javascript:void(0);" <?php if($i != 1): ?>class="un_select"<?php endif; ?> ></a><?php endforeach; endif; else: echo "<div class='no_adver_list index_today_fav_empty'>列表为空</div>" ;endif; ?>
											</div>

										<div class="pre-next">
											<a href="javascript:;" hidefocus="true" class="mt-slider-previous "></a>
											<a href="javascript:;" hidefocus="true" class="mt-slider-next "></a>
										</div>
									</div>

								</div>
								<div class="right cf" style="background-color:#fff;<?php if($now_activity): ?>border-right:1px solid #dfdfdf;<?php endif; ?>">
									<div class="systemNews">
										<img src="<?php echo ($static_path); ?>images/systemnews.png"><div class="title">平台快报<div class="more"><a href="<?php echo ($config["site_url"]); ?>/news/" target="_blank">更多></a></div></div>
										<div class="newslist cf">
											<ul>
												<?php $system_newss = D("System_news")->get_news("8");if(!empty($system_newss)): $i = 0;foreach($system_newss as $key=>$vo): ++$i;?><li><a href="<?php echo ($config["site_url"]); ?>/news/<?php echo ($vo["id"]); ?>.html" target="_blank"><span>[<?php echo ($vo["name"]); ?>]</span><?php echo ($vo["title"]); ?></a></li><?php endforeach; endif;?>
											</ul>
										</div>
									</div>
									<div class="systemQrocde">
										<!--<div class="title">微信专享价 省更多</div>-->
										<div class="qrcodeDiv">
											<img src="<?php echo ($config["wechat_qrcode"]); ?>"/>
										</div>
										<div class="s_title">微信扫描二维码 关注我们</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
				<div style=" position: relative;width: 1200px;">
					<div class="menu cf" >
						<div class="menu_right cf" style="margin-top:0px;width:100%">

							<div class="menu_right_bottom cf" style="width:100%">
								<div class="left cf" style="width: 100%;height: 194px;">
									<div class="mainbav clearfix">
										<div class="main_list cf hot" >
											<div class="mainbav_left clearfix">
												<div class="mainbav_txt group">热门<?php echo ($config["group_alias_name"]); ?></div>
											</div>
											<div class="mainbav_list">
												<?php if(is_array($hot_group_category)): $i = 0; $__LIST__ = $hot_group_category;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span><a href="<?php echo ($vo["url"]); ?>" target="_blank"><?php echo ($vo["cat_name"]); ?></a></span><?php endforeach; endif; else: echo "" ;endif; ?>
												<!--<div class="more"></div>-->
											</div>
										</div>
										<div class="main_list cf allarea" >
											<div class="mainbav_left clearfix">
												<div class="mainbav_txt area">全部区域</div>
											</div>
											<div class="mainbav_list">
												<?php if(is_array($all_area_list)): $i = 0; $__LIST__ = $all_area_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span><a href="<?php echo ($vo["url"]); ?>" target="_blank"><?php echo ($vo["area_name"]); ?></a></span><?php endforeach; endif; else: echo "" ;endif; ?>
												<!--<div class="more"></div>-->
											</div>
										</div>
										<div class="main_list cf circle hotcircle" >
											<div class="mainbav_left clearfix">
												<div class="mainbav_txt circle">热门商圈</div>
											</div>
											<div class="mainbav_list">
												<?php if(is_array($hot_circle_list)): $i = 0; $__LIST__ = $hot_circle_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span><a href="<?php echo ($vo["url"]); ?>" target="_blank"><?php echo ($vo["area_name"]); ?></a></span><?php endforeach; endif; else: echo "" ;endif; ?>
												<!--<div class="more"></div>-->
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php $is_near_shop = false;$near_shop_list = D("Merchant_store")->get_hot_list("10");?>
				<?php if($near_shop_list): $index_meal_top = D("Adver")->get_adver_by_key("index_meal_top","4");if(is_array($index_meal_top)): $i = 0;if(count($index_meal_top)==0) : echo "<div class='no_adver_list index_meal_top_empty'>列表为空</div>" ;else: foreach($index_meal_top as $key=>$vo): ++$i; if($i == 1): ?><ul class="index_adver_ul clearfix"><?php endif; ?>
							<li <?php if($i == 4): ?>class="li_4"<?php endif; ?>>
								<a href="<?php echo ($vo["url"]); ?>" target="_blank" class="link" title="<?php echo ($vo["name"]); ?>">
									<img src="<?php echo ($vo["pic"]); ?>"/>
								</a>
							</li>
						<?php if($i == 4 || $i == count($index_meal_top)): ?></ul><?php endif; endforeach; endif; else: echo "<div class='no_adver_list index_meal_top_empty'>列表为空</div>" ;endif; ?>
					<div class="nearby cf indexMeal">
						<div class="indexMealTitle clearfix">
							<h1><?php if($is_near_shop): ?>附近<?php echo ($config["shop_alias_name"]); else: ?>推荐<?php echo ($config["shop_alias_name"]); endif; ?></h1>
						</div>
						<div class="nearby_list clearfix">
							<ul>
								<?php if(is_array($near_shop_list)): $i = 0; $__LIST__ = $near_shop_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li <?php if($i > 5): ?>style="border-top:0px;"<?php endif; ?>>
										<div class="box">
											<div class="nearby_list_img">
												<a href="<?php echo ($vo["url"]); ?>" target="_blank">

													<img class="meal_img lazy_img" src="<?php echo ($static_public); ?>images/blank.gif" data-original="<?php echo ($vo["image"]); ?>" title="【<?php echo ($vo["area_name"]); ?>】<?php echo ($vo["name"]); ?>"/>
													<div class="bmbox">
														<div class="bmbox_title"> 微信扫码 手机查看</div>
														<div class="bmbox_list">
															<div class="bmbox_list_img"><img class="qrcode_img lazy_img" src="<?php echo ($static_public); ?>images/blank.gif" data-original="<?php echo U('Index/Recognition/see_qrcode',array('type'=>'shop','id'=>$vo['store_id']));?>" /></div>
														</div>
														<!--div class="bmbox_tip">微信扫码 手机查看</div-->
													</div>
													<div class="name" style="height:42px;"><?php if($vo.isverify): ?><span class="zheng">证</span><?php endif; ?>【<?php echo ($vo["area_name"]); ?>】<?php echo ($vo["name"]); ?></div>
													<?php if($vo['state']): ?><!--div class="name_info"><b>营业中</b></div--><?php endif; ?>
													<div class="extro">
														<div class="info">
															<div class="join"><?php if($vo['range']): ?>距离您 <span><?php echo ($vo["range"]); ?> </span><?php else: ?>粉丝 <span><?php echo ($vo["fans_count"]); ?></span><?php endif; ?></div>
														</div>
														<?php if($vo['sale_count']): ?><div class="info mealSales">
																<div class="join">已售 <span><?php echo ($vo["sale_count"]); ?></span></div>
															</div><?php endif; ?>
													</div>
												</a>
											</div>
										</div>
									</li><?php endforeach; endif; else: echo "" ;endif; ?>
							</ul>
						</div>
						<!--if condition="empty($is_near_shop)">
							<section class="nearby_box">
								<div class="nearby_box_txt"><img src="<?php echo ($static_path); ?>images/tankuang_10.png"/></div>
								<button class="nearby_box_but"><span>选取</span></button>
								<div class="nearby_box_close"></div>
							</section>
						</if-->
					</div><?php endif; ?>
				<div class="socll" style="width:100%;z-index:99">
					<?php $autoI=0; ?>
					<?php if(is_array($index_group_list)): $i = 0; $__LIST__ = $index_group_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cat_vo): $mod = ($i % 2 );++$i; if(!empty($cat_vo['group_list']) && count($cat_vo['group_list']) >= 4): $cat_top = D("Adver")->get_adver_by_key("cat_{$cat_vo['cat_id']}_top","4");if(is_array($cat_top)): $m = 0;if(count($cat_top)==0) : echo "<div class='no_adver_list cat_{$cat_vo['cat_id']}_top_empty'>列表为空</div>" ;else: foreach($cat_top as $key=>$vo): ++$m; if($m == 1): ?><ul class="index_adver_ul clearfix"><?php endif; ?>
									<li <?php if($m == 4): ?>class="li_4"<?php endif; ?>>
										<a href="<?php echo ($vo["url"]); ?>" target="_blank" class="link" title="<?php echo ($vo["name"]); ?>">
											<img src="<?php echo ($vo["pic"]); ?>"/>
										</a>
									</li>
								<?php if($m == 4 || $m == count($cat_top)): ?></ul><?php endif; endforeach; endif; else: echo "<div class='no_adver_list cat_{$cat_vo['cat_id']}_top_empty'>列表为空</div>" ;endif; ?>
							<div class="category cf sa" id="f<?php echo ($i); ?>">
								<div class="category_top cf">
									<div class="category_top_left">
										<ul>
											<li id="category_main_<?php echo ($autoI%7+1); ?>">
												<div class="category_main_icon"><?php if($cat_vo['cat_pic']): ?><img src="<?php echo ($cat_vo["cat_pic"]); ?>" style="width:22px;"/><?php endif; ?></div>
												<div class="category_main_txt"><?php echo ($cat_vo["cat_name"]); ?></div>
											</li>
										</ul>
									</div>
									<div class="category_top_right">
										<ul>
											<?php if(count($cat_vo['category_list']) > 1): if(is_array($cat_vo['category_list'])): $j = 0; $__LIST__ = array_slice($cat_vo['category_list'],0,6,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($j % 2 );++$j;?><li><a target="_blank" href="<?php echo ($voo["url"]); ?>" class="link"><?php echo ($voo["cat_name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; endif; ?>
											<li><a target="_blank" href="<?php echo ($cat_vo["url"]); ?>" class="link all">全部></a></li>
										</ul>
									</div>
								</div>
								<div class="category_list cf">
									<ul class="cf">
										<?php if(is_array($cat_vo['group_list'])): $k = 0; $__LIST__ = array_slice($cat_vo['group_list'],0,8,true);if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($k % 2 );++$k;?><li class="<?php if($k > 4): ?>btp0<?php endif; ?> li <?php if($k%4 == 0 || $k == count($cat_vo['group_list'])): ?>last--even<?php endif; ?>">
												<div class="category_list_img">
													<a href="<?php echo ($voo["url"]); ?>" target="_blank" class="imgBox">
														<img alt="<?php echo ($voo["s_name"]); ?>" class="deal_img lazy_img" src="<?php echo ($static_public); ?>images/blank.gif" data-original="<?php echo ($voo["list_pic"]); ?>"/>
														<div class="bmbox">
															<div class="bmbox_title"> 该商家有<span> <?php echo ($voo["fans_count"]); ?> </span>个粉丝</div>
															<div class="bmbox_list">
																<div class="bmbox_list_img"><img class="lazy_img" src="<?php echo ($static_public); ?>images/blank.gif" data-original="<?php echo U('Index/Recognition/see_qrcode',array('type'=>'group','id'=>$voo['group_id']));?>" /></div>
																<div class="bmbox_list_li">
																	<ul class="cf">
																		<li class="open_windows" data-url="<?php echo ($config["site_url"]); ?>/merindex/<?php echo ($voo["mer_id"]); ?>.html">商家</li>
																		<li class="open_windows" data-url="<?php echo ($config["site_url"]); ?>/meractivity/<?php echo ($voo["mer_id"]); ?>.html"><?php echo ($config["group_alias_name"]); ?></li>
																		<li class="open_windows" data-url="<?php echo ($config["site_url"]); ?>/mergoods/<?php echo ($voo["mer_id"]); ?>.html"><?php echo ($config["shop_alias_name"]); ?></li>
																		<li class="open_windows" data-url="<?php echo ($config["site_url"]); ?>/mermap/<?php echo ($voo["mer_id"]); ?>.html">地图</li>
																	</ul>
																</div>
															</div>
															<div class="bmbox_tip">微信扫码 更多优惠</div>
														</div>
													</a>
													<div class="datal">
														<a href="<?php echo ($voo["url"]); ?>" target="_blank">
															<div class="category_list_title"><?php echo ($voo["group_name"]); ?></div>
															<div class="category_list_description">【<?php echo ($voo["prefix_title"]); ?>】<?php echo ($voo["merchant_name"]); ?></div>
														</a>
														<div class="deal-tile__detail cf">
															<span class="price">&yen;<strong><?php echo ($voo["price"]); if($voo["extra_pay_price"] != ''): echo ($voo["extra_pay_price"]); endif; ?></strong> </span>
															<span>门店价 &yen;<?php echo ($voo["old_price"]); if($voo["extra_pay_price"] != ''): echo ($voo["extra_pay_price"]); endif; ?></span>
															<?php if($voo['wx_cheap']): ?><div class="cheap">微信购买立减￥<?php echo ($voo["wx_cheap"]); ?></div><?php endif; ?>
														</div>
													</div>
													<div class="extra-inner cf">
														<div class="sales"><?php echo ($voo['sale_txt']); ?></div>
														<div class="noreviews">
															<?php if($voo['reply_count']): ?><a href="<?php echo ($voo["url"]); ?>#anchor-reviews" target="_blank">
																	<div class="icon"><span style="width:<?php echo ($voo['score_mean']/5*100); ?>%;" class="rate-stars"></span></div>
																	<span><?php echo ($voo["reply_count"]); ?>次评价</span>
																</a>
															<?php else: ?>
																<span>暂无评价</span><?php endif; ?>
														</div >
													</div>
												</div>
											</li><?php endforeach; endif; else: echo "" ;endif; ?>
									</ul>
								</div>
								<div class="category_more cf">
									<a href="<?php echo ($cat_vo["url"]); ?>" target="_blank">
									查看全部 <span><?php echo ($cat_vo["cat_name"]); ?></span> <?php echo ($config["group_alias_name"]); ?> >
									</a>
								</div>
							</div>
							<?php $autoI++; endif; endforeach; endif; else: echo "" ;endif; ?>
				</div>
			</div>
		</div>
		<!--友情链接-->
		<?php if(!empty($flink_list)): ?><style type="text/css">.component-holy-reco {clear: both; margin: 0 auto;width: 1210px; position: relative;bottom: -98px;}.holy-reco{width:100%;margin:0 auto;padding-bottom:20px;_display:none}.holy-reco .tab-item {
			color: #666;}.holy-reco__content{border:1px solid #E8E8E8;padding:10px;background:#FFF}.holy-reco__content a{display:inline-block;color:#666;font-size:12px;padding:0 5px;line-height:16px;white-space:nowrap;width:85px;overflow:hidden;text-overflow:ellipsis}.nav-tabs--small .current {background: #ededed none repeat scroll 0 0;width:80px;text-align:center;padding:0 6px;float:left;cursor:pointer;}</style>
			<div class="component-holy-reco">
				<div class="J-holy-reco holy-reco">
					<div>
						<ul class="ccf cf nav-tabs--small">
							<li class="J-holy-reco__label current"><a href="javascript:void(0)" class="tab-item">友情链接</a></li>
						</ul>
					</div>
					<div class="J-holy-reco__content holy-reco__content">
						<?php if(is_array($flink_list)): $i = 0; $__LIST__ = $flink_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo ($vo["url"]); ?>" title="<?php echo ($vo["info"]); ?>" target="_blank"><?php echo ($vo["name"]); ?></a><?php endforeach; endif; else: echo "" ;endif; ?>
					</div>
				</div>
			</div><?php endif; ?>
		<!--友情链接--end-->
		<footer>
	<div class="footer1">
		<div class="footer_txt cf">
			<div class="footer_list cf">
				<ul class="cf">
					<?php $footer_link_list = D("Footer_link")->get_list();if(is_array($footer_link_list)): $i = 0;if(count($footer_link_list)==0) : echo "列表为空" ;else: foreach($footer_link_list as $key=>$vo): ++$i;?><li><a href="<?php echo ($vo["url"]); ?>" target="_blank"><?php echo ($vo["name"]); ?></a><?php if($i != count($footer_link_list)): ?><span>|</span><?php endif; ?></li><?php endforeach; endif; else: echo "列表为空" ;endif; ?>
				</ul>
			</div>
			<div class="footer_txt"><?php echo nl2br($config['site_show_footer'],'<a>');?></div>
		</div>
	</div>
</footer>
<div style="display:none;"><?php echo ($config["site_footer"]); ?></div>
<!--悬浮框-->
<?php if(MODULE_NAME != 'Login'): ?><div class="rightsead">
		<ul>
			<li>
				<a href="javascript:void(0)" class="wechat">
					<img src="<?php echo ($static_path); ?>images/l02.png" width="47" height="49" class="shows"/>
					<img src="<?php echo ($static_path); ?>images/a.png" width="57" height="49" class="hides"/>
					<img src="<?php echo ($config["wechat_qrcode"]); ?>" width="145" class="qrcode"/>
				</a>
			</li>
			<?php if($config['site_qq']): ?><li>
					<a href="http://wpa.qq.com/msgrd?v=3&uin=<?php echo ($config["site_qq"]); ?>&site=qq&menu=yes" target="_blank" class="qq">
						<div class="hides qq_div">
							<div class="hides p1"><img src="<?php echo ($static_path); ?>images/ll04.png"/></div>
							<div class="hides p2"><span style="color:#FFF;font-size:13px"><?php echo ($config["site_qq"]); ?></span></div>
						</div>
						<img src="<?php echo ($static_path); ?>images/l04.png" width="47" height="49" class="shows"/>
					</a>
				</li><?php endif; ?>
			<?php if($config['site_phone']): ?><li>
					<a href="javascript:void(0)" class="tel">
						<div class="hides tel_div">
							<div class="hides p1"><img src="<?php echo ($static_path); ?>images/ll05.png"/></div>
							<div class="hides p3"><span style="color:#FFF;font-size:12px"><?php echo ($config["site_phone"]); ?></span></div>
						</div>
						<img src="<?php echo ($static_path); ?>images/l05.png" width="47" height="49" class="shows"/>
					</a>
				</li><?php endif; ?>
			<li>
				<a class="top_btn">
					<div class="hides btn_div">
						<img src="<?php echo ($static_path); ?>images/ll06.png" width="161" height="49"/>
					</div>
					<img src="<?php echo ($static_path); ?>images/l06.png" width="47" height="49" class="shows"/>
				</a>
			</li>
		</ul>
	</div><?php endif; ?>
<!--leftsead end-->
	</body>
</html>
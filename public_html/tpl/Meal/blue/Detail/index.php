<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>{pigcms{$store.name}网上{pigcms{$config.meal_alias_name}_{pigcms{$store.name}电话|{pigcms{$store.name}外卖|{pigcms{$store.name}菜单 - {pigcms{$config.site_name}</title>
<meta name="keywords" content="{pigcms{$store.name}网上{pigcms{$config.meal_alias_name},{pigcms{$store.name}电话,{pigcms{$store.name}外卖,{pigcms{$store.name}菜单,{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css" rel="stylesheet">
<link href="{pigcms{$static_path}css/shop.css" type="text/css" rel="stylesheet">
<link href="{pigcms{$static_path}css/kuaisonWM.css" type="text/css" rel="stylesheet">
<link href="{pigcms{$static_path}css/header.css" type="text/css" rel="stylesheet">
<link href="{pigcms{$static_path}css/shop_header.css" type="text/css" rel="stylesheet">
<link href="{pigcms{$static_path}css/ydyfx.css" type="text/css" rel="stylesheet">
<link href="{pigcms{$static_path}css/a.css" type="text/css" rel="stylesheet">
<link href="{pigcms{$static_path}css/meal_detail.css" type="text/css" rel="stylesheet">
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
<script src="{pigcms{$static_public}js/layer/layer.js"></script>
<script type="text/javascript">var  meal_alias_name = "{pigcms{$config.meal_alias_name}";</script>
<script src="{pigcms{$static_path}js/common.js"></script>
<script src="{pigcms{$static_path}js/requestAnimationFrame.js"></script>
<script src="{pigcms{$static_path}js/fly.js"></script>
<script type="text/javascript">var store_id = '{pigcms{$store['store_id']}', get_reply_url="{pigcms{:U('Index/Reply/ajax_get_list',array('order_type'=>1,'parent_id'=>$store['store_id'],'store_count'=>1))}",default_avatar="{pigcms{$static_path}images/meal_default_avatar.png";</script>
<script src="{pigcms{$static_path}js/buy.js"></script>
<style type="text/css">

</style>
</head>
<body>
<header>
	<header class="header" style="padding-bottom:10px;"> 
		<div class="content">
			<div class="header_top">
				<div class="hot">
			        <div class="loginbar cf">
						<if condition="$now_select_city">
							<div class="span" style="font-size:16px;color:red;padding-right:3px;cursor:default;">{pigcms{$now_select_city.area_name}</div>
							<div class="span" style="padding-right:10px;">[<a href="{pigcms{:UU('Index/Changecity/index')}">切换城市</a>]</div>
							<div class="span" style="padding-right:10px;">|</div>
						</if>
						<if condition="empty($user_session)">
							<div class="login"><a href="{pigcms{:U('Index/Login/index')}"> 登录 </a></div>
							<div class="regist"><a href="{pigcms{:U('Index/Login/reg')}">注册 </a></div>
						<else/>
							<p class="user-info__name growth-info growth-info--nav">
								<span>
									<a rel="nofollow" href="{pigcms{:U('User/Index/index')}" class="username">{pigcms{$user_session.nickname}</a>
								</span>
								<a class="user-info__logout" href="{pigcms{:U('Index/Login/logout')}">退出</a>
							</p>
						</if>
						<div class="span">|</div>
						<div class="weixin cf">
							<div class="weixin_txt"><a href="{pigcms{$config.site_url}/topic/weixin.html"> 微信版</a></div>
							<div class="weixin_icon"><p><span>|</span><a href="{pigcms{$config.site_url}/topic/weixin.html">访问微信版</a></p><img src="{pigcms{$config.wechat_qrcode}"/></div>
						</div>
			        </div>
			        <div class="list">
						<ul class="cf">
							<li>
								<div class="li_txt"><a href="{pigcms{:U('User/Index/index')}">我的订单</a></div>
								<div class="span">|</div>
							</li>
							<li class="li_txt_info cf">
								<div class="li_txt_info_txt"><a href="{pigcms{:U('User/Index/index')}">我的信息</a></div>
								<div class="li_txt_info_ul">
									<ul class="cf">
										<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:U('User/Index/index')}">我的订单</a></li>
										<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:U('User/Rates/index')}">我的评价</a></li>
										<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:U('User/Collect/index')}">我的收藏</a></li>
										<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:U('User/Point/index')}">我的{pigcms{$config['score_name']}</a></li>
										<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:U('User/Credit/index')}">帐户余额</a></li>
										<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{:U('User/Adress/index')}">收货地址</a></li>
									</ul>
								</div>
								<div class="span">|</div>
							</li>
							<li class="li_liulan">
								<div class="li_liulan_txt"><a>最近浏览</a></div>	 
								<div class="history" id="J-my-history-menu"></div> 
								<div class="span">|</div>
							</li>
							<li class="li_shop">
								<div class="li_shop_txt"><a>我是商家</a></div>
								<ul class="li_txt_info_ul cf">
									<li><a class="dropdown-menu__item first" rel="nofollow" href="{pigcms{$config.site_url}/merchant.php">商家中心</a></li>
									<li><a class="dropdown-menu__item" rel="nofollow" href="{pigcms{$config.site_url}/merchant.php">我想合作</a></li>
								</ul>
							</li>
						</ul>
			        </div>
				</div>
			</div>
			<div style="clear:both"></div>
		</div>
		<div class="nav">
			<div class="logo">
			<a href="{pigcms{$config.site_url}" title="{pigcms{$config.site_name}">
				<img src="{pigcms{$config.site_logo}" />
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
			</div>
			<div class="menu">
				<div class="ment_left">
					<div class="ment_left_img"><img src="{pigcms{$static_path}images/dianpu_03.png"/></div>
					<a href="{pigcms{$config.site_url}"><div class="ment_left_txt">主页</div></a>
				</div>
				<div class="hr">|</div>
				<div class="ment_left" style="margin-left:15px;">
					<div class="ment_left_img"><img src="{pigcms{$static_path}images/dianpu_05.png"></div>
					<a href="{pigcms{:U('User/Index/meal_list')}"><div class="ment_left_txt">我的订单</div></a>
				</div>
			</div>
			<div style="clear:both"></div>
		</div>
		<div style="clear:both"></div>
 	</header>
  	<div class="shopping-cart clearfix" data-status="1" data-poiname="{pigcms{$store.name}" data-poiid="{pigcms{$store.store_id}">
		<form method="post" action="/meal/order/{pigcms{$store['store_id']}.html" id="shoppingCartForm">
			<div class="order-list">
				<div class="title cf">
					<span class="fl dishes">商品<a href="javascript:;" class="clear-cart">[清空]</a></span>
					<span class="fl">份数</span>
					<span class="fl ti-price">价格</span>
				</div>
				<ul class="clearfix">
				</ul>
				<div class="other-charge hidden">
					<div class="clearfix packing-cost hidden">
						<span class="fl">包装盒</span>
						<span class="fr boxtotalprice">￥0</span>
					</div>
					<div class="clearfix delivery-cost">
						<span class="fl">配送费</span>
						<span class="fr shippingfee">￥0</span>
					</div>
				</div>
				<div class="privilege hidden"></div>
				<div class="total">共<span class="totalnumber">0</span>份，总计<span class="bill">￥0</span></div>
			</div>
			   
			<div class="footer clearfix">
				<div class="logo fl" id="i-shopping-cart"></div>
				<div class="brief-order fl">
					<span class="count"></span>
					<span class="tprice"></span>
				</div>
				<div class="fr">
					<a class="ready-pay borderradius-2" href="javascript:;">还是空的<!--还差<span data-left="20" class="margintominprice">20</span>元起送--></a>
					<input class="go-pay borderradius-2" type="submit" value="去下单">
					<input type="hidden" value="" class="order-data" name="shop_cart" id="shop_cart">
				</div>
			</div>
		</form>
	</div>
	<div class="w-1200 cf">
		<div class="grid_subHead clearfix">
			<div class="col_main">
				<div class="col_sub">
					<div class="shop_logo"><img src="{pigcms{$store['images'][0]}"></div>
				</div>
				<div class="main_wrap cf">
					<div class="mian_wrap_shop">
						<div class="shop_name">{pigcms{$store['name']}</div>
						<div class="top_shop_qrcode">微信访问<img src="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_qrcode&type=meal&id={pigcms{$store['store_id']}" /></div>
					</div>
					<div class="main_wrap_left">
						<div class="appraise_title cf">
							<div class="appraise_icon"><div><span style="width:{pigcms{$store['score_mean']/5*100}%"></span></div></div>
							<em>{pigcms{$store['score_mean']} 分</em>
						</div>
	 					<p class="shop_state">营业时间：{pigcms{$store['office_time']}<if condition="$store['state']"><span class="inner state_1" id="state_node">营业中</span><else /><span class="inner state_3" id="state_node">已打烊，还可以预订</span></if></p>
						<p class="shop_address">地址：{pigcms{$store['adress']}</p>
					</div>
					<!--div class="main_wrap_right">
						<ul class="songcan_data clearfix">
							<li class="songda">
								<strong><em>{pigcms{$store['send_time']}分钟</em></strong>
								<span>送达时间</span>
							</li>
							<li class="renjun">
								<strong><em>{pigcms{:floatval($store['basic_price'])}元</em></strong>
								<span>起送价</span>
							</li>
							<li class="peison">
								<strong><em class="psfee_">{pigcms{:floatval($store['delivery_fee'])}元</em></strong>
								<span>配送费</span>
							</li>
						</ul>
					</div-->
				</div>
			</div>
		</div>
		<!--div class="announcement po_re" id="announcement">
			<div class="announcement_left cf">
				<div class="clearfix" id="at_inner"> <span class="tit"><img src="{pigcms{$static_path}images/dianpu_31.png"></span>
					<div class="inner" style="display:block;width:719px;"><if condition="$store['store_notice'] neq '' AND $store['store_notice'] neq ' '">{pigcms{$store['store_notice']}<else />本店暂无公告</if></div>
					<div class="inner" style="display:none;">支持开发票，开票金额100元起。请在下单时填好发票抬头</div>
					<div class="inner" style="display:none;">本店招聘聘外卖员</div>
				</div>
			</div>
			<div class="ft">
				<if condition="$store['zeng']">			
				<div class="display11"><span class="i_zeng"></span> {pigcms{$store['zeng']}</div>
				<div style="clear:both"></div>
				</if>
				<if condition="$store['full_money'] neq 0.00 AND $store['minus_money'] neq 0.00">
				<div class="display11"><span class="i_jian"></span> 支持立减优惠，每单满{pigcms{$store['full_money']}元减{pigcms{$store['minus_money']}元</div>
				<div style="clear:both"></div>
				</if>
				<if condition="$store['song']">
				<div class="display11"><span class="i_first"></span> {pigcms{$store['song']}</div>
				</if>
			</div>
			<div style="clear:both"></div>
		</div-->
	</div>
</header>
<div class="body"> 
	<article class="shop_list cf">
		<div class="tabright">
			<div class="notice_title">店铺公告</div>
			<div class="notice_con"><if condition="$store['store_notice'] neq '' AND $store['store_notice'] neq ' '">{pigcms{$store['store_notice']}<else />本店暂无公告</if></div>
			<div class="notice_discount">
				<if condition="$store['zeng']">			
					<div class="display11 zeng">{pigcms{$store['zeng']}</div>
				</if>
				<if condition="$store['full_money'] neq '0.00' AND $store['minus_money'] neq '0.00'">
					<div class="display11 jian">每单满{pigcms{:floatval($store['full_money'])}元减{pigcms{:floatval($store['minus_money'])}元</div>
				</if>
				<if condition="$store['song']">
					<div class="display11 song">{pigcms{$store['song']}</div>
				</if>
			</div>
		</div>
		<div class="tab1" id="tab1">
			<div class="menu" style="color: #4c4c4c;">
				<ul class="cf">
					<li class="off tab">商品列表</li>
					<li class="tab">网友点评<if condition="$store['reply_count']"><span>({pigcms{$store['reply_count']})</span></if></li>
					<li class="merchantWeb"><a href="{pigcms{$config.site_url}/merindex/{pigcms{$store.mer_id}.html" target="_blank">商家网站</a></li>
				</ul>
				<div class="btmline"></div>
			</div>
			<div class="menudiv">
				<div id="con_one_1">
					<section>
						<div class="content">
							<div class="bgk">
								<div id="prolist">
									<div class="can_cat cf">
										<div class="bd">
											<ul class="clearfix">
												<volist name="sorts" id="sort">
												<if condition="$sort['meals']['pic'] OR $sort['meals']['txt']">
												<li>
													<a href="#sort_{pigcms{$sort['sort_id']}" data-scrolld="plist_{pigcms{$sort['sort_id']}">{pigcms{$sort['sort_name']}</a>
												</li>
												</if>
												</volist>
											</ul>
										</div>
									</div>
									<volist name="sorts" id="vo" key="y">
										<if condition="$vo['meals']['list']">
										<div class="module_s module_s_open">
											<div class="hd">
												<div class="tit">{pigcms{$vo.sort_name}</div>
												<a href="javascript:;" class="s module_s_open_btn" name="sort_{pigcms{$vo['sort_id']}">收起</a>
											</div>
											<div class="bd">
												<ul class="img clearfix">
													<volist name="vo['meals']['list']" id="meal" key="j">
														<li class="item_{pigcms{$meal['meal_id']} buygoods <if condition="$j%3 eq 0 || $j eq count($vo['meals']['list'])">last-br</if> <if condition="$j gt 3">no-bt</if>" id="{pigcms{$meal['meal_id']}" data-title="{pigcms{$meal['des']}">
															<a href="javascript:;" class="link" name="meal_{pigcms{$meal['meal_id']}">
																<img class="lazy_img" src="http://hf.pigcms.com/static/images/blank.gif" data-original="<if condition="$meal['image']">{pigcms{$meal['image']}<else />../static/images/nopic.jpg</if>" />
																<div class="product_info">
																	<span class="tit">{pigcms{$meal['name']}</span>
																	<span class="price">¥{pigcms{:floatval($meal['price'])}/{pigcms{$meal['unit']}</span>
																	<span class="add_btn"></span>
																</div>
																<span class="buycar" data-id="{pigcms{$meal['meal_id']}" data-name="{pigcms{$meal['name']}" data-price="{pigcms{:floatval($meal['price'])}" data-mincount="1">来一{pigcms{$meal['unit']}</span>
																<span class="buycar2" style="display: none;">已点</span>
															</a>
														</li>
													</volist>
												</ul>
											</div>
										</div>
										</if>
									</volist>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div id="con_one_2" style="display:none;">
					<div class="content_left">
						<div class="appraise_list cf">
							<div class="appraise_li">
								<div class="zzsc">
									<div class="tab">
										<div class="tab_title rate-filter__item">
											<a href="javascript:;" class="on" data-tab="all">全部</a>
											<a href="javascript:;" data-tab="high">好评</a>
											<a href="javascript:;" data-tab="mid">中评</a>
											<a href="javascript:;" data-tab="low">差评</a>
											<a href="javascript:;" data-tab="withpic">有图</a>
										</div>
										<div class="tab_form">
											<div class="form_sec">
												<select name="时间排序" class="select J-filter-ordertype">
													<option value="default">默认排序</option>
													<option value="time">时间排序</option>
													<option value="score">好评排序</option>
												</select>
											</div>
										</div>
									</div>
									<div class="content ratelist-content">
										<div class="appraise_li-list">
											<dl class="J-rate-list"></dl>
										</div>
										<div class="page J-rate-paginator cf"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</article>
</div>
<include file="Public:footer"/>
</body>
</html>

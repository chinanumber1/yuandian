<!doctype html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <title>{pigcms{$_COOKIE.around_adress}附近{pigcms{$config.group_alias_name} | {pigcms{$config.site_name}</title>
	<meta name="keywords" content="{pigcms{$now_category.cat_name},{pigcms{$config.seo_keywords}" />
	<meta name="description" content="{pigcms{$config.seo_description}" />
	<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
	<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/list.css"/>
	
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
	<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
		<script type="text/javascript">
	var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	</script>
	<script src="{pigcms{$static_path}js/common.js"></script>
	<script src="{pigcms{$static_path}js/list.js"></script>
	<style>
		.category_list{
			float:none;
			width:auto;
		}
		#filter {
			margin-bottom: 10px;
		}
		#content {
			float: left;
			width: 720px;
			_display: inline;
			padding: 0;
			width: 950px;
		}
		#content .mainbox {
			background: #FFF;
			border: 1px solid #e8e8e8;
			padding: 25px;
			clear: both;
			zoom: 1;
			height: auto!important;
			min-height: 400px;
			padding: 14px 20px;
			border: 1px solid #D4D4D4;
		}
		.mainbox .list-head {
  margin-bottom: 10px;
  padding-bottom: 10px;
  border-bottom: 1px solid #DCDCDC;
}
.mainbox .list-head h2 {
  float: left;
  border: none;
  padding: 0;
  margin: 0;
  font-size: 20px;
  color: #333;
}
.deals-list .deal {
  float: left;
  padding: 30px 18px 20px 0;
  border-bottom: 1px dotted #E5E5E5;
  height: 200px;
  width: 208px;
  font-size: 12px;
}
.deals-list .deal .pic {
  display: block;
  margin: 0 auto 6px;
}
.deals-list .deal .pic img {
  vertical-align: top;
}
.deals-list h4 {
  height: 45px;
  overflow: hidden;
}
.deals-list h4 a {
  color: #666;
  font-weight: 400;
  height: 38px;
  overflow: hidden;
  display: block;
}
.deals-list .deal .info {
	float:none;
	width:auto;
  font-family: Helvetica,arial,sans-serif;
  color: #666;
}

.deals-list .deal .info .total {
  float: right;
  margin-top: 3px;
  color: #666;
  font-weight:normal;
}
.deals-list .deal .info .total label {
  color: #EF5438;
}
.deals-list .deal .info strong {
  margin-right: 5px;
  font-size: 16px;
}
.price {
  font-family: arial, sans-serif;
  color: #f76120;
}
.deals-list .deal .info .delete {
  text-decoration: line-through;
}
.deals-list .last-row {
  border-bottom: none;
  padding-bottom: 6px;
}
#sidebar {
  float: right;
  width: 240px;
  _display: inline;
}
.side-extension {
  margin-bottom: 16px;
  padding: 10px 20px;
  border: 1px solid #e8e8e8;
  background-color: #FFF;
  font-size: 12px;
}
.side-extension__item {
  display: block;
  border-bottom: 1px dotted #DDD;
  margin-bottom: 20px;
  padding-bottom: 13px;
}
.side-extension__item--last {
  border-bottom: 0;
  margin-bottom: 0;
}
.side-extension--history .side-extension__item {
  width: 198px;
}
.side-extension h3 {
  padding: 3px 0 13px;
  font-size: 16px;
}
.side-extension--history .clear-history {
  float: right;
  font-weight: 400;
  font-size: 12px;
  line-height: 24px;
}
.side-extension--history .no-history {
  height: 60px;
  width: 198px;
  line-height: 60px;
  color: #666;
  text-align: center;
}
.side-extension--history .history-list__item {
  padding: 18px 0;
  font-size: 12px;
  color: #666;
  zoom: 1;
  border-bottom: 1px dotted #DDD;
}
.side-extension--history .history-list__item a {
  color: #666;
}
.side-extension--history .history-list__item img {
  float: left;
  margin: 2px 10px 0 0;
}
.side-extension--history .history-list h5 {
  height: 32px;
  line-height: 16px;
  overflow: hidden;
  font-size: 12px;
  font-weight: 400;
}
.side-extension--history .history-list__item a {
  color: #666;
}
.side-extension--history .history-list__item p {
  padding-top: 4px;
    line-height: 18px;
}
.side-extension--history .history-list__item .price {
  padding-right: 5px;
  font: 700 12px/12px arial,sans-serif;
  color: #F76120;
}
.side-extension--history .history-list__item .old-price {
  color: #999;
}
.extra-inner div.around-distance{
	float:right;
	font-size:12px;
}
.around-distance em {
  padding: 0 3px;
  font-weight: 700;
  color: #fd6b44;
    font-style: normal;
}

	</style>
</head>
<body id="search" class="pg-search">
<include file="Public:header_top"/>
	<div id="doc" class="body bg-for-new-index">
		<article>
			<div class="menu cf">
				<div class="menu_left hide">
					<div class="menu_left_top">全部分类</div>
					<div class="list">
						<ul>
							<volist name="all_category_list" id="vo" key="k">
								<li>
									<div class="li_top cf">
										<if condition="$vo['cat_pic']"><div class="icon"><img src="{pigcms{$vo.cat_pic}" /></div></if>
										<div class="li_txt"><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></div>
									</div>
									<if condition="$vo['cat_count'] gt 1">
										<div class="li_bottom">
											<volist name="vo['category_list']" id="voo" offset="0" length="3" key="j">
												<span><a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a></span>
											</volist>
										</div>
										<div class="list_txt">
											<p><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></p>
											<volist name="vo['category_list']" id="voo" key="j">
												<a class="<if condition="$voo['is_hot']">bribe</if>" href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a>
											</volist>
										</div>
									</if>
								</li>
							</volist>
						</ul>
					</div>
				</div>
				<div class="menu_right cf">
					<div class="menu_right_top">
						<ul>
							<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
								<li class="ctur">
									<a href="{pigcms{$vo.url}">{pigcms{$vo.name}</a>
								</li>
							</pigcms:slider>
						</ul>
					</div>
				</div>
			</div>
		</article>
		<div class="bdw" id="bdw">
			<div class="cf" id="bd">
				<php>if($group_list){</php>
					<div id="filter">
						<div class="filter-main">
							<div id="around-geo-area" class="around-geo-strip" style="border-top:none;">
								<p><i class="F-glob F-glob-position icon"></i>{pigcms{$_COOKIE.around_adress}
									<a href="{pigcms{:U('Around/index')}" class="modify">修改</a>
								</p>
							</div>
						</div>
						<div class="sort">
							<ul class="cf">
								<li><a href="{pigcms{$default_sort_url}" <if condition="$_GET['order'] eq '' || $_GET['order'] eq 'all'">class="selected"</if>>默认排序</a></li>
								<li>
									<a href="{pigcms{$hot_sort_url}"<if condition="$_GET['order'] eq 'hot'">class="selected"</if>>
										<div class="li_txt">销量</div>
										<div class="li_img"></div>
									</a>
								</li>
								<li>
									<a <if condition="$_GET['order'] eq 'price-asc'">href="{pigcms{$price_desc_sort_url}" class="selected time-asc"<elseif condition="$_GET['order'] eq 'price-desc'"/>href="{pigcms{$price_asc_sort_url}" class="selected"<else/>href="{pigcms{$price_desc_sort_url}"  class="time-asc"</if>>
										<div class="li_txt">价格</div>
										<div class="li_img"></div>
									</a>
								</li>
								<li>
									<a href="{pigcms{$rating_sort_url}" <if condition="$_GET['order'] eq 'rating'">class="selected"</if>>
										<div class="li_txt">好评</div>
										<div class="li_img"></div>
									</a>
								</li>
								<li>
									<a href="{pigcms{$time_sort_url}" <if condition="$_GET['order'] eq 'time'">class="selected"</if>>
										<div class="li_txt">发布时间</div>
										<div class="li_img"></div>
									</a>
								</li>
							</ul>
						</div>
					</div>
				<php>}else{</php>
					<div id="filter">
						<div class="filter-main">
							<div id="around-geo-area" class="around-geo-strip" style="border-top:none;">
								<p><i class="F-glob F-glob-position icon"></i>{pigcms{$_COOKIE.around_adress}
									<a href="{pigcms{:U('Around/index')}" class="modify">修改</a>
								</p>
							</div>
						</div>
					</div>
					<div class="no-result">
						<img class="no-result-img" width="80" height="54" src="{pigcms{$static_path}images/search-no-result.png" alt="搜索无结果">
						<span class="no-result-content">未找到附近的相关{pigcms{$config.group_alias_name}</span>
					</div>
					<div class="site-sidebar" id="sidebar">
						<div class="side-extension side-extension--history">
							<div class="side-extension__item side-extension__item--last log-mod-viewed">
								<h3><a href="javascript:;" class="clear-history J-clear">清空</a>最近浏览</h3>
							</div>
							<ul class="history-list J-history-list"></ul>
						</div>
					</div>
					<div id="content" class="cf mall">
						<div class="mainbox cf">
							<div class="list-head cf"><h2>{pigcms{$config.group_alias_name}推荐</h2></div>
							<ul class="deals-list cf log-mod-viewed">
								<volist name="index_sort_group_list" id="vo">
									<li class="deal <if condition="$i gt 4">last-row<else/>first</if> <if condition="$i%4 eq 0">last-deal</if>">
										<a href="{pigcms{$vo.url}" class="pic" target="_blank">
											<img width="208" height="125" alt="【{pigcms{$vo.prefix_title}】{pigcms{$vo.merchant_name}：{pigcms{$vo.group_name}" title="【{pigcms{$vo.prefix_title}】{pigcms{$vo.merchant_name}：{pigcms{$vo.group_name}" src="{pigcms{$vo.list_pic}"/>
										</a>
										<h4><a href="{pigcms{$vo.url}" title="【{pigcms{$vo.prefix_title}】{pigcms{$vo.merchant_name}：{pigcms{$vo.group_name}" target="_blank">【{pigcms{$vo.prefix_title}】{pigcms{$vo.merchant_name}：{pigcms{$vo.group_name}</a></h4>
										<div class="info">
											<if condition="$vo['sale_count']+$vo['virtual_num']"><span class="total">已售<label>{pigcms{$vo['sale_count']+$vo['virtual_num']}</label></span></if>
											<strong class="price">¥{pigcms{$vo.price}</strong>
											门店价<label class="delete">{pigcms{$vo.old_price}</label>
										</div>
									</li>
								</volist>
							</ul>
						</div>
					</div>
				<php>}</php>
				<article class="category cf" id="f1">
					<div class="category_list">
						<if condition="$group_list">
							<ul>
								<volist name="group_list" id="vo">
									<li <if condition="$i%4 eq 0  || $i eq count($group_list)">class="last--even"</if>>
										<div class="category_list_img">
											<a href="{pigcms{$vo.url}" target="_blank"  class="imgBox">
												<img alt="{pigcms{$vo.s_name}" class="deal_img lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{$vo.list_pic}"/>
												<div class="bmbox">
													<div class="bmbox_title"> 该商家有 <span>{pigcms{$vo.fans_count}</span> 个粉丝</div>
													<div class="bmbox_list">
														<div class="bmbox_list_img"><img class="lazy_img" src="{pigcms{$static_public}images/blank.gif" data-original="{pigcms{:U('Index/Recognition/see_qrcode',array('type'=>'group','id'=>$vo['group_id']))}" /></div>
														<div class="bmbox_list_li">
															<ul class="cf">
																<li class="open_windows" data-url="{pigcms{$config.site_url}/merindex/{pigcms{$vo.mer_id}.html">商家</li>
																<li class="open_windows" data-url="{pigcms{$config.site_url}/meractivity/{pigcms{$vo.mer_id}.html">{pigcms{$config.group_alias_name}</li>
																<li class="open_windows" data-url="{pigcms{$config.site_url}/mergoods/{pigcms{$vo.mer_id}.html">{pigcms{$config.meal_alias_name}</li>
																<li class="open_windows" data-url="{pigcms{$config.site_url}/mermap/{pigcms{$vo.mer_id}.html">地图</li>
															</ul>
														</div>
													</div>
													<div class="bmbox_tip">微信扫码 手机查看</div>
												</div>
											</a>
											<div class="datal">
												<a href="{pigcms{$vo.url}" target="_blank">
													<div class="category_list_title">【{pigcms{$vo.prefix_title}】{pigcms{$vo.merchant_name}</div>
													<div class="category_list_description">{pigcms{$vo.group_name}</div>
												</a>
												<div class="deal-tile__detail cf">
													<span class="price">&yen;<strong>{pigcms{$vo.price}</strong> </span>
													<span>门店价 &yen;{pigcms{$vo.old_price}</span>	
													<if condition="$vo['wx_cheap']">												
														<div class="cheap">微信购买立减￥{pigcms{$vo.wx_cheap}</div>												
													</if>
												</div>
											</div>
											<div class="extra-inner cf">
												<div class="sales">已售<strong class="num">{pigcms{$vo['sale_count']+$vo['virtual_num']}</strong></div >
												<div class="around-distance">距离您<em>约{pigcms{:round($group_around_range[$vo['group_id']]/1000,1)}公里</em></div>
											</div>
										</div>
									</li>
								</volist>
							</ul>
						</if>
					</div>
				</article>
				{pigcms{$pagebar}
			</div>
		</div>
		<include file="Public:footer"/>
	</body>
</html>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$config.meal_alias_name}列表</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/css_whir.css"/>
<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
<script>var noAnimate = true;</script>
<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/swiper.min.js" charset="utf-8"></script>
<script type="text/javascript">
	var location_url = "{pigcms{:U('Foodshop/ajaxList')}", back_url = "{pigcms{:U('Foodshop/index')}";
	var now_cat_url="<if condition="!empty($now_cat_url)">{pigcms{$now_cat_url}<else/>-1</if>";
	var now_area_url="<if condition="!empty($now_area_url) && $all_area_list">{pigcms{$now_area_url}<else/>-1</if>";
	var now_sort_id="<if condition="!empty($now_sort_array)">{pigcms{$now_sort_array.sort_id}<else/>defaults</if>";
	var now_queue="<if condition="!empty($now_queue)">{pigcms{$now_queue}<else/>-1</if>";
	<if condition="$long_lat">var user_long = "{pigcms{$long_lat.long}",user_lat = "{pigcms{$long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if>
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/dropdown.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/foodshopsearch.js" charset="utf-8"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->

</head>
<body>
<div id="pageShopSearchHeader" class="searchHeader">
	<div id="pageShopSearchBackBtn" class="searhBackBtn"></div>
	<div id="pageShopSearchBox" class="searchBox">
		<div class="searchIco"></div>
		<input type="text" id="pageShopSearchTxt" class="searchTxt" placeholder="请输入店铺名称" autocomplete="off"/>
		<div class="delIco" id="pageShopSearchDel"><div></div></div>
	</div>
	<div id="pageShopSearchBtn" class="searchBtn">搜索</div>
</div>

<div class="he50"></div>
	<section class="navBox" >
		<div class="navBox_list">
		</div>
	</section>
</body>
<script id="storeListBoxTpl" type="text/html">
				{{# for(var i = 0, len = d.store_list.length; i < len; i++){ }}
					{{# if(d.store_list[i].state == 0){ }}
					<dl class="on">
						<dt data-url="{{ d.store_list[i].url }}">
							<div class="navLtop clr" >
								{{# if(d.store_list[i].isverify == 1){ }}
									<img src="./static/images/rec_2.png" style="width:18px; height:20px;margin-top:1px; margin-right:5px;float:left">
								{{# } }}
								<h2 class="fl">{{ d.store_list[i].name }}</h2>
								<div class="navLtop_right fr">
									
									{{# if(d.store_list[i].is_book == 1){ }}
									<span class="ln">订</span>
									{{# } }}
									{{# if(d.store_list[i].is_queue == 1){ }}
									<span class="zi">排</span>
									{{# } }}
									{{# if(d.store_list[i].is_takeout == 1){ }}
									<span class="lv">外</span>
									{{# } }}
								</div>
							</div>
							<div class="navLBt clr">
								<ul class="navLBt_ul fl show_number clr">  
									<li>
										<div class="atar_Show">
											<p tip="{{ d.store_list[i].score_mean }}" ></p>
										</div>
									</li>
								</ul>
								<div class="Notopen fl">未营业</div>
								<div class="distance fr">{{ d.store_list[i].range }}</div>
							</div>
						</dt>
					</dl>
					{{# } else { }}
					<dl>
						<dt data-url="{{ d.store_list[i].url }}">
							<div class="navLtop clr" >
								{{# if(d.store_list[i].isverify == 1){ }}
									<img src="./static/images/rec_2.png" style="width:18px; height:20px;margin-top:1px; margin-right:5px;float:left">
								{{# } }}
								<h2 class="fl">{{ d.store_list[i].name }}</h2>
								<div class="navLtop_right fr">
									{{# if(d.store_list[i].is_book == 1){ }}
									<span class="ln">订</span>
									{{# } }}
									{{# if(d.store_list[i].is_queue == 1){ }}
									<span class="zi">排</span>
									{{# } }}
									{{# if(d.store_list[i].is_takeout == 1){ }}
									<span class="lv">外</span>
									{{# } }}
								</div>
							</div>
							<div class="navLBt clr">
								<ul class="navLBt_ul fl show_number clr">  
									<li>
										<div class="atar_Show">
											<p tip="{{ d.store_list[i].score_mean }}" ></p>
										</div>
									</li>
								</ul>
								<div class="distance fr">{{ d.store_list[i].range }}</div>
							</div>
						</dt>
						{{# if(d.store_list[i].pay_in_store == 1 && d.store_list[i].discount_txt != ''){ }}
						<dd class="navlink clr">
							<a href="{{ d.store_list[i].store_pay }}">
								<span class="link_Pay">到店付</span>
								{{# if(d.store_list[i].discount_txt.discount_type == 1){ }}
									<span>{{ d.store_list[i].discount_txt.discount_percent }}折</span>
								{{# } else { }}
									<span>每满{{ d.store_list[i].discount_txt.condition_price }}减{{ d.store_list[i].discount_txt.minus_price }}元</span>
								{{# } }}
								<span class="link_jt fr"></span>
							</a>
						</dd>
						{{# } }}
						{{# for(var j = 0, jlen = d.store_list[i].group_list.length; j < jlen; j++){ }}
						<dd class="Menulink clr">
							<a href="{{ d.store_list[i].group_list[j].url }}">
								<div class="Menulink_img fl">
									<img class="on" src="{{ d.store_list[i].group_list[j].list_pic }}">
										{{# if(d.store_list[i].group_list[j].pin_num>0){ }}<span class="PinGroup"></span>{{# }else{ }}<span class="MenuGroup"></span>{{# } }}
								</div>
								<div class="Menulink_right">
									<h2>{{ d.store_list[i].group_list[j].name }}</h2>
									<div class="MenuPrice">
										<span class="PriceF"><i>￥</i><em>{{ d.store_list[i].group_list[j].price }}</em></span>
										<span class="PriceT">门市价:￥{{ d.store_list[i].group_list[j].old_price }}</span>
										<span class="PriceS">已售{{ parseInt(d.store_list[i].group_list[j].sale_count) + parseInt(d.store_list[i].group_list[j].virtual_num) }}</span>
									</div>
								</div>
							</a>
						</dd>
						{{# } }}
					</dl>
					{{# } }}
				{{# } }}
				</script>

</html> 
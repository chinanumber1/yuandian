<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
		<title>{pigcms{$config.group_alias_name}首页</title>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		 <link href="{pigcms{$static_path}css/groupBuy_index.css" rel="stylesheet"/>
		 	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/list.css?210"/>
		<script src="{pigcms{:C('JQUERY_FILE_190')}"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/idangerous.swiper.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?444"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/grouplist.js?210" charset="utf-8"></script>
		<script>
			var location_url = "{pigcms{:U('Group/ajaxList')}";
			var now_cat_url="-1";
			var now_area_url="-1";
			var now_sort_id="{pigcms{$config.group_list_default_type}";
			<if condition="$long_lat">var user_long = "{pigcms{$long_lat.long}",user_lat = "{pigcms{$long_lat.lat}";<else/>var user_long = '0',user_lat  = '0';</if>
		</script>
	</head>
	<body>
		<header>
			<i></i>
			<input type="text" name="" id="search" value="" placeholder="请输入商家名、品类或商圈" />
		</header>
	<div id="container">
		<div id="scroller">
		<div id="pullDown" class="">
					<span class="pullDownIcon"></span><span class="pullDownLabel">下拉可以刷新</span>
				</div>
		<!--第一轮播图-->
		<section id="listBanner" class="banner" style="height: 154px;">
			<div class="swiper-container swiper-container1" style="cursor: -webkit-grab;">
				<div class="swiper-wrapper">
				
					<volist name="group_adver" id="vo">
					 
						<div class="swiper-slide swiper-slide-duplicate">
							<a class="link-url" data-url="{pigcms{$vo.url}">
								<img src="{pigcms{$vo.pic}" style="width:100%;height: 100%;"/>
							</a>
						</div>
			   
					</volist>
					
				</div>
				<div class="swiper-pagination swiper-pagination1">
					
				</div>
			</div>
		</section>
		<!--轮播导航-->
		<if condition="$wap_group_slider  ">
	
		<section class="slider">
			<div class="swiper-container swiper-container2" style="height: 178px; cursor: -webkit-grab;">
				<div class="swiper-wrapper" >
				<volist name="wap_group_slider" id="vo">
				<div class="swiper-slide">
					<ul class="icon-list num10">
					
						<volist name="vo" id="slider">
							<li class="icon">
								<a href="{pigcms{$slider['url']}">
									<span class="icon-circle">
										<img src="{pigcms{$slider['pic']}">
									</span>
									<span class="icon-desc">{pigcms{$slider['name']}</span>
								</a>
							</li>
						</volist>
																
					</ul>				
				</div>
						</volist>
				</div>
				<div class="swiper-pagination swiper-pagination2">
					
				</div>
			</div>
										
		</section>
		</if>
		<!--5-->
		<php> if($config['group_main_page_show_ad']==1){</php>
		<if condition="abs($config['group_main_page_center_type']) eq 5">
			<section class="cityRun">
				<div class="cityItem clear <if condition="$config.group_main_page_show_type eq 0">acss</if>">
					<div class="item0 clear ft link-url" data-url="{pigcms{$slider_list[0]['url']}">
						<if condition="$config.group_main_page_show_type eq 1">
							<div class="itemLeft ft fire" style='width:100%'>
								<h3>{pigcms{$slider_list[0]['name']}</h3>
								<h4 class="lan">{pigcms{$slider_list[0]['sub_name']} </h4>
								
							</div>
							<div class="itemRight ft fire" style='width:100%'>
								<img src="./upload/slider/{pigcms{$slider_list[0]['pic']}" style='width:100%;height:100%'/>
							</div>
						<else />
						    <div class="itemRight ft fire" style='width:100%'>
						    	<img src="./upload/slider/{pigcms{$slider_list[0]['pic']}" style='width:100%;height:100%'/>
						    </div>
							
						</if>
					</div>
					<div class="item0 ft link-url" data-url="{pigcms{$slider_list[1]['url']}">
						<if condition="$config.group_main_page_show_type eq 1">
							<div class="itemLeft ft fire" style='width:100%'>
								<h3>{pigcms{$slider_list[1]['name']}</h3>
								<h4 class="lan">{pigcms{$slider_list[1]['sub_name']} </h4>
								
							</div>
							<div class="itemRight ft fire" style='width:100%'>
								<img src="./upload/slider/{pigcms{$slider_list[1]['pic']}" style='width:100%;height:100%'/>
							</div>
						<else />
						     <div class="itemRight ft fire" style='width:100%'>
						    	<img src="./upload/slider/{pigcms{$slider_list[1]['pic']}" style='width:100%;height:100%'/>
						    </div>
							
						</if>
					</div>
				</div>
				<div class="cityTheck" >
					<ul class="clear" style='display:flex;'>
						<li class="ft link-url" data-url="{pigcms{$slider_list[2]['url']}" style='padding:0px;flex:1'>
							<if condition="$config.group_main_page_show_type eq 1">
								<h3>{pigcms{$slider_list[2]['name']}</h3>
								<h4 class="c1">{pigcms{$slider_list[2]['sub_name']}</h4>
								<img src="./upload/slider/{pigcms{$slider_list[2]['pic']}" class='four ' style='width:100%'/ >
							<else />
								<img src="./upload/slider/{pigcms{$slider_list[2]['pic']}" class='four 'style='width:100%'/>
							</if>
						</li>
						<li class="ft link-url" data-url="{pigcms{$slider_list[3]['url']}" style='padding:0px;flex:1'>
							<if condition="$config.group_main_page_show_type eq 1">
								<h3>{pigcms{$slider_list[3]['name']}</h3>
								<h4 class="c1">{pigcms{$slider_list[3]['sub_name']}</h4>
								<img src="./upload/slider/{pigcms{$slider_list[3]['pic']}" class='four' style='width:100%'/>
							<else />
								<img src="./upload/slider/{pigcms{$slider_list[3]['pic']}" class='four' style='width:100%'/>
							</if>
						</li>
						<li class="ft link-url" data-url="{pigcms{$slider_list[4]['url']}" style='padding:0px;flex:1'>
							<if condition="$config.group_main_page_show_type eq 1">
								<h3>{pigcms{$slider_list[4]['name']}</h3>
								<h4 class="c1">{pigcms{$slider_list[4]['sub_name']}</h4>
								<img src="./upload/slider/{pigcms{$slider_list[4]['pic']}" class='four' style='width:100%'/>
							<else />
								<img src="./upload/slider/{pigcms{$slider_list[4]['pic']}" class='four' style='width:100%'/>
							</if>
						</li>
					</ul>
				</div>
			</section>
			<elseif condition="abs($config['group_main_page_center_type']) eq 3" />
		<!--3-->
		<section class="run clear <if condition="$config.group_main_page_show_type eq 0">adds</if>" style='display:flex;'>
			<div class="runItem ft acc link-url" data-url="{pigcms{$slider_list[0]['url']}" style='padding-top:0px;display:flex;flex-direction: column'>
				<if condition="$config.group_main_page_show_type eq 1">
					<h3 >{pigcms{$slider_list[0]['name']}</h3>
					<h4>{pigcms{$slider_list[0]['sub_name']} </h4>
					<img src="./upload/slider/{pigcms{$slider_list[0]['pic']}" style='width: 100%; flex:1;max-height:200px'/>
				<else />
					<img src="./upload/slider/{pigcms{$slider_list[0]['pic']}" style='flex:1;width: 100%;'/>
				</if>
			</div>
			<div class="runItem ft" style='display: flex; flex-direction: column;'>
				<div class="itemTop" style='flex:1;'>
					<ul class="clear link-url" data-url="{pigcms{$slider_list[1]['url']}">
						<if condition="$config.group_main_page_show_type eq 1">
							<li class="ft">
								<h3 class='hiddens'>{pigcms{$slider_list[1]['name']}</h3>
								<h4>{pigcms{$slider_list[1]['sub_name']} </h4>
									
							</li>
							<li class="ft shree">
								<img src="./upload/slider/{pigcms{$slider_list[1]['pic']}"/>
							</li>
						<else />
							<li class="ft shree">
								<img src="./upload/slider/{pigcms{$slider_list[1]['pic']}"/>
							</li>
						</if>
					</ul>
				</div>
				<div class="itemBottom" style='flex:1'>
					<ul class="clear link-url" data-url="{pigcms{$slider_list[2]['url']}">
						<if condition="$config.group_main_page_show_type eq 1">
							<li class="ft">
								<h3>{pigcms{$slider_list[2]['name']}</h3>
								<h4>{pigcms{$slider_list[2]['sub_name']} </h4>
									
							</li>
							<li class="ft">
								<img src="./upload/slider/{pigcms{$slider_list[2]['pic']}"/>
							</li>
						<else />
							<li class="ft">
								<img src="./upload/slider/{pigcms{$slider_list[2]['pic']}"/>
							</li>
						</if>
					</ul>
				</div>
			</div>
		</section>
			<elseif condition="abs($config['group_main_page_center_type']) eq 4" />
		<!--同城跑腿3-->
		<section class="run clear <if condition="$config.group_main_page_show_type eq 0">adds</if>">
			<div class="runItem ft add ">
				<div class="itemTop">
					<ul class="clear link-url" data-url="{pigcms{$slider_list[0]['url']}">
					<if condition="$config.group_main_page_show_type eq 1">
						<li class="ft shree">
						  <h3 >{pigcms{$slider_list[0]['name']}</h3>
						  <h4>{pigcms{$slider_list[0]['sub_name']} </h4>
						
					    </li>
					    <li class="ft shree">
						   <img src="./upload/slider/{pigcms{$slider_list[0]['pic']}"/>
					    </li>
					<else />
						<li class="ft shree">
							<img src="./upload/slider/{pigcms{$slider_list[0]['pic']}"/>
						</li>
					</if>
						
					</ul>
				</div>
				<div class="itemBottom">
					<ul class="clear link-url" data-url="{pigcms{$slider_list[1]['url']}">
						<if condition="$config.group_main_page_show_type eq 1">
						<li class="ft shree">
							<h3>{pigcms{$slider_list[0]['name']}</h3>
							<h4>{pigcms{$slider_list[0]['sub_name']} </h4>
						
						</li> 
						<li class="ft shree">
							<img src="./upload/slider/{pigcms{$slider_list[1]['pic']}"/>
						</li>
					<else />
						<li class="ft shree">
							<img src="./upload/slider/{pigcms{$slider_list[1]['pic']}"/>
						</li>
					</if>
					</ul>
				</div>
			</div>
		
			<div class="runItem ft">
				<div class="itemTop">
					<ul class="clear link-url" data-url="{pigcms{$slider_list[2]['url']}">
						<if condition="$config.group_main_page_show_type eq 1">
						<li class="ft shree">
							<h3>{pigcms{$slider_list[2]['name']}</h3>
							<h4>{pigcms{$slider_list[2]['sub_name']} </h4>
						</li>
					<li class="ft shree">
						<img src="./upload/slider/{pigcms{$slider_list[2]['pic']}"/>
					</li>
					<else />
						<li class="ft shree">
							<img src="./upload/slider/{pigcms{$slider_list[2]['pic']}"/>
						</li>
					</if>
					</ul>
				</div>
				<div class="itemBottom">
					<ul class="clear link-url" data-url="{pigcms{$slider_list[3]['url']}">
					<if condition="$config.group_main_page_show_type eq 1">
						<li class="ft shree">
							<h3>{pigcms{$slider_list[3]['name']}</h3>
							<h4>{pigcms{$slider_list[3]['sub_name']} </h4>
						
						</li>
					<li class="ft shree">
						<img src="./upload/slider/{pigcms{$slider_list[3]['pic']}"/>
					</li>
					<else />
						<li class="ft shree">
							<img src="./upload/slider/{pigcms{$slider_list[3]['pic']}"/>
						</li>
					</if>
					</ul>
				</div>
			</div>
		</section>
		</if>
							<php>}</php>
		<!--section class="groupBuy" >
			<p class="recommend">-为您推荐-</p>
				<ul class="groupList">
				<li class="clear">
					<img src="img/g1_53.png" alt="" class="ft" />
					<div class="listContent ft clear">
						<h4>托马斯儿童世界</h4>
						<h5>托马斯和他的朋友等你来玩</h5>
						<p>￥<span class="moeny">50</span> <i>微信再减3元</i>  <span class="rg">已售:33</span></p>
					</div>
				</li>
				<li class="clear">
					<img src="img/g1_56.png" alt="" class="ft" />
					<div class="listContent ft clear">
						<h4>托马斯儿童世界</h4>
						<h5>托马斯和他的朋友等你来玩</h5>
						<p>￥<span class="moeny">50</span> <i>微信再减3元</i>  <span class="rg">已售:33</span></p>
					</div>
				</li>
				<li class="clear hide">
					<img src="img/g1_58.png" alt="" class="ft" />
					<div class="listContent ft clear">
						<h4>托马斯儿童世界</h4>
						<h5>托马斯和他的朋友等你来玩</h5>
						<p>￥<span class="moeny">50</span> <i>微信再减3元</i>  <span class="rg">已售:33</span></p>
					</div>
				</li>
				<li class="more">其他1个团购</li>
			</ul>

		</section-->
		
				
				<p class="recommend">-为您推荐-</p>
				<script id="storeListBoxTpl" type="text/html">
					{{# for(var i = 0, len = d.store_list.length; i < len; i++){ }}
						<dd>
							<div class="brand link-url" data-url="{{ d.store_list[i].url }}">
								<div class="brandCon">{{ d.store_list[i].store_name }}<span class="location-right">{{ d.store_list[i].range_txt }}</span></div>
							</div>
							<ul class="goodList">
								{{# for(var j = 0, jlen = d.store_list[i].group_list.length; j < jlen; j++){ }}
									<li class="link-url" data-url="{{ d.store_list[i].group_list[j].url }}" {{# if(j > 1){ }}style="display:none;"{{# } }}>
										<div class="dealcard-img imgbox ">
											{{# if(d.store_list[i].group_list[j].pin_num > 0){ }}<div class="pin_style"></div>{{# } }}
											<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d.store_list[i].group_list[j].list_pic) }}" alt="{{ d.store_list[i].group_list[j].group_name }}"/>
											{{# if(d.store_list[i].group_list[j].is_start == 0){ }}<i class="store_state">未开始</i>{{# } }}
										</div>
										<div class="dealcard-block-right">
											<div class="title">{{ d.store_list[i].group_list[j].group_name }}</div>
											<div class="price">
												<strong>{{ d.store_list[i].group_list[j].price }}</strong>
												<span class="strong-color">元</span> 

												<div class="line_m" style="text-decoration: line-through;  display: inline; color: #ccc;font-size: 10px;">{{ d.store_list[i].group_list[j].old_price }}元{{# if( d.store_list[i].group_list[j].extra_pay_price){ }}{{  d.store_list[i].group_list[j].extra_pay_price }}{{# } }}</div>
												
													{{# if(d.store_list[i].group_list[j].wx_cheap){ }}
														<span class="tag">微信再减{{ d.store_list[i].group_list[j].wx_cheap }}元</span>
													{{# } }}
												<span class="line-right">{{ d.store_list[i].group_list[j].sale_txt }}</span>
											</div>
										</div>
									</li>
								{{# } }}
								{{# if(d.store_list[i].group_list.length > 2){ }}
									<li class="more">其他{{ d.store_list[i].group_list.length-2 }}个{pigcms{$config.group_alias_name}</li>
								{{# } }}
							</ul>
						</dd>
					{{# } }}
				</script>
				<script id="groupListBoxTpl" type="text/html">
					{{# for(var i = 0, len = d.group_list.length; i < len; i++){ }}
						<dd class="link-url" data-url="{{ d.group_list[i].url }}">
						{{# if(d.group_list[i].pin_num > 0){ }}<div class="pin_style"></div>{{# } }}
							<div class="dealcard-img imgbox">
								{{# if(d.group_list[i].pin_num > 0){ }}<div class="pin_style"></div>{{# } }}
								<img src="{pigcms{$config.site_url}/index.php?c=Image&a=thumb&width=276&height=168&url={{ encodeURIComponent(d.group_list[i].list_pic) }}" alt="{{ d.group_list[i].s_name }}"/>
								{{# if(d.group_list[i].is_start == 0){ }}<i class="store_state">未开始</i>{{# } }}
							</div>
							<div class="dealcard-block-right">
								<div class="brand"><div class="s_name">{{ d.group_list[i].s_name }}</div> {{# if(d.group_list[i].juli){ }}<span class="location-right">{{ d.group_list[i].juli_txt }}</span>{{# } }}</div>
								<div class="title" style="padding-left:0px;">{{ d.group_list[i].intro }}</div>
								<div class="price">
									<strong>{{ d.group_list[i].price }}</strong><span class="strong-color">元{{# if(d.group_list[i].wx_cheap){ }}<span class="tag">微信再减{{ d.group_list[i].wx_cheap }}元</span>{{# }else{ }}<del>{{ d.group_list[i].old_price }}</del>{{# } }}<span class="line-right">{{ d.group_list[i].sale_txt }}</span>
								</div>
							</div>
						</dd>
					{{# } }}
				</script>
				<section class="storeListBox listBox">
					<dl></dl>
					<div class="shade hide"></div>
					<div class="no-deals hide">暂无此类{pigcms{$config.group_alias_name}，请查看其他分类</div>
				</section>
				<div id="pullUp" >
					<span class="pullUpIcon"></span><span class="pullUpLabel">上拉加载更多</span>
				</div>
			</div>
		</div>
			<php>$no_footer = true;</php>
		<include file="Public:footer"/>
		<style>
		.hiddens{
			overflow: hidden;
       text-overflow: ellipsis;
        white-space: nowrap;
		}
		.wh{width:100%!important;height:100% !important;}
			.dealcard dd {
				border-bottom: none !important;
				border-top: 1px solid #f1f1f1 !important;
				padding: 20px 0;
				margin-left: 15px;
			}
			
			.dealcard .dealcard-img img {
				width: 120px;
				height: auto;
				margin-left:-15px;
			}
			.noMore{
				padding: 0px !important;
				margin-left: 0px !important;
				background:none !important ; 
			}
		
			#container {
				position: absolute;
				z-index: 1;
				top: 0px;
				bottom: 49px;
				left: 0;
				width: 100%;
				overflow: hidden;
			}

			.banner {
				height: 150px;
				padding-top: 0px;
			}

			.pin_style{
				background-repeat: no-repeat;
				background-image: url({pigcms{$static_path}/images/pin.png);
				width: 100%;
				height: 100%;
				position: absolute;
				background-size: 38px;
				z-index: 100;
			}
			.wx_aside {
				position: fixed;
				right: 0;
				bottom: 10px;
				z-index: 900;
				border-radius: 3px 0 0 3px;
				width: 40px;
				background-color: rgba(0, 0, 0, 0.7);
			}
			.WX_backtop.WX_backtop_active, .wx_aside {
				bottom: 76px;
				z-index: 99;
			}
			a, a:visited {
				text-decoration: none;
				color: #333;
			}
			.wx_aside > a {
				border-top: 1px solid #828282;
				position: relative;
			}
			.wx_aside .btn_ask, .wx_aside .btn_more, .wx_aside .btn_top {
				width: 40px;
				height: 40px;
				font-size: 0;
				text-indent: -9999em;
				display: none;
				overflow: hidden;
			}
			.wx_aside .btn_more {
				display: block;
			}
			.wx_aside > a:first-child {
				border-top: none;
			}
			.wx_aside .btn_ask {
				display: block;
			}
			.wx_aside_item {
				position: absolute;
				bottom: 90px;
				right: 0;
				background-color: #fff;
				border: 1px solid #ddd;
				width: 115px;
				display: none;
			}
			.wx_aside .wx_aside_item {
				width: 130px;
				width: auto;
			}
			.wx_aside .wx_aside_item {
				bottom: 100%;
				margin-bottom: 6px;
			}
			.wx_aside.more_active .wx_aside_item {
				display: block;
			}
			.wx_aside_item a {
				color: #666;
				line-height: 44px;
				height: 44px;
				overflow: hidden;
				display: block;
			/*    margin: 0 15px;*/
				border-bottom: 1px solid #eee;
				float:left;
				margin-left:5px;
			}
			.wx_aside .wx_aside_item > a {
				overflow: visible;
				white-space: nowrap;
			}
			.item_gwq{
				width: 60px;
				color: #666;
				line-height: 44px;
				height: 44px;
				overflow: hidden;
				display: block;
				margin: 0 15px;
				border-bottom: 1px solid #eee;
			}
			.item_gwq img{
				width:20px;height:20px;float:left;margin-top:10px;
			}
			.order-zuo, .order-jiudian {
				background-color: #F5716E;
			}
			.order-icon {
				display: inline-block;
				width: 25px;
				height: 25px;
				text-align: center;
				color: white;
				margin-right: .25rem;
				margin-top: -.06rem;
				margin-bottom: -.06rem;
				background-color: #F5716E;
				vertical-align: initial;
				font-size: 16px;
			}
			.text-icon {
				font-family: base_icon;
				display: inline-block;
				vertical-align: middle;
				font-style: normal;
			}
			.market-icon {
				display: inline-block;
				text-align: center;
				color: white;
				margin-left:5px;
				padding: 0px 3px;
				background-color: #F75D3A;
				vertical-align: initial;
				font-size: 12px;
			}
			.reteInfo_font{
				overflow: hidden; /*自动隐藏文字*/
				text-overflow: ellipsis;/*文字隐藏后添加省略号*/
				white-space: nowrap;/*强制不换行*/
				font-size: 12px;
				margin-left:2px;
				width:100%;
				margin-top:7px;
			}
			
			.line_m{
				font-size: 10px;
			}
		</style>
		<script type="text/javascript">
		if ($(window).width() <= 320) {
           $('.shree').css("height",'115px');
           $(".fire").css("height",'95px');
           $(".four").css('height','65px' )
         }else if( 320<$(window).width() <= 375){
              $('.shree').css("height",'125px');
              $(".fire").css("height",'109px');
               $(".four").css('height','75px' )
         }else{
       	   $('.shree').css("height",'133px');
       	    $(".fire").css("height",'120px');
       	     $(".four").css('height','85px' )
         }


		$(document).on('click','.link-url',function(){
			window.location.href = $(this).data('url');
		});
		$(document).on('click','#search',function(){
			window.location.href = '{pigcms{$config.site_url}/wap.php?g=Wap&c=Search&a=index&type=group';
		});
		   var mySwiper = $('.swiper-container1').swiper({
				pagination:'.swiper-pagination1',
				loop:true,
				grabCursor: true,
				paginationClickable: true,
				autoplay:3000,
				autoplayDisableOnInteraction:false,
				simulateTouch:false
			});
		   	var mySwiper2 = $('.swiper-container2').swiper({
				pagination:'.swiper-pagination2',
				loop:true,
				grabCursor: true,
				paginationClickable: true,
				simulateTouch:false
			});
			 var mySwiper3 = $('.swiper-container3').swiper({
				pagination:'.swiper-pagination3',
				loop:true,
				grabCursor: true,
				paginationClickable: true,
				autoplay:3000,
				autoplayDisableOnInteraction:false,
				simulateTouch:false
			});
			var banner_height	=	$(window).width()/320;
			banner_height	=Math.ceil(banner_height*119);
			$(".banner").css('height',banner_height);
			$('.more').click(function(e){
				$(this).hide();
				$('.hide').show();
			});
		$(function(){
		$('#container').css({top:'50px'});
		});
		</script>	
	</body>
</html>

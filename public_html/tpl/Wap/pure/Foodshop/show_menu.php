<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$store['name']}</title>
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/css_whir.css"/>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-1.7.2.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
<!--[if lte IE 9]>
<script src="scripts/html5shiv.min.js"></script>
<![endif]-->
<script>var store_id = '{pigcms{$store["store_id"]}', all_goods = '{pigcms{$all_goods}', submit_url = '{pigcms{:U("Foodshop/menu_save")}';</script>
</head>
<body>
	<section class="foodleft">
		<!--div class="search">
			<input type="text" placeholder="搜索您想吃的" class="sr">
			<a href="#">搜索</a>
		</div-->
		<div class="foodnav">
			<ul>
				<volist name="goods_list" id="sort" key="i">
				<li><a href="javascript:void(0)" data-cat_id="{pigcms{$sort['sort_id']}" <if condition="$i eq 1">class="on"</if>>{pigcms{$sort['sort_name']}</a></li>
				</volist>
			</ul>
		</div>
	</section>
	<section class="foodright">
		<volist name="goods_list" id="rowset">
		<dl data-cat_id="{pigcms{$rowset['sort_id']}" class="foodright-{pigcms{$rowset['sort_id']}">
			<dt>{pigcms{$rowset['sort_name']}</dt>
			<volist name="rowset['goods_list']" id="goods">
			<dd class="goods_{pigcms{$goods['goods_id']}">
				<div class="foodr_img">
					<img src="{pigcms{$goods['pic_arr'][0]['url']['s_image']}">
				</div>
				<div class="food_right">
					<h2>{pigcms{$goods['name']}</h2>
					<div class="MenuPrice">
						<i>￥</i>{pigcms{$goods['price']}<em>/{pigcms{$goods['unit']}</em>
					</div>
					<if condition="$goods['spec_list'] OR $goods['properties_list']">
					<div class="Addsub">
						<span class="Speci">选规格</span>
					</div>
					<else />
	                <!--div class="Addsub">
	                    <a href="javascript:void(0)" class="jian" data-price="{pigcms{$goods['price']|floatval}" data-id="{pigcms{$goods['goods_id']}" data-index="{pigcms{$goods['goods_id']}" data-name="{pigcms{$goods['name']}"></a>
						<input type="text" value="0" readOnly="true" class="num">
	                    <a href="javascript:void(0)" class="jia" data-price="{pigcms{$goods['price']|floatval}" data-id="{pigcms{$goods['goods_id']}" data-index="{pigcms{$goods['goods_id']}" data-name="{pigcms{$goods['name']}"></a>
					</div-->
					</if>
				</div>
				<if condition="$goods['spec_list'] OR $goods['properties_list']">
					<section class="Tcancel TcancelT">
						<div class="TcancelT_top clr">
							<div class="TcancelT_topL">
								<h2>{pigcms{$goods['name']}</h2>
								<span><i>￥</i>{pigcms{$goods['price']}</span>
							</div>
							<a href="javascript:void(0)" class="gb"></a>
						</div>
						<div class="TcancelT_zh">
							<div class="TcancelT_n">
								<volist name="goods['spec_list']" id="spec_r">
								<div class="TcancelT_list">
									<h2>{pigcms{$spec_r['name']}</h2>
									<div class="fications" data-id="{pigcms{$spec_r['id']}" data-num="1" data-name="{pigcms{$spec_r['name']}" data-type="spec">
										<ul class="clr" >
											<?php foreach ($spec_r['list'] as $srow) {?>
											<li data-id="{pigcms{$srow['id']}" data-name="{pigcms{$srow['name']}" data-type="spec" data-goods_id="{pigcms{$goods['goods_id']}">{pigcms{$srow['name']}</li>
											<?php }?>
										</ul>
									</div>
								</div>
								</volist>
								<volist name="goods['properties_list']" id="pro_r">
								<div class="TcancelT_list">
									<h2>{pigcms{$pro_r['name']}</h2>
									<div class="fications" data-id="{pigcms{$pro_r['id']}" data-name="{pigcms{$pro_r['name']}" data-num="{pigcms{$pro_r['num']}" data-type="properties">
										<ul class="clr" >
											<?php foreach ($pro_r['val'] as $k => $val) {?>
											<li data-id="{pigcms{$k}" data-name="{pigcms{$val}" data-type="properties" data-goods_id="{pigcms{$goods['goods_id']}">{pigcms{$val}</li>
											<?php }?>
										</ul>
									</div>
								</div>
								</volist>
							</div>
						</div>
						<div class="Selected">
							已选：<span></span>
						</div>
						<div class="join" data-goods_id="{pigcms{$goods['goods_id']}" data-name="{pigcms{$goods['name']}" price="{pigcms{$goods['price']}">
							<input type="button" value="加入菜单">
						</div>
					</section>
				</if>
			</dd>
			</volist>
		</dl>
		</volist>
	</section>
	<div class="Mask"></div>
	
	<!-- 新增 -->
	<a href="{pigcms{:U('Foodshop/book_order', array('store_id' => $store['store_id']))}">
	<section class="floor floor2 clr">
		 <img src="{pigcms{$static_path}images/ts.png">想要点菜，请先去订桌哦！
	</section>
	</a>


	
    

	<section class="Cart">
		<div class="Cart_top clr">
			<h2>购物车</h2>
			<span>清空</span>
		</div>
		<div class="Cart_list">
			<ul>
			</ul>
		</div>
	</section>
</body>
<script>
$(function() {
	//设置遮罩层的高度
	$(".Mask").css("height", $(document).height());
	
    //搜素
	$(".foodleft .search").click(function(){
		$(this).addClass("foodleftOn")
		var w = $(window).width();
		$(".foodleftOn").css("width", w - 3);
		$(".foodleftOn input").css("width", w - 100);
		$('.sr').focus();
	});

	$(".foodnav li a").click(function(){
		if($(".search").hasClass("foodleftOn")){
			$(".search").removeClass("foodleftOn");
		}
	});

	//背景单窗高度  
	var hi = $(window).height()
// 	$(".foodnav").css("height", hi - 104);
	$(".foodnav").css("height", hi - 50);
	$(".foodright").css("height", hi - 50);
	$(".foodright dl").last().css("min-height", hi-50);
	/*左侧滚动条*/
	var myScroll2 = new IScroll('.foodnav', {click: true});
	$(".foodright").scroll(function(){
		var top = $(".foodright").scrollTop();
		var menu = $(".foodnav");
		var item = $(".foodright dl");
		var onid = "";
		item.each(function() {
			var n = $(this);
			var itemtop = $('.foodright-'+$(this).data('cat_id')).offset().top-$('.foodright').offset().top+$('.foodright').scrollTop();
			if (top > itemtop - 100) {
				onid = n.data('cat_id');
			}
		});
		var link = menu.find(".on");
		link.removeClass("on");
		menu.find("[data-cat_id="+onid+"]").addClass("on");
	});
	$(document).on('click','.foodnav a',function(){
//		$(".foodnav").find('li a').removeClass("on");
//		$(this).addClass("on");
		$('.foodright').animate({scrollTop:$('.foodright-'+$(this).data('cat_id')).offset().top-$('.foodright').offset().top+$('.foodright').scrollTop()},500) ;
	});
	
	//清空购物车
	$(".Cart_top span").click(function(){
		$(".Cart").slideUp();
		$(".Mask").hide();
	});
	
	//购物效果
	$(".trolley").toggle(function(){
		$(".Cart").slideDown();
		$(".Mask").show();
	},function(){
		$(".Cart").slideUp();
		$(".Mask").hide()
	});
	
	//弹出规格
	$(".Speci").click(function(){
		$(this).parents(".food_right").siblings(".TcancelT").slideDown();
		$(".Mask").show();
	});
	//关闭规格弹出
	$(".gb").click(function(){
		$(this).parents(".TcancelT").slideUp();
		$(".Mask").hide();
	});
           
	
	//规格中选项的选择
	$(document).on('click', '.fications li', function(){
		var father_obj = $(this).parents('.fications');
		var type = father_obj.data('type'), id = father_obj.data('id'), name = father_obj.data('name'), num = father_obj.data('num');
		var this_id = $(this).data('id'), this_name = $(this).data('name'), goods_id = parseInt($(this).data('goods_id'));
		if (num == 1) {
			$(this).addClass('on').siblings('li').removeClass('on');
		} else {
			$(this).toggleClass("on");
			if (father_obj.find('.on').length > num) {
				$(this).removeClass("on");
				motify.log('最多可以选择' + num + '个');
				return false;
			}
		}
		var select_html = '已选：';
		var spec_ids = [];
		$(this).parents('.TcancelT').find('.fications').each(function(dom){
			$(this).find('li').each(function(){
				if ($(this).hasClass('on')) {
					select_html += '<span>' + $(this).data('name') + '</span>';
					if ($(this).data('type') == 'spec') {
						spec_ids.push($(this).data('id'))
					}
				}
			});
		});

		$(this).parents(".TcancelT_zh").siblings(".Selected").html(select_html);
		if (type == 'spec' && spec_ids.length > 0) {
			var ALL_GOODS = $.parseJSON(all_goods);
			var price = 0;
			if (typeof(ALL_GOODS[goods_id][spec_ids.join('_')]) != 'undefined') {
				price = ALL_GOODS[goods_id][spec_ids.join('_')]['price'];
			}
			$(this).parents('.TcancelT').find('.TcancelT_topL span').html('<i>￥</i>' + price);
			$(this).parents('.TcancelT').find('.join').data('price', price);
		}
	});
	//提交规格选中的
	$(document).on('click', '.TcancelT .join', function(){
		$(this).parents(".TcancelT").slideUp();
		$(".Mask").hide();
	});
});
</script>
</html>
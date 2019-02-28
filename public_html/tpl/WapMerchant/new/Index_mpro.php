<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
<link rel="stylesheet" href="{pigcms{$static_path}css/shop_item.css">
<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js"></script>
<style>
.pigcms-container {
  padding: 12px 0 0;
   margin-bottom: 10px;
}
.search-container {padding: 3px;}
</style>
<body>
	<header class="pigcms-header mm-slideout">
		<a href="#slide_menu" id="pigcms-header-left">
			<i class="iconfont icon-menu "></i>
		  </a>
	      <p id="pigcms-header-title">{pigcms{$config.meal_alias_name}商品管理</p>
		<a  href="{pigcms{:U('Index/meal_add')}" id="pigcms-header-right">添加商品</a>
		</header>
	<div class="container container-fill">
		<!--左侧菜单-->
		<include file="Public:leftMenu"/>
		<!--左侧菜单结束-->

	<div class="item-list-wrap">
		<div id="item-list-wrapper" class="pigcms-main" style="">
			<div id="item-list-scroller">
				<ul id="item-list-ul" style="margin-bottom: 70px;">
					<div class="pigcms-container">
						<div class="search-container">
							<div id="input-wrap"></div>
							<i class="iconfont icon-search"></i>
							<input type="text" class="pigcms-search" name="keyword" placeholder="商品名称">
						</div>
						<div class="header-fliter-container"></div>
						<!--<div class="header-fliter-container">
							<div class="header-fliter fliter-active" id="online">
								<span>全部商品</span><i class="iconfont icon-unfold"></i>
							</div>
							<div class="header-fliter" id="cat">
								<span>全部分类</span><i class="iconfont icon-unfold"></i>
							</div>
							<div class="header-fliter" id="sort">
								<span>商品销量</span><i class="iconfont icon-unfold"></i>
							</div>
							<div class="clearfix"></div>
						</div>-->
					</div>
					<div id="item-list-div">

					</div>
				</ul>
			</div>
		</div>

		<!--<div id="fliter-layer" style="display: none;">
			<div>
				<div class="fliter-wrapper" id="online-fliter-wrapper">
					<div id="fliter-scroller">
						<ul class="" id="online-fliter" onclick="online_fliter(this)">
							<li class="fliter-container">
								<div class="fliter">
									<span data-isonline=""></span>
								</div>
							</li>
							<li class="fliter-container">
								<div class="fliter">
									<span data-isonline="1"></span>
								</div>
							</li>
							<li class="fliter-container">
								<div class="fliter">
									<span data-isonline="0"></span>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="fliter-wrapper" id="cat-fliter-wrapper" >
					<div id="fliter-scroller">
						<ul class="" id="cat-fliter" onclick="cat_fliter(this)">
							<li class="fliter-container fliter-selected">
								<div class="fliter">
									<span data-catid="">全部分类</span>
								</div>
							</li>
							<li class="fliter-container">
								<div class="fliter">
									<span data-catid="16595">afsdf</span>
								</div>
							</li>
													</ul>
					</div>
				</div>
				<!--<div class="fliter-wrapper" id="sort-fliter-wrapper">
					<div id="fliter-scroller">
						<ul class="" id="sort-fliter" onclick="sort_fliter(this)">
							<li class="fliter-container">
								<div class="fliter">
									<span data-sort="0">销量排序</span>
								</div>
							</li>
							<li class="fliter-container">
								<div class="fliter">
									<span data-sort="-1">销量从高到低</span>
								</div>
							</li>
							<li class="fliter-container">
								<div class="fliter">
									<span data-sort="1">销量从低到高</span>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div id="fliter-close"></div>
		</div>-->
		<div class="item-list-footer">
			<!--<a href="{pigcms{:U('Index/table_add')}" class="footer-operation">
				<i class="iconfont icon-add"></i><span>添加餐桌</span>
			</a>
			<a href="{pigcms{:U('Index/sort_add')}" class="footer-operation">
				<i class="iconfont icon-add"></i><span>添加分类</span>
			</a>-->
			<a href="{pigcms{:U('Index/meal_add')}" class="footer-operation">
				<i class="iconfont icon-add"></i><span>添加商品</span>
			</a>
		</div>
	</div>
</body>

<script>
    var staticpath="{pigcms{$static_path}";
	var url = "{pigcms{:U('Index/mpro')}",
		can_manage = 1;
		$(function(){
			$(".pigcms-main").css('height', $(window).height()-50);
		})
		var myScroll_fliter;
		/*function loaded() {
			myScroll_fliter = new iScroll('cat-fliter-wrapper',{checkDOMChanges: true});
			$("#cat-fliter-wrapper .fliter-container").eq(0).trigger('click');
		}
		document.addEventListener('touchmove', function(e) {
			e.preventDefault();
		}, false);
		document.addEventListener('DOMContentLoaded', loaded, false);*/

	</script>
	<script src="{pigcms{$static_path}js/shop_item.js"></script>
		<include file="Public:footer"/>
</html>


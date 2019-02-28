<div class="container container-fill" style='padding-top:50px'>
<div id="slide_menu">
	<header class="pigcms-slide-header">
		<a id="pigcms-slide-left"><i class="iconfont icon-set"></i></a>
		<p id="pigcms-slide-title">{pigcms{$mer_name}</p>
		<!--<a id="pigcms-slide-right">
			<i class="iconfont icon-mail "></i>
		</a>-->
		<div id="user-info">
			<img src="{pigcms{$config.site_merchant_logo}" alt="" id="shop-img" onerror="this.src='{pigcms{$config.site_merchant_logo}'">
			<div id="shop-detail-container">
				<p id="shop-balance">粉丝数<span>{pigcms{$fans_count}</span></p>
				 <div id="shop-order-container">
					<div class="order-container">
						<p class="order-count" id='all-order-count'>{pigcms{$allordercount}</p>
						<p class="order-text"><a href="{pigcms{:U('Index/ordermang')}">全部订单</a></p>
					</div>
					<div class="order-container">
						<p class="order-count" id='today-order-count'>{pigcms{$todayordercount}</p>
						<p class="order-text"><a href="{pigcms{:U('Index/index')}">今日订单</a></p>
					</div>
					<div class="order-container">
						<p class="order-count" id='month-order-count'>{pigcms{$monthordercount}</p>
						<p class="order-text"><a href="{pigcms{:U('Index/index')}">本月订单</a></p>
					</div>
				 </div>
			</div>
		</div>
	</header>
	<ul>
		<li>                                       
			<a class="mm-subopen"></a>
			<a href='#' onclick="jumpLink(this)" data-href="{pigcms{:U('WapMerchant/Index/index',array('token'=>$merid))}"><i class="iconfont icon-home"></i>管理首页</a></li>
		<li>
		<li>                                       
			<a class="mm-subopen"></a>
			<a href='#' onclick="jumpLink(this)" data-href="{pigcms{:U('Wap/Index/index',array('token'=>$merid))}"><i class="iconfont icon-home"></i>我的小店</a></li>
		<li>
			<a class="mm-subopen"></a>
			<a href='#' onclick="jumpLink(this)" data-href="{pigcms{:U('Index/store_list')}"><i class="iconfont icon-shop"></i>店铺管理</a></li>
		<li>
			<a class="mm-subopen"></a>
			<a href='#' onclick="jumpLink(this)" data-href="{pigcms{:U('Index/ordermang')}"><i class="iconfont icon-form"></i>订单管理</a></li>
		<li>
			<a class="mm-subopen"></a>
			<a href='#' onclick="jumpLink(this)" data-href="{pigcms{:U('Index/promang')}"><i class="iconfont icon-goods"></i>商品管理</a></li>
		<li>
			<a class="mm-subopen"></a>
			<a href='#' onclick="jumpLink(this)" data-href="{pigcms{:U('Index/staff')}"><i class="iconfont icon-friends"></i>店员管理</a></li>
		<!--li>
			<a class="mm-subopen"></a>
			<a href='#' onclick="jumpLink(this)" data-href=""><i class="iconfont icon-iconfontwechat"></i>分佣管理</a>
		</li-->
		<!--<li>
			<a class="mm-subopen"></a>
			<a href='#' onclick="jumpLink(this)" data-href="{pigcms{:U('Index/Capital')}"><i class="iconfont icon-recharge"></i>资金管理</a>
		</li>----->
		<li>
			<a class="mm-subopen"></a>
			<a href='#' onclick="jumpLink(this)" data-href="{pigcms{:U('Index/hardware')}"><i class="iconfont icon-printer"></i>打印机管理</a>
		</li>
		<li>
			<a class="mm-subopen"></a>
			<a href='#' onclick="jumpLink(this)" data-href="{pigcms{:U('Index/merchantewm')}"><i class="iconfont icon-code"></i>商家二维码</a>
		</li>
	</ul>
	<footer class="pigcms-slide-footer">
		<a id='order-list' href="{pigcms{:U('Index/ordermang')}">
			<i class="iconfont icon-form "></i>
			<span>所有店铺订单</span>
		</a>
		<a id='shop-list' href="{pigcms{:U('Index/store_list')}">
			<i class="iconfont icon-file2"></i> 
			<span>店铺列表</span>
		</a>
		<div class="clearfix"></div>
	</footer>
	<script>
		$("#pigcms-slide-right").click(function(){
			$("#staff-message-li").trigger('click');
		})
		$("#order-list").click(function(){
			$("#order-list-li").trigger('click');
		})
		$("#pigcms-slide-left").click(function(){
			$("#shop-settings-li").trigger('click');
		})
		$("#shop-list").click(function(){
			$("#shop-list-li").trigger('click');
		})
		function jumpLink(obj){
			var url = $(obj).attr('data-href');
			setTimeout(function(){
				window.location.href = url;
			},500);
		}
	</script>
</div>
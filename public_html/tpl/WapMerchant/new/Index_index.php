<include file="Public:top"/>
<body>
	<header class="pigcms-header mm-slideout">
	  <a href="#slide_menu" id="pigcms-header-left">
			<i class="iconfont icon-menu "></i>
	  </a>
	<p id="pigcms-header-title">我的店</p>
	</header>
	<!--左侧菜单-->
	<include file="Public:leftMenu"/>
	<!--左侧菜单结束-->
	<link rel="stylesheet" href="{pigcms{$static_path}css/shop.css">
	<link rel="stylesheet" href="{pigcms{$static_path}css/shop_index.css">
	<div class="pigcms-main">
	 <if condition="!empty($wap_MerchantAd)">
	 <script src="{pigcms{$static_path}js/swipe.js"></script>
	    <div class="pigcms-container">
		   <div class="addWrap">
	          <div class="swipe" id="mySwipe">
	             <div class="swipe-wrap">
				 <volist name="wap_MerchantAd" id="adv">
	                <div>
		               <a href="{pigcms{$adv['url']}">
		                <img class="img-responsive" src="{pigcms{$adv['pic']}"  alt="{pigcms{$adv['name']}"/>
		                </a>
		               </div>
	               </volist>
	             </div>
	            </div>
	        <div id="position_wrap">
	           <ul id="position">
	           </ul>
	       </div>
	  </div>
		<script type="text/javascript">
			var banner_w = $(window).width();
			var banner_h = 200 * banner_w / 640;
			$(".img-responsive").css('height',banner_h);
			for(var i=0;i<$(".img-responsive").length;i++){
				$("<li class=''></li>").appendTo('#position');
			}
			$("#position li:first").addClass('cur');
			var bullets = document.getElementById('position').getElementsByTagName('li');
			var banner = Swipe(document.getElementById('mySwipe'), {
				auto: 4000,
				continuous: true,
				disableScroll: false,
				callback: function(pos) {
					var i = bullets.length;
					while (i--) {
						bullets[i].className = ' ';
					}
					bullets[pos].className = 'cur';
				}
			});
		</script>
		</div>
		</if>
		<!--<div class="pigcms-container" style="position:relative;height:40px">
			<p id="status-container">
				<i class="iconfont icon-notification"></i>
				<span style='color:#fe7d54'>暂停营业中...</span>
			</p>
			<a id="confirm-close" href="."></a>
			<label class='settings-icon'>
			<input type="checkbox" class="ios-switch green tinyswitch" />
				<div>
					<div>
					</div>
				</div>
			</label>
		</div>-->
		<div class="pigcms-container" style="background:#fff;margin-bottom:15px;">
			<a class="index-btn" >
				<i class="iconfont icon-shop" id='open-shop' style="color:#aedda9"></i>
				<p>店铺管理</p>
			</a>
			<a class="index-btn" id='open-item'>
				<i class="iconfont icon-goods" style="color:#ff9f70"></i>
				<p>商品管理</p>
			</a>
			<a class="index-btn" id='open-order'>
				<i class="iconfont icon-form " style="color:#37dddf"></i>
				<p>订单管理</p>
			</a>
			<a class="index-btn" id='open-menu'>
				<i class="iconfont icon-menu" style="color:#ff7a88"></i>
				<p>更多功能</p>
			</a>
			<div class="clearfix"></div>
		</div>
		<div class="pigcms-container" id="count-container">
			<div class="index-count-container" id="order-count-container" style="background:#bbdb9c" onclick="chart_ajax('order');">
				<div class="index-count">
					<p class="count-text" id='order-all'>{pigcms{$allordercount}</p>
					<p class="count-title">订单总数</p>
				</div>
			</div>
			<div class="index-count-container" id="income-count-container" style="background:#7cd6de" onclick="chart_ajax('income');">
				<div class="index-count">
					<p class="count-text" id='income-all'>{pigcms{$allincomecount}</p>
					<p class="count-title">收入总数</p>
				</div>
			</div>
			<div class="index-count-container" id="member-count-container" style="background:#ffae6c" onclick="chart_ajax('member');">
				<div class="index-count">
					<p class="count-text" id='member-all'>{pigcms{$fans_count}							
					</p>
					<p class="count-title">粉丝总数</p>
				</div>
			</div>
			<div class="index-count-container" style="background:#ff8283">
				<div class="index-count">
					<p class="count-text" id='view-all'>{pigcms{$webviwe}</p>
					<p class="count-title">浏览总数</p>
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="pigcms-container" id='canvas-container'>
			<div id="canvas-layer"></div>
			<p id='canvas-title'></p>
			<canvas id="myChart" style="width:100%!important;"></canvas>
		</div>
		<!--<div id="index-footer" class='mm-slideout'>
			<a class="shop-link" href="{pigcms{:U('Index/index',array('token'=>$merid))}">
				<i class="iconfont icon-shop link-icon"></i>
				<p>预览店铺</p>
			</a>
			<a class="shop-link" id='qrcode'>
				<i class="iconfont icon-code link-icon"></i>
				<p></p>
			</a>
			<a class="shop-link share-copy-link" id='share-link'>
				<i class="iconfont icon-share link-icon"></i>
				<p>分享店铺</p>
			</a>
			<a class="shop-link share-copy-link" id='copy-link'>
				<i class="iconfont icon-link link-icon"></i>
				<p>复制链接</p>
			</a>
			<div class="clearfix"></div>
		</div>-->
	</div>
	<div id="share-copy-wrap">
		<img src="{pigcms{$static_path}/images/android_share.png" id='android-share-img'>
		<img src="{pigcms{$static_path}/images/android_copy.png" id='android-copy-img'>
		<img src="{pigcms{$static_path}/images/ios_share.png" id='ios-share-img'>
		<img src="{pigcms{$static_path}/images/ios_copy.png" id='ios-copy-img'>
		<img src="{pigcms{$static_path}/images/qrcode.png" id='qrcode-img'>
	</div>
	</div>
</body>

 <script type="text/javascript">
			var os = "windows",
			container = "web",
			chart_url = "{pigcms{:U('Index/getchart')}",
			pic_url = "" ? "" : "";
</script>
	<script src="{pigcms{$static_path}/js/chart.min.js"></script>
    <script src="{pigcms{$static_path}/js/shop_index.js"></script>
	<script type="text/javascript">
		var on = false;
		$(".settings-icon").click(function(event) {
		$this = $(this);
		if(!on){
			var url = "";
			$.post(url, '', function(data) {
				on = true;
				$("#confirm-close").show();
				$("#status-container span").text("店铺正常营业中").css('color','#696969');
			});
			
		}
	});

	
	</script>
	<include file="Public:footer"/>
</html>

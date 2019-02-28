<!--头部-->
<include file="Public:top"/>
<!--头部结束-->
<body>
	
		<header class="pigcms-header mm-slideout">
			<a href="#slide_menu" id="pigcms-header-left">
				<i class="iconfont icon-menu "></i>
			</a>
				<p id="pigcms-header-title">资金管理</p>
				<a  id="pigcms-header-right"></a>
			</header>
	<div class="container container-fill" style='padding-top:50px'>
	<!--左侧菜单-->
		<include file="Public:leftMenu"/>
	<!--左侧菜单结束-->
	<link rel="stylesheet" href="{pigcms{$static_path}/css/shop_wallet.css">
	<div class="pigcms-main">
		<div class="pigcms-container" style='background:#fff'>
			<div class="wallet-container">
				<div class="wallet-detail-container">
					<span class="wallet-title">余&nbsp;&nbsp;额: </span>
					<p class="wallet-detail"><span class="strong" style="color: #f66327;">0</span>.00 元</p>
				</div>
								<a href="" class="wallet-operation">资金明细 <i class="iconfont icon-right"></i></a>
								<div class="clearfix"></div>
			</div>
			<div class="wallet-container">
				<div class="wallet-detail-container">
					<span class="wallet-title">冻&nbsp;&nbsp;结: </span>
					<p class="wallet-detail"><span class="strong" style="color: #b0b0b0;">0</span>.00 元</p>
				</div>
				<div class="clearfix"></div>
			</div>	
			<div class="wallet-container">
				<div class="wallet-detail-container">
					<span class="wallet-title">已提现: </span>
					<p class="wallet-detail"><span class="strong" style="color: #656565;">0</span> 次</p>
				</div>
								<a href="" class="wallet-operation">提现记录 <i class="iconfont icon-right"></i></a>
								<div class="clearfix"></div>
			</div>
		</div>
		<p class="pigcms-form-title" style='margin:20px 0 10px 10px;color:#7c7c7c'>提现账户设置</p>
		<div class="pigcms-container" style='background:#fff'>
			<div class="wallet-container" style='height:60px;'>
									<div class="wallet-detail-container" style='line-height:30px;float:left'>
						<p class="wallet-title" style='float:left'>微信钱包: </p>
						<p class="wallet-detail" style='color:#aaa;float:left'>未绑定</p>
						<img src="{pigcms{$static_path}/images/nopic.jpg" id="wallet-img">
						<div class="clearfix"></div>
					</div>	
					<a href="" class="wallet-operation">绑定 <i class="iconfont icon-right"></i></a>
													<div class="clearfix"></div>
			</div>
			<div class="wallet-container">
									<div class="wallet-detail-container">
						<span class="wallet-title">安全手机: </span>
						<p class="wallet-detail" style='color:#aaa'>未设置</p>
					</div>
								<div class="clearfix"></div>
			</div>
		</div>
			</div>
	<script>
		function checkChange(obj){
			confirm_open("获取验证码","验证码将发送到安全手机",'请注意接收',obj)
		}
	</script>
	</div>

	
</body>
</html>

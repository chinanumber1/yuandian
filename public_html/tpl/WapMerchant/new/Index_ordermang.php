<!--头部-->
<include file="Public:top"/>
<style>
#order-list-ul li{background-color: rgb(6, 200, 174);
height: 45px;
line-height: 45px;
padding: 10px;
margin: 10px;
border-radius: 7px;
}
.mm-list a.mm-subopen:after{
    border-color: #fff;
	width: 15px;
	height: 15px;
}
#order-list-ul .second{
color: #fff;
font-size: 20px;
}
</style>
<!--头部结束-->
<body>
	<header class="pigcms-header mm-slideout">
		<a href="#slide_menu" id="pigcms-header-left">
			<i class="iconfont icon-menu "></i>
		</a>			
		<p id="pigcms-header-title">订单管理</p>
	</header>
	<!--左侧菜单-->
	<include file="Public:leftMenu"/>
	<!--左侧菜单结束-->

	<div class="container container-fill mm-page mm-slideout" style="padding-top:50px">	
	<div class="order-list-wrap">
		<div class="pigcms-container">
				<ul id="order-list-ul" class="mm-list">
			        <li><a class="mm-subopen"></a><a href="{pigcms{:U('Index/gorder')}" class="second">{pigcms{$config.group_alias_name}订单</a></li>
					<li><a class="mm-subopen"></a><a href="{pigcms{:U('Index/morder')}" class="second">{pigcms{$config.meal_alias_name}订单</a></li>
				</ul>
			</div>
		</div>

	</div>
</body>
	<include file="Public:footer"/>
</html>
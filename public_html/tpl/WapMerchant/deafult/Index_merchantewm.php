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
		<p id="pigcms-header-title">商家二维码</p>
	</header>
	<!--左侧菜单-->
	<include file="Public:leftMenu"/>
	<!--左侧菜单结束-->

	<div class="container container-fill mm-page mm-slideout" style="padding-top:50px">	
	<div class="order-list-wrap">
		<div id="erwm" class="pigcms-container" style="text-align: center; vertical-align: middle;margin:0 auto;margin-top:20px">
		    <img src="{pigcms{$qrcodeinfo['qrcode']}">
			</div>
		</div>

	</div>
</body>
<script type="text/javascript">
 var w=$('body').width();
 if(w>320){
    w=320;
 }else{
   w=w-20;
 }
 $('#erwm img').css('width',w);

</script> 
	<include file="Public:footer"/>
</html>
	<div class="wx_aside more_active" id="quckArea">
		<a id="quckIco2" class="btn_more"><img style="width:40px;height:40px;" src="tpl/Wap/pure/static/img/more.png" />更多</a>
		<!--<a id="shopIM" class="btn_ask on"><img style="width:40px;height:40px;" src="tpl/Wap/pure/static/img/upward.png" />向上</a>-->
		<div class="wx_aside_item" id="quckMenu" style="display:none">
			<a href="{pigcms{:U('wap/Home/index')}" class="item_jd">首页</a>
			<a href="{pigcms{:U('wap/Group/index')}" class="item_search">团购</a>
			<a href="{pigcms{:U('wap/Meal_list/index')}" class="item_gwq">快店</a>
			<a href="{pigcms{:U('wap/My/index')}" class="item_gwq">我的</a>
		</div>
	</div>
	<script>
		$("#quckIco2").on('click',function(){
			$("#quckMenu").toggle();
		});
//		$('#shopIM').on('click',function(){
//			$('body,html').animate({scrollTop:0},1000);
//			location.href = "window.scrollTo(0,0)";
//		});
	</script>
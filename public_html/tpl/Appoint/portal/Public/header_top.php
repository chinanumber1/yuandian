<div class="head clearfix">
	<div class="fl">
		<a class="logo" alt="{pigcms{$config.appoint_site_name}" href="{pigcms{$config.appoint_site_url}" style="background-image:url(<if condition="$config['appoint_site_logo']">{pigcms{$config.appoint_site_logo}<else/>{pigcms{$config.site_logo}</if>);background-size:100%;"></a>
	</div>
	<div class="site fl">
		<p class="site-t">
			<strong>{pigcms{$now_select_city.area_name}</strong> 
		</p>
		<if condition='$config["many_city"]'>
		<p class="site-c">
			[ <span><a href="javascript:void(0)" onclick="changeCity()">切换城市</a></span> ]
		</p>
		</if>
	</div>
	<script>
		function changeCity(){
			window.location.href = "{pigcms{:U('Changecity/index')}&referer="+encodeURIComponent(location.href);
		}
	</script>
	<style>
	.nav a {
		margin: 15px 0 0 23px;
		
		float: left;
	
	}
	</style>
	<div class="head-r fr">
		<div class="tbar-login sty000 fr clearfix"></div>
		<div class="nav sty000 fr clearfix">
			<pigcms:slider cat_key="web_yue_slider" limit="10" var_name="web_yue_slider" reverse="0">
			<a href="{pigcms{$vo.url}">{pigcms{$vo.name}</a>
			</pigcms:slider>
			<form action="{pigcms{:U('Appoint/Search/index')}" method="post"  style=" position:absolute;z-index:10;right:0px;width: 40px;height: 40px;padding: 0px;border: 2px solid #06c1ae;float:right" class='listForm'>
				<input name="w" class="input" type="text" placeholder="请输入商品名称" style=" display:none;   width: 80%;height: 38px;float: left;line-height: 36px;font-size: 14px; border: 0px;    padding-left: 3%;" value="{pigcms{$keywords}"/>
				<button value="" class="btnclick" style="    width: 40px;height: 40px;float: right;background: #06c1ae;cursor: pointer;border: 0px;"><img src="{pigcms{$static_path}images/o2o1_20.png"  /></button>
			</form>
		</div>
		
	</div>
	<form id="form1" method="post" action="#">
		<input type="hidden" id="backCityUrl" name="backCityUrl" value="#">
	</form>
	
</div>
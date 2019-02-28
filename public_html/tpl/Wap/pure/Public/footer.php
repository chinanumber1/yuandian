<if condition="empty($no_footer)">
	<footer class="footerMenu <if condition="!$is_wexin_browser || $home_menu_list">wap</if>">
    <if condition="$home_menu_list">
		<ul>
            <volist name='home_menu_list' id='vo'>
                <li>
                    <a href="{pigcms{$vo['url']}" <if condition='stripos($vo["url"],"c=".MODULE_NAME)'>class="active"</if>><em></em><p>{pigcms{$vo.name}</p></a>
                </li>
                 <style type="text/css">
                    .footerMenu ul li:nth-of-type({pigcms{$key+1}) a em{background:url({pigcms{$config.site_url}/upload/slider/{pigcms{$vo.pic_path}) no-repeat center left; background-size:22px 20px}
                    .footerMenu ul li:nth-of-type({pigcms{$key+1}) a.active em{ background:url({pigcms{$config.site_url}/upload/slider/{pigcms{$vo.hover_pic_path}) no-repeat center left; background-size:22px 20px}
                </style>
            </volist>
		</ul>
    <else />
        <ul>
			<li>
				<a <?php if(MODULE_NAME == 'Home'){ echo 'class="active"'; }?> href="{pigcms{:U('Home/index')}"><em class="home"></em><p>首页</p></a>
			</li>
			<li>
				<a <?php if(MODULE_NAME == 'Group'){ echo 'class="hover"'; }?> href="{pigcms{:U('Group/index')}"><em class="group"></em><p>{pigcms{$config.group_alias_name}</p></a>
			</li>
			<li class="voiceBox">
				<a href="{pigcms{:U('Search/voice')}" class="voiceBtn" data-nobtn="true"></a>
			</li>
			<li>
				<a <?php if(in_array(MODULE_NAME,array('Shop'))){ echo 'class="hover"';}?> href="{pigcms{:U('Shop/index')}"><em class="store"></em><p>{pigcms{$config.shop_alias_name}</p></a>
			</li>
			<li>
				<a <?php if(in_array(MODULE_NAME,array('My','Login'))){ echo 'class="active"'; }?> href="{pigcms{:U('My/index')}"><em class="my"></em><p>我的</p></a>
			</li>
		</ul>
    </if>
	</footer>
<elseif condition="!$is_app_browser && empty($no_small_footer) && $merchant_link_showOther"/>
	<div class="wx_aside more_active" id="quckArea">
		<a id="quckIco2" class="btn_more"><img style="width:40px;height:40px;" src="tpl/Wap/pure/static/img/more.png" />更多</a>
		<div class="wx_aside_item" id="quckMenu" style="display:none">
			<div id="footer_home" class="item_gwq"><img src="tpl/Wap/pure/static/img/footer_home.png" /><a> 首页</a></div>
			<div id="footer_group" class="item_gwq"><img src="tpl/Wap/pure/static/img/footer_group.png" /><a> {pigcms{$config.group_alias_name}</a></div>
			<div id="footer_store" class="item_gwq"><img src="tpl/Wap/pure/static/img/footer_foodshop.png" /><a> {pigcms{$config.meal_alias_name}</a></div>
			<div id="footer_shop" class="item_gwq"><img src="tpl/Wap/pure/static/img/footer_shop.png" /><a> {pigcms{$config.merchant_alias_name}</a></div>
			<div id="footer_my" class="item_gwq"><img src="tpl/Wap/pure/static/img/footer_my.png" /><a> 我的</a></div>
			<div id="footer_refresh" class="item_gwq"><img src="tpl/Wap/pure/static/img/footer_refresh.png" /><a> 刷新</a></div>
		</div>
	</div>
	<script>
		$("#quckIco2").on('click',function(){
			$("#quckMenu").toggle();
		});
		$("#footer_home").on('click',function(){
			location.href = "{pigcms{:U('wap/Home/index')}";
		});
		$("#footer_group").on('click',function(){
			location.href = "{pigcms{:U('wap/Group/index')}";
		});
		$("#footer_store").on('click',function(){
			location.href = "{pigcms{:U('wap/Meal_list/index')}";
		});
		$("#footer_shop").on('click',function(){
			location.href = "{pigcms{:U('wap/Merchant/store_list')}";
		});
		$("#footer_my").on('click',function(){
			location.href = "{pigcms{:U('wap/My/index')}";
		});
		$("#footer_refresh").on('click',function(){
			location.reload();
		});
	</script>
</if>
<div style="display:none;">{pigcms{$config.wap_site_footer}</div>
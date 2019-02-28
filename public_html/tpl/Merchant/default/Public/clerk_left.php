<div id="sidebar" class="sidebar responsive" <if condition="C('butt_open') || $no_sidebar">style="display:none;"</if>>

	<ul class="nav nav-list" style="top: 0px;">
		<li class="open">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa fa-gear"></i>
				<span class="menu-text">店铺管理 </span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu" style="display:block">
                <php>if (in_array('Config', $storeMenus)) {</php>
				<li class="show <if condition="(MODULE_NAME eq 'Config' AND !in_array(ACTION_NAME, array('pick', 'pick_address_add', 'pick_address_edit'))) OR MODULE_NAME eq 'Diypage' ">open</if>">
					<a href="{pigcms{:U('Config/store')}">
						<i class="menu-icon fa fa-caret-right"></i>
						 店铺管理
					</a>
					<b class="arrow"></b>
				</li>
                <php>}</php>
                <php>if (in_array('Shop', $storeMenus)) {</php>
				<li class="show <if condition="MODULE_NAME eq 'Shop'">open</if>">
					<a href="{pigcms{:U('Shop/index')}">
						<i class="menu-icon fa fa-caret-right"></i>
						 {pigcms{$config['shop_alias_name']}管理
					</a>
					<b class="arrow"></b>
				</li>
                <php>}</php>
                <php>if (in_array('Foodshop', $storeMenus)) {</php>
				<li class="show <if condition="MODULE_NAME eq 'Foodshop'">open</if>">
					<a href="{pigcms{:U('Foodshop/index')}">
						<i class="menu-icon fa fa-caret-right"></i>
						 {pigcms{$config['meal_alias_name']}管理
					</a>
					<b class="arrow"></b>
				</li>
                <php>}</php>
				<!--li class="show <if condition="MODULE_NAME eq 'Config' AND in_array(ACTION_NAME, array('pick', 'pick_address_add', 'pick_address_edit'))">open</if>">
					<a href="{pigcms{:U('Config/pick')}">
						<i class="menu-icon fa fa-caret-right"></i>
						自提点管理
					</a>
					<b class="arrow"></b>
				</li>
				<li class="show <if condition="MODULE_NAME eq 'Express'">open</if>">
					<a href="{pigcms{:U('Express/index')}">
						<i class="menu-icon fa fa-caret-right"></i>
						运费设置
					</a>
					<b class="arrow"></b>
				</li-->
                <php>if (in_array('Hardware', $storeMenus)) {</php>
				<li class="show <if condition="MODULE_NAME eq 'Hardware'">open</if>">
					<a href="{pigcms{:U('Hardware/index')}">
						<i class="menu-icon fa fa-caret-right"></i>
						无线打印机
					</a>
					<b class="arrow"></b>
				</li>
                <php>}</php>
                <php>if (in_array('Dizwifi', $storeMenus)) {</php>
				<li class="show <if condition="MODULE_NAME eq 'Dizwifi'">open</if>">
					<a href="{pigcms{:U('Dizwifi/index')}">
						<i class="menu-icon fa fa-caret-right"></i>
						微信wifi
					</a>
					<b class="arrow"></b>
				</li>
                <php>}</php>
                <php>if (in_array('Deliver', $storeMenus)) {</php>
				<li class="show <if condition="MODULE_NAME eq 'Deliver' AND ACTION_NAME neq 'deliverList'">open</if>">
					<a href="{pigcms{:U('Deliver/user')}">
						<i class="menu-icon fa fa-caret-right"></i>
						配送员管理
					</a>
					<b class="arrow"></b>
				</li>
                <php>}</php>
                <php>if (in_array('Deliver', $storeMenus)) {</php>
				<li class="show <if condition="MODULE_NAME eq 'Deliver' AND ACTION_NAME eq 'deliverList'">open</if>">
					<a href="{pigcms{:U('Deliver/deliverList')}">
						<i class="menu-icon fa fa-caret-right"></i>
						配送列表
					</a>
					<b class="arrow"></b>
				</li>
                <php>}</php>
			</ul>
		</li>
	</ul>
	<!-- /.nav-list -->

	<!-- #section:basics/sidebar.layout.minimize -->
	<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
		<i class="ace-icon fa fa-angle-double-left"
			data-icon1="ace-icon fa fa-angle-double-left"
			data-icon2="ace-icon fa fa-angle-double-right"></i>
	</div>

	<!-- /section:basics/sidebar.layout.minimize -->
	<script type="text/javascript">
		try {
			ace.settings.check('sidebar', 'collapsed')
		} catch (e) {
		}
	</script>
</div>
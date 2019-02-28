<div id="sidebar" class="sidebar responsive">
	<ul class="nav nav-list" style="top: 0px;">
		<li class="hsub <if condition="strpos(ACTION_NAME,'group') nheq false">open active</if>">
			<a href="#" class="dropdown-toggle"> 
				<i class="menu-icon fa fa-desktop"></i>
				<span class="menu-text">{pigcms{$config.group_alias_name}订单管理</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu">
				<li <if condition="strpos(ACTION_NAME,'group') nheq false">class="active"</if>>
					<a href="{pigcms{:U('Store/group_list')}"> 
						<i class="menu-icon fa fa-caret-right"></i> {pigcms{$config.group_alias_name}订单列表
					</a>
					<b class="arrow"></b>
				</li>					
			</ul>
		</li>
		<li class="hsub <if condition="strpos(ACTION_NAME,'shop') nheq false">open active</if>">
			<a href="#" class="dropdown-toggle"> 
				<i class="menu-icon fa fa-shopping-cart"></i>
				<span class="menu-text">{pigcms{$config.shop_alias_name}订单管理</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu">
				<li <if condition="strpos(ACTION_NAME,'shop') nheq false">class="active"</if>>
					<a href="{pigcms{:U('Store/shop_list')}">
						<i class="menu-icon fa fa-caret-right"></i> {pigcms{$config.shop_alias_name}订单列表
					</a>
					<b class="arrow"></b>
				</li>
				<li <if condition="strpos(ACTION_NAME,'goods') nheq false">class="active"</if>>
					<a href="{pigcms{:U('Store/goods')}">
						<i class="menu-icon fa fa-caret-right"></i>发货清单
					</a>
					<b class="arrow"></b>
				</li>		
			</ul>
		</li>
		<li class="hsub <if condition="strpos(ACTION_NAME,'meal') nheq false">open active</if>">
			<a href="#" class="dropdown-toggle"> 
				<i class="menu-icon fa fa-cutlery"></i>
				<span class="menu-text">{pigcms{$config.meal_alias_name}订单管理</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu">
				<li <if condition="strpos(ACTION_NAME,'meal') nheq false">class="active"</if>>
					<a href="{pigcms{:U('Store/meal_list')}">
						<i class="menu-icon fa fa-caret-right"></i> {pigcms{$config.meal_alias_name}订单列表
					</a>
					<b class="arrow"></b>
				</li>
				<li <if condition="strpos(ACTION_NAME,'table') nheq false">class="active"</if>>
					<a href="{pigcms{:U('Store/table')}">
						<i class="menu-icon fa fa-caret-right"></i> {pigcms{$config.meal_alias_name}餐台列表
					</a>
					<b class="arrow"></b>
				</li>					
			</ul>
		</li>
		<li class="hsub <if condition="strpos(ACTION_NAME,'coupon') nheq false">open active</if>">
			<a href="#" class="dropdown-toggle"> 
				<i class="menu-icon fa fa-empire"></i>
				<span class="menu-text">优惠券验证</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu">
				<li <if condition="strpos(ACTION_NAME,'coupon') nheq false">class="active"</if>>
					<a href="{pigcms{:U('Store/coupon_list')}">
						<i class="menu-icon fa fa-caret-right"></i> 优惠券验证
					</a>
					<b class="arrow"></b>
				</li>					
			</ul>
		</li>
		<li class="hsub <if condition="in_array(ACTION_NAME,array('appoint_list','allot_appoint_list')) nheq false">open active</if>">
			<a href="#" class="dropdown-toggle"> 
				<i class="menu-icon fa fa-group"></i>
				<span class="menu-text">预约订单管理</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu">
				<li <if condition="ACTION_NAME eq appoint_list">class="active"</if>>
					<a href="{pigcms{:U('Store/appoint_list')}">
						<i class="menu-icon fa fa-caret-right"></i> 预约订单列表
					</a>
					<b class="arrow"></b>
				</li>	
                <li <if condition="ACTION_NAME eq allot_appoint_list">class="active"</if>>
					<a href="{pigcms{:U('Store/allot_appoint_list')}">
						<i class="menu-icon fa fa-caret-right"></i> 商家派发订单
					</a>
					<b class="arrow"></b>
				</li>					
			</ul>
		</li>
		<if condition="$config['is_cashier'] eq 1">
			<li class="hsub <if condition="in_array(ACTION_NAME,array('cashier','store_order','store_arrival','{pigcms{$config.cash_alias_name}')) nheq false">open active</if>">
				<a href="#" class="dropdown-toggle"> 
					<i class="menu-icon fa fa-money"></i>
					<span class="menu-text">到店消费</span>
					<b class="arrow fa fa-angle-down"></b>
				</a>
				<b class="arrow"></b>
				<ul class="submenu">
					<li <if condition="ACTION_NAME eq 'store_order'">class="active"</if>>
						<a href="{pigcms{:U('Store/store_order')}">
							<i class="menu-icon fa fa-caret-right"></i> 优惠买单
						</a>
						<b class="arrow"></b>
					</li>
					<li <if condition="ACTION_NAME eq 'store_arrival' || ACTION_NAME eq '{pigcms{$config.cash_alias_name}'">class="active"</if>>
						<a href="{pigcms{:U('Store/store_arrival')}">
							<i class="menu-icon fa fa-caret-right"></i> 店内消费
						</a>
						<b class="arrow"></b>
					</li>
					<li <if condition="ACTION_NAME eq 'cashier'">class="active"</if>>
						<a href="{pigcms{:U('Store/cashier')}">
							<i class="menu-icon fa fa-caret-right"></i> 收银台
						</a>
						<b class="arrow"></b>
					</li>	
				</ul>
			</li>
		</if>
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
		<link href="{pigcms{$static_path}css/footer.css" rel="stylesheet"/>
		<if condition="empty($no_gotop)">
			<div style="height:10px"></div>
			<div class="top-btn"><a class="react"><i class="text-icon">⇧</i></a></div>
		</if>
		<if condition="empty($no_footer)">
			<footer class="footermenu">
				<ul>
					<li>
						<a <if condition="MODULE_NAME eq 'Home'">class="active"</if> href="{pigcms{:U('Home/index')}">
							<em class="home"></em>
							<p>首页</p>
						</a>
					</li>
					<li>
						<a <if condition="MODULE_NAME eq 'Group'">class="active"</if> href="{pigcms{:U('Group/index')}">
							<em class="group"></em>
							<p>{pigcms{$config.group_alias_name}</p>
						</a>
					</li>
					<li>
						<a <if condition="in_array(MODULE_NAME,array('Meal_list','Meal')) AND $store_type eq 2">class="active"</if> href="{pigcms{:U('Meal_list/index', array('store_type' => 2))}">
							<em class="meal"></em>
							<p>{pigcms{$config.meal_alias_name}</p>
						</a>
					</li>
					<li>
						<a <if condition="in_array(MODULE_NAME,array('My','Login'))">class="active"</if> href="{pigcms{:U('My/index')}">
							<em class="my"></em>
							<p>我的</p>
						</a>
					</li>
				</ul>
			</footer>
		</if>
		<div style="display:none;">{pigcms{$config.wap_site_footer}</div>
        
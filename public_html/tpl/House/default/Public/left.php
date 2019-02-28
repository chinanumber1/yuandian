<div id="sidebar" class="sidebar responsive">
	<ul class="nav nav-list" style="top: 0px;">
		<li class="hsub <if condition="(strtolower(MODULE_NAME) eq 'index') && in_array(ACTION_NAME, array('index'))">open active</if>">
			<a href="{pigcms{:U('Index/index')}" >
				<i class="menu-icon fa fa-home"></i>
				<span class="menu-text">首页</span>
			</a>
			<b class="arrow"></b>
		</li>
		<if condition="in_array(1,$house_session['menus'])">
		<li class="hsub <if condition="(strtolower(MODULE_NAME) eq 'index' || strtolower(MODULE_NAME) eq 'role') && in_array(ACTION_NAME, array('config', 'worker', 'worker_add', 'worker_edit','worker_order','printer', 'printer_add', 'printer_edit','role_list', 'role_add', 'role_edit'))">open active</if>">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa fa-gear"></i>
				<span class="menu-text">社区管理</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu">
			<if condition="in_array(2,$house_session['menus'])">
				<li <?php if ((strtolower(MODULE_NAME) == 'index') && (strtolower(ACTION_NAME) == 'config')){ ?>class="active"<?php }?>>
					<a href="{pigcms{:U('Index/config')}">
						<i class="menu-icon fa fa-caret-right"></i> 基本信息
					</a>
					<b class="arrow"></b>
				</li>
			</if>
			<if condition="in_array(4,$house_session['menus'])">
				<li <?php if ((strtolower(MODULE_NAME) == 'index') && in_array(ACTION_NAME, array('worker', 'worker_add', 'worker_edit','worker_order'))){ ?>class="active"<?php }?>>
					<a href="{pigcms{:U('Index/worker')}">
						<i class="menu-icon fa fa-caret-right"></i> 工作人员
					</a>
					<b class="arrow"></b>
				</li>
			</if>
			<if condition="in_array(8,$house_session['menus'])">
				<li <?php if ((strtolower(MODULE_NAME) == 'role') && in_array(ACTION_NAME, array('role_list', 'role_add', 'role_edit'))){ ?>class="active"<?php }?>>
					<a href="{pigcms{:U('Role/role_list')}">
						<i class="menu-icon fa fa-caret-right"></i> 权限管理
					</a>
					<b class="arrow"></b>
				</li>
			</if>
			<if condition="in_array(12,$house_session['menus'])">
				<li <?php if ((strtolower(MODULE_NAME) == 'index') && in_array(ACTION_NAME, array('printer', 'printer_add', 'printer_edit'))){ ?>class="active"<?php }?>>
					<a href="{pigcms{:U('Index/printer')}">
						<i class="menu-icon fa fa-caret-right"></i> 打印机管理
					</a>
					<b class="arrow"></b>
				</li>
			</if>
			</ul>
		</li>
		</if>

		<if condition="in_array(16,$house_session['menus'])">
		<li class="hsub <if condition="MODULE_NAME eq 'Promote' && in_array(ACTION_NAME,array('index'))">open active</if>">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa fa-qrcode"></i>
				<span class="menu-text">社区推广</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu">
        		<php>if (in_array(17,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'Promote' && in_array(ACTION_NAME,array('index'))">class="active"</if>>
					<a href="{pigcms{:U('Promote/index')}">
						<i class="menu-icon fa fa-caret-right"></i> 推广二维码
					</a>
					<b class="arrow"></b>
				</li>
        		<php> } </php>
			</ul>
		</li>
		</if>

        <php>if ($house_session['is_open_estate'] == 1) { </php>
			<if condition="in_array(19,$house_session['menus'])">
	        <li class="hsub <if condition="(in_array(MODULE_NAME ,array('Unit','Library','Village_money'))&& in_array(ACTION_NAME,array('index','unit_add','unit_edit','unittype_list','unittype_add','unittype_edit','pay_order_all','pay_order','preferential_list','preferential_add','preferential_edit','merchant_order','import_village_add','import_village','import_village_edit','import_village_add','owner_arrival_add','owner_arrival','money_list','recharge','withdraw','sms_note','parking_management','vehicle_management','parking_add','garage_management','garage_add','parking_edit','garage_edit','parking_position_addall','vehicle_add','deposit_management','deposit_add','deposit_refund','parking_position_add','count_management','parking_detail','vehicle_edit','vehicle_detail','vehicle_import_add','parking_import_add','cashier_unpaid')))">open active</if>">
				<a href="#" class="dropdown-toggle">
					<i class="menu-icon fa fa-briefcase"></i>
					<span class="menu-text">物业管理</span>
					<b class="arrow fa fa-angle-down"></b>
				</a>	
				<b class="arrow"></b>
				<ul class="submenu">
	        		<php>if (in_array(24,$house_session['menus'])) { </php>
					<li <if condition="(MODULE_NAME eq 'Unit') && in_array(ACTION_NAME,array('unittype_list','unittype_add','unittype_edit'))">class="active"</if>>
						<a href="{pigcms{:U('Unit/unittype_list')}">
							<i class="menu-icon fa fa-caret-right"></i> 单元类型列表
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>

	        		<php>if (in_array(20,$house_session['menus'])) { </php>
					<li <if condition="MODULE_NAME eq 'Unit' && in_array(ACTION_NAME,array('index','unit_add','unit_edit'))">class="active"</if>>
						<a href="{pigcms{:U('Unit/index')}">
							<i class="menu-icon fa fa-caret-right"></i> 单元列表
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>
					
	        		<php>if (in_array(37,$house_session['menus'])) { </php>
					<li <if condition="(MODULE_NAME eq 'Unit') && in_array(ACTION_NAME,array('import_village','import_village_add','import_village_edit','import_village_add'))">class="active"</if>>
						<a href="{pigcms{:U('Unit/import_village')}">
							<i class="menu-icon fa fa-caret-right"></i> 房间管理
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>
					
	        		<php>if (in_array(28,$house_session['menus'])) { </php>
					<li <if condition="(MODULE_NAME eq 'Unit') && in_array(ACTION_NAME,array('preferential_list','preferential_add','preferential_edit'))">class="active"</if>>
						<a href="{pigcms{:U('Unit/preferential_list')}">
							<i class="menu-icon fa fa-caret-right"></i> 物业费缴费周期管理
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>
					
	        		<php>if (in_array(60,$house_session['menus'])) { </php>
					<li <if condition="(MODULE_NAME eq 'Village_money') && in_array(ACTION_NAME,array('money_list','recharge','check','go_pay','withdraw','sms_note'))">class="active"</if>>
						<a href="{pigcms{:U('Village_money/money_list')}">
							<i class="menu-icon fa fa-caret-right"></i> 物业余额
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>
					
	        		<php>if (in_array(32,$house_session['menus'])) { </php>
					<li <if condition="(in_array(MODULE_NAME ,array('Unit','Library'))) && in_array(ACTION_NAME,array('pay_order_all','pay_order','owner_arrival','owner_arrival_add'))">class="active"</if>>
						<a href="{pigcms{:U('Unit/pay_order')}">
							<i class="menu-icon fa fa-caret-right"></i> 物业已缴总账单
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>

	        		<php>if (in_array(85,$house_session['menus'])) { </php>
					<li <if condition="(MODULE_NAME eq 'Unit') && in_array(ACTION_NAME,array('cashier_unpaid'))">class="active"</if>>
						<a href="{pigcms{:U('Unit/cashier_unpaid')}">
							<i class="menu-icon fa fa-caret-right"></i> 物业未缴总账单
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>
					
	        		<php>if (in_array(36,$house_session['menus'])) { </php>
					<li <if condition="(MODULE_NAME eq 'Unit') && in_array(ACTION_NAME,array('merchant_order'))">class="active"</if>>
						<a href="{pigcms{:U('Unit/merchant_order')}">
							<i class="menu-icon fa fa-caret-right"></i> 物业商家流水
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>

	        		<php>if (in_array(41,$house_session['menus'])) { </php>
					<li <if condition="(MODULE_NAME eq 'Unit') && in_array(ACTION_NAME,array('deposit_management','deposit_add','deposit_refund'))">class="active"</if>>
						<a href="{pigcms{:U('Unit/deposit_management')}">
							<i class="menu-icon fa fa-caret-right"></i> 押金管理
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>

	        		<php>if (in_array(45,$house_session['menus'])) { </php>
					<li <if condition="(MODULE_NAME eq 'Unit') && in_array(ACTION_NAME,array('parking_management','parking_add','garage_add','parking_edit','garage_edit','parking_position_addall','parking_position_add','parking_detail','parking_import_add','garage_management'))">class="active"</if>>
						<a href="{pigcms{:U('Unit/parking_management')}">
							<i class="menu-icon fa fa-caret-right"></i> 车位管理
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>

	        		<php>if (in_array(55,$house_session['menus'])) { </php>
					<li <if condition="(MODULE_NAME eq 'Unit') && in_array(ACTION_NAME,array('vehicle_management','vehicle_add','vehicle_edit','vehicle_detail','vehicle_import_add'))">class="active"</if>>
						<a href="{pigcms{:U('Unit/vehicle_management')}">
							<i class="menu-icon fa fa-caret-right"></i> 车辆管理
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>

	        		<php>if (in_array(64,$house_session['menus'])) { </php>
					<li <if condition="(MODULE_NAME eq 'Unit') && in_array(ACTION_NAME,array('count_management'))">class="active"</if>>
						<a href="{pigcms{:U('Unit/count_management')}">
							<i class="menu-icon fa fa-caret-right"></i> 统计管理
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>
				</ul>
			</li>
			</if>
			
        <php> } </php>
        <if condition="in_array(65,$house_session['menus'])">
			<li class="hsub <if condition="(in_array(MODULE_NAME ,array('Cashier','Library'))&& in_array(ACTION_NAME,array('cashier','payment_item','payment_item_add','payment_item_edit','payment_standard','payment_standard_add','personal_order_list','cashier_paid_list','history_cashier_order','cashier_detail','print_template_list','print_template_detail','print_template_add','print_template_del','print_template_save','print_template_custom','save_custom','print_start','owner_order_add','pay_type_list','pay_type_add','payment_standard_edit')))">open active</if>">
				<a href="#" class="dropdown-toggle">
					<i class="menu-icon fa fa-laptop"></i>
					<span class="menu-text">收费管理</span>
					<b class="arrow fa fa-angle-down"></b>
				</a>	
				<b class="arrow"></b>
				<ul class="submenu">
	        		<php>if (in_array(66,$house_session['menus'])) { </php>
					<li <if condition="(in_array(MODULE_NAME ,array('Cashier','Library'))) && in_array(ACTION_NAME,array('cashier','personal_order_list','owner_order_add','history_cashier_order'))">class="active"</if>>
						<a href="{pigcms{:U('Cashier/cashier')}">
							<i class="menu-icon fa fa-caret-right"></i> 收银台
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>

	        		<php>if (in_array(83,$house_session['menus'])) { </php>
					<li <if condition="(MODULE_NAME eq 'Cashier') && in_array(ACTION_NAME,array('cashier_paid_list'))">class="active"</if>>
						<a href="{pigcms{:U('Cashier/cashier_paid_list')}">
							<i class="menu-icon fa fa-caret-right"></i> 收银台已缴账单
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>

	        		<php>if (in_array(71,$house_session['menus'])) { </php>
					<li <if condition="MODULE_NAME eq 'Cashier' && in_array(ACTION_NAME,array('payment_item','payment_item_add','payment_item_edit','payment_standard','payment_standard_add','pay_type_list','pay_type_add','payment_standard_edit'))">class="active"</if>>
						<a href="{pigcms{:U('Cashier/payment_item')}">
							<i class="menu-icon fa fa-caret-right"></i> 收费设置
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>

	        		<php>if (in_array(86,$house_session['menus'])) { </php>
					<li <if condition="(MODULE_NAME eq 'Cashier') && in_array(ACTION_NAME,array('print_template_list','print_template_detail','print_template_add','print_template_del','print_template_save','save_custom','print_template_custom'))">class="active"</if>>
						<a href="{pigcms{:U('Cashier/print_template_list')}">
							<i class="menu-icon fa fa-caret-right"></i> 打印模板设置
						</a>
						<b class="arrow"></b>
					</li>
	        		<php> } </php>

				</ul>
			</li>
			</if>

		<if condition="in_array(90,$house_session['menus'])">
		<li class="hsub <if condition="MODULE_NAME eq 'User' && in_array(ACTION_NAME,array('index','user_import','detail_import','edit','orders','pay_detail','user_add','user_data','audit_index','audit_edit','audit_unbind','audit_unbind_edit','bind_audit_list'))">open active</if>">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa fa-user"></i>
				<span class="menu-text">业主管理</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu">
	        	<php>if (in_array(91,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'User' && in_array(ACTION_NAME,array('index','user_import','detail_import','edit','orders','pay_detail','user_add','user_data'))">class="active"</if>>
					<a href="{pigcms{:U('User/index')}">
						<i class="menu-icon fa fa-caret-right"></i> 业主列表
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(101,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'User' && in_array(ACTION_NAME,array('audit_index','audit_edit'))">class="active"</if>>
					<a href="{pigcms{:U('User/audit_index')}">
						<i class="menu-icon fa fa-caret-right"></i> 业主审核列表
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(103,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'User' && in_array(ACTION_NAME,array('bind_audit_list'))">class="active"</if>>
					<a href="{pigcms{:U('User/bind_audit_list')}">
						<i class="menu-icon fa fa-caret-right"></i> 家属审核列表
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>
                
	        	<php>if (in_array(107,$house_session['menus'])) { </php>
                <li <if condition="MODULE_NAME eq 'User' && in_array(ACTION_NAME,array('audit_unbind','audit_unbind_edit'))">class="active"</if>>
					<a href="{pigcms{:U('User/audit_unbind')}">
						<i class="menu-icon fa fa-caret-right"></i> 申请解绑列表
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>
			</ul>
		</li>
		</if>

		<if condition="in_array(121,$house_session['menus'])">
		<li class="hsub <if condition="(MODULE_NAME eq 'News' && in_array(ACTION_NAME,array('reply','suggess'))) || (MODULE_NAME eq 'Repair' && in_array(ACTION_NAME,array('suggess')))">open active</if>">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa fa-group"></i>
				<span class="menu-text">业主交流</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu">
	        	<php>if (in_array(122,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'News' && in_array(ACTION_NAME,array('reply'))">class="active"</if>>
					<a href="{pigcms{:U('News/reply')}">
						<i class="menu-icon fa fa-caret-right"></i> 新闻评论列表
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>
				<!--li <if condition="MODULE_NAME eq 'Repair' && in_array(ACTION_NAME,array('suggess'))">class="active"</if>>
					<a href="{pigcms{:U('Repair/suggess')}">
						<i class="menu-icon fa fa-caret-right"></i> 投诉建议列表
					</a>
					<b class="arrow"></b>
				</li-->
			</ul>
		</li>
		</if>

		<if condition="in_array(127,$house_session['menus'])">
		<li class="<if condition="MODULE_NAME eq 'Bbs'">active</if>">
			<a href="{pigcms{:U('Bbs/index')}">
				<i class="menu-icon fa fa-comments-o"></i>
				<span class="menu-text">社区论坛</span>
			</a>
		</li>
		</if>

		<if condition="in_array(139,$house_session['menus'])">
		<li class="hsub <if condition="MODULE_NAME eq 'Index' && strpos(ACTION_NAME,'active') nheq false">open active</if>">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa fa-empire"></i>
				<span class="menu-text">推荐活动管理</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu">
	        	<php>if (in_array(140,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'Index' && in_array(ACTION_NAME,array('active_group_list','active_group'))">class="active"</if>>
					<a href="{pigcms{:U('Index/active_group_list')}">
						<i class="menu-icon fa fa-caret-right"></i> {pigcms{$config.group_alias_name}列表
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(143,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'Index' && in_array(ACTION_NAME,array('active_meal_list','active_meal'))">class="active"</if>>
					<a href="{pigcms{:U('Index/active_meal_list')}">
						<i class="menu-icon fa fa-caret-right"></i> {pigcms{$config.meal_alias_name}列表
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>
       		 	
	        	<php>if (in_array(146,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'Index' && in_array(ACTION_NAME,array('active_appoint_list','active_appoint','active_appoint_edit'))">class="active"</if>>
					<a href="{pigcms{:U('Index/active_appoint_list')}">
						<i class="menu-icon fa fa-caret-right"></i> 预约列表
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>
       		 	
	        	<php>if (in_array(150,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'Index' && in_array(ACTION_NAME,array('active_store_list','active_store','active_store_edit'))">class="active"</if>>
					<a href="{pigcms{:U('Index/active_store_list')}">
						<i class="menu-icon fa fa-caret-right"></i> 快店管理
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>
       		 	
			</ul>
		</li>
		</if>

		<if condition="in_array(154,$house_session['menus'])">
		<li class="hsub <if condition="MODULE_NAME eq 'News' && in_array(ACTION_NAME,array('index','news_edit','cate','cate_edit'))">open active</if>">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa fa-tablet"></i>
				<span class="menu-text">新闻管理</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu">
	        	<php>if (in_array(155,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'News' &&  in_array(ACTION_NAME,array('cate','cate_edit'))">class="active"</if>>
					<a href="{pigcms{:U('News/cate')}">
						<i class="menu-icon fa fa-caret-right"></i> 新闻分类
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>
       		 	
	        	<php>if (in_array(158,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'News' && in_array(ACTION_NAME,array('index','news_edit'))">class="active"</if>>
					<a href="{pigcms{:U('News/index')}">
						<i class="menu-icon fa fa-caret-right"></i> 新闻列表
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>
			</ul>
		</li>
		</if>

		<if condition="in_array(163,$house_session['menus'])">
        <li class="hsub <if condition="((MODULE_NAME eq 'Service') && (in_array(ACTION_NAME,array('service_category','service_category_add','service_category_edit','s_service_category'))))  OR (MODULE_NAME eq 'Service' && (in_array(ACTION_NAME,array('service_info','service_info_add','service_info_edit')))) OR (MODULE_NAME eq 'Service' && (in_array(ACTION_NAME,array('service_slide','service_slide_add','service_slide_edit'))))">open active</if>">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa fa-columns"></i>
				<span class="menu-text">便民服务</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu">
	        	<php>if (in_array(164,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'Service' && (in_array(ACTION_NAME,array('service_category','service_category_add','service_category_edit','s_service_category')))">class="active"</if>>
					<a href="{pigcms{:U('Service/service_category')}">
						<i class="menu-icon fa fa-caret-right"></i> 便民分类
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>
	        	<php>if (in_array(172,$house_session['menus'])) { </php>
                <li <if condition="MODULE_NAME eq 'Service' && (in_array(ACTION_NAME,array('service_info','service_info_add','service_info_edit')))">class="active"</if>>
					<a href="{pigcms{:U('Service/service_info')}">
						<i class="menu-icon fa fa-caret-right"></i> 便民列表
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(244,$house_session['menus']) && $house_session['has_service_slide'] == 1) { </php>
                <li <if condition="MODULE_NAME eq 'Service' && (in_array(ACTION_NAME,array('service_slide','service_slide_add','service_slide_edit')))">class="active"</if>>
					<a href="{pigcms{:U('Service/service_slide')}">
						<i class="menu-icon fa fa-caret-right"></i> 便民页面幻灯片
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>
			</ul>
		</li>
		</if>

		<if condition="in_array(176,$house_session['menus'])">
        <li class="hsub <if condition="MODULE_NAME eq 'Weixin'">open active</if>">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa fa-wechat"></i>
				<span class="menu-text">公众号设置</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu">
	        	<php>if (in_array(177,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'Weixin' && (in_array(ACTION_NAME,array('index')))">class="active"</if>>
					<a href="{pigcms{:U('Weixin/index')}">
						<i class="menu-icon fa fa-caret-right"></i> 公众号绑定
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(179,$house_session['menus'])) { </php>
                <li <if condition="MODULE_NAME eq 'Weixin' && (in_array(ACTION_NAME,array('auto')))">class="active"</if>>
					<a href="{pigcms{:U('Weixin/auto')}">
						<i class="menu-icon fa fa-caret-right"></i> 自动回复
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(181,$house_session['menus'])) { </php>
                 <li <if condition="MODULE_NAME eq 'Weixin' && (in_array(ACTION_NAME,array('menu')))">class="active"</if>>
					<a href="{pigcms{:U('Weixin/menu')}">
						<i class="menu-icon fa fa-caret-right"></i>自定义菜单
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>
       		 	
	        	<php>if (in_array(183,$house_session['menus'])) { </php>
                  <li <if condition="MODULE_NAME eq 'Weixin' && (in_array(ACTION_NAME,array('txt', 'reply_txt', 'img', 'reply_img')))">class="active"</if>>
					<a href="{pigcms{:U('Weixin/txt')}">
						<i class="menu-icon fa fa-caret-right"></i> 关键词回复
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(190,$house_session['menus'])) { </php>
                  <li <if condition="MODULE_NAME eq 'Weixin' && (in_array(ACTION_NAME,array('article', 'one', 'multi')))">class="active"</if>>
					<a href="{pigcms{:U('Weixin/article')}">
						<i class="menu-icon fa fa-caret-right"></i> 图文素材
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>
			</ul>
		</li>
		</if>

		<if condition="in_array(194,$house_session['menus'])">
		<li class="hsub <if condition="((MODULE_NAME eq 'Library') && (in_array(ACTION_NAME,array('express_service_list','visitor_list','visitor_add','express_add','index_nav','nav_add','nav_edit','express_send_list')))) OR ((MODULE_NAME eq 'User') && (in_array(ACTION_NAME,array('village_order')))) OR ((MODULE_NAME eq 'Repair') && (in_array(ACTION_NAME,array('water','index','village_suggest')))) OR ((MODULE_NAME eq 'Activity') && (in_array(ACTION_NAME,array('index','activity_add','activity_edit','apply_list','apply_edit')))) OR (MODULE_NAME eq 'Slide') OR (MODULE_NAME eq 'Openphone') OR (MODULE_NAME eq 'Door')">open active</if>">
			<a href="#" class="dropdown-toggle">
				<i class="menu-icon fa fa-shopping-cart"></i>
				<span class="menu-text">功能库</span>
				<b class="arrow fa fa-angle-down"></b>
			</a>
			<b class="arrow"></b>
			<ul class="submenu">
	        	<php>if (in_array(195,$house_session['menus'])) { </php>
				<li <if condition="in_array(MODULE_NAME,array('Slide'))">class="active"</if>>
					<a href="{pigcms{:U('Slide/index')}">
						<i class="menu-icon fa fa-caret-right"></i>首页幻灯片
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(199,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'Openphone'">class="active"</if>>
					<a href="{pigcms{:U('Openphone/phone')}">
						<i class="menu-icon fa fa-caret-right"></i> 常用电话
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(207,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'Library' && (in_array(ACTION_NAME,array('express_service_list','express_add')))">class="active"</if>>
					<a href="{pigcms{:U('Library/express_service_list')}">
						<i class="menu-icon fa fa-caret-right"></i> 快递代收
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(214,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'Library' && (in_array(ACTION_NAME,array('express_send_list')))">class="active"</if>>
					<a href="{pigcms{:U('Library/express_send_list')}">
						<i class="menu-icon fa fa-caret-right"></i> 快递代发
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(216,$house_session['menus'])) { </php>
                <li <if condition="MODULE_NAME eq 'Library' && (in_array(ACTION_NAME,array('visitor_list','visitor_add')))">class="active"</if>>
					<a href="{pigcms{:U('Library/visitor_list')}">
						<i class="menu-icon fa fa-caret-right"></i> 访客登记
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(219,$house_session['menus'])) { </php>
                <li <if condition="MODULE_NAME eq 'Repair' && ACTION_NAME eq 'index'">class="active"</if>>
					<a href="{pigcms{:U('Repair/index')}">
						<i class="menu-icon fa fa-caret-right"></i> 在线报修
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(222,$house_session['menus'])) { </php>
                <li <if condition="MODULE_NAME eq 'Repair' && ACTION_NAME eq 'water'">class="active"</if>>
					<a href="{pigcms{:U('Repair/water')}">
						<i class="menu-icon fa fa-caret-right"></i> 水电煤上报
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>
                <!--li <if condition="MODULE_NAME eq 'User' && ACTION_NAME eq 'village_order'">class="active"</if>>
					<a href="{pigcms{:U('User/village_order')}">
						<i class="menu-icon fa fa-caret-right"></i> 在线缴费订单
					</a>
					<b class="arrow"></b>
				</li-->

	        	<php>if (in_array(224,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'Repair' && ACTION_NAME eq 'village_suggest'">class="active"</if>>
					<a href="{pigcms{:U('Repair/village_suggest')}">
						<i class="menu-icon fa fa-caret-right"></i> 投诉建议
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(226,$house_session['menus'])) { </php>
				<li <if condition="MODULE_NAME eq 'Door'">class="active"</if>>
					<a href="{pigcms{:U('Door/door_list')}">
						<i class="menu-icon fa fa-caret-right"></i> 门禁设置
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(232,$house_session['menus'])) { </php>
				<li <if condition="(MODULE_NAME eq 'Activity') AND (in_array(ACTION_NAME,array('activity_edit','index','activity_add')))">class="active"</if>>
					<a href="{pigcms{:U('Activity/index')}">
						<i class="menu-icon fa fa-caret-right"></i> 社区活动
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(236,$house_session['menus'])) { </php>
				<li <if condition="(MODULE_NAME eq 'Activity') AND (in_array(ACTION_NAME,array('apply_list','apply_edit')))">class="active"</if>>
					<a href="{pigcms{:U('Activity/apply_list')}">
						<i class="menu-icon fa fa-caret-right"></i> 社区活动报名列表
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>

	        	<php>if (in_array(239,$house_session['menus'])) { </php>
				<li <if condition="(MODULE_NAME eq 'Library') AND (in_array(ACTION_NAME,array('index_nav','nav_add','nav_edit')))">class="active"</if>>
					<a href="{pigcms{:U('Library/index_nav')}">
						<i class="menu-icon fa fa-caret-right"></i> 首页自定义导航
					</a>
					<b class="arrow"></b>
				</li>
       		 	<php> } </php>
				
				<!--li  <if condition="(MODULE_NAME eq 'Library') AND (in_array(ACTION_NAME,array('owner_arrival','owner_arrival_add')))">class="active"</if>>
					<a href="{pigcms{:U('Library/owner_arrival')}">
						<i class="menu-icon fa fa-caret-right"></i> 在线付款
					</a>
					<b class="arrow"></b>
				</li-->
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
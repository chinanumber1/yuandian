<div id="navbar" class="navbar navbar-default" <if condition="C('butt_open') || $no_sidebar">style="display:none;"</if>>
	<div class="navbar-container" id="navbar-container">
		<button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler">
			<span class="sr-only">Toggle sidebar</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<div class="navbar-header pull-left">
			<a href="{pigcms{:U('Store/index')}" class="navbar-brand" style="padding: 5px 0 0 0;"> 
				<small> 
					<img src="{pigcms{$config.site_merchant_logo}" style="height:38px;width:38px;"/> {pigcms{$config.site_name} - 店员中心
				</small>
			</a>
		</div>
		<div class="navbar-buttons navbar-header pull-right" role="navigation">
			<ul class="nav ace-nav">
				<li class="light-blue">
					<a data-toggle="dropdown" href="#" class="dropdown-toggle"> 
						<img class="nav-user-photo" src="{pigcms{$static_public}images/user.jpg" alt="Jason&#39;s Photo" /> 
						<span class="user-info"> <small>欢迎您，</small> {pigcms{$staff_session.name}</span> 
						<i class="ace-icon fa fa-caret-down"></i>
					</a>
					<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
						<li>
							<a href="{pigcms{:U('Store/index')}">
								<i class="ace-icon fa fa-link"></i> 返回首页
							</a>
						</li>
						<li class="divider"></li>
						<li>
							<a href="{pigcms{:U('Store/logout')}"> 
								<i class="ace-icon fa fa-power-off"></i> 退出
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
	</div>
</div>
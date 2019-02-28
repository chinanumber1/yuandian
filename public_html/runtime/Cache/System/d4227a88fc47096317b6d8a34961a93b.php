<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="<?php echo ($static_path); ?>css/index.css?t=<?php echo ($_SERVER["REQUEST_TIME"]); ?>"/>
		<?php if($config['site_favicon']): ?><link rel="shortcut icon" href="<?php echo ($config["site_favicon"]); ?>"/><?php endif; ?>
		<title>后台管理 - <?php echo ($config["site_name"]); ?></title>
		<script type="text/javascript">if(self!=top){window.top.location.href = "<?php echo U('Index/index');?>";}var selected_module="<?php echo strval($_GET['module']);?>",selected_action="<?php echo strval($_GET['action']);?>",selected_url="<?php echo urldecode(strval(htmlspecialchars_decode($_GET['url'])));?>";</script>
		<script type="text/javascript" src="<?php echo C('JQUERY_FILE');?>"></script>
		<script type="text/javascript" src="<?php echo ($static_public); ?>js/artdialog/jquery.artDialog.js"></script>
		<script type="text/javascript" src="<?php echo ($static_public); ?>js/artdialog/iframeTools.js"></script>
		<script type="text/javascript" src="<?php echo ($static_public); ?>js/jquery.colorpicker.js"></script>
		<script type="text/javascript" src="<?php echo ($static_public); ?>js/screenfull.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo ($static_public); ?>font-awesome/css/font-awesome.css"/>
	</head>
	<body style="background:#E2E9EA;overflow:hidden;">
		<div id="Main_content" >
			<div id="HelpBox" style="width:0px;position:relative;z-index:9999;right:-200px; height:100%; overflow:hidden">
				<div id="right_open" style="position:fixed;right:0px;top:80px;cursor:pointer;"><img src="<?php echo ($static_path); ?>images/jt_05.png" /></div>
				<div id="right_close" style="position:fixed;right:200px;top:80px;display:none;cursor:pointer;"><img src="<?php echo ($static_path); ?>images/jt_04.png" /></div>
				<div class="helpTitle">帮助中心&nbsp;&nbsp;<i class="fa fa-question-circle-o"></i></div>
				<div class="helpContent">
					<div id="indexProfile">
						<div class="helpContentTitle">个人信息</div>
						<div class="helpContentContent" style="padding-top:0px;margin-bottom:0px;">
							<p><span>会员名：</span><?php echo ($system_session["account"]); ?></p>
							<p><span>会员组：</span><?php echo ($system_session["show_account"]); ?></p>
							<p><span>最后登录时间：</span><?php echo (date('Y-m-d H:i:s',$system_session["last_time"])); ?></p>
							<p><span>最后登录IP/地址：</span><?php echo (long2ip($system_session["last_ip"])); ?> / <?php echo ($system_session["last"]["country"]); ?> <?php echo ($system_session["last"]["area"]); ?></p>
							<p><span>登录次数：</span><?php echo ($system_session["login_count"]); ?></p>
						</div>
						<hr/>
						<div class="helpContentTitle" style="margin-top:20px;">系统信息</div>
						<div class="helpContentContent" style="padding-top:0px;margin-bottom:0px;">
							<?php if(is_array($server_info)): $i = 0; $__LIST__ = $server_info;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><p><span><?php echo ($key); ?>:</span><?php echo ($vo); ?></p><?php endforeach; endif; else: echo "" ;endif; ?>
						</div>
						<hr/>
						<div <?php if($config['open_help'] == 0 && $system_session['level'] != 2): ?>style="display:none;"<?php endif; ?>>
							<div class="helpContentTitle" style="margin-top:20px;">官方动态</div>
							<div class="helpContentContent" style="padding-top:0px;margin-bottom:0px;" id="official_news">
							</div>
						</div>
					</div>
					<div id="helpBox" style="display:none;">
						<div class="helpContentTitle">
							帮助文档
						</div>
						<div class="helpContentContent" <?php if($config['open_help'] == 0 && $system_session['level'] != 2): ?>style="display:none;"<?php endif; ?>>
							<div id="helpContentText"></div>
							<p id="loadHelpContent">文档加载中...</p>
							<p id="emptyHelpContent" style="display:none;">暂无官方帮助文档</p>
							<div id="helpContentBox"></div>
						</div>
					</div>
					<div class="helpContentLine"></div>
					<div class="helpContentContent"  <?php if($system_session['level'] != 2): ?>style="display:none;"<?php endif; ?>>
						<p><a href="<?php echo U('Updatesys/comein');?>" target="blank" id="postWorkorderBtn"><i class="fa fa-edit"></i>&nbsp;&nbsp;提交售后工单</a></p>
						<p><a href="http://wpa.qq.com/msgrd?v=3&uin=800022936&site=qq&menu=yes" target="_blank"><i class="fa fa-qq"></i>&nbsp;&nbsp;联系售后QQ</a></p>
					</div>
				</div>
			</div>
			<div id="leftMenuBox">
				<div id="leftHideBtn">
					<i class="fa fa-dedent" title="收缩左侧导航"></i>
					<i class="fa fa-indent" title="展开左侧导航"></i>
				</div>
				<div id="leftProfile">
					<div class="profile-2-wrapper">
						<div class="profile-2-details">
							<div class="profile-2-img">
								<?php if($system_session['level'] == 2 || $config['system_admin_logo'] != ''): ?><a href="<?php echo U('Index/index');?>"><img src="<?php if($config["system_admin_logo"] != ''): echo ($config["system_admin_logo"]); else: echo ($static_path); ?>images/pigcms_logo.png<?php endif; ?>"/></a>
								<?php else: ?>
									&nbsp;<?php endif; ?>
							</div>
							<ul class="profile-2-info">
								<li>
									<h3><?php echo ($system_session["account"]); ?></h3>
								</li>
								<li style="color:#999;"><?php echo ($system_session["show_account"]); ?></li>
								<?php if($now_area): ?><li style="color:#999;"><?php echo ($now_area["area_name"]); ?></li><?php endif; ?>
								<li>
									<div class="btn-group btn-group-sm btn-group-justified">
										<a href="<?php echo ($config["site_url"]); ?>" target="_blank" class="toggle-visitors btn btn-dark tt-top tooltipstered" title="浏览网站"><i class="fa fa-tv"></i></a>
										<a href="javascript:addIframe('清空缓存','leftmenu_Index_cache','<?php echo U('Index/cache',array('no_back'=>1));?>')" class="btn btn-dark tt-top tooltipstered" title="清空缓存"><i class="fa fa-refresh"></i></a>
										<a href="<?php echo U('Login/logout');?>" class="toggle-stats btn btn-dark tt-top tooltipstered" title="退出登录"><i class="fa fa-sign-out"></i></a>
									</div>
								</li>
							</ul>
						</div>
					</div>
					<div id="leftNavBox">
						<ul>
							<!--li class="title_bar">
								<span>导航列表</span>
							</li--> 
						
							<?php if($system_session['level']==2 || strpos($system_session['menus'],'9999')!==false){ ?>
							
						
							<li class="active nav-top">
								<a href="<?php echo U('Index/main');?>" target="main" class="auto" id="leftmenu_Index_main">
									<i class="fa fa-line-chart icon"></i>
									<span class="font-bold">概况</span>
								</a>
							</li>
							<?php } ?>
							<?php if(is_array($system_menu)): $i = 0; $__LIST__ = $system_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="nav-top">
									<a class="auto" href="#">
										<span class="pull-right text-muted">
											<i class="fa fa-fw fa-angle-right text"></i>
											<i class="fa fa-fw fa-angle-down text-active"></i>
										</span>
										<i class="fa fa-<?php echo ($vo["icon"]); ?> icon"></i>
										<span class="font-bold"><?php echo ($vo["name"]); ?></span>
									</a>
									<ul class="nav nav-sub dk">
										<li class="sub-title"><?php echo ($vo["name"]); ?></li>
										<?php if(is_array($vo['menu_list'])): $i = 0; $__LIST__ = $vo['menu_list'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><li>
												<a target="main" href="<?php echo U(ucfirst($voo['module']).'/'.$voo['action']);?>"  id="leftmenu_<?php echo ucfirst($voo['module']);?>_<?php echo ($voo['action']); ?>">
													<span><?php echo ($voo["name"]); ?></span>
												</a>
											</li><?php endforeach; endif; else: echo "" ;endif; ?>
									</ul>
								</li><?php endforeach; endif; else: echo "" ;endif; ?>
						</ul>
					</div>
				</div>
			</div>
			<div id="MainBox" >
				<div class="main_box">
					<div id="fullscreenBtn" title="全屏显示"><i class="fa fa-expand"></i><i class="fa fa-compress"></i></div>
					<div id="sx" onclick="main_refresh();" title="刷新框架"></div>
					<div class="layadmin-pagetabs" id="LAY_app_tabs"> 
						<div class="layui-icon layadmin-tabs-control layui-icon-prev fa fa-angle-double-left" layadmin-event="leftPage"></div> 
						<div class="layui-icon layadmin-tabs-control layui-icon-next" layadmin-event="rightPage"> >> </div> 
						<div class="layui-icon layadmin-tabs-control layui-icon-down"> 
							<ul class="layui-nav layadmin-tabs-select" lay-filter="layadmin-pagetabs-nav"> 
								<li class="layui-nav-item" lay-unselect=""> 
									<a href="javascript:;"><span class="layui-nav-more">></span></a> 
									<dl class="layui-nav-child layui-anim-fadein"> 
										<dd><a href="javascript:;" id="closeThisTabs">关闭当前标签页</a></dd> 
										<dd><a href="javascript:;" id="closeOtherTabs">关闭其它标签页</a></dd> 
										<dd><a href="javascript:;" id="closeAllTabs">关闭全部标签页</a></dd> 
									</dl>
								</li>
							<span class="layui-nav-bar"></span></ul>
						</div>
						<div class="layui-tab" lay-allowclose="true" lay-filter="layadmin-layout-tabs">
							<ul class="layui-tab-title" id="LAY_app_tabsheader"></ul>
						</div> 
					</div>
					<div class="iframe-body" id="Main"></div>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
		<script type="text/javascript" src="<?php echo ($static_path); ?>js/index.js?t=<?php echo ($_SERVER["REQUEST_TIME"]); ?>"></script>
		<?php if($system_session['level'] == 2): ?><script type="text/javascript" src="https://o2o-service.pigcms.com/updatetips.php?soft_version=<?php echo ($config["system_version"]); ?>&domain=<?php echo ($_SERVER["SERVER_NAME"]); ?>"></script><?php endif; ?>
		<script type="text/javascript">
			$("#right_open").click(function(){
				$("#HelpBox").css('right','0px');
				$("#HelpBox").css('width',"200px");
				$("#right_open").hide();
				$("#right_close").show();
				$('#MainBox').css('margin-right','200px');
			})
			$("#right_close").click(function(){
				$("#HelpBox").css('right','-200px');
				$("#HelpBox").css('width',"0px");
				$("#right_open").show();
				$("#right_close").hide();
				$('#MainBox').css('margin-right','0px');
			})
		</script>
		<?php if($config['system_keep_login']): ?><script type="text/javascript">
				setInterval(function(){
					$.get("<?php echo U('loginKeep');?>")
				},300000);
			</script><?php endif; ?>
	</body>
</html>
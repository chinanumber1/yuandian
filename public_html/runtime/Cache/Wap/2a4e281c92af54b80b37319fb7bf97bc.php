<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title>登录 - <?php echo ($config["site_name"]); ?></title>
	<meta name="description" content="<?php echo ($config["seo_description"]); ?>"/>
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no"/>
	<meta name="apple-mobile-web-app-capable" content="yes"/>
	<meta name='apple-touch-fullscreen' content='yes'/>
	<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
	<meta name="format-detection" content="telephone=no"/>
	<meta name="format-detection" content="address=no"/>

    <link href="<?php echo ($static_path); ?>css/eve.7c92a906.css" rel="stylesheet"/>
	<link href="<?php echo ($static_path); ?>css/index_wap.css" rel="stylesheet"/>
	<link href="<?php echo ($static_path); ?>css/idangerous.swiper.css" rel="stylesheet"/>
	<style>
		#login{margin: 0.5rem 0.2rem;}
		.btn-wrapper{margin:.28rem 0;}
		dl.list{border-bottom:0;border:1px solid #ddd8ce;}
		dl.list:first-child{border-top:1px solid #ddd8ce;}
		dl.list dd dl{padding-right:0.2rem;}
		dl.list dd dl>.dd-padding, dl.list dd dl dd>.react, dl.list dd dl>dt{padding-right:0;}
	    .nav{text-align: center;}
	    .subline{margin:.28rem .2rem;}
	    .subline li{display:inline-block;}
	    .captcha img{margin-left:.2rem;}
	    .captcha .btn{margin-top:-.15rem;margin-bottom:-.15rem;margin-left:.2rem;}
	</style>
</head>
<body id="index" data-com="pagecommon">
        <!--header  class="navbar">
            <div class="nav-wrap-left">
                <a class="react back" href="javascript:history.back()"><i class="text-icon icon-back"></i></a>
            </div>
            <h1 class="nav-header"><?php echo ($config["site_name"]); ?></h1>
            <div class="nav-wrap-right">
                <a class="react" href="<?php echo U('Home/index');?>">
                    <span class="nav-btn"><i class="text-icon">⟰</i>首页</span>
                </a>
            </div>
        </header-->
        <div id="container">
        	<div id="tips" style="-webkit-transform-origin:0px 0px;opacity:1;-webkit-transform:scale(1, 1);"></div>
			<div id="login">
			    <form id="login-form" autocomplete="off" method="post" action="<?php echo U('Login/index');?>" location_url="<?php echo ($referer); ?>">
			        <dl class="list list-in">
			        	<dd>
			        		<dl>
			            			<?php if($config["international_phone"] == 1): ?><dd class="dd-padding">
									 <select name="phone_country_type" id="phone_country_type" style="width:100%; height:30px;">
										  <option value="">请选择国家...,choose country</option>
										  <option value="86" <?php if($config["qcloud_sms_default_country"] == 86): ?>selected<?php endif; ?>>+86 中国 China</option>
										  <option value="1" <?php if($config["qcloud_sms_default_country"] == 1): ?>selected<?php endif; ?>>+1 加拿大 Canada</option>
									</select>
			            		</dd><?php endif; ?>
			            		<dd class="dd-padding">
							
			            			<input id="phone" class="input-weak" type="tel" placeholder="手机号" name="phone" value="<?php echo ($_COOKIE["login_name"]); ?>" required="" />
			            		</dd>
			            		<dd class="dd-padding">
			            			<input id="password" class="input-weak" type="password" placeholder="请输入您的密码" name="password" required=""/>
			            		</dd>
			        		</dl>
			        	</dd>
			        </dl>
			        <div class="btn-wrapper">
						<button type="submit" class="btn btn-larger btn-block">登录</button>
			        </div>
			    </form>
			</div>
			<ul class="subline">
			    <li><a href="<?php echo U('Login/reg');?>">立即注册</a></li>
			    <?php if(C('config.sms_key')): ?><li id="forgetpwd" style="display:inline;float:right;s"><a href="<?php echo U('Login/forgetpwd');?>">找回密码</a></li><?php endif; ?>
			</ul>
		</div>
		<script src="<?php echo C('JQUERY_FILE');?>"></script>
		<script src="<?php echo ($static_path); ?>js/common_wap.js"></script>
		<!--script src="<?php echo ($static_path); ?>layer/layer.m.js"></script>
		<script>
			if(is_weixin()){var location_url = "<?php echo U('Login/weixin');?>";layer.open({title:'提示',content:'跳转到微信登录',end:function(){window.location.href=location_url;}});window.location.href = location_url}
		</script-->
		<script>var international_phone = <?php echo (intval($config["international_phone"])); ?>;</script>
		<script src="<?php echo ($static_path); ?>js/login.js"></script>
				<link href="<?php echo ($static_path); ?>css/footer.css" rel="stylesheet"/>
		<?php if(empty($no_gotop)): ?><div style="height:10px"></div>
			<div class="top-btn"><a class="react"><i class="text-icon">⇧</i></a></div><?php endif; ?>
		<?php if(empty($no_footer)): ?><footer class="footermenu">
				<ul>
					<li>
						<a <?php if(MODULE_NAME == 'Home'): ?>class="active"<?php endif; ?> href="<?php echo U('Home/index');?>">
							<em class="home"></em>
							<p>首页</p>
						</a>
					</li>
					<li>
						<a <?php if(MODULE_NAME == 'Group'): ?>class="active"<?php endif; ?> href="<?php echo U('Group/index');?>">
							<em class="group"></em>
							<p><?php echo ($config["group_alias_name"]); ?></p>
						</a>
					</li>
					<li>
						<a <?php if(in_array(MODULE_NAME,array('Meal_list','Meal')) AND $store_type == 2): ?>class="active"<?php endif; ?> href="<?php echo U('Meal_list/index', array('store_type' => 2));?>">
							<em class="meal"></em>
							<p><?php echo ($config["meal_alias_name"]); ?></p>
						</a>
					</li>
					<li>
						<a <?php if(in_array(MODULE_NAME,array('My','Login'))): ?>class="active"<?php endif; ?> href="<?php echo U('My/index');?>">
							<em class="my"></em>
							<p>我的</p>
						</a>
					</li>
				</ul>
			</footer><?php endif; ?>
		<div style="display:none;"><?php echo ($config["wap_site_footer"]); ?></div>
        

<?php echo ($hideScript); ?>
	</body>
</html>
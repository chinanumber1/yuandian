<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>社区中心 - {pigcms{$config.site_name}</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}login/new_login.css"/>
		<script type="text/javascript">if(self!=top){window.top.location.href = "{pigcms{:U('Login/index')}";}</script>
	</head>
	<body>
		<div class="W_header_line"></div>
		<div id="hdw">
			<div id="hd" style="background-image:url({pigcms{$config.site_logo});">社区中心 - {pigcms{$config.site_name}</div>
		</div>
		<div id="login">
			<div id="box">
				<div style="float:left;">
					<form method="post" id="login_form">
						<input type="hidden" name="village_id" id="village_id" value=""/>
						<p>
							<label>帐 号：</label>
							<input class="text-input" type="text" name="account" id="login_account"/>
							<span class="check">* 长度为6~16位字符</span>
						</p>
						<p>
							<label>密码：</label>
							<input class="text-input" type="password" name="pwd" id="login_pwd"/>
							<span class="check">* 长度为大于6位字符</span>
						</p>
						<p>
							<label>验证码：</label>
							<input class="text-input" type="text" id="login_verify" style="width:60px;" maxlength="4" name="verify"/>&nbsp;&nbsp;
							<span class="verify_box">
								<img src="{pigcms{:U('Login/verify',array('type'=>'login'))}" id="login_verifyImg" onclick="login_fleshVerify('{pigcms{:U('Login/verify',array('type'=>'login'))}')" title="刷新验证码" alt="刷新验证码"/>&nbsp;
								<a href="javascript:login_fleshVerify('{pigcms{:U('Login/verify',array('type'=>'login'))}')">刷新验证码</a>
							</span>
						</p>
						<p class="btn_login"><input type="submit" value="登 录" class="login_btn"/></p>
					</form>
				</div>
			</div>
		</div>
		<div class="copyright">
			<p style="float:left;"><a href="{pigcms{$config.site_url}">{pigcms{$config.site_name}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<if condition="!empty($config['site_icp'])"><a href="http://www.miibeian.gov.cn/" target="_blank">{pigcms{$config.site_icp}</a></if></p>
			<p style="float:right;">Copyright &copy; <span>{pigcms{:date('Y')}</span>&nbsp;{pigcms{$config.top_domain}</p>
			<div class="clear"></div> 
		</div>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
		<script type="text/javascript">
			var static_public="{pigcms{$static_public}",static_path="{pigcms{$static_path}",login_check="{pigcms{:U('Login/check')}",house_index="{pigcms{:U('Index/index')}",choose_province="{pigcms{:U('Area/ajax_province')}",choose_city="{pigcms{:U('Area/ajax_city')}",choose_area="{pigcms{:U('Area/ajax_area')}",choose_circle="{pigcms{:U('Area/ajax_circle')}", show_circle = 1;
		</script>
		<script type="text/javascript" src="{pigcms{$static_path}login/login.js?v=1"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/area.js"></script>
		<style>
		.col-sm-1 {
		  border: 1px solid #ccc;
		  color: #333;
		  -moz-border-radius: 2px;
		  -webkit-border-radius: 2px;
		  border-radius: 6px;
		  padding: 6px;
		  outline: 0;
		  box-shadow: 0px 1px 1px 0px #eaeaea inset;
		}
		.village_list_select{
			width: 300px;
			list-style: none;
			padding:0 30px;
		}
		.village_list_select li{
			height:50px;
			line-height:50px;
			cursor:pointer;
			border-top: 1px solid #ccc;
			padding-left:15px;
		}
		.village_list_select li.first{
			border-top:0px;
		}
		</style>
	</body>
</html>
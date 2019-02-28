<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8" />
		<title>{pigcms{$now_village.village_name}</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name='apple-touch-fullscreen' content='yes' />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="address=no" />

		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css" />
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/new_village.css" />
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
<style type="text/css">
.m-simpleFooter {
  position: fixed;
  z-index: 2;
  left: 0;
  right: 0;
  border: 1px solid #D4D4D4;
  background: rgba(240,240,240,.8);
  padding: 8px 10px;
  bottom: 0;
  border-width: 1px 0;
  line-height: 32px;
  height: 32px;
}
.m-simpleFooter-text{
	  text-align: center;
}
.m-simpleFooter .w-button {
  text-align: center;
  white-space: nowrap;
  font-size: 14px;
  display: inline-block;
  vertical-align: middle;
  color: #fff;
  background: #3399FE;
  border-width: 0;
  border-style: solid;
  border-color: #1B7DE0;
  padding: 0 15px;
  text-align: center;
  height: 30px;
  line-height: 30px;
  border-radius: 3px;
  cursor: pointer;
  text-decoration: none!important;
  outline: none;
  color:#fff
}

.village_my nav section:after{
	border:none
}
</style>
	</head>

	<body>
		<form method="post" action="__SELF__">
			<div id="container">
				<div id="scroller" class="village_my">
					<nav>
						<section class="link-url">
							<p>我的角色</p>
						</section>
						<section class="link-url">
							<input type="radio" name="type" checked="checked" value="1" /><p>家人</p>
						</section>
						<section class="link-url">
							<input type="radio" name="type"  value="2" /><p>租客</p>
						</section>
						
						<if condition='$_GET["is_vacancy"]'>
							<section class="link-url">
								<input type="radio" name="type"  value="0" /><p>房主</p>
							</section>
						<else />
							<section class="link-url">
								<input type="radio" name="type"  value="3" /><p>替换房主</p>
							</section>
						</if>
					</nav>
					
					
					<if condition='!$_GET["is_vacancy"]'>
						<nav>
							<section class="link-url">
								<p>请输入业主手机号后四位</p>
							</section>
							<section class="link-url">
								<p>{pigcms{$bind_info.phone|msubstr=###,0,7,false|substr_replace=###,'****',3,4}<input type="text" name="chk_phone" /></p>
							</section>
						</nav>
					</if>
					
					<nav>
							<section class="link-url">
								<p>申请人信息</p>
							</section>
							<section class="link-url">
								<p>我的手机号：<input type="text" name="phone" /></p>
							</section>
							<section class="link-url">
								<p>我的姓名：<input type="text" name="name" /></p>
							</section>
							
							<if condition='$_GET["is_vacancy"]'>
							<section class="link-url">
								<p>房子平方：<input type="text" name="housesize" /></p>
							</section>
							
							<section class="link-url">
								<p>是否有停车位：<input type="radio" name="park_flag" value="1" checked="checked" />有<input type="radio" name="park_flag" value="0" />无</p>
							</section>
							</if>
						</nav>
						
						<nav>
							<section class="link-url" style=" height:50px">
							<p>备注：<textarea name="memo"></textarea></p>
							</section>
						</nav>
				</div>
			</div>
		
			<div class="m-simpleFooter m-detail-buy">
				<div class="m-simpleFooter-text">
					<input type="hidden" name="is_vacancy" value="{pigcms{$bind_info.is_vacancy}" />
					<input type="submit" id="quickBuy" class="w-button w-button-main" value="完&nbsp;&nbsp成" />
				</div>
			</div>
		</form>
		{pigcms{$shareScript}
	</body>

</html>
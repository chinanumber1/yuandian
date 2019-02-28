<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>我的信息| {pigcms{$config.site_name}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
<link href="{pigcms{$static_path}css/meal_order_list.css"  rel="stylesheet"  type="text/css" />
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
	<script type="text/javascript">
	   var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	</script>
<script src="{pigcms{$static_path}js/common.js"></script>
<script src="{pigcms{$static_path}js/category.js"></script>
<!--[if IE 6]>
<script  src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js" mce_src="{pigcms{$static_path}js/DD_belatedPNG_0.0.8a.js"></script>
<script type="text/javascript">
   DD_belatedPNG.fix('.enter,.enter a,.enter a:hover');
</script>
<script type="text/javascript">DD_belatedPNG.fix('*');</script>
<style type="text/css"> 
body{behavior:url("{pigcms{$static_path}css/csshover.htc");}
.category_list li:hover .bmbox {filter:alpha(opacity=50);}
.gd_box{display: none;}
</style>
<![endif]-->
<script src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
	<style>
		#content .address-field-list .form-field .f-text{height:18px;}
		#content .address-field-list .form-field .address-city, #content .address-field-list .form-field .address-district, #content .address-field-list .form-field .address-province{margin:3px 10px 0 0;width:140px;height:30px;}
		#address-form .input{width:230px;}
	</style>
</head>
<body id="orders" class="has-order-nav" style="position:static;">
<include file="Public:header_top"/>
 <div class="body pg-buy-process"> 
	<div id="doc" class="bg-for-new-index">
		<article>
			<div class="menu cf">
				<div class="menu_left hide">
					<div class="menu_left_top"><img src="{pigcms{$static_path}images/o2o1_27.png" /></div>
					<div class="list">
						<ul>
							<volist name="all_category_list" id="vo" key="k">
								<li>
									<div class="li_top cf">
										<if condition="$vo['cat_pic']"><div class="icon"><img src="{pigcms{$vo.cat_pic}" /></div></if>
										<div class="li_txt"><a href="{pigcms{$vo.url}">{pigcms{$vo.cat_name}</a></div>
									</div>
									<if condition="$vo['cat_count'] gt 1">
										<div class="li_bottom">
											<volist name="vo['category_list']" id="voo" offset="0" length="3" key="j">
												<span><a href="{pigcms{$voo.url}">{pigcms{$voo.cat_name}</a></span>
											</volist>
										</div>
									</if>
								</li>
							</volist>
						</ul>
					</div>
				</div>
				<div class="menu_right cf">
					<div class="menu_right_top">
						<ul>
							<pigcms:slider cat_key="web_slider" limit="10" var_name="web_index_slider">
								<li class="ctur">
									<a href="{pigcms{$vo.url}">{pigcms{$vo.name}</a>
								</li>
							</pigcms:slider>
						</ul>
					</div>
				</div>
			</div>
		</article>
		<include file="Public:scroll_msg"/>
		<div id="bdw" class="bdw">
			<div id="bd" class="cf">
				<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/order-nav.v0efd44e8.css" />
				<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/order-list.v04de2fe7.css" />
				<include file="Public:sidebar"/>
				<div id="content" class="coupons-box">
					<div class="mainbox mine">
					  <div class="balance" style="color:red">完善您的信息，可以更快的通过审核认证，获得优惠哦！</div>
						<div class="orders-wrapper" id="order-list">
						<form id="address-form" class="form" method="post">
						<div class="address-field-list">
							<div class="form-field">
								<label for="truename"><em>*</em> 您的姓名：</label>
								<input id="truename" type="text" maxlength="15" size="15" name="truename" class="f-text input" value="{pigcms{$now_user['truename']}"/>
							</div>
							<div class="form-field">
								<label><em>*</em> 您的性别：</label>
								<input type="radio" name="sex" value="1" <if condition="$now_user['sex'] neq 2"> checked="checked"</if> /> 男&nbsp;&nbsp;&nbsp;
								<input type="radio" name="sex" value="2" <if condition="$now_user['sex'] eq 2"> checked="checked"</if>/> 女
							</div>
							<div class="form-field">
								<label for="youaddress"><em>*</em> 通讯地址：</label>
								<input type="text" maxlength="60" size="60" name="youaddress" id="youaddress" class="f-text input" value="{pigcms{$now_user['youaddress']}"/>
							</div>
							<div class="form-field">
								<label for="qq"> Q Q号：</label>
								<input id="qq" class="f-text input" type="text" maxlength="20" size="10" name="qq" value="{pigcms{$now_user['qq']}"/>
							</div>
							<div class="form-field">
								<label for="email"> Email：</label>
								<input id="email" type="text" maxlength="15" size="15" name="email" class="f-text input" value="{pigcms{$now_user['email']}"/>
							</div>
							<div class="form-field comfirm">
								<input type="submit" class="btn" id="formsubmit" value="保 存"/>
							</div>
						</div>
					</form>
				  </div>
					</div>
				</div>
			</div> <!-- bd end -->
		</div>
	</div>	
	<include file="Public:footer"/>
	<script type="text/javascript">
	 $('#address-form').submit(function(){
			var qq = $.trim($('#qq').val());
				if(qq && !(/^(\d){3,15}$/.test(qq))){
					alert('QQ号格式不对！');
					return false;
		       }
			var email =  $.trim($('#email').val());
				if(email && !(/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/.test(email))){
					alert('邮箱格式不对！');
					return false;
		       }
		 $('#formsubmit').val('保存中...').prop('disabled',true);
		 	$.post("{pigcms{:U('User/Index/savemyinfo')}",$('#address-form').serialize(),function(result){
				result.error=parseInt(result.error);
			if(!result.error){
				alert(result.msg);
				window.location.href='/';
			}else{
				alert(result.msg);
				$('#formsubmit').val('保 存').prop('disabled',false);
				return false;
			}
		  },'JSON');
		  return false;
	});
	</script>
</body>
</html>

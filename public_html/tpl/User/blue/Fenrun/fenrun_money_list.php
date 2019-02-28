<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>分润钱包 | {pigcms{$config.site_name}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
<link href="{pigcms{$static_path}css/meal_order_list.css"  rel="stylesheet"  type="text/css" />
<style>
a.btn {
  display: inline-block;
  vertical-align: middle;
  padding: 7px 20px 6px;
  font-size: 14px;
  font-weight: 700;
  -webkit-font-smoothing: antialiased;
  line-height: 1.5;
  letter-spacing: .1em;
  text-align: center;
  text-decoration: none;
  border-width: 0 0 1px;
  border-style: solid;
  background-repeat: repeat-x;
  -moz-border-radius: 2px;
  -webkit-border-radius: 2px;
  border-radius: 2px;
  -moz-user-select: -moz-none;
  -ms-user-select: none;
  -webkit-user-select: none;
  user-select: none;
  cursor: pointer;
  color: #fff;
  background-color: #2eb7aa;
  border-color: #008177;
  filter: progid:DXImageTransform.Microsoft.gradient(gradientType=0, startColorstr='#FF2BB8AA', endColorstr='#FF2EB7AA');
  background-size: 100%;
  background-image: -moz-linear-gradient(top,#2bb8aa,#2eb7aa);
  background-image: -webkit-linear-gradient(top,#2bb8aa,#2eb7aa);
  background-image: linear-gradient(to bottom,#2bb8aa,#2eb7aa);
}
.pay_form {
	width:350px;
}
.pay_form .pay_tip{
	font-size:12px;margin-bottom:30px;
}
.pay_form #recharge_money,#truename,#withdraw_money{
	line-height: 24px;
	/* margin-left: 80px; */
	width: 88px;
	height: 24px;
	padding: 5px;
	border: 1px solid #aaa;
	line-height: 24px;
	vertical-align: top;
}
.pay_form .form-field label{
	line-height:36px;
}
.pay_form .form-field .inline-link {
  margin: 0 0 0 8px;
  font-size: 12px;
  line-height: 36px;
  vertical-align: top;
  zoom: 1;
}
</style>
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
	<script type="text/javascript">
	   var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	</script>
<script src="{pigcms{$static_path}js/common.js"></script>
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
</head>
<body id="credit" class="has-order-nav" style="position:static;">
<include file="Public:header_top"/>
 <div class="body pg-buy-process">
	<div id="doc" class="bg-for-new-index">
		<article>
			<div class="menu cf">
		
				<div class="menu_left hide">
					<div class="menu_left_top">全部分类</div>
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
				<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/account.v1a41925d.css" />
				<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/table-section.v538886b7.css" />
				<include file="Public:sidebar"/>
				<div id="content" class="coupons-box">
					<div class="mainbox mine">
						<div class="balance">您当前的分润余额： <strong>¥{pigcms{$now_user.fenrun_money}</strong><a class="btn" id="withdraw">提现到余额</a></div>
						<ul class="filter cf">
							<li class="current"><a href="{pigcms{:U('Fenrun/fenrun_money_list')}">分润余额记录</a></li>
						</ul>
						<div class="table-section">
							<table cellspacing="0" cellpadding="0" border="0">
								<tr>
									<th width="130">时间</th>
									<th width="auto">详情</th>
									<th width="110">金额（元）</th>
								</tr>
								<volist name="list" id="vo">
									<tr>
										<td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
										<td class="detail">{pigcms{$vo.des}</td>
										<if condition="$vo['type'] eq 1">
											<td class="income">+{pigcms{$vo.fenrun_money}</td>
										<else/>
											<td class="expense">-{pigcms{$vo.fenrun_money}</td>
										</if>
									</tr>
								</volist>
							</table>
						</div>
						{pigcms{$pagebar}
                    </div>
				</div>
			</div> <!-- bd end -->
		</div>
	</div>
	<include file="Public:footer"/>
	<style>
		.webuploader-container{
			position:relative;
		}
		.webuploader-element-invisible{
			position: absolute !important;
			clip: rect(1px 1px 1px 1px); /* IE6, IE7 */
			clip: rect(1px,1px,1px,1px);
		}
		.webuploader-pick{
			position: relative;
			display: inline-block;
			cursor: pointer;
			color: #fff;
			text-align: center;
			border-radius: 3px;
			overflow: hidden;
			width:100%;
			height:100%;
		}
		.webuploader-pick-disable{
			opacity: 0.6;
			pointer-events:none;
		}
		.p-node-wordcounter {
			position: absolute;
			padding: 1px 5px;
			line-height: 18px;
			font-size: 12px;
			color: #FFF;
			background: #0B0;
			border-radius: 0 0 3px 3px;
		}
	</style>
	<script src="{pigcms{$static_public}js/webuploader.min.js"></script>
	<script>
		$(function(){
			
			
			
			$('#withdraw').click(function(){
				art.dialog({
					id: 'withdraw_handle',
					title:'分润转余额',
					padding: 0,
					width: 380,
					height: 200,
					lock: true,
					resize: false,
					background:'black',
					init:function(){
						$('#withdraw_money').focus();
					},
					fixed: false,
					left: '50%',
					top: '38.2%',
					opacity:'0.4',
					content:'<div class="pay_form"><div class="pay_tip">转余额金额不能超过分润金额：{pigcms{$now_user.fenrun_money} 元</div><div class="form-field"><label for="withdraw_money">转换金额：</label><input type="text" name="money" autocomplete="off" id="withdraw_money"/><span class="inline-link">元</span></div><div id="money_tips" style="color:red;"></div></div>',
					ok:function(){
						var money = parseFloat($('#withdraw_money').val());
						var name = $('#truename').val();

						if(isNaN(money)){
							$('#money_tips').html('请输入合法的金额！');
							$('#withdraw_money').focus();
							return false;
						}else if(money > {pigcms{$now_user.fenrun_money}){
							$('#money_tips').html('提款金额最高不能超过{pigcms{$now_user.fenrun_money}元');
							$('#withdraw_money').focus();
							return false;
						}else if(money < {pigcms{$config.min_fenrun_to_balance_money}){
							$('#money_tips').html('单次转换金额最低不能低于 {pigcms{$config.min_fenrun_to_balance_money}元');
							$('#withdraw_money').focus();
							return false;
						}else{
							$('#money_tips').empty();
							window.location.href = '{pigcms{:U('Fenrun/fenrun_to_balance')}&money='+money;
						}
					},
					cancel:true
				});
					
			});
	
		});
	</script>
</body>
</html>

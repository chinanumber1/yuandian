<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>我的推广 | {pigcms{$config.site_name}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
<link href="{pigcms{$static_path}css/meal_order_list.css"  rel="stylesheet"  type="text/css" />
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_path}zclip/jquery.zclip.js"></script>
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
<style type="text/css">
.line{margin-bottom:20px;}
/* 复制提示 */
.copy-tips{position:fixed;z-index:999;bottom:50%;left:50%;margin:0 0 -20px -80px;background-color:rgba(0, 0, 0, 0.2);filter:progid:DXImageTransform.Microsoft.Gradient(startColorstr=#30000000, endColorstr=#30000000);padding:6px;}
.copy-tips-wrap{padding:10px 20px;text-align:center;border:1px solid #F4D9A6;background-color:#FFFDEE;font-size:14px;}
</style>
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
						<div class="balance">您当前的余额： <strong>¥{pigcms{$now_user.now_money}</strong></div>
						<if condition="$config.open_score_fenrun eq 1">
							<div class="balance">我的推广码: <strong id="spread_code">{pigcms{$spread_code}</strong> <a href="#none" class="copy btn">点击复制</a> 使用方法：将邀请码发送给您的好友，您的好友注册为平台会员或成为平台审核通过的商家，则您可获得一定的推广佣金</div>
						</if>
						<if condition="$un_spread_list">
							<ul class="filter cf">
								<li class="current"><a href="{pigcms{:U('Spread/index')}">未结算推广记录</a></li>
							</ul>
							<div class="table-section un_spread_list" style="margin-bottom:50px;">
								<table cellspacing="0" cellpadding="0" border="0">
									<tr>
										<th width="130">时间</th>
										<th width="auto">详情</th>
										<th width="110">金额</th>
										<th width="112">操作</th>
									</tr>
									<volist name="un_spread_list" id="vo">
										<tr>
											<td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
											<td class="detail">{pigcms{$vo.desc.txt} 《<a href="{pigcms{$vo.desc.url}" target="_blank" style="color:#F76120;">{pigcms{$vo.desc.info}</a>》</td>
											<td class="income" style="text-align:center;">{pigcms{$vo.money}</td>
											<td style="text-align:center;">
												<if condition="$vo['status'] eq 0">
													<if condition="$vo['order_info']['status'] eq 0">
														待消费
													<else/>
														<a class="btn-hot btn-mini spread_check_btn" href="{pigcms{:U('Spread/check',array('id'=>$vo['pigcms_id']))}" style="color:white;">验证</a>
													</if>
												<elseif condition="$vo['status'] eq 1"/>
													已结算
												<else/>
													订单已退款
												</if>
											</td>
										</tr>
									</volist>
								</table>
							</div>
						</if>
						<if condition="$config.open_user_spread">
						<ul class="filter cf">
							<li class="current"><a href="{pigcms{:U('Spread/index')}">推广记录</a></li>
						</ul>
						<div class="table-section">
							<table cellspacing="0" cellpadding="0" border="0">
								<tr>
									<th width="130">时间</th>
									<th width="auto">详情</th>
									<th width="110">金额</th>
									<th width="112">操作</th>
								</tr>
								<volist name="spread_list" id="vo">
									<tr>
										<td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
										<td class="detail">{pigcms{$vo.desc.txt} 《<a href="{pigcms{$vo.desc.url}" target="_blank" style="color:#F76120;">{pigcms{$vo.desc.info}</a>》</td>
										<td class="income" style="text-align:center;">{pigcms{$vo.money}</td>
										<td style="text-align:center;">
											<if condition="$vo['status'] eq 0">
												<if condition="$vo['order_info']['status'] eq 0">
													待消费
												<else/>
													<a class="btn-hot btn-mini spread_check_btn" href="{pigcms{:U('Spread/check',array('id'=>$vo['pigcms_id']))}" style="color:white;">验证</a>
												</if>
											<elseif condition="$vo['status'] eq 1"/>
												已结算
											<else/>
												订单已退款
											</if>
										</td>
									</tr>
								</volist>
							</table>
						</div>
						</if>
						{pigcms{$pagebar}
                    </div>
				</div>
			</div> <!-- bd end -->
		</div>
	</div>
	<include file="Public:footer"/>
	<script>
		$(function(){
			if($('.un_spread_list .spread_check_btn').size() > 0){
				checkSpread();
			}
			
			  $(".copy").zclip({
		path: "{pigcms{$static_path}zclip/ZeroClipboard.swf",
		copy: function(){

		return $('#spread_code').text();
		},
		beforeCopy:function(){/* 按住鼠标时的操作 */
			$(this).css("color","orange");
		},
		afterCopy:function(){/* 复制成功后的操作 */
			var $copysuc = $("<div class='copy-tips'><div class='copy-tips-wrap'>☺ 复制成功</div></div>");
			$("body").find(".copy-tips").remove().end().append($copysuc);
			$(".copy-tips").fadeOut(3000);
        }
	});
		});
		function checkSpread(){
			var parentDom = $('.un_spread_list .spread_check_btn').eq(0).parent();
			var postHref = $('.un_spread_list .spread_check_btn').eq(0).attr('href');
			parentDom.html('<font color="red">自动验证中..</font>');
			$.post(postHref,function(result){
				parentDom.html(result.info);
				if($('.un_spread_list .spread_check_btn').size() > 0){
					checkSpread();
				}else{
					alert('推广订单验证完成，点击确定后即将刷新页面！');
					window.location.reload();
				}
			});
		}


 

     
	</script>
</body>
</html>

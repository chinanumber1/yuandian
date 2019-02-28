<!doctype html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title>我的{pigcms{$config['score_name']} | {pigcms{$config.site_name}</title>
<meta name="keywords" content="{pigcms{$config.seo_keywords}" />
<meta name="description" content="{pigcms{$config.seo_description}" />
<link href="{pigcms{$static_path}css/css.css" type="text/css"  rel="stylesheet" />
<link href="{pigcms{$static_path}css/header.css"  rel="stylesheet"  type="text/css" />
<link href="{pigcms{$static_path}css/meal_order_list.css"  rel="stylesheet"  type="text/css" />
<script src="{pigcms{$static_path}js/jquery-1.7.2.js"></script>
<script src="{pigcms{$static_public}js/jquery.lazyload.js"></script>
	<script type="text/javascript">
	   var  meal_alias_name = "{pigcms{$config.meal_alias_name}";
	   var  score_name = "{pigcms{$config.score_name}";
	   var levelToupdateUrl="{pigcms{$config['site_url']}{pigcms{:U('User/Level/levelUpdate')}"
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
						<div class="balance">您当前的{pigcms{$config['score_name']}： <strong>{pigcms{$now_user.score_count}</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;当前等级：<span><strong><php>if(isset($levelarr[$now_user['level']])){ $nextlevel=$levelarr[$now_user['level']]['level']+1;echo $levelarr[$now_user['level']]['lname'];}else{ $nextlevel=1; echo '暂无等级';}</php></strong></span></div>
						<ul class="filter cf">
							<li class="current"><a href="{pigcms{:U('Level/index')}">下一等级详情</a></li>
						</ul>
						<div class="table-section">
							<table cellspacing="0" cellpadding="0" border="0">
								<tr>
									<th width="130">等级名称</th>
									<th width="110">所需{pigcms{$config['score_name']}</th>
									<if condition="$levelarr[$nextlevel]['use_money'] gt 0"><th width="110">所需余额</th></if>
									<th width="auto">等级优惠</th>
									<th width="auto">升级</th>
								</tr>
								 <if condition="isset($levelarr[$nextlevel])">
									<tr>
										<td>{pigcms{$levelarr[$nextlevel]['lname']}</td>
										<td>{pigcms{$levelarr[$nextlevel]['integral']}</td>
										<if condition="$levelarr[$nextlevel]['use_money'] gt 0"><td>{pigcms{$levelarr[$nextlevel]['use_money']}元</td></if>
										<td><if condition="$levelarr[$nextlevel]['type'] eq 1">商品按原价{pigcms{$levelarr[$nextlevel]['boon']}%计算<elseif condition="$levelarr[$nextlevel]['type'] eq 2" />商品价格立减{pigcms{$levelarr[$nextlevel]['boon']}元<else />无</if></td>
										<td>
										<if condition="$config.level_update_auto eq 0">
										<a href="javascript:void(0);" class="btn" onclick="levelToupdate({pigcms{$now_user.score_count},{pigcms{$levelarr[$nextlevel]['integral']},$(this))" style="color:#FFF;">{pigcms{$config['score_name']}兑换</a></if>
										<if condition="$levelarr[$nextlevel]['use_money'] gt 0"><a href="javascript:void(0);" class="btn" onclick="levelBuyupdate({pigcms{$now_user.now_money},{pigcms{$levelarr[$nextlevel]['use_money']},$(this))" style="color:#FFF;">余额购买</a></if>
										</td>
									</tr>
									<else />
									<tr>
									<td colspan="4">
									 <if condition="$nextlevel eq 1">
									   商家没有设置等级，敬请期待！
									 <else />
									  没有更高的等级了！
									  </if>
									</td>
									</tr>
                             </if>
							</table>
							<if condition="isset($levelarr[$now_user['level']])">
							<div style="margin-top:20px;">
							<strong>{pigcms{$levelarr[$now_user['level']]['lname']} 详情描述：</strong>
							 <div>
							  {pigcms{$levelarr[$now_user['level']]['description']|htmlspecialchars_decode=ENT_QUOTES}
							 </div>
							</div>
						   </if>
							<if condition="isset($levelarr[$nextlevel])">
							<div style="margin-top:20px;">
							<strong>{pigcms{$levelarr[$nextlevel]['lname']} 详情描述：</strong>
							 <div>
							  {pigcms{$levelarr[$nextlevel]['description']|htmlspecialchars_decode=ENT_QUOTES}
							 </div>
							</div>
						   </if>
						</div>
						{pigcms{$pagebar}
                    </div>
				</div>
			</div> <!-- bd end -->
		</div>
	</div>
	<include file="Public:footer"/>
</body>
</html>

<!DOCTYPE html>
<html class="no-js admin <?php if($_GET['ps']<=320){echo ' responsive-320';}elseif($_GET['ps']>=540){echo ' responsive-540';} if($_GET['ps']>540){echo ' responsive-800';} ?>" lang="zh-CN">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta http-equiv="Expires" content="-1"/>
	<meta http-equiv="Cache-Control" content="no-cache"/>
	<meta http-equiv="Pragma" content="no-cache"/>
	<meta name="format-detection" content="telephone=no"/>
	<meta charset="utf-8">
	<title>{pigcms{$now_store.name}</title>
	<link rel="stylesheet" href="{pigcms{$static_path}diypage/css/base.css"/>
	
	<?php if($is_mobile){ ?>
		<link rel="stylesheet" href="{pigcms{$static_path}diypage/css/showcase.css"/>
	<?php }else{ ?>
		<link rel="stylesheet" href="{pigcms{$static_path}diypage/css/showcase_admin.css"/>
	<?php } ?>
		
	<link rel="stylesheet" href="{pigcms{$static_path}diypage/css/swiper.min.css"/>
	<link rel="stylesheet" href="{pigcms{$static_path}diypage/css/shop.css"/>
</head>
<body>
	<div class="container">
		<div class="header">
			<?php if(!$is_mobile && $_SESSION['merchant']['mer_id'] == $now_store['mer_id']){ ?>
				<div class="headerbar">
					<div class="headerbar-wrap clearfix">
						<div class="headerbar-preview">
							<span>预览：</span>
							<ul>
								<li>
								   <a href="{pigcms{:U('page',array('page_id'=>$now_page['page_id'],'ps'=>320))}" class="js-no-follow <?php if(empty($_GET['ps']) || $_GET['ps'] == '320') echo ' active';?>">iPhone版</a>
								</li>
								<li>
								   <a href="{pigcms{:U('page',array('page_id'=>$now_page['page_id'],'ps'=>540))}" class="js-no-follow <?php if($_GET['ps'] == '540') echo ' active';?>">三星Note3版</a>
								</li>
								<!--li>
								   <a href="{pigcms{:U('page',array('page_id'=>$now_page['page_id'],'ps'=>800))}" class="js-no-follow">PC版</a>
								</li-->
							</ul>
						</div>
						<if condition="$_SESSION['merchant']">
							<div class="headerbar-reedit">
								<a href="{pigcms{$config.site_url}/merchant.php?c=Diypage&a=create&page_id={pigcms{$now_page.page_id}&store_id={pigcms{$now_page.store_id}" class="js-no-follow">重新编辑</a>
							</div>
						</if>
					</div>
				</div>
			<?php } ?>
			<!-- ▼顶部通栏 -->
			<div class="js-mp-info share-mp-info">
				<a class="page-mp-info" href="{pigcms{$now_store.url}">
					<img class="mp-image" width="24" height="24" src="{pigcms{$now_store.all_pic.0}" alt="{pigcms{$now_store.name}"/>
					<i class="mp-nickname">{pigcms{$now_store.name}</i>
				</a>
				<div class="links">
					<a class="mp-homepage" href="{pigcms{$now_store.card_url}">会员中心</a>
				</div>
			</div>
		<!-- ▲顶部通栏 -->
		</div>
		<div class="content" <?php if($now_page['bgcolor']){ ?>style="background-color:<?php echo $now_page['bgcolor'];?>;"<?php } ?>>
			<div class="content-body">
				<?php foreach($field_list as $value){echo $value['html'];} ?>
			</div>
			<?php if(!$is_mobile){ ?>
				<div class="content-sidebar">
					<div class="sidebar-section qrcode-info">
						<div class="section-detail">
							<p class="text-center shop-detail"><strong>手机扫码访问</strong></p>
							<p class="text-center weixin-title">微信“扫一扫”分享到朋友圈</p>
							<p class="text-center qr-code">
								<img width="158" height="158" src="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=get_own_qrcode&qrCon={pigcms{:urlencode($config['site_url'].'/wap.php?c=Diypage&a=page&page_id='.$now_page['page_id'])}"/>
							</p>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
		<div class="js-footer">          
			<div class="footer">
				<div class="copyright">
					<div class="ft-links">
						<a href="{pigcms{$now_store.url}">店铺主页</a>
						<a href="{pigcms{:U('Mall/cart',array('store_id'=>$now_store['store_id']))}">购物车</a>
						<a href="{pigcms{$now_store.card_url}">会员中心</a>
					</div>
				</div>
			</div>
		</div>
	</div>
    <if condition="$config['map_config'] eq 'google' AND $config['google_map_ak']">
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&key={pigcms{$config.google_map_ak}"></script>
        <script type="text/javascript">var is_google_map = "{pigcms{$config.google_map_ak}"</script>
        <else />
	<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1"></script>
    </if>
	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}" charset="utf-8"></script>
	<script src="{pigcms{$static_path}diypage/js/swiper.min.js"></script>
	<script src="{pigcms{$static_path}diypage/js/base.js"></script>
	<script type="text/javascript">
	window.shareData = {
        "moduleName":"Mall",
        "moduleID":"0",
        "imgUrl": "{pigcms{$now_store.all_pic.0}",
        "sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('Diypage/page',array('page_id'=>$now_page['page_id']))}",
        "tTitle": "{pigcms{$now_store.name}",
        "tContent": "{pigcms{$now_store.txt_info}"
	};
	</script>
	{pigcms{$shareScript}
</body>
</html>
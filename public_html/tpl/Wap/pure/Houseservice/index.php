<!DOCTYPE html>
<html>

	<head>
		<meta charset="UTF-8">
		<title>{pigcms{$now_village.village_name}</title>
        <meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<meta name='apple-touch-fullscreen' content='yes' />
		<meta name="apple-mobile-web-app-status-bar-style" content="black" />
		<meta name="format-detection" content="telephone=no" />
		<meta name="format-detection" content="address=no" />
		<link rel="stylesheet" href="{pigcms{$static_path}css/common.css" />
		<link rel="stylesheet" href="{pigcms{$static_path}css/cat_list.css" />
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
	</head>

	<body>
		<div id="container" style="position:static;">
			<if condition='$cat_list'>
				<volist name='cat_list' id='vo'>
				<section>
					<div class="title">{pigcms{$vo.cat_name}</div>
					<div class="cat_list">
						<ul>
							<volist name="vo['son_list']" id="voo">
								<li>
									<a href="<if condition="$voo['cat_url']">{pigcms{:wapLbsTranform($voo['cat_url'],array('title'=>$voo['cat_name'],'pic'=>$voo['cat_img']))}<else/>{pigcms{:U('Houseservice/cat_list',array('village_id'=>$now_village['village_id'],'id'=>$voo['id']))}</if>">
										<img src="{pigcms{$voo.cat_img}" width="50px" height="50px"/>
										<p>{pigcms{$voo.cat_name}</p>
									</a>
								</li>
							</volist>
						</ul>
					</div>
				</section>
				</volist>
			</if>
		</div>
		<include file="House:footer"/>
		<script type="text/javascript">
			window.shareData = {  
				"moduleName":"Village",
				"moduleID":"0",
				"imgUrl": "<if condition="$config['wechat_share_img']">{pigcms{$config.wechat_share_img}<else/>{pigcms{$config.site_logo}</if>", 
				"sendFriendLink": "{pigcms{$config.site_url}{pigcms{:U('House/village',array('village_id'=>$now_village['village_id']))}",
				"tTitle": "{pigcms{$now_village.village_name}",
				"tContent": "欢迎您进入{pigcms{$now_village.village_name}"
			};
		</script>
	</body>

</html>
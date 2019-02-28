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
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script>
	</head>

	<body>
		<div id="container">
			<div id="scroller" class="village_my">

				<nav>
					<volist name='unit_list' id='unit_info'>
						<section class="link-url" data-url="{pigcms{:U('bind_village',array('type'=>'go_layer','village_id'=>$_GET['village_id'],'floor_id'=>$unit_info['floor_id']))}">
							<p>{pigcms{$unit_info['floor_name']}（{pigcms{$unit_info['floor_layer']}）</p>
						</section>
					</volist>
				</nav>
			</div>
		</div>
		{pigcms{$shareScript}
	</body>

</html>
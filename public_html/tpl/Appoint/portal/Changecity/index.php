<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge">
		<title>选择城市 - {pigcms{$config.appoint_site_name}</title>
		<meta name="keywords" content="{pigcms{$config.appoint_seo_keywords}" />
		<meta name="description" content="{pigcms{$config.appoint_seo_description}" />
		<link href="{pigcms{$static_path}css/changecity.css" type="text/css" rel="stylesheet" />
	</head>
	<body>
		<div class="cslb_banner" style="background-image:url({pigcms{$config.appoint_changecity_bgimg});"></div>
		<div class="search_city">
			<div class="search_a">
				<p>热门城市：
					<volist name="hot_city" id="vo">
						<a href="{pigcms{:U('Changecity/go',array('city'=>$vo['area_url'],'referer'=>urlencode(htmlspecialchars_decode($_GET['referer']))))}" class="ext_a">{pigcms{$vo.area_name}</a>
					</volist>
				</p><span><a href="{pigcms{:U('Changecity/go',array('city'=>$now_city['area_url'],'referer'=>urlencode(htmlspecialchars_decode($_GET['referer']))))}" class="ext_a"><i class="fl"></i><i class="fc">进入 {pigcms{$now_city.area_name} {pigcms{$config.appoint_site_name}</i><i class="fr"></i></a></span>
			</div>
			<div class="search_b">
				<strong>所有开通城市：</strong>
				<ul id="open_city">
					<volist name="all_city" id="vo">
						<li>
							<b>{pigcms{$key}</b>
							<span>
								<volist name="vo" id="voo">
									<a href="{pigcms{:U('Changecity/go',array('city'=>$voo['area_url'],'referer'=>urlencode(htmlspecialchars_decode($_GET['referer']))))}" class="ext_a">{pigcms{$voo.area_name}</a>
								</volist>
							</span>	
						</li>
					</volist>
				</ul>
			</div>
		</div>
		<div class="footer clear">
			<p>{pigcms{$config.appoint_footer_code}</p>
		</div>
	</body>
</html>

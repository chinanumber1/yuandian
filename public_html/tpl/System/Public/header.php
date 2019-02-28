<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset={pigcms{:C('DEFAULT_CHARSET')}" />
		<title>网站后台管理 Powered by pigcms.com</title>
		<script type="text/javascript">
			/*<if condition="!C('butt_open')">
				if(self==top){window.top.location.href="{pigcms{:U('Index/index')}";}
			<else/>
				if(self==top){window.top.location.href="{pigcms{:C('butt_system_url')}";}
			</if>*/
			var kind_editor=null,static_public="{pigcms{$static_public}",static_path="{pigcms{$static_path}",system_index="{pigcms{:U('Index/index')}",choose_province="{pigcms{:U('Area/ajax_province')}",choose_city="{pigcms{:U('Area/ajax_city')}",choose_area="{pigcms{:U('Area/ajax_area')}",choose_circle="{pigcms{:U('Area/ajax_circle')}",choose_market="{pigcms{:U('Area/ajax_market')}",choose_map="{pigcms{:U('Map/frame_map')}",get_firstword="{pigcms{:U('Words/get_firstword')}",frame_show=<if condition="$_GET['frame_show']">true<else/>false</if>;
 var  meal_alias_name = "{pigcms{$config.meal_alias_name}",parentShowHelpParam = [],parentShowIndex = false,choose_provincess="{pigcms{:U('Area/ajax_province')}",choose_cityss="{pigcms{:U('Area/ajax_city')}";
		</script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/style.css" />
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE')}"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.form.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.validate.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.colorpicker.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}/js/area_adver.js"></script>
	</head>
	<body width="100%" <if condition="$bg_color">style="background:{pigcms{$bg_color};"</if>>
<!DOCTYPE html>
<html lang="zh-CN">
    <head>
        <meta charset="utf-8"/>
        <title>{pigcms{$config.cash_alias_name}</title>
        <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0,user-scalable=no,width=device-width"/>
        <meta http-equiv="pragma" content="no-cache"/>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name='apple-touch-fullscreen' content='yes'>
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="format-detection" content="telephone=no"/>
        <meta name="format-detection" content="address=no"/>
        <link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/pay.css?2151"/>
		<script>
			var mer_id = '{pigcms{$_GET['mer_id']}';
			if(mer_id){
				var can_change_store = true;
			}else{
				var can_change_store = false;
			}
		</script>
    </head>
    <body>
		
        {pigcms{$hideScript}

      	<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js?11" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/common.js?2112" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_public}number/number.js?11" charset="utf-8"></script>
        <script type="text/javascript" src="{pigcms{$static_path}js/pay.js?11" charset="utf-8"></script>
		<script>
		window.setTimeout("window.location.href='{pigcms{:U('pay',$_GET)}'",500); 
			;
		</script>
    </body>
</html>
<script>
    
</script>



<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>成功提示</title>
		<meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
	</head>
	<body>
        <script src="{pigcms{$static_path}layer/layer.m.js"></script>
        <script>var location_url = '{pigcms{$url}';
            layer.open({
                title: ['成功提示', 'background-color:#06c1ae;color:#fff;'],
                content: '{pigcms{$msg}',
                btn: ['确定'],
                end: function () {
                    var lastPageNeedReload = '{pigcms{$lastPageNeedReload}';
                    if (lastPageNeedReload) {
                        sessionStorage.setItem('lastPageNeedReload', lastPageNeedReload);
                    }
                    <php>if(ACTION_NAME == 'group_order_check_refund' && $app_browser_type == 'android'){</php>
						history.go(-2);
					<php>}else{</php>
						if(location_url.indexOf('javascript') == -1){
							var stateObj = { foo: 'success' };
							history.replaceState(stateObj, "", location_url);
							location.reload();
						}else{
							location.href = location_url;
						}
					<php>}</php>
                }
            });</script>
    </body>
</html>

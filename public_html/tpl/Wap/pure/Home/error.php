<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title><if condition="$title"><?php echo ($title);?><elseif condition="$is_app_browser && strpos($url,'c=Login&a=index')"/>跳转登录中<else/>页面提示</if></title>
	<meta name="description" content="{pigcms{$config.seo_description}">
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <!--link href="{pigcms{$static_path}css/eve.7c92a906.css" rel="stylesheet"/-->
	<script src="{pigcms{:C('JQUERY_FILE')}"></script>
    <script src="{pigcms{$static_path}layer/layer.m.js"></script>
</head>
<body>
    <if condition="$is_app_browser && strpos($url,'c=Login&a=index')">
        <script type="text/javascript">
            layer.open({type: 2});
            <if condition="$app_browser_type eq 'android'">
                window.lifepasslogin.startJsToJavaFunction();
                function ReturnLastPage(){
                    history.back();
                };
                function androidRefresh(ticket,device){
                    if(ticket && device){
                        $.post("{pigcms{:U('Home/ajaxLogin')}", { ticket: ticket, 'Device-Id': device },function(){
                            location.reload();
                        });
                    }
                }
            <else/>
                $('body').append('<iframe src="pigcmso2o://login" style="display:none"></iframe>');
                function refresh(ticket,device){
                    if(ticket && device){
                        $.post("{pigcms{:U('Home/ajaxLogin')}", { ticket: ticket, 'Device-Id': device },function(){
                            location.reload();
                        });
                    }
                }
                function ReturnLastPage(){
                    history.back();
                };
            </if>
        </script>
    <else/>
        <script type="text/javascript">
            var location_url = '{pigcms{$url}', wxscan='{pigcms{$_GET["wxscan"]}', is_wexin_browser = '{pigcms{$is_wexin_browser}';
            layer.open({content:'{pigcms{$msg}',btn: ['确定'],end:function(){
                    if (wxscan == 1 && is_wexin_browser) {
                        wx.closeWindow();
                    } else {
                        <php>if($is_app_browser){</php>
                            <php>if($app_browser_type == 'android'){</php>
                                window.lifepasslogin.webViewGoBack();
                            <php>}else{</php>
                                $('body').append('<iframe src="pigcmso2o://webViewGoBack" style="display:none"></iframe>');
                            <php>}</php>
                        <php>}else{</php>
                            if(location_url.indexOf('javascript') == -1){
								var stateObj = { foo: 'error' };
								history.replaceState(stateObj, "", location_url);
								location.reload();
							}else{
								location.href = location_url;
							}
                        <php>}</php>
                    }
                }});
        </script>
    </if>
	
    {pigcms{$shareScript}
</body>
</html>
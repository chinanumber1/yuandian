<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
	<title><?php if($title): echo ($title); elseif($is_app_browser && strpos($url,'c=Login&a=index')): ?>跳转登录中<?php else: ?>页面提示<?php endif; ?></title>
	<meta name="description" content="<?php echo ($config["seo_description"]); ?>">
    <meta name="viewport" content="initial-scale=1, width=device-width, maximum-scale=1, user-scalable=no">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name='apple-touch-fullscreen' content='yes'>
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="format-detection" content="telephone=no">
	<meta name="format-detection" content="address=no">
    <!--link href="<?php echo ($static_path); ?>css/eve.7c92a906.css" rel="stylesheet"/-->
	<script src="<?php echo C('JQUERY_FILE');?>"></script>
    <script src="<?php echo ($static_path); ?>layer/layer.m.js"></script>
</head>
<body>
    <?php if($is_app_browser && strpos($url,'c=Login&a=index')): ?><script type="text/javascript">
            layer.open({type: 2});
            <?php if($app_browser_type == 'android'): ?>window.lifepasslogin.startJsToJavaFunction();
                function ReturnLastPage(){
                    history.back();
                };
                function androidRefresh(ticket,device){
                    if(ticket && device){
                        $.post("<?php echo U('Home/ajaxLogin');?>", { ticket: ticket, 'Device-Id': device },function(){
                            location.reload();
                        });
                    }
                }
            <?php else: ?>
                $('body').append('<iframe src="pigcmso2o://login" style="display:none"></iframe>');
                function refresh(ticket,device){
                    if(ticket && device){
                        $.post("<?php echo U('Home/ajaxLogin');?>", { ticket: ticket, 'Device-Id': device },function(){
                            location.reload();
                        });
                    }
                }
                function ReturnLastPage(){
                    history.back();
                };<?php endif; ?>
        </script>
    <?php else: ?>
        <script type="text/javascript">
            var location_url = '<?php echo ($url); ?>', wxscan='<?php echo ($_GET["wxscan"]); ?>', is_wexin_browser = '<?php echo ($is_wexin_browser); ?>';
            layer.open({content:'<?php echo ($msg); ?>',btn: ['确定'],end:function(){
                    if (wxscan == 1 && is_wexin_browser) {
                        wx.closeWindow();
                    } else {
                        <?php if($is_app_browser){ ?>
                            <?php if($app_browser_type == 'android'){ ?>
                                window.lifepasslogin.webViewGoBack();
                            <?php }else{ ?>
                                $('body').append('<iframe src="pigcmso2o://webViewGoBack" style="display:none"></iframe>');
                            <?php } ?>
                        <?php }else{ ?>
                            if(location_url.indexOf('javascript') == -1){
								var stateObj = { foo: 'error' };
								history.replaceState(stateObj, "", location_url);
								location.reload();
							}else{
								location.href = location_url;
							}
                        <?php } ?>
                    }
                }});
        </script><?php endif; ?>
	
    <?php echo ($shareScript); ?>
</body>
</html>
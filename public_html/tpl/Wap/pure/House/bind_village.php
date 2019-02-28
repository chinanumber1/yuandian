<!DOCTYPE html>
<html lang="zh-CN">

	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

		<title>绑定小区</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="apple-touch-fullscreen" content="yes">
		<meta name="apple-mobile-web-app-status-bar-style" content="black">
		<meta name="format-detection" content="telephone=no">
		<meta name="format-detection" content="address=no">
		<link href="{pigcms{$static_path}village_list/css/pigcms.css" rel="stylesheet"/>
	</head>
	<body>
		<style>
			.bind-link-a { color:#FFF;float:left; width:100%; }
		</style>
        <section class="popup">
            <div class="p400">
                <p>您不属于当前小区是否去绑定小区？</p>
                <div class="clr button">
                    <div class="fl cancel link-url" data-url="{pigcms{:U('village',array('village_id'=>$_GET['village_id']))}">取消</div>
                    <div class="fr binding"><a href="{pigcms{:U('empty_village_unit_list',array('village_id'=>$_GET['village_id']))}" class="bind-link-a">去绑定</a></div>
                </div>
            </div>
        </section>
        <div class="mask"></div>
    
    
    <script src="{pigcms{$static_path}js/jquery-1.8.3.min.js"></script>
        <script src="{pigcms{$static_path}village_list/js/common.js"></script>
    <script type="text/javascript">
		// 弹窗
		$(function(){
			$(".popup,.mask").show();	
		})
		// $(".bind_list ul").height($(window).height()-$(".bind_top").innerHeight());

    // 弹窗
    $(".bind_top").click(function(){
        $(".popup,.mask").show();
    });
    $(".popup .cancel,.mask").click(function(){
        $(".popup,.mask").hide();
    });

	</script>
	<script type="text/javascript">
	/*layer.open({
		content: '您不属于当前小区，是否去绑定小区',
		btn: ['去绑定', '取消'],
		shadeClose: false,
		yes: function(){
			location.href="{pigcms{:U('bind_village')}&type=go&village_id={pigcms{$_GET['village_id']}"
		},
		no:function(){
			location.href="{pigcms{:U('village')}&village_id={pigcms{$_GET['village_id']}"
		}
	});
*/
	</script>
	</body>

</html>


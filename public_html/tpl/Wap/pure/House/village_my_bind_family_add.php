<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <title>绑定家属</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, width=device-width"/>
		<meta name="apple-mobile-web-app-capable" content="yes"/>
		<meta name='apple-touch-fullscreen' content='yes'/>
		<meta name="apple-mobile-web-app-status-bar-style" content="black"/>
		<meta name="format-detection" content="telephone=no"/>
		<meta name="format-detection" content="address=no"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/common.css?210"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/village.css?211"/>
		<script type="text/javascript" src="{pigcms{:C('JQUERY_FILE_190')}" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/iscroll.js?444" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/fastclick.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/jquery.cookie.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/common.js?210" charset="utf-8"></script>
		<script type="text/javascript">
			var okUrl = "{pigcms{:U('House/village_my_bind_family_list',array('village_id'=>$_GET['village_id']))}";
		</script>
		<!--script type="text/javascript" src="{pigcms{$static_path}js/village_my_bind_family.js?210" charset="utf-8"></script-->
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>绑定家属</header>
	<else />
		<style type="text/css">
			#container{top:0}
		</style>
    </if>
		<div id="container">
			<div id="scroller" class="village_repair">
				<form id="repair_form" onsubmit="return false;">
                	<section>
                      <div class="area_input" style="margin-top:15px;">
							<input type="text" class="bind_family_txt" id="bind_family_name" placeholder="请输入绑定家属名称">
							<span class="nametip"></span>
						</div>
					</section>
                
					<section>
                      <div class="area_input" style="margin-top:15px;">
							<input type="tel" class="bind_family_txt" id="bind_family_phone" placeholder="请输入绑定家属手机号">
							<span class="nametip"></span>
						</div>
					</section>
					<div class="area_btn"><input type="submit" id="submit_btn" value="绑定"/></div>
				</form>
				<script>
				$('#backBtn').click(function(){
					window.history.go(-1);
				});
				$('#submit_btn').click(function(){
					$('#bind_family_phone').val($.trim($('#bind_family_phone').val()));
					$('#bind_family_name').val($.trim($('#bind_family_name').val()));

					layer.open({type: 2,content: '绑定中，请稍等',shadeClose:false});
					$.post(window.location.href,{'name':$('#bind_family_name').val(),'phone':$('#bind_family_phone').val()},function(result){
						layer.closeAll();
						if(result.err_code == 1){
							layer.open({content:'绑定成功!',shadeClose:false,btn:['确定'],yes:function(){
								window.location.href = okUrl;
							}});
						}else{
							motify.log(result.err_msg);
						}
					});
				});
				</script>
			</div>
		</div>
		{pigcms{$shareScript}
	</body>
</html>
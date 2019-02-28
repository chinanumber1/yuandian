<?php if(!defined('PigCms_VERSION')){ exit('deny access!');} ?>
<!DOCTYPE html>
<html lang="zh-CN">
	<head>
		<meta charset="utf-8" />
        <if condition="!$is_app_browser">
        <title>{pigcms{$now_village.village_name}</title>
        <else/>
        <title>投诉建议</title>
        </if>
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
			var okUrl = "{pigcms{:U('House/village_my_suggestlist',array('village_id'=>$now_village['village_id']))}";
		</script>
		<!--script type="text/javascript" src="{pigcms{$static_path}js/village_my.js?210" charset="utf-8"></script-->
		<script type="text/javascript" src="{pigcms{$static_path}js/exif.js?210" charset="utf-8"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/imgUpload.js?210" charset="utf-8"></script>
	</head>
	<body>
    <if condition="!$is_app_browser">
        <header class="pageSliderHide"><div id="backBtn"></div>投诉建议</header><else />
		<style type="text/css">
			#container{top:0}
		</style>
    </if>
		<div id="container">
			<div id="scroller" class="village_repair">
				<form id="repair_form" onsubmit="return false;">
					<section>
						<textarea id="j_cmnt_input" class="newarea" name="content" placeholder="文字"></textarea>
						<div class="pic_tip" id="uploadNum">还可上传<span class="leftNum orange">8</span>张图片，已上传<span class="loadedNum orange">0</span>张(非必填)</div>
						<div class="upload_box"> 
							<ul class="upload_list clearfix" id="upload_list"> 
								<li class="upload_action">
									<img src="{pigcms{$config.site_url}/tpl/Wap/default/static/classify/upimg.png"/>
									<input type="file" accept="image/*" id="fileImage" name=""/>
								</li> 
							</ul> 
						</div>
					</section>
				</form>
				<div class="area_btn"><input type="button" id="submit_btn" value="提交"/></div>
			</div>
		</div>
		<script>
			$('#backBtn').click(function(){
				window.history.go(-1);
			});
			
			if($("#upload_list").length){
				var imgUpload = new ImgUpload({
					fileInput: "#fileImage",
					container: "#upload_list",
					countNum: "#uploadNum",
					url:"/wap.php?c=House&a=ajaxImgUpload"
				});
				$('#submit_btn').click(function(){
					$('#j_cmnt_input').val($.trim($('#j_cmnt_input').val()));
					if($('#j_cmnt_input').val() == ''){
						motify.log('请填写内容');
						return false;
					}
					layer.open({type: 2,content: '提交中，请稍等',shadeClose:false});
					$.post(window.location.href,$('#repair_form').serialize(),function(result){
						layer.closeAll();
						if(result.err_code == 1){
							layer.open({content:'提交成功!',shadeClose:false,btn:['确定'],yes:function(){
								layer.closeAll();
								window.location.href = okUrl;
							}});
						}else{
							motify.log(result.err_msg);
						}
					});
				});
			}
		</script>
		{pigcms{$shareScript}
	</body>
</html>
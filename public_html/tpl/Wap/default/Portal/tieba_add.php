<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<title>发表新帖-贴吧-{pigcms{$config.site_name}</title>
	<meta name="keywords" content="地方门户,百姓生活门户">
	<meta name="description" content="{pigcms{$config.site_name}">
	<link href="{pigcms{$static_path}portal/css/tieba-mb.css" rel="stylesheet">
	<link href="{pigcms{$static_path}portal/css/member-mb.css" rel="stylesheet" type="text/css">
	<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-base.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-index.css">
	<link rel="stylesheet" rev="stylesheet" href="{pigcms{$static_path}portal/css/mb-common.css">
	<script src="{pigcms{$static_path}portal/js/jquery-2.1.1.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_public}js/ajaxfileupload.js"></script>
	<script type="text/javascript" src="{pigcms{$static_path}layer/layer.m.js"></script>
	<!--必须在现有的script外-->
</head>
<body class="<if condition="$like_app_browser">like_app</if>">
	<div id="pageMain">
		<div class="header">
			<a href="javascript:void(0);" onclick="return window.history.go(-1);" class="back">返回</a>
			<div class="search" id="search_ico" onclick="showNewPage(&#39;搜索&#39;,searchHtml,newPageSearch);" style="display:none;">搜索</div>
			<a href="{pigcms{:U('Wap/My/index')}" class="my ico_ok" id="login_ico" style="display: none;">我的</a>
			<div class="type" id="nav_ico" style="display: none;">导航</div>
			<span id="ipageTitle" style="">发表帖子</span>
			<include file="Portal:top_nav"/>
		</div>
	<style>
		.cat a { display:inline-block; vertical-align:middle; width:23%; word-break:break-all; white-space:nowrap; padding:2px 6px; border:1px solid #eee; text-align:center; box-sizing:border-box; margin:5px 1%; border-radius:16px; font-size:14px;}
		.cat .current { border-color:#f93; color:#f93;}
		.cat .cat_item_reset { border-color:#60b6f3; color:#60b6f3;}
	</style>
		<div class="o_main">
			<form method="post" action="{pigcms{:U('Portal/tieba_add')}" onsubmit="return checkmyfabu();">
				<div class="tabItem tab-cont" style="display:block;">
					<div class="inp_Itembox">
						<if condition="$return_val eq 0">
							<div class="span_2" style="padding: 10px 0;border-bottom: 1px solid #f1f1f1;">
								<label style="margin: 0  5%">
									<input type="radio" name="type"  checked="checked" value="0"> 
									<span>图文</span>
								</label>
								<label>
									<input type="radio" name="type" value="1">
									<span>视频</span>
								</label>			
							</div>
						</if>
							<script>
								$('input:radio[name="type"]').click(function(){
									var val=$('input:radio[name="type"]:checked').val();
									if(val == 0){
										$("#imgType").css('display','block');
										$("#content").css('display','block');
										$("#videoType").css('display','none');
									}else{
										$("#imgType").css('display','none');
										$("#content").css('display','none');
										$("#videoType").css('display','block');
									}

								})
							</script>


						<dl class="clearfix" style="padding-left:0;">
							<dd><input type="text" name="title" id="fabu_title" value="" placeholder="标题(必填)"></dd>
						</dl>
					
						<dl class="clearfix" style="padding-left:0; display: block;" id="content">
							<dd>
								<p class="reply_tips" style="color:#aaa; display: none;">内容(必填)</p>
								<div class="qita" id="cmt_txt" style="height:160px; overflow:auto; -webkit-overflow-scrolling:touch;-webkit-user-select:auto;" contenteditable="true"></div>
								<textarea rows="8"  id="fabu_content" name="content" style="display: none;" placeholder="内容必填" style="width: 100%;border:none;resize:none;"></textarea> 
							</dd>
						</dl>

						<dl class="clearfix" style="padding:0">
							<dd style="padding:10px 0;">
								<div class="cat">
									<input type="hidden" id="plate_id" name="plate_id" value="">
									<volist name="tiebaPlateList" id="vo">
										<a href="javascript:void(0);" class="cat_item" id="plate_{pigcms{$vo.plate_id}" onclick="checkPlate({pigcms{$vo.plate_id})">{pigcms{$vo.plate_name}</a>
									</volist>
								</div>
							</dd>
						</dl>
					</div>
				</div>

				<div class="fill_content">
					<div class="reply_tabs" id="reply_tabs">
						<div class="tab-cont" style="display:block;" id="imgType">
							<div class="imgcon">
								<div class="my_prop_imgitem_node clearfix">
									<div class="upimgFileBtnNode">
										<img src="{pigcms{$static_path}portal/images/upimg.png" id="upimgFileBtn" class="upimgFileBtn imgview" alt="" style="height: 78px;">
										<input type="file" id="imgUploadFile" onchange="imgUpload()" style="display: none;" name="imgFile" value="选择文件上传"/>
									</div> 
									<!-- <a href="javascript:void(0);" onclick="delfile(this)" class="del">删除</a>  -->
								</div>
							</div>
						</div>

						<div class="tab-cont" style="display:none;" id="videoType">
							<div class="imgcon" style="text-align: left; padding-left: 5px;">
								<div class="my_video_node clearfix">
									<div class="upimgFileBtnNode">
										<img src="{pigcms{$static_path}portal/images/upimg.png" id="upvideoFileBtn" class="upimgFileBtn imgview" alt="" style="height: 78px;">
										<input type="file" accept="video/*" id="videoUploadFile" onchange="videoUpload()" style="display: none;" name="videoFile" value="选择文件上传"/>
									</div>
									<div class="my_prop_imgitem" style="display: none;"> <div class="imgviewNode" onclick="preview()"> 
									<!-- <video id="imgId" src="" class="imgview" style="height: 78px;"></video> -->
									<img id="imgId" src="" class="imgview" style="height: 78px;" alt="">
									<input type="hidden" name="videoUrl" id="videoUrl" value=""/></div></div>
								</div>
								<span style=" padding-left: 10px; color: red;">仅支持上传<?php echo ini_get('upload_max_filesize') ?>以内的视频文件</span>
							</div>
						</div>
					</div>
				</div>

				<div style="margin:10px 10px 20px;"><input class="comn-submit fabu_btn" type="submit" value="发 布"></div>
			</form>
		</div>
	</div>
	
	<style>
        .preview{
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 10000;
            background: #F1F1F1;
            display: none;
        }
    </style>
    <input type="hidden" id="previewUrl" value=""/>
	<div class="preview">
		<script type="text/javascript" src="{pigcms{$static_public}ckplayer/ckplayer.js"></script>
		<div class="video" style="width: 100%;height: 400px; margin-top: 20%" ></div>
		<div style="text-align: center; margin-top: 15px;" onclick="$('.preview').css('display','none')"><button class="comn-submit fabu_btn"> 关闭</button></div>
		<script type="text/javascript">
			function preview(){
				var url = $("#previewUrl").val();
				var videoObject = {
					container: '.video',//“#”代表容器的ID，“.”或“”代表容器的class
					variable: 'player',//该属性必需设置，值等于下面的new chplayer()的对象
					poster:'pic/wdm.jpg',//封面图片
					video:url
				};
				var player=new ckplayer(videoObject);
				$('.preview').css('display','block');
			}
		</script>
	</div>
</body>

<script type='text/javascript'>

	$("#upvideoFileBtn").click(function(){
		$("#videoUploadFile").click();
	})
	check_login()
	
	function check_login() {  
	var uid  = "{pigcms{$user_session['uid']}";
		if(!uid){
			layer.open({
                content: '请先登录'
                ,btn: ['去登录']
                ,yes: function(index){
                    location.href = "{pigcms{:U('Login/index')}";
                }
            });
			return false;
		}
	}  
	
	
	function outputObj(obj) {  
		var description = "";  
		for (var i in obj) {  
			description += i + " = " + obj[i] + "\n";  
		}  
		alert(description);  
	}  

	function videoUpload(){

		layer.open({
		    type: 2
		    ,shadeClose:false
		    ,content: '上传中'
	  	});

		$.ajaxFileUpload({
			url:"{pigcms{:U('Portal/ajax_upload_video')}",
			secureuri:false,
			fileElementId:'videoUploadFile',
			dataType: 'json',
			success: function (data) {
				console.log(data);

				setTimeout(function(){layer.closeAll();},1000);

				if(data.error == 2){
					//$("#imgId").attr('src',data.url+'.png');
					$("#imgId").attr('src','{pigcms{$static_path}portal/images/open.png');
					$("#videoUrl").val(data.url);
					$(".my_prop_imgitem").css('display','block');
					$("#previewUrl").val(data.url+'.mp4');
				}else{
					alert(data.msg);
				}
			},
			  error: function (data, status, e)//服务器响应失败处理函数
			{
				alert('不支持该视频类型');
				// outputObj(data);
			}
		});
		
	}



										
	$("#upimgFileBtn").click(function(){
		$("#imgUploadFile").click();
	})

	function imgUpload(){
		var len=$('.my_prop_imgitem_node>.my_prop_imgitem').length;
		if(len<=7){
			$.ajaxFileUpload({
				url:"{pigcms{:U('Portal/ajax_upload_file')}",
				secureuri:false,
				fileElementId:'imgUploadFile',
				dataType: 'json',
				success: function (data) {
					console.log(data);
					if(data.error == 2){
						$(".my_prop_imgitem_node").append('<div class="my_prop_imgitem"> <div class="imgviewNode"> <b></b><img src="'+data.url+'" class="imgview" style="height: 78px;"><input type="hidden" name="imgList[]" value="'+data.url+'" /></div></div>');
					}else{
						alert(data.msg);
					}
				}
			}); 
		}else{
			alert('最多只能上传8张图片');
		}
		
	}
	// 删除照片按钮点击
	$('.my_prop_imgitem_node.clearfix').off('click','.imgviewNode b').on('click','.imgviewNode b',function(e){
		$(this).parents('.my_prop_imgitem').remove();
	});

	function checkPlate(id){
		if ($("#plate_"+id).hasClass('current')) {
			$("#plate_"+id).removeClass('current');
		}else{
			$(".cat_item").removeClass('current');
			$("#plate_"+id).addClass('current');
			$("#plate_id").val(id);
		}
	}
	

	function delfile(obj){
		alert(obj);
	}

	function checkmyfabu(){
		var uid  = "{pigcms{$user_session['uid']}";
		if(!uid){
			layer.open({
                content: '请先登录'
                ,btn: ['去登录']
                ,yes: function(index){
                    location.href = "{pigcms{:U('Login/index')}";
                }
            });
			return false;
		}
		
		if(!$("#fabu_title").val()){
			layer.open({
				content: '标题不可以为空'
				,skin: 'msg'
				,time: 2  
			});
			return false;
		}
		// if(!$("#cmt_txt").html()){
		// 	layer.open({
		// 		content: '内容不可以为空'
		// 		,skin: 'msg'
		// 		,time: 2  
		// 	});
		// 	return false;
		// }
		$("#fabu_content").html($("#cmt_txt").html());
	}
</script>
</html>
<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('App/index')}">App在线打包</a>
			</li>
			<li class="active">创建应用</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
				#levelcoupon select {width:150px;margin-right: 20px;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="add_form">
						<div class="tab-content">				
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1">应用名称：</label>
									<input class="col-sm-3" maxlength="100" name="name" id="name" type="text" />
									<span class="form_tips">应用名称1至10个字。手机桌面上的应用名称</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">应用简介：</label>
									<textarea class="col-sm-3" rows="5" name="intro" id="intro"></textarea>
									<span class="form_tips"></span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">应用链接：</label>
									<input class="col-sm-3" maxlength="100" name="webUrl" type="text" id="webUrl"/>
									&nbsp;&nbsp;&nbsp;<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('webUrl',0)" data-toggle="modal">从功能库选择</a>
									<span class="form_tips">可以从功能库中选择链接，也可以添加其他链接如http://www.baidu.com</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">应用类别：</label>
									<select name="appType" class="col-sm-1" id="appType">
										<option value="0">安卓</option>
										<option value="1">IOS</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1">应用图标</label>
									<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_IcoSelectImage">上传图片</a>
									<span class="form_tips" id="icoTip">必须上传大小为 200(宽)*200(高) 像素的 <font color="red">png格式</font> 图片！否则生成应用会提示失败。</span>
								</div>
								<div class="form-group" id="upload_ico_box" style="display:none;">
									<label class="col-sm-1">应用图标预览</label>
									<div id="upload_ico_pic_box">
										<ul id="upload_ico_pic_ul"></ul>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-1">欢迎图</label>
									<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_HelloSelectImage">上传图片</a>
									<span class="form_tips" id="helloPicTip">为达最佳显示效果请选择大小为 780(宽)*1280(高) 像素的 <font color="red">png格式</font> 图片！（注意：安卓和IOS图片大小不一样）</span>
								</div>
								<div class="form-group" id="upload_hello_box" style="display:none;">
									<label class="col-sm-1">欢迎图预览</label>
									<div id="upload_hello_pic_box">
										<ul id="upload_hello_pic_ul"></ul>
									</div>
								</div>
								<div class="form-group">
									<label class="col-sm-1">隐藏标题栏：</label>
									<select name="hideTop" class="col-sm-1">
										<option value="0">否</option>
										<option value="1">是</option>
									</select>
									<span class="form_tips">是否显示系统的标题栏</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">横竖屏：</label>
									<select name="screen" class="col-sm-1">
										<option value="0">竖屏</option>
										<option value="1">横屏</option>
									</select>
									<span class="form_tips">打开应用哪种方式展现，手机一般为竖屏，Pad一般为横屏</span>
								</div>
							</div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit" id="save_btn">
										<i class="ace-icon fa fa-check bigger-110"></i>
										保存
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
input.ke-input-text {
background-color: #FFFFFF;
background-color: #FFFFFF!important;
font-family: "sans serif",tahoma,verdana,helvetica;
font-size: 12px;
line-height: 24px;
height: 24px;
padding: 2px 4px;
border-color: #848484 #E0E0E0 #E0E0E0 #848484;
border-style: solid;
border-width: 1px;
display: -moz-inline-stack;
display: inline-block;
vertical-align: middle;
zoom: 1;
}
.form-group>label{font-size:12px;line-height:24px;}
#upload_ico_pic_box,#upload_hello_pic_box{margin-top:20px;height:100px;}
#upload_ico_pic_box .upload_pic_li,#upload_hello_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
#upload_ico_pic_box img,#upload_hello_pic_box img{width:100px;height:70px;}
</style>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script type="text/javascript">
KindEditor.ready(function(K) {
	var editor = K.editor({
		allowFileManager : true
	});
	K('#J_IcoSelectImage').click(function(){
		if($('#upload_ico_pic_ul .upload_pic_li').size() >= 1){
			alert('最多上传1张图片！请先删除已上传图片再上传');
			return false;
		}
		editor.uploadJson = "{pigcms{:U('App/ajax_upload_pic')}";
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#icoPic').val(),
				clickFn : function(url, title, width, height, border, align) {
					$('#upload_ico_box').show();
					$('#upload_ico_pic_ul').append('<li class="upload_pic_li"><img src="'+url+'"/><input type="hidden" name="icoPic" id="icoPic" value="'+title+'"/><br/><a href="#" onclick="deleteImage(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
					editor.hideDialog();
				}
			});
		});
	});
	K('#J_HelloSelectImage').click(function(){
		if($('#upload_hello_pic_ul .upload_pic_li').size() >= 1){
			alert('最多上传1张图片！请先删除已上传图片再上传');
			return false;
		}
		editor.uploadJson = "{pigcms{:U('App/ajax_upload_pic')}";
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#helloPic').val(),
				clickFn : function(url, title, width, height, border, align) {
					$('#upload_hello_box').show();
					$('#upload_hello_pic_ul').append('<li class="upload_pic_li"><img src="'+url+'"/><input type="hidden" name="helloPic" id="helloPic" value="'+title+'"/><br/><a href="#" onclick="deleteImage(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
					editor.hideDialog();
				}
			});
		});
	});
	
	$('#appType').change(function(){
		if($(this).val() == '0'){
			$('#helloPicTip').html('为达最佳显示效果请选择大小为 780(宽)*1280(高) 像素的 <font color="red">png格式</font> 图片！（注意：安卓和IOS图片大小不一样）');
		}else{
			$('#helloPicTip').html('为达最佳显示效果请选择大小为 640(宽)*960(高) 像素的 <font color="red">png格式</font> 图片！（注意：安卓和IOS图片大小不一样）');
		}
	});
	$('#add_form').submit(function(){
		$('#name').val($.trim($('#name').val()));
		if($('#name').val().length > 10 || $('#name').val().length < 1){
			alert('应用名称1至10个字。');
			return false;
		}
		
		$('#webUrl').val($.trim($('#webUrl').val()));
		if($('#webUrl').val() == ''){
			alert('请填写应用链接');
			return false;
		}
		
		if($('#icoPic').size() < 1){
			alert('请上传应用图标');
			return false;
		}
		
		if($('#helloPic').size() < 1){
			alert('请上传应用欢迎图');
			return false;
		}
		
		$('#save_btn').prop('disabled',true);
		$.post("{pigcms{:U('App/add')}",$('#add_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('App/index')}";
			}else{
				alert(result.info);
			}
			$('#save_btn').prop('disabled',false);
		})
		return false;
	});
});
function deleteImage(path,obj){
	$.post("{pigcms{:U('Group/ajax_del_pic')}",{path:path});
	var formGroup = $(obj).closest('.form-group');
	$(obj).closest('.upload_pic_li').remove();
	formGroup.hide();
}
</script>
<include file="Public:footer"/>
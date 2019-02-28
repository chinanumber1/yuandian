<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-home"></i>
				<a href="{pigcms{:U('Scenic/project')}">景区管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Scenic/project_details',array('project_id'=>$_GET['project_id']))}">项目详情</a></li>
			<li class="active">修改详情</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active">
								<a data-toggle="tab" href="#basicinfo">修改详情</a>
							</li>

						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<input type="hidden" name="project_id" value="{pigcms{$_GET['project_id']}"/>
						<input type="hidden" name="pigcms_id" value="{pigcms{$_GET['pigcms_id']}"/>
						<div class="tab-content">
							<div class="tab-content"><div class="form-group">
								<label class="col-sm-1"><label>项目标题</label></label>
								<input type="text" name="project_title" value="{pigcms{$details['project_title']}" />
								<span class="form_tips">必填。20字以内</span>
							</div>
							<div class="form-group">
								<label class="col-sm-1">项目描述</label>
								<textarea id="project_conter" name="project_conter"  placeholder="写上一些想要发布的内容">{pigcms{$details['project_conter']|htmlspecialchars_decode=ENT_QUOTES}</textarea>
							</div>
							<div class="form-group">
								<label class="col-sm-1">项目语音文本</label>
								<textarea class="col-sm-5" rows="5" name="project_conter_audio" id="project_conter_audio">{pigcms{$details['project_conter_audio']}</textarea>
							</div>
							<div class="form-group">
								<label class="col-sm-1"><label>排序</label></label>
								<input type="number" name="project_sort" value="{pigcms{$details['project_sort']}" />
								<span class="form_tips">值越大，越靠前</span>
							</div>
							<div class="form-group">
								<label class="col-sm-1">项目图片</label>
								<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectImage">上传图片</a>
								<span class="form_tips">第1张将作为主图片！最多上传10张图片！图片宽度建议为640px，高度建议为230px。</span>
							</div>
							<div class="form-group">
								<label class="col-sm-1">图片预览</label>
								<div id="upload_pic_box">
									<ul id="upload_pic_ul">
										<volist name="details['pic']" id="vo">
											<li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
										</volist>
									</ul>
								</div>
							</div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit">
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
#upload_pic_box{margin-top:20px;height:150px;}
#upload_pic_box .upload_pic_li{width:130px;float:left;list-style:none;}
#upload_pic_box img{width:100px;height:70px;}
</style>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
KindEditor.ready(function(K){
	var editor = K.editor({
		allowFileManager : true
	});
	K('#J_selectImage').click(function(){
		if($('.upload_pic_li').size() >= 10){
			alert('最多上传10个图片！');
			return false;
		}
		editor.uploadJson = "{pigcms{:U('ajax_upload_pic')}";
		editor.loadPlugin('image', function(){
			editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#course_pic').val(),
				clickFn : function(url, title, width, height, border, align) {
					$('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+url+'"/><input type="hidden" name="pic[]" value="'+title+'"/><br/><a href="#" onclick="deleteImage(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
					editor.hideDialog();
				}
			});
		});
	});
	kind_editor = K.create("#project_conter",{
		width:'200px',
		height:'200px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		items : [
			'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist'
		],
//		emoticonsPath : './static/emoticons/',
//		uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=merchant/news" , 'link' 'image', , '|', 'emoticons'
	});
	$('#edit_form').submit(function(){
		$.post("{pigcms{:U('project_details_edit')}",$('#edit_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('project_details',array('project_id'=>$_GET['project_id']))}";
			}else{
				alert(result.info);
			}
		})
		return false;
	});
});
function deleteImage(path,obj){
	$.post("{pigcms{:U('ajax_del_pic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}
</script>
<include file="Public:footer"/>
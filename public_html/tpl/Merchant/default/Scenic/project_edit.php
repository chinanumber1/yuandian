<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-home"></i>
				<a href="{pigcms{:U('Scenic/project')}">景区管理</a>
			</li>
			<li class="active">修改项目</li>
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
								<a data-toggle="tab" href="#basicinfo">添加项目</a>
							</li>

						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<input type="hidden" name="project_id" value="{pigcms{$pro.project_id}"/>
						<div class="tab-content">
							<div class="form-group">
								<label class="col-sm-1"><label>项目名</label></label>
								<input type="text" name="project_title" value="{pigcms{$pro.project_title}" />
								<span class="form_tips">请填写项目 比如(过山车、水上世界)</span>
							</div>
							<div class="form-group">
								<label class="col-sm-1"><label>排序</label></label>
								<input type="number" name="project_sort" value="{pigcms{$pro.project_sort}" />
								<span class="form_tips">值越大越靠前</span>
							</div>

							<div class="form-group">
								<label class="col-sm-1"><label>项目金额</label></label>
								<input type="number" name="project_price" value="{pigcms{$pro.project_price}" />
								<span class="form_tips">请输入金额，免费请填0</span>
							</div>
							<div class="form-group">
								<label class="col-sm-1"><label for="long_lat">经纬度</label></label>
								<input class="col-sm-2" size="10" name="long_lat" id="long_lat" value="{pigcms{$pro.long},{pigcms{$pro.lat}" type="text" readonly="readonly"/>
								&nbsp;&nbsp;&nbsp;&nbsp;<a href="#modal-table" class="btn btn-sm btn-success" id="show_map_frame" data-toggle="modal">点击选取经纬度</a>
							</div>
							<div class="form-group">
								<label class="col-sm-1">项目描述</label>
								<textarea id="project_conter" name="project_conter"  placeholder="写上一些想要发布的内容">{pigcms{$pro['project_conter']|htmlspecialchars_decode=ENT_QUOTES}</textarea>
							</div>
							<div class="form-group">
								<label class="col-sm-1">项目语音文本</label>
								<textarea class="col-sm-5" rows="5" name="project_conter_audio" id="project_conter_audio">{pigcms{$pro.project_conter_audio}</textarea>
							</div>
							<div class="form-group">
								<label class="col-sm-1">项目图片</label>
								<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectImage">上传图片</a>
								<span class="form_tips">第1张将作为主图片！最多上传10个图片！图片宽度建议为640px，高度建议为230px。</span>
							</div>
							<div class="form-group">
								<label class="col-sm-1">图片预览</label>
								<div id="upload_pic_box">
									<ul id="upload_pic_ul">
										<volist name="pro['pic']" id="vo">
											<li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
										</volist>
									</ul>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-1" for="project_status">状态</label>
								<select name="project_status" id="project_status">
									<option value="1" <if condition="$pro['project_status'] eq 1">selected="selected"</if>>开启</option>
									<option value="2" <if condition="$pro['project_status'] eq 2">selected="selected"</if>>关闭</option>
								</select>
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
<div id="modal-table" class="modal fade" tabindex="-1">
	<div class="modal-dialog" style="width:80%;">
		<div class="modal-content" style="width:100%;">
			<div class="modal-header no-padding" style="width:100%;">
				<div class="table-header">
					<button id="close_button" type="button" class="close" data-dismiss="modal" aria-hidden="true">
						<span class="white">&times;</span>
					</button>
					(用鼠标滚轮可以缩放地图)    拖动红色图标，经纬度框内将自动填充经纬度。
				</div>
			</div>
			<div class="modal-body no-padding" style="width:100%;">
				<form id="map-search" style="margin:10px;">
					<input id="map-keyword" type="textbox" style="width:500px;" placeholder="尽量填写城市、区域、街道名"/>
					<input type="submit" value="搜索"/>
				</form>
				<div style="width:100%;height:600px;min-height:600px;" id="cmmap"></div>
			</div>
			<div class="modal-footer no-margin-top">
				<button class="btn btn-sm btn-success pull-right" data-dismiss="modal">
					<i class="ace-icon fa fa-times"></i>
					关闭
				</button>
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
<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/map.js"></script>
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
		$.post("{pigcms{:U('project_edit')}",$('#edit_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('project')}";
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
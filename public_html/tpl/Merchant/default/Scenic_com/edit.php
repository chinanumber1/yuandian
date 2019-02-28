<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('index')}">景区管理</a>
			</li>
			<li class="active">修改分类</li>
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
								<a data-toggle="tab" href="#basicinfo">修改分类</a>
							</li>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<input type="hidden" name="cat_id" value="{pigcms{$now_order.cat_id}"/>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label>分类名</label></label>
									<input type="text" name="cat_name" value="{pigcms{$now_order.cat_name}" />
									<span class="form_tips">必填。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>排序</label></label>
									<input type="number" name="cat_sort" value="{pigcms{$now_order['cat_sort']}" />
								</div>
								<div class="form-group">
									<label class="col-sm-1">详情页面推荐</label>
									<select name="is_recom">
										<option value="1" <if condition="$now_order['is_recom'] eq 1">selected="selected"</if>>推荐</option>
										<option value="0" <if condition="$now_order['is_recom'] eq 0">selected="selected"</if>>不推荐</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1">状态</label>
									<select name="status">
										<option value="1" <if condition="$now_order['status'] eq 1">selected="selected"</if>>开启</option>
										<option value="0" <if condition="$now_order['status'] eq 0">selected="selected"</if>>关闭</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1">图片</label>
									<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectImage">上传图片</a>
									<span class="form_tips">必填。只需要上传1张。必须清楚，尺寸建议：150px*150px</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">图片预览</label>
									<div id="upload_pic_box">
										<ul id="upload_pic_ul">
											<volist name="now_order['pic']" id="vo">
												<li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
											</volist>
										</ul>
									</div>
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
<script type="text/javascript" src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript">
KindEditor.ready(function(K){
	var editor = K.editor({
		allowFileManager : true
	});
	K('#J_selectImage').click(function(){
		if($('.upload_pic_li').size() >= 1){
			alert('最多上传1个图片！');
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
	$('#edit_form').submit(function(){
		$.post("{pigcms{:U('edit')}",$('#edit_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('index')}";
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
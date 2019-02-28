<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Config/store')}">店铺管理</a>
			</li>
			<li class="active">店铺资质资料管理</li>
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
								<a data-toggle="tab" href="#basicinfo">资质审核</a>
							</li>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div id="txtstore" class="tab-pane active">
								<if condition="$config['store_shop_auth_tips']">
								<div class="alert alert-info" style="margin:10px 0;">
									<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
									{pigcms{$config['store_shop_auth_tips']}
								</div>
								</if>
								<div class="form-group">
									<label class="col-sm-1">资质资料图片</label>
									<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectImage">上传资质资料</a>
								</div>
								<div class="form-group">
									<label class="col-sm-1">资质资料预览</label>
									<div id="upload_pic_box">
										<ul id="upload_pic_ul">
											<volist name="now_store['auth_files']" id="vo">
												<li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
											</volist>
										</ul>
									</div>
								</div>
								<if condition="($now_store['auth'] eq 2 OR $now_store['auth'] eq 5) AND $now_store['reason']">
								<div class="form-group">
									<label class="col-sm-1"><if condition="$now_store['auth'] eq 2">拒绝<else />驳回</if>理由</label>
									<label class="col-sm-2"><b style="color:red">{pigcms{$now_store['reason']}</b></label>
								</div>
								</if>
							</div>
						</div>
						<div class="space"></div>
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
.webuploader-element-invisible {
    position: absolute !important;
    clip: rect(1px 1px 1px 1px);
    clip: rect(1px,1px,1px,1px);
}
.webuploader-pick-hover .btn{
	background-color: #629b58!important;
    border-color: #87b87f;
}
</style>
<link rel="stylesheet" href="{pigcms{$static_public}kindeditor/themes/default/default.css">
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script src="{pigcms{$static_public}kindeditor/lang/zh_CN.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
	setTimeout(function(){
		var uploader = WebUploader.create({
				auto: true,
				swf: '{pigcms{$static_public}js/Uploader.swf',
				server: "{pigcms{:U('Config/ajax_upload_authfile', array('store_id' => $now_store['store_id']))}",
				pick: {
					id:'#J_selectImage',
					multiple:false
				},
				accept: {
					title: 'Images',
					extensions: 'gif,jpg,jpeg,png',
					mimeTypes: 'image/gif,image/jpeg,image/jpg,image/png'
				}
			});
		uploader.on('fileQueued',function(file){
			if($('.upload_pic_li').size() >= 5){
				uploader.cancelFile(file);
				alert('最多上传5个图片！');
				return false;
			}
		});
		uploader.on('uploadSuccess',function(file,response){
			if(response.error == 0){
				$('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+response.url+'"/><input type="hidden" name="pic[]" value="'+response.title+'"/><br/><a href="#" onclick="deleteImage(\''+response.title+'\',this);return false;">[ 删除 ]</a></li>');
			}else{
				alert(response.info);
			}
		});
		
		uploader.on('uploadError', function(file,reason){
			$('.loading'+file.id).remove();
			alert('上传失败！请重试。');
		});
		
	}, 20);

function deleteImage(path,obj){
// 	$.post("{pigcms{:U('Config/ajax_del_authfile')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}

</script>

<include file="Public:footer"/>

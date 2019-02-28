<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('index')}">酒店管理</a>
			</li>
			<li class="active">添加房型类别</li>
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
								<a data-toggle="tab" href="#basicinfo">基本信息</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtintro">房型设置</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtimage">房型图片</a>
							</li>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="cat_name">类别名称</label></label>
									<input class="col-sm-2" size="20" name="cat_name" id="cat_name" type="text" value="{pigcms{$_POST.cat_name}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="cat_sort">类别排序</label></label>
									<input class="col-sm-1" size="10" name="cat_sort" id="cat_sort" type="text" value="{pigcms{$_POST.cat_sort|default='0'}"/>
									<span class="form_tips">默认添加顺序排序！手动调值，数值越大，排序越前</span>
								</div>
								<if condition="$ok_tips">
									<div class="form-group" style="margin-left:0px;">
										<span style="color:blue;">{pigcms{$ok_tips}</span>				
									</div>
								</if>
								<if condition="$error_tips">
									<div class="form-group" style="margin-left:0px;">
										<span style="color:red;">{pigcms{$error_tips}</span>				
									</div>
								</if>
							</div>
							<div id="txtintro" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1"><label for="window_info">窗户</label></label>
									<input class="col-sm-1" size="20" name="window_info" id="window_info" type="text" value="{pigcms{$_POST.window_info}"/>
									<span class="form_tips">例如：部分明窗，部分过道窗</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="floor_info">楼层</label></label>
									<input class="col-sm-1" size="20" name="floor_info" id="floor_info" type="text" value="{pigcms{$_POST.floor_info}"/>
									<span class="form_tips">例如：16-18层</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="room_size">面积</label></label>
									<input class="col-sm-1" size="20" name="room_size" id="room_size" type="text" value="{pigcms{$_POST.room_size}"/>
									<span class="form_tips">例如：15-20平米，18平米</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="bed_info">床型</label></label>
									<input class="col-sm-1" size="20" name="bed_info" id="bed_info" type="text" value="{pigcms{$_POST.bed_info}"/>
									<span class="form_tips">例如：1.5米/2张</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="network_info">网络</label></label>
									<input class="col-sm-1" size="20" name="network_info" id="network_info" type="text" value="{pigcms{$_POST.network_info}"/>
									<span class="form_tips">例如：无线WIFI，无线有线</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="breakfast_info">早餐</label></label>
									<input class="col-sm-1" size="20" name="breakfast_info" id="breakfast_info" type="text" value="{pigcms{$_POST.breakfast_info}"/>
									<span class="form_tips">例如：赠送双早，赠送单早，不含早</span>
								</div>
								<div class="form-group" >
									<label class="col-sm-1">其他描述：</label>
									<textarea name="cat_info" id="cat_info" style="width:402px;height:120px;">{pigcms{$_POST.cat_info}</textarea>
									<span class="form_tips">例如：国内长途电话,洗衣机/烘干机</span>
								</div>
							</div>
							<div id="txtimage" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">上传图片</label>
									<div style="display:inline-block;" id="J_selectImage">
										<div class="btn btn-sm btn-success" style="position:relative;width:78px;height:34px;">上传图片</div>
									</div>
									<span class="form_tips">第一张将作为主图展示！最多上传5个图片！图片宽度建议为：760px，高度建议为：450px</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">图片预览</label>
									<div id="upload_pic_box">
										<ul id="upload_pic_ul">
											
										</ul>
									</div>
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
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
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
<script type="text/javascript" src="{pigcms{$static_public}js/webuploader.min.js"></script>
<script>
$(function(){
	$('form.form-horizontal').submit(function(){
		$(this).find('button[type="submit"]').html('保存中...').prop('disabled',true);
	});
});

var uploaderHas = false;
$('#myTab li a').click(function(){
	if(uploaderHas == false && $(this).attr('href') == '#txtimage'){
		setTimeout(function(){
			var  uploader = WebUploader.create({
					auto: true,
					swf: '{pigcms{$static_public}js/Uploader.swf',
					server: "{pigcms{:U('ajax_upload_pic')}",
					pick: {
						id:'#J_selectImage',
						multiple:false
					},
					accept: {
						title: 'Images',
						extensions: 'gif,jpg,jpeg,png',
						mimeTypes: 'image/jpg,image/jpeg,image/gif,image/png'
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
			
		},20);
		uploaderHas = true; 
	}
});


				
var formathtml = new Array();
var format_value = new Array();
var json = '{pigcms{$now_goods['json']}';
var cssPath = "{pigcms{$static_path}css/group_editor.css";
function deleteImage(path,obj){
	$.post("{pigcms{:U('ajax_del_pic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}
</script>

<include file="Public:footer"/>

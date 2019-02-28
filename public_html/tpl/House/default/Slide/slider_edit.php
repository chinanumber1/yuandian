<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('Slide/index')}">功能库</a>
			</li>
			<li class="active">添加导航</li>
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
					<form  class="form-horizontal" method="post" onSubmit="return check_submit()" action="__SELF__">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="name">广告名称</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" type="text" value="{pigcms{$slider_info['name']}"/>
								</div>
                                <div class="form-group" style="display:none" >
									<textarea id="content"></textarea>
								</div>
								<div id="txtimage" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">上传图片</label>
									<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectImage">上传图片</a>
									图片宽度建议为：640px，高度建议为：238px 
								</div>
								<div class="form-group">
									<label class="col-sm-1">图片预览</label>
									<div id="upload_pic_box">
										<li class="upload_pic_li">
                                        	<img src="/upload/slider/{pigcms{$slider_info['pic']}">
                                            <input type="hidden" value="{pigcms{$slider_info['pic']}" name="pic"><br>
                                            <a onclick="deleteImg('{pigcms{$slider_info[\'pic\']}',this);return false;" href="#">[ 删除 ]</a>
                                        </li>
									</div>
								</div>
							</div>
                             
                                
                                <div class="form-group">
									<label class="col-sm-1"><label for="phone">链接地址</label></label>
									<input class="col-sm-2" size="20" name="url" id="url" type="text" value="{pigcms{$slider_info['url']}" />
                                    <label style=" margin-left:10px"><a href="#modal-table" class="btn btn-sm btn-success" onClick="addLink('url',0)">从功能库选择</a></label>
								</div>
                                
                                
                                <div class="form-group">
									<label class="col-sm-1"><label for="sort">排序</label></label>
									<input class="col-sm-2" size="20" name="sort" id="sort" type="text" value="{pigcms{$slider_info['sort']}" />
                                   	<label class="col-sm-1"> &nbsp;&nbsp;请填写1-10之间的值</label>
								</div>
                                
                               <div class="form-group">
									<label class="col-sm-1">广告状态</label>
									
										<label style="padding-left:0px;padding-right:20px;"><input type="radio" <if condition='$slider_info.status eq 1'>checked="checked"</if> class="ace" value="1" name="status"><span style="z-index: 1" class="lbl">开启</span></label>
										<label style="padding-left:0px;"><input type="radio" class="ace" value="0" name="status" <if condition='$slider_info.status eq 0'>checked="checked"</if>><span style="z-index: 1" class="lbl">关闭</span></label>
								</div>
							</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit" <if condition="!in_array(197,$house_session['menus'])">disabled="disabled"</if>>
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
<style>
.BMap_cpyCtrl{display:none;}
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
#upload_pic_box img{width:100px;height:70px;border:1px solid #ccc;}
</style>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
function addLink(domid,iskeyword){
	art.dialog.data('domid', domid);
	art.dialog.open('?g=shequ&c=Link&a=insert&iskeyword='+iskeyword,{lock:true,title:'插入链接或关键词',width:600,height:400,yesText:'关闭',background: '#000',opacity: 0.45});
}


KindEditor.ready(function(K) {
	var content_editor = K.create("#content",{
		width:'702px',
		height:'260px',
		resizeType : 1,
		allowPreviewEmoticons:false,
		allowImageUpload : true,
		filterMode: true,
		autoHeightMode : true,
		afterCreate : function() {
			this.loadPlugin('autoheight');
		},
		items : [
			'fullscreen', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'emoticons', 'image', 'link', 'table'
		],
		emoticonsPath : './static/emoticons/',
		uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=appoint/content",
		cssPath : "{pigcms{$static_path}css/group_editor.css"
	});
	
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
					$('#upload_pic_box').append('<li class="upload_pic_li"><img src="'+url+'"/><input type="hidden" name="pic" value="'+title+'"/><br/><a href="#" onclick="deleteImg(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
					editor.hideDialog();
				}
			});
		});
	});
	})
	
	function deleteImg(path,obj){
		$.post("{pigcms{:U('ajax_del_pic')}",{path:path});
		$(obj).closest('.upload_pic_li').remove();
	}
	
function check_submit(){
	if($('#name').val() == ''){
		alert('幻灯片名称不能为空！');
		return false;
	}

	if($.type($('input[name="pic"]').val()) == 'undefined'){
		alert('幻灯片图片不能为空！');
		return false;
	}
	
	if($('#url').val() == ''){
		alert('链接地址不能为空！');
		return false;
	}
	
	var sort_num = parseInt($('#sort').val());

	if(!(sort_num>=1&&sort_num<=10)){
		alert('请填写1-10之间的值');
		return false;
	}
}
</script>

<include file="Public:footer"/>
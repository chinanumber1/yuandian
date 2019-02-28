<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('service_category')}">便民服务</a>
			</li>
            
             <if condition='$_GET["cat_id"]'>
            	<li><a href="{pigcms{:U('s_service_category',array('cat_id'=>$_GET['cat_id']))}">{pigcms{$detail.cat_name}</a></li>
            </if>
            
				<li class="active">添加分类</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post" onSubmit="return check_submit()" action="__SELF__">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="cat_name">分类名称：</label></label>
									<input class="col-sm-2" size="20" name="cat_name" id="cat_name" type="text" value=""/>
								</div></div>
                                
                            <if condition="$_GET['cat_id']">
                                <div class="form-group">
									<label class="col-sm-1"><label for="cat_url">分类链接：</label></label>
									<input class="col-sm-2" size="20" name="cat_url" id="url" type="text" value=""/>
                                    <label style=" margin-left:10px"><a href="#modal-table" class="btn btn-sm btn-success" onClick="addLink('url',0)">从功能库选择</a></label>
                                    <label style=" margin-left:10px"><span class="red">*&nbsp;&nbsp;链接可不填写</span></label>
								</div>
                            </if>
                                <if condition="$_GET['cat_id']">
                                <div class="form-group">
									<label class="col-sm-1">分类图标：</label>
									<a id="J_selectImage" class="btn btn-sm btn-success" href="javascript:void(0)">上传图片</a>
									&nbsp;&nbsp;&nbsp;&nbsp;图片宽度建议为：150px，高度建议为：150px 
								</div>
								<div class="form-group">
									<label class="col-sm-1">图标预览：</label>
									<div id="upload_pic_box">
										<ul id="upload_pic_ul"></ul>
									</div>
								</div>
							
                            <div style="display:none">
                           		<textarea id="content"></textarea>
                           </div>
                                </if>
                                
                                <div class="form-group">
									<label class="col-sm-1"><label for="sort">分类排序：</label></label>
									<input type="text" value="0" id="sort" name="sort" size="20" class="col-sm-2">
                                    <label class="col-sm-3"><span class="red">*&nbsp;&nbsp;可不填写（排序值越大，越靠前显示）</span></label>
								</div>
                                
                                
                                <if condition="$_GET['cat_id']">
                                    <div class="form-group">
									<label class="col-sm-1">是否首页显示</label>
									
										<label style="padding-left:0px;padding-right:20px;"><input type="radio" checked="" class="ace" value="1" name="is_index_show"><span style="z-index: 1" class="lbl">开启</span></label>
										<label style="padding-left:0px;"><input type="radio" class="ace" value="0" name="is_index_show"><span style="z-index: 1" class="lbl">关闭</span></label>
								</div>
                                </if>
                                
                                
                                <div class="form-group">
									<label class="col-sm-1">是否开启</label>
									
										<label style="padding-left:0px;padding-right:20px;"><input type="radio" checked="" class="ace" value="1" name="status"><span style="z-index: 1" class="lbl">开启</span></label>
										<label style="padding-left:0px;"><input type="radio" class="ace" value="0" name="status"><span style="z-index: 1" class="lbl">关闭</span></label>
								</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
                                <if condition="$_GET['cat_id']"><input type="hidden" name="parent_id" value="{pigcms{$_GET.cat_id}"/></if>
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

	function check_submit(){
		if($('#cat_name').val()==''){
			alert('分类名称不能为空！');
			return false;
		}

		if(confirm('确认保存？')){
			return true;
		}else{
			return false;
		}
	}
	
	
	$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'分类详情',
				padding: 0,
				width: 720,
				height: 520,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4'
			});
			return false;
		});
		
		
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
    					$('#upload_pic_ul').append('<li class="upload_pic_li"><img src="'+url+'"/><input type="hidden" name="cat_img" value="'+title+'"/><br/><a href="#" onclick="deleteImg(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
    					editor.hideDialog();
    				}
    			});
    		});
    	});
	});
	
	function deleteImg(path,obj){
		$.post("{pigcms{:U('ajax_del_pic')}",{path:path});
		$(obj).closest('.upload_pic_li').remove();
	}
</script>




<include file="Public:footer"/>
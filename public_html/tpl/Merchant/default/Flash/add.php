<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('Flash/index')}">微网站</a>
			</li>
			<li class="active">添加<if condition="$tip eq 1">幻灯片<else />背景图</if></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="post" action="{pigcms{:U('Flash/insert')}">
								<input type="hidden" name="tip" value="{pigcms{$tip}" />
								<input type="hidden" name="id" value="{pigcms{$info.id}" />
								<if condition="$tip eq 1">
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_info">幻灯片描述</label></label>
									<input type="text" class="col-sm-3" name="info" value="{pigcms{$info.info}" />
									<span class="form_tips">30个字简短分类描述，可为空</span>
								</div>
								</if>
								<div class="form-group" style="margin-bottom:-35px;">
									<label class="col-sm-3"><label for="AutoreplySystem_img"><if condition="$tip eq 1">幻灯片<else />背景图</if>图片</label></label>
								</div>
								<div class="form-group" style="width:417px;padding-left:140px;">
									<label class="ace-file-input">
										<input class="col-sm-4" id="ace-file-input" size="50" onchange="preview1(this)" name="img" type="file">
										<span class="ace-file-container" data-title="选择">
											<span class="ace-file-name" data-title="上传图片..."><i class=" ace-icon fa fa-upload"></i></span>
										</span>
									</label>
									</div>
									<div class="form-group">
										<label class="col-sm-1">选择图片</label>
										<a href="#modal-table" class="btn btn-sm btn-success" onclick="selectImg('flash_preview')">选择图片</a>
									</div>
									<div id="flash_preview"><img style="width:417px;height:200px;display:none;" id="img" src="{pigcms{$info.img}"/></div>
								</div>
								<if condition="$tip eq 1">
								<div class="form-group">
									<label class="col-sm-1"><label for="contant_url">幻灯片链接地址  </label></label>
									<input type="text" class="col-sm-3" name="url" value="{pigcms{$info.url}" id="url"/>
									&nbsp;&nbsp;&nbsp;&nbsp;<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url',0)" data-toggle="modal">从功能库选择</a>
								</div>
								</if>
								
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
	</div>
</div>

<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>

<script type="text/javascript">
function preview1(input){
	if (input.files && input.files[0]){
		var reader = new FileReader();
		reader.onload = function (e) { $('#img').attr('src', e.target.result).show();}
		reader.readAsDataURL(input.files[0]);
	}
}

function viewTpl(){
	var tid = $('#tpid').val();
	chooseTpl(tid,'',2);
}

function viewTpl2(){
	var tid = $('#conttpid').val();
	chooseTpl(tid,'',4);
}
</script>
<include file="Public:footer"/>
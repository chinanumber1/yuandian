<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('index')}">景区设置</a>
			</li>
			<li class="active">基础设置</li>
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
								<a data-toggle="tab" href="#basicinfo">基本设置</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtstore">景区描述</a>
							</li>
							<li>
								<a data-toggle="tab" href="#txtis">辅助开启</a>
							</li>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<input type="hidden" name="scenic_id" value="{pigcms{$now_merchant.scenic_id}"/>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label>景区名称</label></label>
									<if condition="!$now_merchant['scenic_title']">
										<input class="col-sm-2" size="20" name="scenic_title" id="scenic_title" value="" type="text"/>
									<else/>
										<input class="col-sm-2" size="20" name="scenic_title" id="scenic_title" value="{pigcms{$now_merchant.scenic_title}" type="text" style="border:none;background:white!important;" readonly="readonly"/>
									</if>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>管理员</label></label>
									<input class="col-sm-2" size="20" name="scenic_name" id="scenic_name" value="{pigcms{$now_merchant.scenic_name}" type="text"/>
									<span class="form_tips">必填！</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="scenic_phone">电话</label></label>
									<input class="col-sm-2" size="20" name="scenic_phone" id="scenic_phone" value="{pigcms{$now_merchant.scenic_phone}" type="text"/>
									<span class="form_tips">多个电话号码以空格分开</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>店铺所在地</label></label>
									<fieldset id="choose_cityarea" province_id="{pigcms{$now_merchant.province_id}" city_id="{pigcms{$now_merchant.city_id}" area_id="{pigcms{$now_merchant.area_id}"></fieldset>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="scenic_address">地址</label></label>
									<input class="col-sm-2" size="20" name="scenic_address" id="scenic_address" value="{pigcms{$now_merchant.scenic_address}" type="text"/>
									<span class="form_tips">请填写景区的详细地址</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="money">景区消费</label></label>
									<input class="col-sm-2" size="20" name="money" id="money" value="{pigcms{$now_merchant.money}" type="text"/>
									<span class="form_tips">景区大约消费金额，0代表免费</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="money">建议游玩时间</label></label>
									<input class="col-sm-2" size="20" name="sugest_time" id="sugest_time" value="{pigcms{$now_merchant.sugest_time}" type="number"/>
									<span class="form_tips">建议游玩的时间，填写小时</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="long_lat">店铺经纬度</label></label>
									<input class="col-sm-2" size="10" name="long_lat" id="long_lat" <if condition="$now_merchant['long'] neq 0">value="{pigcms{$now_merchant.long},{pigcms{$now_merchant.lat}"</if> type="text" readonly="readonly"/>
									&nbsp;&nbsp;&nbsp;&nbsp;<a href="#modal-table" class="btn btn-sm btn-success" id="show_map_frame" data-toggle="modal">点击选取经纬度</a>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="long_lat">开园时间</label></label>
									<input class="col-sm-1 Wdate" name="start_time" type="time" value="{pigcms{$now_merchant['start_time']}"/>
									<span class="form_tips">几点可以进入景区，方便有夜场的景区</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="long_lat">闭园时间</label></label>
									<input class="col-sm-1 Wdate" name="end_time" type="time" value="{pigcms{$now_merchant['end_time']}"/>
									<span class="form_tips">几点必须离开景区，方便有夜场的景区</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="level">景区等级</label></label>
									<input class="col-sm-2" size="20" name="level" id="level" value="{pigcms{$now_merchant.level}" type="text"/>
									<span class="form_tips">请填写景区的星级(5星景区，请填写5)</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>点击量</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$now_merchant.hits}" type="text" style="border:none;background:white!important;" readonly="readonly"/>
								</div>
							</div>
							<div id="txtstore" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">景区介绍</label>
									<input class="col-sm-5" size="20" name="scenic_brief" id="scenic_brief" value="{pigcms{$now_merchant.scenic_brief}" type="text"/>
									<span class="form_tips">用一句话介绍你的景区，50字以内</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">景区描述</label>
									<textarea id="scenic_intr" name="scenic_intr"  placeholder="写上一些想要发布的内容">{pigcms{$now_merchant['scenic_intr']|htmlspecialchars_decode=ENT_QUOTES}</textarea>
									<span class="form_tips">详情的介绍你的景区，包括景点，游玩，项目等...</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">门票说明</label>
									<textarea id="scenic_explain" name="scenic_explain"  placeholder="写上一些想要发布的内容">{pigcms{$now_merchant['scenic_explain']|htmlspecialchars_decode=ENT_QUOTES}</textarea>
									<span class="form_tips">门票预定说明，比如优惠政策</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">景区图片</label>
									<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectImage">上传图片</a>
									<span class="form_tips">第1张将作为主图片！最多上传10张图片！图片宽度建议为640px，高度建议为230px。</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">图片预览</label>
									<div id="upload_pic_box">
										<ul id="upload_pic_ul">
											<volist name="now_merchant['pic']" id="vo">
												<li class="upload_pic_li"><img src="{pigcms{$vo.url}"/><input type="hidden" name="pic[]" value="{pigcms{$vo.title}"/><br/><a href="#" onclick="deleteImage('{pigcms{$vo.title}',this);return false;">[ 删除 ]</a></li>
											</volist>
										</ul>
									</div>
								</div>
							</div>
							<div id="txtis" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1"><label for="parking_price">停车位费用</label></label>
									<input class="col-sm-1" size="20" name="parking_price" id="parking_price" value="{pigcms{$now_merchant.parking_price}" type="text"/>
									<span class="form_tips">停车位费用，0代表免费</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="guide_price">向导费用</label></label>
									<input class="col-sm-1" size="20" name="guide_price" id="guide_price" value="{pigcms{$now_merchant.guide_price}" type="text"/>
									<span class="form_tips">景内向导费用，0代表免费</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="is_parking">停车场</label>
									<select name="is_parking" id="is_parking">
										<option value="1" <if condition="$now_merchant['is_parking'] eq 1">selected="selected"</if>>开启</option>
										<option value="2" <if condition="$now_merchant['is_parking'] eq 2">selected="selected"</if>>关闭</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1" for="is_guide">景内向导</label>
									<select name="is_guide" id="is_guide">
										<option value="1" <if condition="$now_merchant['is_guide'] eq 1">selected="selected"</if>>开启</option>
										<option value="2" <if condition="$now_merchant['is_guide'] eq 2">selected="selected"</if>>关闭</option>
									</select>
								</div>
								<!--<div class="form-group">
									<label class="col-sm-1" for="is_broadcast">景内播报</label>
									<select name="is_broadcast" id="is_broadcast">
										<option value="1" <if condition="$now_merchant['is_broadcast'] eq 1">selected="selected"</if>>开启</option>
										<option value="2" <if condition="$now_merchant['is_broadcast'] eq 2">selected="selected"</if>>关闭</option>
									</select>
								</div>-->
								<div class="form-group">
									<label class="col-sm-1" for="scenic_status">状态</label>
									<select name="scenic_status" id="scenic_status">
										<option value="1" <if condition="$now_merchant['scenic_status'] eq 1">selected="selected"</if>>开启</option>
										<option value="2" <if condition="$now_merchant['scenic_status'] eq 2">selected="selected"</if>>关闭</option>
									</select>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>评论总分</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$now_merchant.score_all}" type="text" style="border:none;background:white!important;" readonly="readonly"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>评论人数</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$now_merchant.reply_count}" type="text" style="border:none;background:white!important;" readonly="readonly"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>评论平均分</label></label>
									<input class="col-sm-2" size="20" value="{pigcms{$now_merchant.score_mean}" type="text" style="border:none;background:white!important;" readonly="readonly"/>
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
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- PAGE CONTENT ENDS -->
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
var choose_province="{pigcms{:U('Scenic_area/ajax_province')}",choose_city="{pigcms{:U('Scenic_area/ajax_city')}",choose_area="{pigcms{:U('Scenic_area/ajax_area')}";
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/area.js"></script>
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
	kind_editor = K.create("#scenic_intr",{
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
	kind_editor = K.create("#scenic_explain",{
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
		$.post("{pigcms{:U('index')}",$('#edit_form').serialize(),function(result){
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
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<include file="Public:footer"/>

<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('Activity/index')}">功能库</a>
			</li>
			<li class="active">修改活动</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
				#time_error{ height:27px; line-height:27px; color:red}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post" onSubmit="return check_submit()" action="__SELF__">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="title">活动名称</label></label>
									<input class="col-sm-2" size="20" name="title" id="title" type="text" value="{pigcms{$detail['title']}"/>
								</div>
								
								
								<div id="txtimage" class="tab-pane">
								<div class="form-group">
									<label class="col-sm-1">上传图片</label>
									<a href="javascript:void(0)" class="btn btn-sm btn-success" id="J_selectImage">上传图片</a>
									图片宽度建议为：640px，高度建议为：238px 。最多上传5张
								</div>
								<div class="form-group">
									<label class="col-sm-1">图片预览</label>
									<div id="upload_pic_box">
										<volist name="detail['pic']" id="pic">
											<li class="upload_pic_li">
												<img src="/upload/activity/{pigcms{$pic}">
												<input type="hidden" value="{pigcms{$pic}" name="pic[]"><br>
												<a onclick="deleteImg('{pigcms{$pic}',this);return false;" href="#">[ 删除 ]</a>
											</li>
										</volist>
									</div>
								</div>
							</div>
								
								
								<div class="form-group">
									<label class="col-sm-1"><label for="content">发布内容</label></label>
									<textarea id="content" name="content"  placeholder="写上一些想要发布的内容">{pigcms{$detail['content']}</textarea> 
								</div>
								
								<div class="form-group">
									<label class="col-sm-1">活动时间：</label>
									<input class="col-sm-2 Wdate" type="text" readonly style="height:30px;" onfocus="WdatePicker({minDate:'{pigcms{:date('Y年m月d日 ',$_SERVER['REQUEST_TIME'])}',isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日',startDate:'{pigcms{:date('Y-m-d ',$detail['activity_start_time'])}',vel:'activity_start_time'})" value="{pigcms{:date('Y年m月d日',$detail['activity_start_time'])}"/>
									<input name="activity_start_time" id="activity_start_time" type="hidden" value="{pigcms{:date('Y-m-d',$detail['activity_start_time'])}"/>
									<label class="col-sm-1" style=" text-align:center; width:40px">至</label>
									<input class="col-sm-2 Wdate" type="text" readonly style="height:30px;" onfocus="WdatePicker({minDate:'{pigcms{:date('Y年m月d日 ',$_SERVER['REQUEST_TIME'])}',isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日',startDate:'{pigcms{:date('Y-m-d',$detail['activity_end_time'])}',vel:'activity_end_time'})" value="{pigcms{:date('Y年m月d日',$detail['activity_end_time'])}"/>
									<input name="activity_end_time" id="activity_end_time" type="hidden" value="{pigcms{:date('Y-m-d',$detail['activity_end_time'])}"/>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1">报名截止时间：</label>
									<input class="col-sm-2 Wdate" type="text" readonly style="height:30px;" onfocus="WdatePicker({minDate:'{pigcms{:date('Y年m月d日 ',$_SERVER['REQUEST_TIME'])}',isShowClear:false,readOnly:true,dateFmt:'yyyy年MM月dd日',startDate:'{pigcms{:date('Y-m-d',$detail['apply_end_time'])}',vel:'stop_apply_time'})" value="{pigcms{:date('Y年m月d日',$detail['apply_end_time'])}"/>
									<input name="stop_apply_time" id="stop_apply_time" type="hidden" value="{pigcms{:date('Y-m-d',$detail['apply_end_time'])}"/>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="apply_limit_num">活动报名人数</label></label>
									<input class="col-sm-2" size="20" name="apply_limit_num" id="apply_limit_num" type="text" value="{pigcms{$detail['apply_limit_num']}" disabled="disabled" />
									<span class="form_tips">0 为不限制</span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">排序</label></label>
									<input class="col-sm-2" size="20" name="sort" id="sort" type="text" value="{pigcms{$detail['sort']}" />
									<span class="form_tips">值越大，越靠前显示。</span>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="apply_fee">报名费用</label></label>
									<input class="col-sm-2" size="20" name="apply_fee" id="apply_fee" type="text" value="{pigcms{$detail['apply_fee']}" />
								</div>

                                <div class="form-group">
									<label class="col-sm-1">活动状态</label>
										<label style="padding-left:0px;padding-right:20px;"><input type="radio" <if condition='$detail["status"] eq 1'>checked="checked"</if> class="ace" value="1" name="status"><span style="z-index: 1" class="lbl">开启</span></label>
										<label style="padding-left:0px;"><input type="radio" class="ace" <if condition='$detail["status"] eq 0'>checked="checked"</if> value="0" name="status"><span style="z-index: 1" class="lbl">关闭</span></label>
								</div>

                                <div class="form-group">
									<label class="col-sm-1">是否可以重复报名</label>
										<label style="padding-left:0px;padding-right:20px;"><input type="radio" <if condition='$detail["is_repeat_join"] eq 1'>checked="checked"</if> class="ace" value="1" name="is_repeat_join"><span style="z-index: 1" class="lbl">是</span></label>
										<label style="padding-left:0px;"><input type="radio" class="ace" <if condition='$detail["is_repeat_join"] eq 0'>checked="checked"</if> value="0" name="is_repeat_join"><span style="z-index: 1" class="lbl">否</span></label>
								</div>
							</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit" <if condition="!in_array(234,$house_session['menus'])">disabled="disabled"</if>>
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

<!--<div id="modal-table" class="modal fade" tabindex="-1" style="display:block;">
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
</div>-->
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
<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/area.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/service_map.js"></script>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/date/WdatePicker.js"></script> 
<script type="text/javascript">
function check_submit(){
	if($("#title").val()==''){
		alert('名称不能为空!');
		return false;		
	}
	
	if($("input[name='pic[]']").length<=0){
		alert('至少上传一张图片！');
		return false;	
	}
	
	if($(document.getElementsByTagName('iframe')[0].contentWindow.document.body).html()==""){
		alert('发布内容不能为空！');
		return false;
	}
	
	if($("#activity_start_time").val() < $("#stop_apply_time").val()){
		alert('活动开始时间不能小于报名截止时间');
		return false;	
	}
	
}

KindEditor.ready(function(K){
		kind_editor = K.create("#content",{
			width:'400px',
			height:'400px',
			resizeType : 1,
			allowPreviewEmoticons:false,
			allowImageUpload : true,
			filterMode: true,
			items : [
				'source', '|', 'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
				'removeformat', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
				'insertunorderedlist', '|', 'emoticons', 'image', 'link'
			],
			emoticonsPath : './static/emoticons/',
			uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=merchant/activity"
		});
			
			
	K('#J_selectImage').click(function(){
		if($('.upload_pic_li').size() >= 5){
			alert('最多上传5个图片！');
			return false;
		}
		kind_editor.uploadJson = "{pigcms{:U('ajax_upload_pic')}";
		kind_editor.loadPlugin('image', function(){
			kind_editor.plugin.imageDialog({
				showRemote : false,
				imageUrl : K('#course_pic').val(),
				clickFn : function(url, title, width, height, border, align) {
					$('#upload_pic_box').append('<li class="upload_pic_li"><img src="'+url+'"/><input type="hidden" name="pic[]" value="'+title+'"/><br/><a href="#" onclick="deleteImg(\''+title+'\',this);return false;">[ 删除 ]</a></li>');
					kind_editor.hideDialog();
				}
			});
		});
	});
});
		
		
		
		
		
		function check_submit(){
			if($('#title').val()==''){
				alert('活动名称不能为空！');
				return false;
			}
			if($('#content').val()==''){
				alert('内容不能为空！');
				return false;
			}
		}
		
$('.Wdate').blur(function(){
	var activity_start_time = $('.Wdate:eq(0)').val();
	var apply_end_time = $('.Wdate:eq(3)').val();
	var url = "{pigcms{:U('ajax_activity_time')}";
	$.post(url,{'apply_end_time':apply_end_time,'activity_start_time':activity_start_time},function(data){
		if(data.status==0){
			$('#time_error').show().html(data.msg);
		}else{
			$('#time_error').hide();
		}
	},'json')
});	


function deleteImg(path,obj){
	$.post("{pigcms{:U('ajax_del_pic')}",{path:path});
	$(obj).closest('.upload_pic_li').remove();
}
</script>

<include file="Public:footer"/>
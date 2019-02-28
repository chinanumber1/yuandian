<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tablet"></i>
				<a href="{pigcms{:U('Activity/apply_list')}">功能库</a>
			</li>
			<li class="active">编辑报名人员信息</li>
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
									<label class="col-sm-1"><label for="name">报名姓名</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" type="text" value="{pigcms{$detail['name']}"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="phone">报名手机号</label></label>
									<input class="col-sm-2" size="20" name="phone" id="phone" type="text" value="{pigcms{$detail['phone']}"/>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="activity_name">所属活动</label></label>
									<a target="_blank" href="{pigcms{$detail['url']}" style=" height:37px; line-height:37px">{pigcms{$detail['activity_name']}</a>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="apply_num">报名人数</label></label>
									<input class="col-sm-2" size="20" name="apply_num" id="apply_num" type="text" value="{pigcms{$detail['apply_num']}"/>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1"><label for="apply_num">备注</label></label>
									<textarea style=" width:280px; height:100px">{pigcms{$detail['memo']}</textarea>
								</div>
								
								<div class="form-group">
									<label class="col-sm-1">报名状态</label>
										<label style="padding-left:0px;padding-right:20px;"><input type="radio" <if condition='$detail["apply_status"] eq 1'>checked="checked"</if> class="ace" value="1" name="apply_status"><span style="z-index: 1" class="lbl">开启</span></label>
										<label style="padding-left:0px;"><input type="radio" class="ace" <if condition='$detail["apply_status"] eq 0'>checked="checked"</if> value="0" name="apply_status"><span style="z-index: 1" class="lbl">禁止</span></label>
								</div>
							</div>
						</div>
						<div class="space"></div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="submit" <if condition="!in_array(237,$house_session['menus'])">disabled="disabled"</if>>
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

<div id="modal-table" class="modal fade" tabindex="-1" style="display:block;">
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
				uploadJson : "{pigcms{$config.site_url}/index.php?g=Index&c=Upload&a=editor_ajax_upload&upload_dir=merchant/news"
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
</script>

<include file="Public:footer"/>
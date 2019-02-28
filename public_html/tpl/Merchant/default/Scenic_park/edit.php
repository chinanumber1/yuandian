<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('index')}">功能库</a>
			</li>
			<li class="active">修改停车场</li>
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
								<a data-toggle="tab" href="#basicinfo">修改停车场</a>
							</li>
						</ul>
					</div>
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<input type="hidden" name="parking_id" value="{pigcms{$now_order.parking_id}"/>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label>停车场名称</label></label>
									<input type="text" name="parking_name" value="{pigcms{$now_order.parking_name}" />
									<span class="form_tips">必填。在订单页显示此名称！</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">停车场地址</label>
									<input type="text" name="parking_address" value="{pigcms{$now_order.parking_address}" />
									<span class="form_tips">必填。方便用户找到！</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="long_lat">经纬度</label></label>
									<input class="col-sm-2" size="10" name="long_lat" id="long_lat" value="{pigcms{$now_order.parking_long},{pigcms{$now_order.parking_lat}" type="text" readonly="readonly"/>
									&nbsp;&nbsp;&nbsp;&nbsp;<a href="#modal-table" class="btn btn-sm btn-success" id="show_map_frame" data-toggle="modal">点击选取经纬度</a>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>车位数量</label></label>
									<input type="number" name="parking_count" value="{pigcms{$now_order.parking_count}" />
									<span class="form_tips">必填。能停多少辆车！</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1">状态</label>
									<select name="parking_status">
										<option value="1" <if condition="$now_order['parking_status'] eq 1">selected="selected"</if>>开启</option>
										<option value="2" <if condition="$now_order['parking_status'] eq 2">selected="selected"</if>>关闭</option>
									</select>
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
<script type="text/javascript" src="https://api.map.baidu.com/api?ak=4c1bb2055e24296bbaef36574877b4e2&v=2.0&s=1" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/map.js"></script>
<script type="text/javascript">
KindEditor.ready(function(K){
	$('#edit_form').submit(function(){
		$('#edit_form button[type="submit"]').prop('disabled',true).html('保存中...');
		$.post("{pigcms{:U('edit')}",$('#edit_form').serialize(),function(result){
			if(result.status == 1){
				alert(result.info);
				window.location.href = "{pigcms{:U('index')}";
			}else{
				$('#edit_form button[type="submit"]').prop('disabled',false).html('<i class="ace-icon fa fa-check bigger-110"></i>保存');
				alert(result.info);
			}
		})
		return false;
	});
});
</script>
<include file="Public:footer"/>
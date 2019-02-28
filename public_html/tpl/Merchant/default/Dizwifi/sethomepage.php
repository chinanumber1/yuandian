<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-cloud"></i>
			<li class="active">微硬件</li>
			<li class="active"><a href="{pigcms{:U('Dizwifi/index')}">微信链接WIFI</a></li>
			<li class="active">设置商家主页</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" id="edit_form">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1" for="shop_id">门店名称</label>
									<input type="hidden" name="shop_id" value="{pigcms{$shop_id}" />
									<input class="col-sm-2" size="20" value="{pigcms{$shop_name}" type="text" style="border:none;background:white!important;" readonly="readonly"/>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label>模板类型</label></label>
									<label><span><label><input name="template_id" value="0" type="radio" <if condition="$set.template_id eq '0' or $set.template_id eq ''">checked</if>></label>&nbsp;<span>默认模板</span>&nbsp;</span></label>
									<label><span><label><input name="template_id" value="1" type="radio" <if condition="$set.template_id eq 1">checked</if>></label>&nbsp;<span> 自定义模板</span></span></label>
								</div>
								<div class="form-group" id="seturl">
									<label class="col-sm-1"><label for="password">自定义链接</label></label>
									<input class="col-sm-2" size="20" name="url" value="{pigcms{$set.url}" id="url" type="text"/>
									&nbsp;&nbsp;&nbsp;&nbsp;<a href="#modal-table" class="btn btn-sm btn-success" onclick="addLink('url',0)" data-toggle="modal">从功能库选择</a>
									<span class="form_tips red">(设置连接成功后或点击顶部文案后的显示模板)</span>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="bssid">顶部文案显示</label></label>
									<label><span><label><input name="bar_type" value="0" type="radio" <if condition="$set.bar_type eq '0' or $set.bar_type eq ''">checked</if>></label>&nbsp;<span>公众号名称</span>&nbsp;</span></label>
									<label><span><label><input name="bar_type" value="1" type="radio" <if condition="$set.bar_type eq 1">checked</if>></label>&nbsp;<span>门店名称</span></span></label>
									<span class="form_tips red">(设置顶部是显示公众号名称还是门店名称)</span>
								</div>
							</div>
							<div class="clearfix form-actions">
								<div class="col-md-offset-3 col-md-9">
									<button class="btn btn-info" type="button">
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

<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script type="text/javascript" src="./static/js/upyun.js"></script>
<script type="text/javascript">
//简单的表单验证
$(function(){
	var template_id = $(":input[name=template_id]:checked").val();
	if(template_id == 1){
		$("#seturl").show();
	} else {
		$("#seturl").hide();
	}
	$("input[name=template_id]").click(function(){
		if($(this).val() == 1){
			$("#seturl").show();
		}else{
			$("#seturl").hide();
		}
	})
	$(".btn-info").click(function(){
		var template_id = $("input[name=template_id]:checked").val();
		var url = $("input[name=url]").val();
		if(template_id == 1 && url == ''){
			alert('自定义链接不能为空');
			return false;
		}
		$("#edit_form").submit();
	});
});
</script>
<include file="Public:footer"/>

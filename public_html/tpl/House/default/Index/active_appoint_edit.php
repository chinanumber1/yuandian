<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-empire"></i>
				<a href="{pigcms{:U('Index/active_appoint_list')}">推荐预约管理</a>
			</li>
			<li class="active">编辑预约</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form enctype="multipart/form-data" class="form-horizontal" method="post" action="{pigcms{:U('Index/active_appoint_edit',array('id'=>$now_acitive['pigcms_id']))}" onsubmit="return doSub();">
						<input type="hidden" name="appoint_id" id="group_id"/>
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="title">预约网址</label></label>
									<input class="col-sm-2" size="80" name="group_url" id="group_url" type="text" value="{pigcms{$config.site_url}/appoint/{pigcms{$now_acitive.appoint_id}.html"/>&nbsp;&nbsp;
									<button style="width:50px;height:35px" onclick="return checkGroup();">检测</button>
									<span style='color:red;display:none;' class='js-show-red'>暂无匹配的预约信息</span>
								</div>
								<div class="form-group js-show-red-no">
									<label class="col-sm-1"><label for="title">预约名称</label></label>
									<input class="col-sm-2" size="20" type="text" style="border:none;background:white!important;" readonly="readonly" id="group_name"/>
								</div>
								<div class="form-group js-show-red-no">
									<label class="col-sm-1"><label for="title">所属商家</label></label>
									<input class="col-sm-2" size="20" type="text" style="border:none;background:white!important;" readonly="readonly" id="meal_name"/>
								</div>
								<div class="form-group js-show-red-no">
									<label class="col-sm-1"><label for="title">首页别名</label></label>
									<input name="label" class="col-sm-2" size="20" type="text" id="label" value="{pigcms{$now_acitive.label}"/>
									<span class="form_tips">填写首页别名和上传首页图片，将在手机端社区首页“社区服务”中显示，例如 上门开锁等</span>
								</div>
								<div class="form-group js-show-red-no" style="margin-bottom:-35px;">
									<label class="col-sm-3"><label for="AutoreplySystem_img">首页图片</label></label>
								</div>
								<div class="form-group js-show-red-no" style="width:417px;padding-left:140px;">
									<label class="ace-file-input">
										<input class="col-sm-4" id="ace-file-input" size="50" onchange="preview1(this)" name="img" type="file"/>
										<span class="ace-file-container" data-title="选择">
											<span class="ace-file-name" data-title="上传图片..."><i class=" ace-icon fa fa-upload"></i></span>
										</span>
									</label>
									<div id="imgBox" <if condition="!$now_acitive['pic']">style="display:none;"</if>><img style="width:100px;height:100px" id="img" src="{pigcms{$config.site_url}/upload/house/appoint/{pigcms{$now_acitive.pic}"/></div>
								</div>
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">排序</label></label>
									<input class="col-sm-2" size="80" name="sort" id="sort" type="text" value="0"/>
								</div>
								<div class="space"></div><div class="space"></div><div class="space"></div>
								<div class="clearfix form-actions">
									<div class="col-md-offset-3 col-md-9">
										<button class="btn btn-info" type="submit" <if condition="!in_array(148,$house_session['menus'])">disabled="disabled"</if>>
											<i class="ace-icon fa fa-check bigger-110"></i>
											保存
										</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
.ke-dialog-body .ke-input-text{height: 30px;}
</style>
<<script type="text/javascript">
$(function(){
	$('.js-show-red').html('暂无匹配的预约信息');
	checkGroup()
	hide();
})
function preview1(input){
	if (input.files && input.files[0]){
		var reader = new FileReader();
		reader.onload = function (e) { $('#img').attr('src', e.target.result);$('#imgBox').show();}
		reader.readAsDataURL(input.files[0]);
	}
}

function checkGroup(){
	var check_url = $('#group_url').val();
	if(check_url.length <1){
		alert('请输入预约地址，才能做匹配');
	}
	 if(check_url.indexOf('wap.php')>0){
		var a = parseURL(check_url);
		var group_id = a.params.appoint_id;
	}else{
		var spstr = check_url.split("/");
		var subhtml = spstr[spstr.length-1].split(".");
		var group_id = subhtml[subhtml.length-2];
	}
	// var spstr = check_url.split("/");
	// var subhtml = spstr[spstr.length-1].split(".");
	// var group_id = subhtml[subhtml.length-2];
	
	if(!group_id){
		showRed();
	}
	$('#group_id').val(group_id);
	$.post("{pigcms{:U("Index/check_appoint",array('id'=>$now_acitive['pigcms_id']))}", {appoint_id:group_id}, function(result){
		
		if(result.error == 0){
			$('.js-show-red').html('暂无匹配的预约信息');
			hideRed();
			$('#group_name').val(result.appoint_name);
			$('#meal_name').val(result.merchant_name);
		}else if(result.error == 1){
			$('#group_name').val('');
			$('#meal_name').val('');
			$('.js-show-red').html(result.msg);
			showRed();
		}else{
			alert('保存失败，请稍后重试');
		}
		
		return false;
	});
	return false;
}
function showRed(){
	$('.js-show-red').show();
	$('.js-show-red-no').hide();
}
function hideRed(){
	$('.js-show-red').hide();
	$('.js-show-red-no').show();
}
function hide(){
	$('.js-show-red').hide();
	$('.js-show-red-no').hide();
}
function doSub(){
	var groupId = $('#group_id').val();
	var sort = $('#sort').val();
	if(!groupId){
		alert('检测通过之后才能保存哦');
		return false;
	}
	if($.trim($('#ace-file-input').val()) != '' && $.trim($('#label').val()) == ''){
		alert('您填写了别名，请上传图片');
		return false;
	}
}
</script>
<include file="Public:footer"/>
<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-shopping-cart gear-icon"></i>
				功能库
			</li>
			<li><a href="{pigcms{:U('Openphone/phone')}">常用电话</a></li>
			<li><a class="active" href="{pigcms{:U('Openphone/index')}">分类列表</a></li>
			<li class="active">添加分类</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form class="form-horizontal" method="post" onSubmit="return check_submit();" action="__SELF__">
						<div class="tab-content">
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="cat_name">分类名称：</label></label>
									<input class="col-sm-2" size="20" name="cat_name" id="cat_name" type="text" value=""/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-1"><label for="cat_sort">分类排序：</label></label>
								<input type="text" id="cat_sort" name="cat_sort" size="20" class="col-sm-2" value="0"/>
								<label class="col-sm-3"><span class="red">*&nbsp;&nbsp;可不填写（排序值越大，越靠前显示）</span></label>
							</div>
							<div class="form-group">
								<label class="col-sm-1">状态</label>
								<label style="padding-left:0px;padding-right:20px;"><input name="cat_status" value="1" type="radio" class="ace" checked=""><span class="lbl" style="z-index: 1">开启</span></label>
								<label style="padding-left:0px;"><input name="cat_status" value="0" type="radio" class="ace"><span class="lbl" style="z-index: 1">关闭</span></label>
							</div>
							<div class="space"></div>
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
<script>
function check_submit(){
		if($('#cat_name').val()==''){
			alert('分类名称不能为空！');
			return false;
		}
	}
</script>




<include file="Public:footer"/>
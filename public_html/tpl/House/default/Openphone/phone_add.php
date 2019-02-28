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
			<!-- <li class="active"><a href="{pigcms{:U('Openphone/phone',array('cat_id'=>$now_cat['cat_id']))}">【{pigcms{$now_cat.cat_name}】电话列表</a></li> -->
			<li class="active">添加电话</li>
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
									<label class="col-sm-1"><label for="name">分类：</label></label>
									<select name="cat_id" id="cat_id" style="height:42px">
	                                    <option value="0">请选择</option>
	                                    <volist name="cate_list" id="vo">
	                                    <option value="{pigcms{$vo['cat_id']}">{pigcms{$vo['cat_name']}</option> 
	                                    </volist>
	                                </select>
								</div>
							</div>
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="name">名称：</label></label>
									<input class="col-sm-2" size="20" name="name" id="name" type="text" value=""/>
								</div>
							</div>
							<div id="basicinfo" class="tab-pane active">
								<div class="form-group">
									<label class="col-sm-1"><label for="phone">号码：</label></label>
									<input class="col-sm-2" size="20" name="phone" id="phone" type="text" value=""/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-1"><label for="sort">排序：</label></label>
								<input type="text" id="sort" name="sort" size="20" class="col-sm-2" value="0"/>
								<label class="col-sm-3"><span class="red">*&nbsp;&nbsp;可不填写（排序值越大，越靠前显示）</span></label>
							</div>
							<div class="form-group">
								<label class="col-sm-1">状态</label>
								<label style="padding-left:0px;padding-right:20px;"><input name="status" value="1" type="radio" class="ace" checked=""><span class="lbl" style="z-index: 1">开启</span></label>
								<label style="padding-left:0px;"><input name="status" value="0" type="radio" class="ace"><span class="lbl" style="z-index: 1">关闭</span></label>
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
		if($('#cat_id').val()=='0'){
			alert('请选择分类！');
			return false;
		}
		if($('#name').val()==''){
			alert('名称不能为空！');
			return false;
		}
	}
</script>




<include file="Public:footer"/>
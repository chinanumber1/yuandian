<include file="Public:header"/>
<div class="main-content">
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear"></i>
				<a href="{pigcms{:U('Index/worker')}">工作人员管理</a>
			</li>
			<li class="active">添加工作人员</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form  class="form-horizontal" method="post">
						<div class="tab-content">
							<div class="form-group">
								<label class="col-sm-1"><label for="name">姓名</label></label>
								<input class="col-sm-2" size="20" name="name" id="name" type="text" value=""/>
							</div>
							<div class="form-group">
								<label class="col-sm-1"><label for="phone">手机号码</label></label>
								<input class="col-sm-2" size="20" name="phone" id="phone" type="text" value=""/>
								<span class="col-sm-5" style="padding-top: 3px">此手机号需与注册时的手机号一致，才能开门成功。</span>
							</div>
                            <div class="form-group">
								<label class="col-sm-1">职务类型</label>
								<select name="type">
								<volist name="worker_name" id="vo">
									<option value="{pigcms{$key}">{pigcms{$vo}</option>
								</volist>
								</select>
						
								
								</label>
							</div> 
							<div class="form-group">
								<label class="col-sm-1">是否可以开门</label>
							
								<label style="padding-left:0px;padding-right:20px;">
									<input type="radio" checked="" class="ace" value="1" name="open_door">
									
									<span style="z-index: 1" class="lbl">可以</span>
									
								</label>
								<label style="padding-left:0px;padding-right:20px;">
									<input type="radio" checked="" class="ace" value="0" name="open_door">
									
									<span style="z-index: 1" class="lbl">不可以</span>
									
								</label>
								
								</label>
							</div>
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
<include file="Public:footer"/>
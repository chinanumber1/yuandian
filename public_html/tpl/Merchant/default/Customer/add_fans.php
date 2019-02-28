<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-comments-o comments-o-icon"></i>
			<li class="active">粉丝管理</li>
			<li><a href="{pigcms{:U('Customer/add_fans')}">添加粉丝</a></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tab-content">
						<div class="grid-view">
							<form  class="form-horizontal" method="post" action="{pigcms{:U('Customer/add_fans')}">
								<div class="form-group">
									<label class="col-sm-1"><label for="contact_name"><span class="required" style="color:red;">*</span>手机号码</label></label>
									<input type="text" class="col-sm-2" name="phone" value="" />
									<label>添加粉丝后，用户的平台余额中会增加相应的实体卡余额数；如果平台在添加实体卡时绑定了商户编号，则店员只能绑定该商家下的实体卡用户；反之，则店员不能添加粉丝</label>
								</div>
							
								
								
								
								<div class="form-group">
									<label class="col-sm-1"><label for="sort">选择卡号</label></label>
									<input type="text" id="openid" class="col-sm-2" value="{pigcms{$info.openid}" name="cardid" autocomplete="off" readonly />
									&nbsp;&nbsp;&nbsp;&nbsp;<a href="#modal-table" class="btn btn-sm btn-success" onclick="selectCard('openid')">选择卡号</a>
								</div>
								
								
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
<include file="Public:footer"/>
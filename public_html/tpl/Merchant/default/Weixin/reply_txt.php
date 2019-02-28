<include file="Public:header" />
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li><i class="ace-icon fa fa-wechat"></i> <a
				href="{pigcms{:U('Weixin/index')}">公众号设置</a></li>
			<li>关键词回复</li>
			<li class="active">文本回复</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
			.ace-file-input a {
				display: none;
			}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li class="active"><a href="{pigcms{:U('Weixin/txt')}"> 文字回复 </a></li>
							<li><a href="{pigcms{:U('Weixin/img')}">图文回复 </a></li>
							<!--li><a href="javascript:;">系统功能回复 </a></li-->
						</ul>
						<div class="tab-content">
							<div>
								<a href="{pigcms{:U('Weixin/txt')}" class="btn btn-success" style="margin-bottom: 20px;">返回列表</a>
							</div>
							<div class="form">
								<form class="well" id="food-form" action="" method="post">
									<p class="note">
										标有<span class="required" style="color: red;">*</span>的为必填选项
									</p>
									<div class="alert alert-danger" id="food-form_es_" <if condition="empty($error)">style="display:none"</if>><p>请更正下列输入错误:</p>
										<ul><li>{pigcms{$error}</li></ul>
									</div><br>
									<br> 关键词<span class="required" style="color: red;">*</span>：<br>
									<input class="span3" size="30" maxlength="30" name="keyword" id="keyword" type="text" value="{pigcms{$keyword['keyword']}"><br>
									<br> <input id="pigcms_id" type="hidden" value="{pigcms{$keyword['pigcms_id']}" name="pigcms_id">
									<br>
									<br>文字回复内容<span class="required" style="color: red;">*</span>:<br>
									<textarea style="width: 400px;" rows="8" maxlength="1000" id="content" name="content">{pigcms{$keyword['content']}</textarea>
									<span class="emotion"></span> <br>
									<div class="form-actions">
										<button class="btn btn-info" type="submit"><i class="ace-icon fa fa-check bigger-110"></i> 提交</button>
									</div>
								</form>
							</div>
							<!-- form -->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<include file="Public:footer" />
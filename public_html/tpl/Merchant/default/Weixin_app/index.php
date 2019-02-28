<include file="Public:header"/>

<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-wechat"></i>
				<a href="{pigcms{:U('Weixin_app/index')}">小程序设置</a>
			</li>
			<li class="active">小程序绑定</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<if condition="$go_url">
						<div id="shopList" class="grid-view">
							<div class="span12" style="margin-top:10px;">
								<div class="form-actions">
									<a class="btn btn-success cert-setting-btn js-wxauth-btn" target="_blank" data-url="{pigcms{$go_url}" href="{pigcms{$go_url}"><i class="ace-icon fa fa-wechat"></i>我有微信小程序，立即设置</a>
								</div>
							</div>
						</div>
					<else />
						<div id="shopList" class="grid-view">
							<div class="span12" style="margin-top:10px;">
								<div class="form-actions">
									<a class="btn btn-error cert-setting-btn">平台还没有开启网页授权，或管理员配置错误，请联系系统管理者！</a>
								</div>
							</div>
						</div>
					</if>
				</div>
			</div>
		</div>
	</div>
</div>

<if condition="empty($bind)">
<script type="text/javascript">
	$(document).ready(function(){
		$('.js-wxauth-btn').click(function(){
			var url = $(this).attr('data-url');
			var html = '';
			html += '<div class="modal fade in" style="width: 400px;height:165px;margin-top:-5px; " aria-hidden="false"><div class="modal-header">';
			html += '<a class="close" data-dismiss="modal">×</a>提示</div>';
			html += '<div class="modal-body">';
			html += '<p>请在新窗口中完成微信小程序授权&nbsp;&nbsp;<a href="" target="_blank"></a></p>';
			html += '</div>';
			html += '<div class="modal-footer">';
			html += '<div style="text-align: center;">';
			html += '<button type="button" class="btn btn-success js-refresh">已成功设置</button>';
			html += '<a class="btn btn-default js-retry" href="'+url+'" target="_blank" data-loading-text="地址读取中..">授权失败，重试</a>';
			html += '</div>';
			html += '</div></div>';
			$('body').append(html);
		});
		$('.close').live('click', function(){
			$(this).parents('.modal').remove();
			$('.modal-backdrop').remove();
		});
		$('.js-refresh').live('click', function(){
			location.reload();
		});
	});
</script>
</if>

<link rel="stylesheet" href="{pigcms{$static_path}css/base.css">
<style>
.form-actions {
background-color: #fff;
}
.modal{
display:block;
}
</style>
<include file="Public:footer"/>

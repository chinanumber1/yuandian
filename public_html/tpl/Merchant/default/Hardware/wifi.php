<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-cloud"></i>
			<li class="active">微硬件</li>
			<li class="active">微信WiFi路由器</li>
		</ul>
	</div>
	<div class="alert alert-info" style="margin:10px;">
		<button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button>
		微信WIFI路由器可用于以下场景：在门店用户使用微信关注过平台微信公众号后即可以上网。如果绑定了商家公众号，关注后也能上网。
		<br/>
		<br/>
		如果在绑定微店WIFI路由器之前用户已经关注过公众号，则用户需要对公众号回复“go”，则能自动上网。
		<br/>
		<br/>
		适用距离：开放环境200平方米
		<br/>
		<br/>
		具体方案请联系网站管理员。
	</div>
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="form-group">
					<label class="col-sm-1">WIFI二维码</label>
					<div style="padding-top:4px;line-height:24px;"><a href="{pigcms{$config.site_url}/index.php?g=Index&c=Recognition&a=see_qrcode&type=wifi&id={pigcms{$merchant_session.mer_id}&img=1" class="see_qrcode">查看二维码</a></div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	$(function(){
		$('#indexsort_edit_btn').click(function(){
			$(this).prop('disabled',true).html('提交中...');
			$.post("{pigcms{:U('Config/merchant_indexsort')}",{group_indexsort:$('#group_indexsort').val(),indexsort_groupid:$('#indexsort_groupid').val()},function(result){
				alert('处理完成！正在刷新页面。');
				window.location.href = window.location.href;
			});
		});
		$('.see_qrcode').click(function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看渠道二维码',
				padding: 0,
				width: 430,
				height: 433,
				lock: true,
				resize: false,
				background:'black',
				button: null,
				fixed: false,
				close: null,
				left: '50%',
				top: '38.2%',
				opacity:'0.4'
			});
			return false;
		});
	});
</script>
<include file="Public:footer"/>

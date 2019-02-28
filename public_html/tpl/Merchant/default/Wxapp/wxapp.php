<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-tasks"></i>&nbsp;营销活动
			</li>
			<li><a href="javascript:window.location.reload();">{pigcms{$accessName}</a></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<if condition="$_SERVER['REQUEST_SCHEME'] eq 'https'">
		<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
		<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
		<script>
			window.art.dialog({
				title: "HTTPS提示：",
				opacity:'0.4',
				lock: true,
				fixed: true,
				resize: false,
				padding:'20px',
				content: "您现在使用的是HTTPS，请点击确认将新窗口打开网站。",
				ok:function(){
					window.open("{pigcms{$accessUrl}");
				}
			});
			
		</script>
	<else/>
		<iframe src="{pigcms{$accessUrl}" style="width:100%;border:0;" id="accessUrl"></iframe>
	</if>
</div>
<script type="text/javascript">
	var winHeight = $(window).height()-$('#navbar').height()-$('#breadcrumbs').height()-8;
	var LeftHeight = $('#sidebar .nav-list').height() + $('#sidebar-collapse').height()+30;
	$('#accessUrl').height(winHeight > LeftHeight ? winHeight : LeftHeight);
</script>

<include file="Public:footer"/>
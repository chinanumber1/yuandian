<include file="Public:header"/>

		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="javascript:void(0);" class="on">营销活动</a>>>
					<a href="javascript:void(0);">{pigcms{$accessName}</a>
				</ul>
			</div>
			<if condition="$_SERVER['REQUEST_SCHEME'] eq 'https'">
				<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
				<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
				<script>
					window.art.dialog({
						title: "HTTPS提示：",
						opacity:'0.4',
						top:'200px',
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
			var winHeight = $(window.parent).height()-$('#navbar').height()-12;
			$('#accessUrl').height(winHeight);
		</script>

<include file="Public:footer"/>
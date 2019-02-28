	</body>
	<if condition="empty($_GET['frame'])">
		<script type="text/javascript">
			parent.showHelpText(parentShowHelpParam);
			parent.showHelpType(parentShowIndex,'{pigcms{:GROUP_NAME}','{pigcms{:MODULE_NAME}','{pigcms{:ACTION_NAME}');
			$(function(){
				parent.iframeRealHeight = $('body').height() + 40;
				parent.setMainHeight({iframeHeight:true});
				/* alert($(window.parent).scrollTop()); */
				/* parent.scrollTo(0,0); */
				// alert(parent.iframeRealHeight);
			});
		</script>
	</if>
</html>
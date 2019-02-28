<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo C('DEFAULT_CHARSET');?>" />
		<title>网站后台管理 Powered by pigcms.com</title>
		<script type="text/javascript">
			/*<?php if(!C('butt_open')): ?>if(self==top){window.top.location.href="<?php echo U('Index/index');?>";}
			<?php else: ?>
				if(self==top){window.top.location.href="<?php echo C('butt_system_url');?>";}<?php endif; ?>*/
			var kind_editor=null,static_public="<?php echo ($static_public); ?>",static_path="<?php echo ($static_path); ?>",system_index="<?php echo U('Index/index');?>",choose_province="<?php echo U('Area/ajax_province');?>",choose_city="<?php echo U('Area/ajax_city');?>",choose_area="<?php echo U('Area/ajax_area');?>",choose_circle="<?php echo U('Area/ajax_circle');?>",choose_market="<?php echo U('Area/ajax_market');?>",choose_map="<?php echo U('Map/frame_map');?>",get_firstword="<?php echo U('Words/get_firstword');?>",frame_show=<?php if($_GET['frame_show']): ?>true<?php else: ?>false<?php endif; ?>;
 var  meal_alias_name = "<?php echo ($config["meal_alias_name"]); ?>",parentShowHelpParam = [],parentShowIndex = false,choose_provincess="<?php echo U('Area/ajax_province');?>",choose_cityss="<?php echo U('Area/ajax_city');?>";
		</script>
		<link rel="stylesheet" type="text/css" href="<?php echo ($static_path); ?>css/style.css" />
		<script type="text/javascript" src="<?php echo C('JQUERY_FILE');?>"></script>
		<script type="text/javascript" src="<?php echo ($static_public); ?>js/jquery.form.js"></script>
		<script type="text/javascript" src="<?php echo ($static_public); ?>js/jquery.cookie.js"></script>
		<script type="text/javascript" src="<?php echo ($static_public); ?>js/jquery.validate.js"></script>
		<script type="text/javascript" src="<?php echo ($static_public); ?>js/date/WdatePicker.js"></script>
		<script type="text/javascript" src="<?php echo ($static_public); ?>js/jquery.colorpicker.js"></script>
		<script type="text/javascript" src="<?php echo ($static_public); ?>js/layer/layer.js"></script>
		<script type="text/javascript" src="<?php echo ($static_path); ?>js/common.js"></script>
		<script type="text/javascript" src="<?php echo ($static_path); ?>/js/area_adver.js"></script>
	</head>
	<body width="100%" <?php if($bg_color): ?>style="background:<?php echo ($bg_color); ?>;"<?php endif; ?>>
		<div class="mainbox">
			<?php if(empty($_GET['galias'])): ?><div id="nav" class="mainnav_title">
					<ul>
						<?php if(is_array($group_list)): $i = 0; $__LIST__ = $group_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Config/index',array('gid'=>$vo['gid']));?>" <?php if($gid == $vo['gid']): ?>class="on"<?php endif; ?>><?php echo ($vo["gname"]); ?></a>|<?php endforeach; endif; else: echo "" ;endif; ?>
					</ul>
				</div>
			<?php else: ?>
				<?php if($header_file): endif; endif; ?>
			<?php if($system_session['level'] == 2 && in_array($gid,array(7,8,22,34,43))): ?><div class="page_tips">
					<ol>
						<?php if($gid == 7): ?><li>1.微信支付 对接文档&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E5%BE%AE%E4%BF%A1%E6%94%AF%E4%BB%98.pdf" target="_blank">（PDF版）</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E5%BE%AE%E4%BF%A1%E6%94%AF%E4%BB%98.doc" target="_blank">（WORD版）</a></li>
							<li>2.支付宝H5 对接文档&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E6%94%AF%E4%BB%98%E5%AE%9DH5.pdf" target="_blank">（PDF版）</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E6%94%AF%E4%BB%98%E5%AE%9DH5.doc" target="_blank">（WORD版）</a></li>
						<?php elseif($gid == 43): ?>
							<li>1.支付宝H5 对接文档&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E6%94%AF%E4%BB%98%E5%AE%9DH5.pdf" target="_blank">（PDF版）</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E6%94%AF%E4%BB%98%E5%AE%9DH5.doc" target="_blank">（WORD版）</a></li>
						<?php elseif($gid == 8): ?>
							<li>1.微信公众号 对接文档&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E5%BE%AE%E4%BF%A1%E5%85%AC%E4%BC%97%E5%8F%B7%E5%AF%B9%E6%8E%A5.pdf" target="_blank">（PDF版）</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E5%BE%AE%E4%BF%A1%E5%85%AC%E4%BC%97%E5%8F%B7%E5%AF%B9%E6%8E%A5.doc" target="_blank">（WORD版）</a></li>
						<?php elseif($gid == 22): ?>
							<li>1.车牌识别配置 对接文档&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E8%BD%A6%E7%89%8C%E8%AF%86%E5%88%AB%E9%85%8D%E7%BD%AE.pdf" target="_blank">（PDF版）</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E8%BD%A6%E7%89%8C%E8%AF%86%E5%88%AB%E9%85%8D%E7%BD%AE.doc" target="_blank">（WORD版）</a></li>
						<?php elseif($gid == 34): ?>
							<li>1.支付宝H5 对接文档&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E6%94%AF%E4%BB%98%E5%AE%9DH5.pdf" target="_blank">（PDF版）</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="http://o2o-static.pigcms.com/help_doc/%E6%94%AF%E4%BB%98%E5%AE%9DH5.doc" target="_blank">（WORD版）</a></li><?php endif; ?>
					</ol>
				</div><?php endif; ?>
			<form id="myform" method="post" action="<?php echo U('Config/amend');?>" refresh="true">
				<?php echo ($config_tab_html); ?>
				<?php echo ($config_html); ?>
				<div class="btn" style="margin-top:20px;">
					<input TYPE="submit"  name="dosubmit" value="提交" class="button" />
					<input type="reset"  value="取消" class="button" />
					<?php if(empty($_GET['galias'])): ?><input type="button"  value="获取及时聊天的key" class="button" id="im_key"/>
						<input type="button"  value="微信API接口填写信息" class="button" onclick="window.top.artiframe('<?php echo U('Config/show',array('id'=>$vo['id']));?>','API接口信息',560,100,true,false,false,'','add',true);"/>
						<input type="button"  value="获取生活服务充值的key" class="button" id="live_service_key"/><?php endif; ?>
				</div>
			</form>
		</div>
		<script>
			$(function(){
				$('.table_form:eq(0)').show();
				
				$('.tab_ul li a').click(function(){
					$(this).closest('li').addClass('active').siblings('li').removeClass('active');
					$($(this).attr('href')).show().siblings('.table_form').hide();
					return false;
				});
				$('#im_key').click(function(){
					window.top.msg(2,'正在请求中,请稍等...',true,100);
					$.get("<?php echo U('Config/im');?>",function(data){
						if(data.error_code){
							window.top.msg(0,data.msg,true,3);
						}else{
							window.top.msg(1,data.msg,true,3);
						}
					},'json');
				});
				$('#live_service_key').click(function(){
					window.top.msg(2,'正在请求中,请稍等...',true,100);
					$.get("<?php echo U('Config/live_service');?>",function(data){
						if(data.error_code){
							window.top.msg(0,data.msg,true,3);
						}else{
							window.top.msg(1,data.msg,true,3);
						}
					},'json');
				});
			});
		</script>
		<link rel="stylesheet" href="<?php echo ($static_public); ?>kindeditor/themes/default/default.css">
		<script src="<?php echo ($static_public); ?>kindeditor/kindeditor.js"></script>
		<script src="<?php echo ($static_public); ?>kindeditor/lang/zh_CN.js"></script>
		<script type="text/javascript">
			KindEditor.ready(function(K){
				var site_url = "<?php echo ($config["site_url"]); ?>";
				var editor = K.editor({
					allowFileManager : true
				});
				$('.config_upload_image_btn').click(function(){
					var upload_file_btn = $(this);
					editor.uploadJson = "<?php echo U('Config/ajax_upload_pic');?>";
					editor.loadPlugin('image', function(){
						editor.plugin.imageDialog({
							showRemote : false,
							clickFn : function(url, title, width, height, border, align) {
								upload_file_btn.siblings('.input-image').val(site_url+url);
								editor.hideDialog();
							}
						});
					});
					setTimeout(function(){
						$('.ke-dialog').css('top',$('#Main_content',parent.document).scrollTop()+((screen.height-$('.ke-dialog').height())/2)+'px');
					},200);
				});
				$('.config_upload_file_btn').click(function(){
					var upload_file_btn = $(this);
					editor.uploadJson = "<?php echo U('Config/ajax_upload_file');?>&name="+upload_file_btn.siblings('.input-file').attr('name');
					editor.loadPlugin('insertfile', function(){
						editor.plugin.fileDialog({
							showRemote : false,
							clickFn : function(url, title, width, height, border, align) {
								upload_file_btn.siblings('.input-file').val(url);
								editor.hideDialog();
							}
						});
					});
					setTimeout(function(){
						$('.ke-dialog').css('top',$('#Main_content',parent.document).scrollTop()+((screen.height-$('.ke-dialog').height())/2)+'px');
					},200);
				});
				
				window.editor = K.create('#config_register_agreement',{pasteType : 1});
				window.editor = K.create('#config_store_register_agreement',{pasteType : 1});
			});
		</script>
		<style>
			.table_form{border:1px solid #ddd;}
			.tab_ul{margin-top:10px;border-color:#C5D0DC;margin-bottom:0!important;margin-left:0;position:relative;top:1px;border-bottom:1px solid #ddd;padding-left:0;list-style:none;}
			.tab_ul>li{position:relative;display:block;float:left;margin-bottom:-1px;}
			.tab_ul>li>a {
				position: relative;
				display: block;
				padding: 10px 15px;
				margin-right: 2px;
				line-height: 1.42857143;
				border: 1px solid transparent;
				border-radius: 4px 4px 0 0;
				padding: 7px 12px 8px;
				min-width: 100px;
				text-align: center;
			}
			.tab_ul>li>a, .tab_ul>li>a:focus {
				border-radius: 0!important;
				border-color: #c5d0dc;
				background-color: #F9F9F9;
				color: #999;
				margin-right: -1px;
				line-height: 18px;
				position: relative;
			}
			.tab_ul>li>a:focus, .tab_ul>li>a:hover {
				text-decoration: none;
				background-color: #eee;
			}
			.tab_ul>li>a:hover {
				border-color: #eee #eee #ddd;
			}
			.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #555;
				background-color: #fff;
				border: 1px solid #ddd;
				border-bottom-color: transparent;
				cursor: default;
			}
			.tab_ul>li>a:hover {
				background-color: #FFF;
				color: #4c8fbd;
				border-color: #c5d0dc;
			}
			.tab_ul>li:first-child>a {
				margin-left: 0;
			}
			.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #576373;
				border-color: #c5d0dc #c5d0dc transparent;
				border-top: 2px solid #4c8fbd;
				background-color: #FFF;
				z-index: 1;
				line-height: 18px;
				margin-top: -1px;
				box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
			}
			.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #555;
				background-color: #fff;
				border: 1px solid #ddd;
				border-bottom-color: transparent;
				cursor: default;
			}
			.tab_ul>li.active>a, .tab_ul>li.active>a:focus, .tab_ul>li.active>a:hover {
				color: #576373;
				border-color: #c5d0dc #c5d0dc transparent;
				border-top: 2px solid #4c8fbd;
				background-color: #FFF;
				z-index: 1;
				line-height: 18px;
				margin-top: -1px;
				box-shadow: 0 -2px 3px 0 rgba(0,0,0,.15);
			}
			.tab_ul:before,.tab_ul:after{
				content: " ";
				display: table;
			}
			.tab_ul:after{
				clear: both;
			}
		</style>
	</body>
	<?php if(empty($_GET['frame'])): ?><script type="text/javascript">
			parent.showHelpText(parentShowHelpParam);
			parent.showHelpType(parentShowIndex,'<?php echo GROUP_NAME;?>','<?php echo MODULE_NAME;?>','<?php echo ACTION_NAME;?>');
			$(function(){
				parent.iframeRealHeight = $('body').height() + 40;
				parent.setMainHeight({iframeHeight:true});
				/* alert($(window.parent).scrollTop()); */
				/* parent.scrollTo(0,0); */
				// alert(parent.iframeRealHeight);
			});
		</script><?php endif; ?>
</html>
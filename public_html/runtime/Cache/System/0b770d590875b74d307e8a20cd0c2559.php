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
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="<?php echo U('Home/group');?>" class="on"><?php echo ($config["group_alias_name"]); ?>回复回复</a>
				</ul>
			</div>
			<form method="post" action="" refresh="true" enctype="multipart/form-data" >
				<table cellpadding="0" cellspacing="0" class="table_form" width="100%">
					<tr>
						<th width="160">关键词　</th>
						<td><?php echo ($config["group_alias_name"]); ?>  —— 当用户输入该关键词时，将会触发此回复。</td>
					</tr>
					<tr>
						<th width="160">回复标题　</th>
						<td><input type="text" class="input-text" name="title" id="title" value="<?php echo ($info["title"]); ?>" /></td>
					</tr>
					<tr>
						<th width="160">内容介绍　</th>
						<td><textarea rows="4" cols="25" name="info" id="info"><?php echo ($info["info"]); ?></textarea></td>
					</tr>
					<tr>
						<th width="160">回复图片　</th>
						<td><input type="file" class="input-text" name="pic" id="pic" value="<?php echo ($info["pic"]); ?>"/></td>
					</tr>
					<?php if($info['pic']): ?><tr>
							<th width="160"></th>
							<td><img src="<?php echo ($info["pic"]); ?>" width="280" height="180"></td>
						</tr><?php endif; ?>
				</table>
				<div class="btn">
					<input type="submit"  name="dosubmit" value="提交" class="button" />
					<input type="reset"  value="取消" class="button" />
				</div>
			</form>
		</div>
		<style>
			.table_form{border:1px solid #ddd;}
			.tab_ul{margin-top:20px;border-color:#C5D0DC;margin-bottom:0!important;margin-left:0;position:relative;top:1px;border-bottom:1px solid #ddd;padding-left:0;list-style:none;}
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
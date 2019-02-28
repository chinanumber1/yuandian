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
	<body width="100%" <?php if($bg_color): ?>style="background:<?php echo ($bg_color); ?>;"<?php endif; ?>>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>					<a href="<?php echo U('Activity/index');?>" class="on">活动列表</a>|					<a href="javascript:void(0);" onclick="window.top.artiframe('<?php echo U('Activity/add');?>','创建活动',480,300,true,false,false,addbtn,'add',true);">创建活动</a>|					<a href="<?php echo U('Config/index',array('galias'=>'activity','header'=>'Activity/header'));?>">活动配置</a>				</ul>			</div>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<table width="100%" cellspacing="0">						<colgroup>							<col/>							<col/>							<col/>							<col/>							<col/>							<col/>							<?php if($system_session['level'] == 2 || $system_session['level'] == 0): ?><col width="180" align="center"/><?php endif; ?>						</colgroup>						<thead>							<tr>								<th>编号</th>								<th>名称</th>								<th>期数</th>								<th>开始时间</th>								<th>结束时间</th>								<th>活动列表</th>								<?php if($system_session['level'] == 2 || $system_session['level'] == 0): ?><th class="textcenter">操作</th><?php endif; ?>							</tr>						</thead>						<tbody>							<?php if(is_array($activity_list)): if(is_array($activity_list)): $i = 0; $__LIST__ = $activity_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>										<td><?php echo ($vo["activity_id"]); ?></td>										<td><?php echo ($vo["name"]); ?></td>										<td><?php echo ($vo["term"]); ?></td>										<td><?php echo (date('Y-m-d H:i',$vo["begin_time"])); ?></td>										<td><?php echo (date('Y-m-d H:i',$vo["end_time"])); ?></td>										<td><a href="<?php echo U('Activity/activity_list',array('id'=>$vo['activity_id']));?>">活动列表</a></td>										<?php if($system_session['level'] == 2 || $system_session['level'] == 0): ?><td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('<?php echo U('Activity/edit',array('id'=>$vo['activity_id']));?>','编辑活动分类',480,370,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="category_id=<?php echo ($vo["activity_id"]); ?>" url="<?php echo U('Activity/del');?>" tip="删除活动分类，子分类也会删除！">删除</a></td><?php endif; ?>									</tr><?php endforeach; endif; else: echo "" ;endif; ?>							<?php else: ?>								<tr><td class="textcenter red" colspan="8">列表为空！</td></tr><?php endif; ?>						</tbody>					</table>				</div>			</form>		</div>	</body>
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
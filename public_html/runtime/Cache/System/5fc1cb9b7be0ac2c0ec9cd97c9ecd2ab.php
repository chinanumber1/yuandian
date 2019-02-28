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
	<body width="100%" <?php if($bg_color): ?>style="background:<?php echo ($bg_color); ?>;"<?php endif; ?>>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>					<a href="<?php echo U('Searchhot/index');?>" class="on">热门搜索词列表</a>|					<a href="javascript:void(0);" onclick="window.top.artiframe('<?php echo U('Searchhot/add');?>','添加热门搜索词',500,200,true,false,false,addbtn,'add',true);">添加热门搜索词</a>				</ul>			</div>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<table width="100%" cellspacing="0">						<colgroup><col> <col> <col> <col><col> <col width="140" align="center"> </colgroup>						<thead>							<tr>								<th>排序</th>								<th>编号</th>								<th>名称</th>								<th>链接地址</th>								<th>编辑时间</th>								<th class="textcenter">操作</th>							</tr>						</thead>						<tbody>							<?php if(is_array($search_hot_list)): if(is_array($search_hot_list)): $i = 0; $__LIST__ = $search_hot_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>										<td><?php echo ($vo["sort"]); ?></td>										<td><?php echo ($vo["id"]); ?></td>										<td><?php echo ($vo["name"]); ?></td>										<td>											<?php if($vo['url']): ?>有链接 <a href="<?php echo ($vo["url"]); ?>" target="_blank">预览链接</a>											<?php elseif($vo['type'] == 0): ?>												<?php echo ($config["group_alias_name"]); ?>链接 <a href="<?php echo ($config["site_url"]); ?>/index.php?g=Group&c=Search&a=index&w=<?php echo (urlencode($vo["name"])); ?>" target="_blank">预览链接</a>											<?php elseif($vo['type'] == 1): ?>												<?php echo ($config["meal_alias_name"]); ?>链接 <a href="<?php echo ($config["site_url"]); ?>/index.php?g=Meal&c=Search&a=index&w=<?php echo (urlencode($vo["name"])); ?>" target="_blank">预览链接</a><?php endif; ?>										</td>										<td><?php echo (date('Y-m-d H:i:s',$vo["add_time"])); ?></td>										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('<?php echo U('Searchhot/edit',array('id'=>$vo['id'],'frame_show'=>true));?>','查看详细信息',500,200,true,false,false,false,'add',true);">查看</a> | <a href="javascript:void(0);" onclick="window.top.artiframe('<?php echo U('Searchhot/edit',array('id'=>$vo['id']));?>','编辑热门搜索词',500,200,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="id=<?php echo ($vo["id"]); ?>" url="<?php echo U('Searchhot/del');?>">删除</a></td>									</tr><?php endforeach; endif; else: echo "" ;endif; ?>								<tr><td class="textcenter pagebar" colspan="8"><?php echo ($pagebar); ?></td></tr>							<?php else: ?>								<tr><td class="textcenter red" colspan="8">列表为空！</td></tr><?php endif; ?>						</tbody>					</table>				</div>			</form>		</div>	</body>
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
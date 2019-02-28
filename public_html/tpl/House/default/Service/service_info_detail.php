<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 社区管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
    <style type="text/css">
    .green{ color:green}
	.red{ color:red}
    </style>
	<body>
		<table>
			<tr>
				<th width="15%">标题</th>
				<td width="35%">{pigcms{$detail.title}</td>
				<th width="15%">分类</th>
				<td width="35%">{pigcms{$detail.cat_name}</td>
			</tr>
            
            <tr>
				<th width="15%">状态</th>
				<td width="35%">
                	<if condition='$detail["status"] eq 0'>
                    	<span class="red">关闭</span>
                    <else />
                    	<span class="green">开启</span>
                    </if>
                </td>
				<th width="15%">排序值</th>
				<td width="35%">{pigcms{$detail.sort}</td>
			</tr>
            <tr>
				<th width="15%">描述</th>
				<td width="35%">{pigcms{$detail.desc}</td>
				<th width="15%">添加时间</th>
				<td width="35%">{pigcms{$detail.add_time|date='Y-m-d H:i:s',###}</td>
			</tr>
            
            <tr>
				<th width="15%">链接地址</th>
				<td colspan="3">{pigcms{$detail.url}</td>
			</tr>
            <tr>
            	<th width="15%">图片</th>
				<td colspan="3"><img src="{pigcms{$config.site_url}/upload/service/{pigcms{$detail.img_path}" width="50" height="50" /></td>
            </tr>
            
		</table>
	</body>
</html>
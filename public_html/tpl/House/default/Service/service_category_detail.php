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
				<th width="15%">分类名称</th>
				<td width="35%">{pigcms{$detail.cat_name}</td>
                
                <th width="15%">状态</th>
				<td colspan="3">
                	<if condition='$detail["status"] eq 0'>
                        <span class="red">关闭</span>
                    <elseif condition='$detail["status"] eq 1'/>
                        <span class="green">开启</span>
                    </if>
                </td>
				
			</tr>
            
            <tr>
				<th width="15%">添加时间</th>
				<td width="35%"><if condition='$detail["add_time"]'>{pigcms{$detail.add_time|date='Y-m-d H:i:s',###}</if></td>
				<th width="15%">排序值</th>
				<td width="35%">{pigcms{$detail.sort}</td>
			</tr>
            
            <if condition='$detail["parent_id"]'>
                <tr>
                    <th width="15%">分类链接</th>
                    <td colspan="3">{pigcms{$detail.cat_url}</td>
                </tr>
            <else />
            	<if condition='$detail["cat_img"]'>
                    <tr>
                        <th width="15%">分类图标</th>
                        <td colspan="3"><img src="{pigcms{$config.site_url}/upload/service/{pigcms{$detail.cat_img}" width="50px" height="50px"/></td>
                    </tr>
                </if>
            </if>
		</table>
	</body>
</html>
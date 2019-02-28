<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>文章详情</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
	<body>
		<table>
			<tr>
				<th width="15%">文章ID</th>
				<td width="35%">{pigcms{$aBbsAricleDetails.aricle_id}</td>
				<th width="15%">分类ID</th>
				<td width="35%">{pigcms{$aBbsAricleDetails.cat_id}</td>
			</tr>
            <tr>
                <th width="15%">赞数量</th>
				<td width="35%">{pigcms{$aBbsAricleDetails.aricle_praise_num}</td>
                <th width="15%">评论数</th>
				<td width="35%">{pigcms{$aBbsAricleDetails.aricle_comment_num}</td>
			</tr>
			<tr>
				<th width="15%">状态</th>
                <td width="35%">
                	<if condition='$aBbsAricleDetails["aricle_status"] eq 1'>
                        <span style="color:green;">审核通过</span>
                    <elseif condition='$aBbsAricleDetails["aricle_status"] eq 2'/>
                        <span style="color:Gray;">待审核</span>
                    <elseif condition='$aBbsAricleDetails["aricle_status"] eq 3'/>
                        <span style="color:red;">审核不通过</span>
                    <elseif condition='$aBbsAricleDetails["aricle_status"] eq 4'/>
						<span style="color:Gray;">用户删除</span>
                    </if>
                </td>
                <th width="15%">排序</th>
				<td width="35%">{pigcms{$aBbsAricleDetails.aricle_sort}</td>
			</tr>
            <tr>
				<th width="15%">更新时间</th>
				<td width="35%"><if condition='$aBbsAricleDetails["update_time"]'>{pigcms{$aBbsAricleDetails.update_time|date='Y-m-d H:i:s',###}</if></td>
				<th width="15%">过期时间</th>
				<td width="35%"><if condition='$aBbsAricleDetails["exp_time"]'>{pigcms{$aBbsAricleDetails.exp_time|date='Y-m-d H:i:s',###}</if></td>
			</tr>
            <tr>
				<th width="15%">标题</th>
				<td style="word-break:break-all" width="85%" colspan="3">{pigcms{$aBbsAricleDetails.aricle_title}</td>
			</tr>
			<tr>
				<th width="15%">内容</th>
				<td style="word-break:break-all"  width="85%" colspan="3">{pigcms{$aBbsAricleDetails.aricle_content}</td>
			</tr>
			<if condition='$aBbsAricleImg'>
				<tr>
					<th width="15%">图片</th>
					<td style="word-break:break-all"  width="85%" colspan="3"><volist name="aBbsAricleImg" id="vo"><img style="width:100%;" src="{pigcms{$vo.aricle_img}"></volist></td>
				</tr>
			</if>
		</table>
	</body>
</html>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<meta http-equiv="Expires" content="-1">
<meta http-equiv="Cache-Control" content="no-cache">
<meta http-equiv="Pragma" content="no-cache">
<meta charset="utf-8">
<title>{pigcms{$worker_session['name']}-个人信息</title>
<meta name="description" content="{pigcms{$config.seo_description}"/>
<link href="{pigcms{$static_path}css/worker_deliver.css" rel="stylesheet"/>
<script src="{pigcms{:C('JQUERY_FILE')}"></script>
</head>
<body>
<div id="shopReplyBox">
<if condition='$comment_list["list"]'>
	<volist name='comment_list["list"]' id='comment'>
		<dl>
			<dd>
				<div class="avatar">
					<img src="{pigcms{$comment.avatar}" />
				</div>
				<div class="right">
					<div class="nickname">{pigcms{$comment.nickname}<div class="time">{pigcms{$comment.add_time|date='Y-m-d H:i:s',###}</div></div>
					<div class="star">
					<for start='0' end='$comment.avg_score'>
						<i class="full"></i>
					</for>
					</div>
					<div class="content">{pigcms{$comment.content}</div>
				</div>
			</dd>
		</dl>
	</volist>
<else />
	<div id="noReply">暂无评价</div>
</if>
</div>
</body>
</html>
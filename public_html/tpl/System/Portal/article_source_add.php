<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="//apps.bdimg.com/libs/bootstrap/3.3.4/css/bootstrap.css">
	<script type="text/javascript" src="//apps.bdimg.com/libs/jquery/2.1.4/jquery.min.js"></script>
	<script type="text/javascript" src="{pigcms{$static_public}layer/layer.js"></script>
	<style type="text/css">
		.form-inline{margin-top:10px;}
	</style>
</head>
<body>
<form id="myform" method="post" action="{pigcms{:U('Portal/save_source')}" frame="true" refresh="true">
	<div class="container">
		<input type="hidden" id="source_id" name="source_id" value="{pigcms{$source.id}"/>
		<div class="form-inline">
			<div class="form-group">
				<label>来源名称：</label>
				<input type="text" id="title" name="name" class="form-control" value="{pigcms{$source.title}" placeholder="请输入来源名称">
			</div>
		</div>

		<div class="btn hidden">
			<input type="submit" name="dosubmit" id="dosubmit" onclick="save()" class="button" />
			<input type="reset" value="取消" class="button" />
		</div>

	</div>
</form>
<script type="text/javascript">
	function save(){
		var source_id = $('#source_id').val();
		var name = $.trim($('#title').val());

		if(name == ''){
			layer.alert('请输入来源名称');
			return;
		}
		$("#myform").submit();
	}
</script>
</body>
</html>
<include file="Public:header"/>
<style>
	.station{margin-right:5px; height: 30px;line-height: 30px; float: left;}
</style>
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui-timepicker-addon.css">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery-ui-timepicker-addon.min.js"></script>

<form id="myform" method="post" action="{pigcms{:U('Village/save_history_price')}" >
	<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
		
		<tr>
			<td width="80">小区名称</td>
			<td>{pigcms{$village_name}</td>
		</tr>

		<tr>
			<td width="80">时间</td>
			<td><input class="input fl" size="20" name="dateline" id="dateline" type="text"/></td>
		</tr>
		<tr>
			<td width="80">价格</td>
			<td><input type="text" class="input f1" size="20" name="price"></td>
		</tr>
		<tr>
			<td width="80">租房价格</td>
			<td><input class="input fl" size="20" name="rent_price" type="text" placeholder="元/平米" /></td>
		</tr>

		
	</table>

	<script type="text/javascript">

	</script>

	<div class="btn hidden">
		<input type="hidden" name="village_id" value="{pigcms{$_GET['village_id']}" />
		<input type="submit" name="dosubmit" id="dosubmit" value="提交" class="button" />
		<input type="reset" value="取消" class="button" />
	</div>
</form>
<script type="text/javascript">
$('#dateline').datetimepicker({
        dateFormat: "yy-mm-dd",
        showSecond: false,
        showHour:false,
        showMinute:false,
        showTime:false
    });
</script>
<include file="Public:footer"/>
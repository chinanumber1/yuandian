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
    </style>
	<body>
		<form id="myform" action="{pigcms{:U('express_config')}" method='post'>
		<table>
        	<tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>快递配置：</b></td>
			</tr>
			<tr>
				<th width="15%">快递代送</th>
				<td width="85%">
					
					<label style="padding-left:0px;padding-right:20px;"><input type="radio" class="ace" value="1" name="status"  <if condition="$express_config.status eq 1">checked="checked"</if>><span style="z-index: 1" class="lbl">开启</span></label>
					<label style="padding-left:0px;"><input type="radio" class="ace" value="0" name="status" <if condition="$express_config.status eq 0">checked="checked"</if>><span style="z-index: 1" class="lbl">关闭</span></label>
				</td>
			</tr>
			<tr>
				<th width="15%">配送时间段</th>
				<td width="85%">
					
					 <label>
						<input class="col-sm-2 Wdate" type="text" readonly style="height:30px;" placeholder="开始时间" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'HH:mm',vel:'start_time'})" value="{pigcms{$express_config.start_time|date='H:i',###}"/></label>
						<input name="start_time" id="start_time" type="hidden" value="{pigcms{$express_config.start_time|date='H:i',###}"/>
&nbsp;&nbsp;至&nbsp;&nbsp;
 <label>
						<input class="col-sm-2 Wdate" placeholder="结束时间" type="text" readonly style="height:30px;" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'H:mm',vel:'end_time'})" value="{pigcms{$express_config.end_time|date='H:i',###}"/></label>
						<input name="end_time" id="end_time" type="hidden" value="{pigcms{$express_config.end_time|date='H:i',###}"/>
						
				</td>
			</tr>
			
          	<tr>
				<th width="15%">收费模式</th>
				<td width="85%">
					
					<label style="padding-left:0px;padding-right:20px;"><input type="radio" class="ace" value="0" name="free" <if condition="$express_config.free eq 0">checked="checked"</if>><span style="z-index: 1" class="lbl">收费</span></label>
					<label style="padding-left:0px;"><input type="radio" class="ace" value="1" name="free" <if condition="$express_config.free eq 1">checked="checked"</if>><span style="z-index: 1" class="lbl">免费</span>
					</label>
				</td>
			</tr>
			<tr>
				<th width="15%">代送通知</th>
				<td width="85%">
					<input type="text" name="notice_name" value="{pigcms{$express_config.notice_name}" style="    line-height: 20px;" placeholder="姓名">&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="text" name="notice_phone" value="{pigcms{$express_config.notice_phone}" style="    line-height: 20px;" placeholder="手机号码">
				</td>
			</tr>
			<tr>
				<th width="15%">超时自动确认</th>
				<td width="85%">
					<input type="text" name="out_time" value="{pigcms{$express_config.out_time}" style="line-height: 20px;" placeholder="请填写超时时间，单位/时">
				</td>
			</tr>
			 <tr>
				<td colspan="4"><button class="chk_express" style=" margin:0 auto; display:block;float:right" <if condition="!in_array(208,$house_session['menus'])">disabled="disabled"</if>>保存</button></td>
			</tr>
          
         
		</table>
		</form>
	</body>
	<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
<script src="{pigcms{$static_public}kindeditor/kindeditor.js"></script>
</html>
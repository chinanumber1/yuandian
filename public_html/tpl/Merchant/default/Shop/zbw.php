<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
	<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
	<title>{pigcms{$config.site_name} - 店铺管理中心</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
</head>
<body>
	<form id="myform" method="post" action="{pigcms{:U('Shop/zbw')}" enctype="multipart/form-data">
		<input type="hidden" name="store_id" value="{pigcms{$store_id}"/>
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<tr>
				<th width="80">机构编码</th>
				<td><input type="text" class="input fl" name="zbw_sBranchNo" /></td>
			</tr>
<!-- 			<tr> -->
<!-- 				<th width="80">sAppCode</th> -->
<!-- 				<td><input type="text" class="input fl" name="sAppCode" /></td> -->
<!-- 			</tr> -->
            <tr><td></td><td style="float:right"><button type="button">保存</button></td></tr>
		</table>
	</form>
    <script>
    $(document).ready(function(){
        $('button').click(function(){
            $.post("{pigcms{:U('Shop/zbw', array('store_id' => $store_id))}", {'zbw_sBranchNo':$('input[name=zbw_sBranchNo]').val()}, function(res){
                if (res.status == 1) {
                    parent.location.reload();
                } else {
                    art.dialog.alert(res.info);
                    art.dialog.close();
                }
            },'json');
        });
    });
    </script>
</body>
</html>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/styles.css">
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/jquery.ba-bbq.min.js"></script>
<if condition="$config['site_favicon']">
	<link rel="shortcut icon" href="{pigcms{$config.site_favicon}"/>
</if>
<title>{pigcms{$config.site_name} - 社区中心</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" href="{pigcms{$static_path}css/bootstrap.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/font-awesome.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-fonts.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace.min.css" id="main-ace-style">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-skins.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/ace-rtl.min.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/global.css">
<link rel="stylesheet" href="{pigcms{$static_path}css/jquery-ui-timepicker-addon.css">
</head>

<body class="no-skin">
	<div class="main-container" id="main-container">
<include file="Public:left"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="page-content">
        <div class="page-content-area">
        	
            <div class="row">
                <div class="col-xs-12">
                
                
                    <div id="shopList" class="grid-view">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
									<th width="5%">选中</th>
                                    <th width="5%">ID</th>
									<th width="15%">物业编号</th>
                                    <th width="10%">业主姓名</th>
                                    <th width="10%">业主手机号</th>
                                    <th width="20%">业主住址</th>
                                    <th width="20%">服务到期时间</th>
                                    <th class="button-column" width="15%">缴费记录</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$now_bind_user">
									<tr>
										<td><input type="checkbox" onclick="returnHomepage('{pigcms{$now_bind_user['usernum']}')" /></td>
										<td><div class="tagDiv">{pigcms{$now_bind_user['pigcms_id']}</div></td>
										<td>{pigcms{$now_bind_user["usernum"]}</td>
										<td>{pigcms{$now_bind_user["name"]}</td>
										<td><div class="tagDiv">{pigcms{$now_bind_user.phone}</div></td>
										<td><div class="tagDiv">{pigcms{$now_bind_user.address}</div></td>
										<td><div class="tagDiv"><if condition='$now_bind_user["expire_time"] gt 0'>{pigcms{$now_bind_user.expire_time|date='Y-m-d H:i:s',###}<else />暂无</if><div></td>
										<td class="button-column">
											<a style="width: 60px;" class="label label-sm label-info handle_btn" title="详情" href="{pigcms{:U('search_owner_pay_list',array('pigcms_id'=>$now_bind_user['pigcms_id']))}">查看</a>
									   </td>
									</tr>
                                <else />
                                    <tr class="odd"><td class="button-column" colspan="8" >暂无记录。</td></tr>
                                </if>
                            </tbody>
                        </table>
                        {pigcms{$list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
// 返回数据到主页面
function returnHomepage(url){
	var origin = artDialog.open.origin;
	var dom = origin.document.getElementById('usernum');
	dom.value = url;
	setTimeout("art.dialog.close()", 100 )
}
</script>
<include file="Public:footer"/>
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
                                    <th width="10%">ID</th>
                                    <th width="10%">开始时间</th>
                                    <th width="10%">结束时间</th>
                                    <th width="20%">物业时间</th>
                                    <th width="25%">赠送物业时间</th>
									<th width="25%">添加时间</th>
                                </tr>
                            </thead>
                            <tbody>
                                <if condition="$list">
									<volist name='list' id='vo'>
										<tr>
											<td><div class="tagDiv">{pigcms{$vo.id}</div></td>
											<td>{pigcms{$vo["start_time"]|date='Y-m-d H:i:s',###}</td>
											<td><div class="tagDiv">{pigcms{$vo["end_time"]|date='Y-m-d H:i:s',###}</div></td>
											<td><div class="tagDiv">{pigcms{$vo.property_month_num}个月</div></td>
											<td><div class="tagDiv"><if condition='$vo["presented_property_month_num"] gt 0'>{pigcms{$vo.presented_property_month_num}个月<else />暂无</if></div></td>
											<td><div class="tagDiv">{pigcms{$vo["add_time"]|date='Y-m-d H:i:s',###}</div></td>
										</tr>
									</volist>
                                <else/>
                                    <tr class="odd"><td class="button-column" colspan="6" >暂无记录。</td></tr>
                                </if>
                                <tr class="odd">
                                	<td colspan="6" class="button-column"><button type="button" onClick="history.back(-1);" style="float:right; margin-right:10px;">返回列表</button></td>
                                </tr>
                            </tbody>
                        </table>
                        {pigcms{$list.pagebar}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

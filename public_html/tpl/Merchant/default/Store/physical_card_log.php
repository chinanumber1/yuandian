<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>
		<script type="text/javascript" src=".{pigcms{$static_public}js/date/WdatePicker.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
	</head>
	<body>
		<div class="headerBox">
			<div class="txt">实体卡管理</div>
			<div class="back urlLink" data-url="{pigcms{:U('index')}" title="返回首页"></div>
			<div class="reload urlLink" data-url="reload" title="刷新页面"></div>
		</div>
		<div class="mainBox">
			<div class="leftMenu">
				<ul>
					<li class="urlLink " data-url="{pigcms{:U('physical_card')}">
						<div class="icon order"></div>
						<div class="text">实体卡管理</div>
					</li>
					<li class="urlLink cur" data-url="{pigcms{:U('physical_card_log')}">
						<div class="icon order"></div>
						<div class="text">实体卡管理记录</div>
					</li>
				</ul>
			</div>
			<div class="rightMain">
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th>ID</th>
								<th>实体卡ID</th>
								<th>商家</th>
								<th>店员</th>
								<th>描述</th>
								<th>添加时间</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($log_list)">
								<volist name="log_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.card_id}</td>
										<td>{pigcms{$merchant_session.name}</td>
										<td>{pigcms{$vo.staff_name}</td>
										<td>{pigcms{$vo.des}</td>
										<td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
									
									</tr>
								</volist>
							<else/>
								<tr><td class="textcenter red" colspan="7">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
					{pigcms{$pagebar}
				</div>
			</div>
		</div>
	</body>
</html>
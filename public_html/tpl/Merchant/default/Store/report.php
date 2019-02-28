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
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
		<style>
		td{
		text-align: center;
		}
		</style>
	</head>
	<body>
		<div class="headerBox">
			<div class="txt">报表统计</div>
			<div class="back urlLink" data-url="{pigcms{:U('index')}" title="返回首页"></div>
			<div class="reload urlLink" data-url="reload" title="刷新页面"></div>
		</div>
		<div class="mainBox">
			<div class="leftMenu">
				<ul>
					<li class="urlLink <if condition="$type eq 0">cur</if>" data-url="{pigcms{:U('report', array('type' => 0))}">
						<div class="icon list"></div>
						<div class="text">{pigcms{$config.meal_alias_name}统计</div>
					</li>
					<li class="urlLink <if condition="$type eq 1">cur</if>" data-url="{pigcms{:U('report', array('type' => 1))}">
						<div class="icon order"></div>
						<div class="text">到店订单统计</div>
					</li>
					<li class="urlLink <if condition="$type eq 2">cur</if>" data-url="{pigcms{:U('report', array('type' => 2))}">
						<div class="icon order"></div>
						<div class="text">线下零售统计</div>
					</li>
				</ul>
			</div>
			<div class="rightMain">
				<div class="alert alert-block alert-success" style="position:relative;">
					<span>时间筛选：</span>
					<div style="display:inline-block;">
						<select class='custom-date' id="time_value" name='select'>
							<option  value='1'>今天</option>
							<option selected='selected' value='7'>7天</option>
							<option value='30'>30天</option>
							<option value='180'>180天</option>
							<option value='365'>365天</option>
							<option value='custom'>自定义</option>
						</select>
					</div>
					<input type="button" value="查询" class="button" id="time" style="width: 60px;height: 25px;"/>
				</div>
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>									
								<th id="shopList_c1" width="50">支付类型</th>
								<th id="shopList_c1" width="50">支付总额</th>
								<th id="shopList_c5" width="60" >操作</th>
							</tr>
						</thead>
						<tbody>
<!-- 							<if condition="$order_list"> -->
<!-- 								<volist name="order_list" id="vo"> -->
<!-- 									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>"> -->
<!-- 										<td><div class="tagDiv">{pigcms{$vo.pay_type}</div></td> -->
<!-- 										<td><div class="tagDiv">{pigcms{$vo.money|floatval}</div></td> -->
<!-- 										<td nowrap> -->
<!-- 											<a title="查看订单详情" data-title="订单详情" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('report_detail',array('order_id'=>$vo['order_id']))}"> -->
<!-- 												<i class="shortBtn">查看详情</i> -->
<!-- 											</a> -->
<!-- 										</td> -->
<!-- 									</tr> -->
<!-- 								</volist> -->
<!-- 							<else/> -->
<!-- 								<tr class="odd"><td class="button-column" colspan="3" >您的店铺暂时还没有订单。</td></tr> -->
<!-- 							</if> -->
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
<script>
var day = '', period = '', type = '{pigcms{$type}';
$(document).ready(function(){
	$('#time').click(function(){
		int_html();
	});
	int_html();
});
function int_html()
{
	if($('#time_value option:selected').attr('value')=='custom'){
		period = $('#time_value option:selected').html();
	}else{
		day = $('#time_value option:selected').attr('value');
	}
	var index = layer.load(0, {shade: false}); //0代表加载的风格，支持0-2
	$.post('{pigcms{:U("Store/ajax_report")}', {'type':type, 'day':day, 'period':period}, function(response){
		if (response.error_code) {
		} else {
			var html = '';
			if (response.count > 0) {
				$.each(response.data, function(i, row){
					html += '<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">';
					html += '<td>' + row.pay_type + '</td>';
					html += '<td>' + row.money + '</td>';
					html += '<td nowrap>';
					html += '<a title="查看订单详情" data-title="报表详情" class="green handle_btn" style="padding-right:8px;" href="' + row.report_detail + '">';
					html += '<i class="shortBtn">查看详情</i>';
					html += '</a>';
					if(!checkApp()){
						html += '<a href="' + row.report_export + '">';
						html += '<i class="shortBtn">下载报表</i>';
						html += '</a>';
					}
					html += '</td>';
					html += '</tr>';
				});
			} else {
				html = '<tr class="odd"><td class="button-column" colspan="3" >暂无订单统计记录。</td></tr>';
			}
			layer.closeAll('loading');
			$('tbody').html(html);
		}
	},'json');
}
</script>
	<script type="text/javascript" src="{pigcms{$static_public}js/date-picker/index.js"></script>
	<link rel="stylesheet" type="text/css" href="{pigcms{$static_public}js/date-picker/index.css" />
</html>
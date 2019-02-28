<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-bar-chart-o bar-chart-o-icon"></i>
			<li class="active">商家账单</li>
			<li class="active">微店店铺的分销商</li>
		</ul>
	</div>

	
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-sm-12">
					<div class="tabbable" style="margin-top:20px;">
								<div class="row">
									<form id="frmselect" method="get" action="" style="margin-bottom:0;margin-left:13px;">
										<select id="fid" name="fid">
											<option value="all">选择店铺</option>
											<volist name="store_list" id="vo">
												<option value="{pigcms{$vo.store_id}" <if condition="$_GET['store_id'] eq $vo['store_id']">selected="selected"</if>>{pigcms{$vo.name}</option>
											</volist>
										</select>
									</form>			
									<div class="col-xs-12">		
										<div class="grid-view">
											<table class="table table-striped table-bordered table-hover">
												<thead>
													<tr>
														<th>店铺ID</th>
														<th>店铺名称</th>
														<th>联系人</th>
														<th>联系号码</th>
														<th>入驻时间</th>
														<th>销售总金额</th>
														<th>查看账单</th>
													</tr>
												</thead>
												<tbody id="show_shop_list"></tbody>
											</table>
										</div>						
									</div>
								</div>
							</div>
						</div>
					</div>	
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('#fid').change(function(){
		var store_id = $(this).val();
		
		change_store(store_id);
	});
	change_store('{pigcms{$store_id}');
});

function change_store(store_id)
{
	$('#show_shop_list').html('<tr><td colspan="9" style="text-align: center;"><img src="tpl/Merchant/default/static/images/loading-0.gif"></td></tr>');

	$.get("{pigcms{:U('Count/child_stores')}", {'store_id':store_id}, function(response){
		var html = '';
		if (response.error_code == 0 && response.stores.length > 0) {
			$.each(response.stores, function(i, data){
				html += '<tr>';
				html += '<td>' + data.store_id + '</td>';
				html += '<td>' + data.name + '</td>';
				html += '<td>' + data.linkman + '</td>';
				html += '<td>' + data.tel + '</td>';
				html += '<td>' + data.date_added + '</td>';
				html += '<td>' + data.sales + '</td>';
				html += '<td><a href="/merchant.php?g=Merchant&c=Count&a=weidian_bill&fid=' + store_id + '&store_id=' + data.store_id + '">查看账单</a></td>';
				html += '</tr>';
			});
			$('#show_shop_list').html(html);
		} else {
			$('#show_shop_list').html('');
		}
	}, 'json');
}
</script>
<include file="Public:footer"/>

<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Merchant_money/index')}">商家余额</a>
			</li>
			<li class="active">充值</li>
			
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="tab-content">
						<div class="grid-view">
							<form enctype="multipart/form-data" class="form-horizontal" method="get" action="{pigcms{:U(Merchant_money/mer_recharge)}">
								<input type="hidden" name="c" value="Merchant_money"/>
								<input type="hidden" name="a" value="mer_recharge"/>
								<label style="color:red">您的余额为：￥{pigcms{$now_merchant['money']|floatval}</label><br>
								
							
								<div class="form-group">
									<label class="col-sm-1"><label for="money">金额</label></label>
									<input type="text" class="col-sm-2" name="money" id="money" value="{pigcms{$money}" />元&nbsp;<label id="percent"> </label>
								</div>
								
							
								<div class="clearfix form-actions">
									<div class="col-md-9">
										<button class="btn btn-info" type="submit">
											<i class="ace-icon fa fa-check bigger-110"></i>
											充值
										</button>
									</div>
								</div>
							</form>
							
							
							<table class="table table-striped table-bordered table-hover">
								<thead>
									
									<tr>
								
										<th>ID</th>
										<th>金额</th>
										<th>支付方式</th>
										<th>充值时间</th>
										
									</tr>
								</thead>
								<tbody>
									<if condition="$recharge_list">
										<volist name="recharge_list" id="vo">
											<tr>
											
											
											
												<td>{pigcms{$vo.order_id}</td>
												<td>{pigcms{$vo.money|floatval}</td>
												<td>{pigcms{$vo.pay_type}</td>
									
												<td>{pigcms{$vo.pay_time|date="Y-m-d H:i:s",###}</td>
												
											</tr>
										</volist>
								
										
										<tr class="odd">
											<td colspan="4" id="show_count"></td>
										</tr>
										<tr><td class="textcenter pagebar" colspan="4">{pigcms{$pagebar}</td></tr>	
									<else />
										<tr class="odd"><td class="textcenter red" colspan="4" >暂时还没有充值记录</td></tr>
									</if>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>

	$(document).ready(function() {
		$('#money').change(function(){
			if(!isNaN($(this).val()) || $(this).val()<0){
				alter('金额有误');
			}
			
		});
		
	});


</script>
<include file="Public:footer"/>
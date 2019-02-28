<include file="Store:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>【{pigcms{$now_store.name}】餐台预定详情
			</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="alert alert-block alert-success">
						<button type="button" class="close" data-dismiss="alert">
							<i class="ace-icon fa fa-times"></i>
						</button>	
						<p>
							可以查看到该餐台大于当前时间的预订情况
						</p>
					</div>
					<button class="btn btn-success">{pigcms{$table['name']}</button>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>预定人</th>
									<th>预定时间</th>
									<th>电话</th>
									<th>支付状态</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$order_list">
									<volist name="order_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.name}</td>
											<td>{pigcms{$vo.arrive_time|date="Y-m-d H:i:s",###}</td>
											<td>{pigcms{$vo.phone}</td>
											<td><if condition="$vo['paid'] eq 0">未支付<elseif condition="$vo['paid'] eq 2" />预付定金{pigcms{$vo['pay_money']}元<else />已支付</if></td>
											<td>
												<label class="statusSwitch" style="display:inline-block;">
													<input name="switch-field-1" class="ace ace-switch ace-switch-6" type="checkbox" data-id="{pigcms{$vo.order_id}" <if condition="$vo['is_confirm'] eq 1">checked="checked" data-status="OPEN"<else/>data-status="CLOSED"</if>/>
													<span class="lbl"></span>
												</label>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="5" >无内容</td></tr>
								</if>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	$('#navbar,#sidebar,#breadcrumbs').hide();
	$('.main-content').css('margin-left','0');
});

updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "shopstatus");

function updateStatus(dom1, dom2, status1, status2, attribute){
	$(dom1).each(function(){
		if($(this).attr("data-status")==status1){
			$(this).attr("checked",true);
		}else{
			$(this).attr("checked",false);
		}
		$(dom2).show();
	}).click(function(){
		var _this = $(this),
		 	type = 'open',
		 	id = $(this).attr("data-id");
		_this.attr("disabled",true);
		if(_this.attr("checked")){	//开启
			type = 'open';
		}else{		//关闭
			type = 'close';
		}
		$.ajax({
			url:"{pigcms{:U('Store/table_check')}",
			type:"post",
			data:{"type":type,"id":id,"status1":status1,"status2":status2,"attribute":attribute},
			dataType:"text",
			success:function(d){
				if(d != '1'){		//失败
					if(type=='open'){
						_this.attr("checked",false);
					}else{
						_this.attr("checked",true);
					}
					bootbox.alert("操作失败");
				}
				_this.attr("disabled",false);
			}
		});
	});
}
</script>
<include file="Public:footer"/>
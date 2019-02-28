<include file="Public:header"/>
<div class="main-content">
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Foodshop/index')}">{pigcms{$config.meal_alias_name}管理</a>
			</li>
			<li class="active">{pigcms{$now_store['name']}</li>
			<li class="active">套餐列表</li>
		</ul>
	</div>
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<a class="btn btn-success" href="{pigcms{:U('Foodshop/package_add', array('store_id' => $now_store['store_id']))}">添加套餐</a>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="150">套餐名称</th>
									<th width="100">套餐价格</th>
									<th width="150">使用说明</th>
									<th class="button-column" width="140">菜品详情</th>
									<th class="button-column" width="140">状态</th>
									<th class="button-column" width="140">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$packages">
									<volist name="packages" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td><div class="tagDiv">{pigcms{$vo.name}</div></td>
											<td><div class="tagDiv">{pigcms{$vo.price|floatval}</div></td>
											<td><div class="tagDiv">{pigcms{$vo.note}</div></td>
											<td class="button-column">
												<a href="{pigcms{:U('Foodshop/package_detail', array('store_id' => $vo['store_id'], 'id' => $vo['id']))}">查看套餐菜单</a>
											</td>
											<td class="button-column">
												<label class="statusSwitch" style="display:inline-block;">
													<input name="switch-field-1" class="ace ace-switch ace-switch-6" type="checkbox" data-id="{pigcms{$vo.id}" <if condition="$vo['status'] eq 1">checked="checked" data-status="OPEN"<else/>data-status="CLOSED"</if>/>
													<span class="lbl"></span>
												</label>
											</td>
											<td class="button-column">
												<a href="{pigcms{:U('Foodshop/package_edit', array('store_id' => $vo['store_id'], 'id' => $vo['id']))}">修改套餐</a>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="9" >还没有套餐</td></tr>
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
	updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "shopstatus");
});
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
			url:"{pigcms{:U('Foodshop/package_status', array('store_id' => $now_store['store_id']))}",
			type:"post",
			data:{"type":type,"id":id},
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
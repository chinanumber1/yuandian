<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Market/sell_order')}">进销存</a>
			</li>
			<li class="active">我发布的商品</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="50">商品条形码</th>
                                    <th width="100">父分类名称</th>
                                    <th width="100">分类名称</th>
									<th width="100">商品名称</th>
									<th width="50">商品图片</th>
									<th width="100">批发单价（元）</th>
									<th width="50">库存</th>
									<th width="100">最低批发数</th>
									<th width="50">已售</th>
									<th width="100">优惠明细</th>
									<th width="100">店铺信息</th>
									<th width="50">状态</th>
									<th width="100" class="button-column">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$orders">
									<volist name="orders" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.number}</td>
											<td>{pigcms{$vo.cat_fname}</td>
											<td>{pigcms{$vo.cat_name}</td>
											<td>{pigcms{$vo.name}</td>
											<td><img src="{pigcms{$vo.pic}" width="50" height="50"></td>
											<td>{pigcms{$vo.price|floatval}</td>
											<td>{pigcms{$vo.stock_num}({pigcms{$vo.unit})</td>
											<td>{pigcms{$vo.min_num}({pigcms{$vo.unit})</td>
											<td>{pigcms{$vo.sell_count}({pigcms{$vo.unit})</td>
											<td>{pigcms{$vo.discount_info_txt}</td>
											<td>
											                 店铺名:{pigcms{$vo.store_name}<br/>
											                 店铺电话:<span style="color:green">{pigcms{$vo.merchant_phone}</span><br/>
											</td>
                                            
                                            <td class="button-column">
                                                <if condition="$vo['status'] eq 0">
                                                <b style="color:#ff893c">审核中</b>
                                                <elseif condition="$vo['status'] eq 2" />
                                                <b style="color:gray">被拒绝</b>
                                                <elseif condition="$vo['status'] eq 1 OR $vo['status'] eq 3" />
                                                <label class="statusSwitch" style="display:inline-block;">
                                                    <input name="switch-field-1" class="ace ace-switch ace-switch-6" type="checkbox" data-id="{pigcms{$vo['goods_id']}" <if condition="$vo['status'] eq 1">checked="checked" data-status="OPEN"<elseif condition="$vo['status'] eq 3"/>data-status="CLOSED"</if>/>
                                                    <span class="lbl"></span>
                                                </label>
                                                </if>
                                            </td>
											<td class="button-column">
												<a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('Shop/goods_push',array('goods_id'=>$vo['goods_id']))}">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>
												
												<a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('Market/sell_order',array('goods_id'=>$vo['goods_id']))}">售出详情</a>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="10" >暂无订单信息</td></tr>
								</if>
							</tbody>
						</table>
						{pigcms{$pagebar}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	/*店铺状态*/
	updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "store_theme");
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
		var _this = $(this), type = 'open', id = $(this).attr("data-id");
		_this.attr("disabled",true);
		if(_this.attr("checked")){	//开启
			type = 'open';
		}else{		//关闭
			type = 'close';
		}
		$.ajax({
			url:"{pigcms{:U('Market/changeStatus')}",
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
<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Foodshop/index')}">{pigcms{$config.meal_alias_name}管理</a>
			</li>
			<li>{pigcms{$now_store['name']}</li>
			<li class="active">订单列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div class="form-group">
						
						<form action="{pigcms{:U('Foodshop/order')}" method="get">
							<input type="hidden" name="c" value="Foodshop"/>
							<input type="hidden" name="a" value="order"/>
							<input type="hidden" name="store_id" value="{pigcms{$_GET.store_id}"/>
							搜索: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="real_orderid" <if condition="$_GET['searchtype'] eq 'real_orderid'">selected="selected"</if>>订单流水号</option>
								<option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option>
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>客户名称</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>客户电话</option>
							</select>
							<font color="#000">日期筛选：</font>
							<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
							订单状态筛选: 
							<select id="status" name="status">
								<volist name="status_list" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $status">selected="selected"</if>>{pigcms{$vo}</option>
								</volist>
							</select>
							支付方式筛选: 
							<select id="pay_type" name="pay_type">
									<option value="" <if condition="'' eq $pay_type">selected="selected"</if>>全部支付方式</option>
								<volist name="pay_method" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
								</volist>
									<option value="balance" <if condition="'balance' eq $pay_type">selected="selected"</if>>余额支付</option>
								<option value="store_online" <if condition="'store_online' eq $pay_type">selected="selected"</if>>店员点餐线上支付</option>
								<option value="store_offline" <if condition="'store_offline' eq $pay_type">selected="selected"</if>>店员点餐线下支付</option>
							</select>
							
							<input type="submit" value="查询" class="button"/>　　
							<a  href="javascript:void(0)" onclick="exports()" class="btn btn-success" style="float:right;margin-right: 10px;">导出订单</a>
						</form>
					</div>
                    
                    <div class="alert alert-info" style="margin:10px 0;">
                        <b>应收总金额：{pigcms{$total_price|floatval}</b>　
                        <b>在线支付总额：{pigcms{$online_price|floatval}</b>　
                        <b>其他支付总额：{pigcms{$offline_price|floatval}</b>
                    </div>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="50">订单编号</th>
									<th width="80">客户姓名</th>
									<th width="80">客户电话</th>
									<th width="80" class="button-column">预订金</th>
									<th width="80">预订时间
									<if condition="$type eq 'book_time' AND false">
										<if condition="$sort eq 'ASC'">
											<a href="{pigcms{:U('Foodshop/order', array('store_id' => $now_store['store_id'], 'type' => 'book_time', 'sort' => 'DESC', 'status' => $status))}">预订时间<i class="menu-icon fa fa-sort-desc"></i></a>
										<elseif condition="$sort eq 'DESC'" />
											<a href="{pigcms{:U('Foodshop/order', array('store_id' => $now_store['store_id'], 'type' => 'book_time', 'sort' => 'ASC', 'status' => $status))}">预订时间<i class="menu-icon fa fa-sort-asc"></i></a>
										<else />
											<a href="{pigcms{:U('Foodshop/order', array('store_id' => $now_store['store_id'], 'type' => 'book_time', 'sort' => 'DESC', 'status' => $status))}">预订时间<i class="menu-icon fa fa-sort"></i></a>
										</if>
									<elseif condition="false" />
										<a href="{pigcms{:U('Foodshop/order', array('store_id' => $now_store['store_id'], 'type' => 'book_time', 'sort' => 'DESC', 'status' => $status))}">预订时间<i class="menu-icon fa fa-sort"></i></a>
									</if>
									</th>
									<th width="80" class="button-column">桌台类型</th>
									<th width="80" class="button-column">桌台名称</th>
									<th width="80" class="button-column">订单状态</th>
									<th width="80" class="button-column">订单总价
									<if condition="$type eq 'price' AND false">
										<if condition="$sort eq 'ASC'">
											<a href="{pigcms{:U('Foodshop/order', array('store_id' => $now_store['store_id'], 'type' => 'price', 'sort' => 'DESC', 'status' => $status))}">订单总价<i class="menu-icon fa fa-sort-desc"></i></a>
										<elseif condition="$sort eq 'DESC'" />
											<a href="{pigcms{:U('Foodshop/order', array('store_id' => $now_store['store_id'], 'type' => 'price', 'sort' => 'ASC', 'status' => $status))}">订单总价<i class="menu-icon fa fa-sort-asc"></i></a>
										<else />
											<a href="{pigcms{:U('Foodshop/order', array('store_id' => $now_store['store_id'], 'type' => 'price', 'sort' => 'DESC', 'status' => $status))}">订单总价<i class="menu-icon fa fa-sort"></i></a>
										</if>
									<elseif condition="false"  />
										<a href="{pigcms{:U('Foodshop/order', array('store_id' => $now_store['store_id'], 'type' => 'price', 'sort' => 'DESC', 'status' => $status))}">订单总价<i class="menu-icon fa fa-sort"></i></a>
									</if>
									</th>
									<th width="80" class="button-column">查看订单详情</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$order_list">
									<volist name="order_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.real_orderid}</td>
											<td>{pigcms{$vo.name}</td>
											<td>{pigcms{$vo.phone}</td>
											<td class="button-column">{pigcms{$vo.book_price|floatval}</td>
											<if condition="$vo['book_time']">
											<td>{pigcms{$vo.book_time|date='Y-m-d H:i:s',###}</td>
											<else />
											<td>--</td>
											</if>
											<td class="button-column">{pigcms{$vo.table_type_name}</td>
											<td class="button-column">{pigcms{$vo.table_name}</td>
											<td class="button-column">{pigcms{$vo.show_status}</td>
											<if condition="$vo['status'] gt 2">
											<td class="button-column" style="color:green;">{pigcms{$vo.price|floatval}</td>
											<else />
											<td class="button-column" style="color:red;">还未买单</td>
											</if>
											<td class="button-column">
												<a title="操作订单" class="green handle_btn" href="{pigcms{:U('Foodshop/order_detail', array('order_id' => $vo['order_id'], 'store_id' => $vo['store_id']))}">
													<i class="ace-icon fa fa-search bigger-130"></i>
												</a>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="30" >您的店铺暂时还没有订单。</td></tr>
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
<script type="text/javascript" src="./static/js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="./static/js/artdialog/iframeTools.js"></script>
<script>
$(function(){
	$('.handle_btn').live('click',function(){
		art.dialog.open($(this).attr('href'),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: 'handle',
			title:'操作订单',
			padding: 0,
			width: 720,
			height: 520,
			lock: true,
			resize: false,
			background:'black',
			button: null,
			fixed: false,
			close: null,
			left: '50%',
			top: '38.2%',
			opacity:'0.4'
		});
		return false;
	});
	// $('#status').change(function(){
		// location.href = "{pigcms{:U('Foodshop/order', array('store_id' => $now_store['store_id'], 'type' => $type, 'sort' => $sort))}&status=" + $(this).val();
	// });	
});
   var url = "{pigcms{$config.site_url}"
    var export_url = "{pigcms{:U('Foodshop/export')}"
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/export.js"> </script>
<include file="Public:footer"/>

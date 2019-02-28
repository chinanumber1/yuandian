<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Shop/index')}">{pigcms{$config.shop_alias_name}管理</a>
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
						<form action="{pigcms{:U('Shop/sort_order')}" method="get">
							<input type="hidden" name="c" value="Shop"/>
							<input type="hidden" name="a" value="sort_order"/>
							<input type="hidden" name="store_id" value="{pigcms{$_GET.store_id}"/>
							
							商品分类:
							<select name="sort_id">
							    <volist name="sort_list" id="sort">
								<option value="{pigcms{$sort['sort_id']}" <if condition="$sort['sort_id'] eq $sortId">selected="selected"</if>>{pigcms{$sort['sort_name']}</option>
								</volist>
							</select>
							<font color="#000">日期筛选：</font>
							<input type="text" class="input-text" name="stime" style="width:200px;" id="d4311"  value="{pigcms{$_GET.stime}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>			   
							<input type="text" class="input-text" name="etime" style="width:200px;" id="d4311" value="{pigcms{$_GET.etime}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>
							<input type="submit" value="查询" class="button"/>　
							<a href="{pigcms{:U('Shop/sort_export', $_GET)}" class="btn btn-success" style="float:right;margin-right: 10px;">导出数据</a>
						</form>
					</div>
					<div id="shopList" class="grid-view">
								<div class="alert alert-info" style="margin:10px 0;">
									商品销量总数:<b style="color: red">{pigcms{$total_num}</b>，商品单价总金额（是商品单价的总和）:<b style="color: red">￥{pigcms{$total_price|floatval}</b>，商品进价总和:<b style="color: red">￥{pigcms{$cost_price|floatval}</b>
								</div>
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th id="shopList_c1" width="50">商品名称</th>
									<th id="shopList_c1" width="50">商品属性</th>
									<th id="shopList_c1" width="50">数量</th>
									<th id="shopList_c0" width="80">单价</th>
									<th id="shopList_c0" width="80">销售时间</th>
									<th id="shopList_c0" width="80">查看订单详情</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$order_list">
									<volist name="order_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td><div class="tagDiv">{pigcms{$vo.name}</div></td>
											<td><div class="tagDiv">{pigcms{$vo.spec}</div></td>
											<td><div class="tagDiv">{pigcms{$vo.num}</div></td>
											<td><div class="shopNameDiv"><if condition="$vo['discount_price'] gt 0">{pigcms{$vo.discount_price|floatval}<else />{pigcms{$vo.price|floatval}</if></div></td>
											<td><div class="shopNameDiv">{pigcms{$vo.create_time|date="Y-m-d H:i:s",###}</div></td>
											<td>
											<a title="操作订单" class="green handle_btn" href="{pigcms{:U('Shop/order_detail',array('order_id'=>$vo['order_id']))}">
												<i class="ace-icon fa fa-search bigger-130"></i>
											</a>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="30" >暂无数据</td></tr>
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
});
</script>
<include file="Public:footer"/>
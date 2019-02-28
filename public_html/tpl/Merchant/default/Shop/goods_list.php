<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Shop/index')}">{pigcms{$config.shop_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Shop/goods_sort',array('store_id'=>$now_store['store_id']))}">分类列表</a></li>
			<li class="active">{pigcms{$now_sort.sort_name}</li>
			<li class="active">商品列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<div class="col-xs-12">
					<div class="clearfix">
						<button class="btn btn-success" onclick="CreateShop()" style="float:left;">添加商品</button>
						<form action="{pigcms{:U('Shop/goods_list')}" method="get" style="float:left;margin-left:10px">
							<input type="hidden" name="c" value="Shop"/>
							<input type="hidden" name="a" value="goods_list"/>
							<input type="hidden" name="sort_id" value="{pigcms{$now_sort.sort_id}"/>
							商品名称: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<button class="btn btn-success" >查询</button>
						</form>
					</div>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="50">编号</th>
									<th width="50">排序</th>
									<th class="button-column">商品名称</th>
									<th width="100">价格</th>
									<th width="100">二维码</th>
									<th class="button-column" style="width:100px;">单位</th>
									<th width="50">原始库存</th>
									<th width="50">实际库存</th>
									<th width="50">今日销量</th>
									<th width="50">总销量</th>
									<th class="button-column" style="width:180px;">最后操作时间</th>
									<th class="button-column" style="width:100px;">归属打印机</th>
									<th width="100" class="button-column">状态</th>
									<th width="150" class="button-column">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$goods_list">
									<volist name="goods_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.goods_id}</td>
											<td>{pigcms{$vo.sort}</td>
											<td>{pigcms{$vo.name}</td>
											<td>{pigcms{$vo.price|floatval}</td>
											<td><a href="{pigcms{:U('see_goods_qrcode',array('code'=>$vo['goods_id']))}" class="see_qrcode">二维码</a></td>
											<td class="button-column">{pigcms{$vo.unit}</td>
											<if condition="$vo['original_stock'] eq -1">
											<td>无限</td>
											<else />
											<td>{pigcms{$vo.original_stock}</td>
											</if>
											<td>{pigcms{$vo.stock_num_t}</td>
											<td>{pigcms{$vo.today_sell_count}</td>
											<td>{pigcms{$vo.sell_count}</td>
											<td class="button-column">{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</td>
											<td class="button-column">{pigcms{$vo.print_name}</td>
											<td class="button-column">
												<label class="statusSwitch" style="display:inline-block;">
													<input name="switch-field-1" class="ace ace-switch ace-switch-6" type="checkbox" data-id="{pigcms{$vo.goods_id}" <if condition="$vo['status'] eq 1">checked="checked" data-status="OPEN"<else/>data-status="CLOSED"</if>/>
													<span class="lbl"></span>
												</label>
											</td>
											<td class="button-column">
												<a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('Shop/goods_edit',array('goods_id'=>$vo['goods_id'],'page'=>$_GET['page']))}">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>
												<a title="删除" class="red" style="padding-right:8px;" href="{pigcms{:U('Shop/goods_del',array('goods_id'=>$vo['goods_id']))}">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
												<if condition="empty($vo['original_goods_id']) && $config['is_open_market'] && $merchant_menu[110]['menu_list'][10008]">
													<a title="发布商品到市场" class="green" style="padding-right:8px;" href="{pigcms{:U('Shop/goods_push',array('goods_id'=>$vo['goods_id']))}">发布</a>
												</if>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="13" >无内容</td></tr>
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
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
$(function(){
	/*店铺状态*/
	updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "shopstatus");
	
	jQuery(document).on('click','#shopList a.red',function(){
		if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
	});
	
	$('.see_qrcode').click(function(){
		art.dialog.open($(this).attr('href'),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
				
			},
			id: 'handle',
			title:'查看商品二维码',
			padding: 0,
			width: 430,
			height: 433,
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
function CreateShop(){
	window.location.href = "{pigcms{:U('Shop/goods_add',array('sort_id' => $now_sort['sort_id']))}";
}
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
			url:"{pigcms{:U('Shop/goods_status')}",
			type:"post",
			data:{"type":type,"id":id,"status1":status1,"status2":status2,"attribute":attribute},
			dataType:"text",
			success:function(d){
				if(d != '1'){		//失败
					if (type=='open') {
						_this.attr("checked",false);
					} else {
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

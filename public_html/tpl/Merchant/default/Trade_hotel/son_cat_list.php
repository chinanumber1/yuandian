<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('index')}">酒店管理</a>
			</li>
			<li class="active">{pigcms{$now_cat.cat_name} - 子类别列表</li>
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
					<button class="btn btn-success" onclick="CreateShop()">新建分类</button>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>编号</th>
									<th>排序</th>
									<th>分类名称</th>
									<th>入住时间</th>
									<th>发票信息</th>
									<th>退款信息</th>
									<th>商家编码</th>
									<th>价格库存设置</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$cat_list">
									<volist name="cat_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.cat_id}</td>
											<td>{pigcms{$vo.cat_sort}</td>
											<td>{pigcms{$vo.cat_name}</td>
											<td>{pigcms{$vo.enter_time}</td>
											<td><if condition="$vo['has_receipt']">支持<else/>不支持</if></td>
											<td>
												<if condition="$vo['has_refund'] eq 0">任意退<elseif condition="$vo['has_refund'] eq 1"/>不能退<else/>入住时间{pigcms{$vo.refund_hour}小时前内能退</if>
											</td>
											<td>{pigcms{$vo.code}</td>
											<td>
												<a class="green" style="padding-right:8px;" title="管理链接" href="{pigcms{:U('cat_stock',array('cat_id'=>$vo['cat_id'],'cat_fid'=>$vo['cat_fid']))}">管理链接<i class="ace-icon fa fa-pencil bigger-130"></i></a>
												
											</td>
											<td>
												<a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('son_category_edit',array('cat_id'=>$vo['cat_id'],'cat_fid'=>$vo['cat_fid']))}">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>　　
												<a title="删除" class="red" style="padding-right:8px;" href="{pigcms{:U('son_category_del',array('cat_id'=>$vo['cat_id']))}">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="8" >无内容</td></tr>
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
			title:'批量添加库存',
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
	$('#status').change(function(){
		location.href = "{pigcms{:U('Shop/order', array('store_id' => $now_store['store_id'], 'type' => $type, 'sort' => $sort))}&status=" + $(this).val();
	});	
});
</script>
<script type="text/javascript">
$(function(){
	jQuery(document).on('click','#shopList a.red',function(){
		if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
	});
});
function CreateShop(){
	window.location.href = "{pigcms{:U('son_category_add',array('cat_id'=>$now_cat['cat_id']))}";
}
</script>
<include file="Public:footer"/>

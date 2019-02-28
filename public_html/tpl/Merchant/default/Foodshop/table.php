<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Foodshop/index')}">{pigcms{$config.meal_alias_name}管理</a>
			</li>
			<li class="active">{pigcms{$now_store.name}</li>
			<li class="active">桌台管理</li>
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
					<div class="tabbable">
						<ul class="nav nav-tabs" id="myTab">
							<li <if condition="$showTab eq 0">class="active"</if>>
								<a data-toggle="tab" href="#table_type">桌台分类列表</a>
							</li>
							<li <if condition="$showTab eq 1">class="active"</if>>
								<a data-toggle="tab" href="#table">桌台列表</a>
							</li>
						</ul>
					</div>
					<div class="tab-content">
						<div id="table_type" class="tab-pane <if condition="$showTab eq 0">active</if>">
							<a class="btn btn-success" href="{pigcms{:U('Foodshop/type_add', array('store_id' => $now_store['store_id']))}">新建桌台分类</a>
							<div id="shopList" class="grid-view">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th>分类ID</th>
											<th>分类名</th>
											<th>桌台数</th>
											<th>容纳最少人数</th>
											<th>容纳最多人数</th>
											<th>预订金</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
										<if condition="$table_types">
											<volist name="table_types" id="vo">
												<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
													<td>{pigcms{$vo.id}</td>
													<td>{pigcms{$vo.name}</td>
													<td>{pigcms{$vo.num}</td>
													<td>{pigcms{$vo.min_people}</td>
													<td>{pigcms{$vo.max_people}</td>
													<td>{pigcms{$vo.deposit|floatval}</td>
													<td>
														<a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('Foodshop/type_edit',array('id' => $vo['id'], 'store_id' => $vo['store_id']))}">
															<i class="ace-icon fa fa-pencil bigger-130"></i>
														</a>
														<a title="删除" class="red" style="padding-right:8px;" href="{pigcms{:U('Foodshop/type_del',array('id' => $vo['id'], 'store_id' => $vo['store_id']))}">
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
						<div id="table" class="tab-pane <if condition="$showTab eq 1">active</if>">
							<a class="btn btn-success" href="{pigcms{:U('Foodshop/table_add', array('store_id' => $now_store['store_id']))}">新建桌台</a>
							<div id="shopList" class="grid-view">
								<table class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<th>编号ID</th>
											<th>桌台类型</th>
											<th>桌台名</th>
											<th>查看二维码</th>
                                            <th>归属店员</th>
											<th>操作</th>
										</tr>
									</thead>
									<tbody>
										<if condition="$tables">
											<volist name="tables" id="vo">
												<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
													<td>{pigcms{$vo.id}</td>
													<td>{pigcms{$vo.type_name}</td>
													<td>{pigcms{$vo.name}</td>
													<td>
														<a href="{pigcms{:U('Foodshop/url_qrcode',array('pigcms_id'=>$vo['id'], 'store_id' => $vo['store_id']))}" class="see_qrcode">查看二维码</a>
													</td>
                                                    <td>{pigcms{$vo.staff_name}</td>
													<td>
														<a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('Foodshop/table_edit',array('id' => $vo['id'], 'store_id' => $vo['store_id']))}">
															<i class="ace-icon fa fa-pencil bigger-130"></i>
														</a>
														<a title="删除" class="red" style="padding-right:8px;" href="{pigcms{:U('Foodshop/table_del',array('id' => $vo['id'], 'store_id' => $vo['store_id']))}">
															<i class="ace-icon fa fa-trash-o bigger-130"></i>
														</a>
													</td>
												</tr>
											</volist>
										<else/>
											<tr class="odd"><td class="button-column" colspan="6" >无内容</td></tr>
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
	</div>
</div>
<script type="text/javascript">
$(function(){
	jQuery(document).on('click','a.red',function(){
		if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
	});
});
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	$(function(){
		$('.see_qrcode').click(function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'查看渠道二维码',
				padding: 0,
				width: 395,
				height: 395,
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
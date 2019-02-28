<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-file-excel-o"></i>
				<a href="{pigcms{:U('Weixin/article')}">素材管理</a>
			</li>
			<li>素材列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<div>
						<if condition="in_array(191,$house_session['menus'])">
							<a href="{pigcms{:U('Weixin/one')}" class="btn btn-success">新建单图文素材</a>　
							<a href="{pigcms{:U('Weixin/multi')}" class="btn btn-success">新建多图文素材</a>
						<else/>
							<button type="button" class="btn btn-success" disabled="disabled">新建单图文素材</button>
							<button type="button" class="btn btn-success" disabled="disabled">新建多图文素材</button>
						</if>
					</div>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th id="shopList_c1" width="100">标题</th>
									<th id="shopList_c1" width="100">创建时间</th>
									<th id="shopList_c11" width="180">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$list">
									<volist name="list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>
											<volist name="vo['list']" id="row">
											<a href="{pigcms{$config['site_url']}/wap.php?c=Article&a=hdetail&imid={pigcms{$row['pigcms_id']}" target="_blank">{pigcms{$row['title']}</a> 阅读量:{pigcms{$row['read_quantity']}<br/>
											</volist>
											</td>
											<td>{pigcms{$vo.dateline}</td>
											<td class="button-column" nowrap="nowrap">
												<if condition="empty($vo['type'])">
												<a title="修改" class="green" style="padding-right:8px;" href="{pigcms{:U('Weixin/one',array('pigcms_id'=>$vo['pigcms_id']))}">
													<i class="ace-icon fa fa-pencil bigger-130"></i>
												</a>
												</if>
												<if condition="in_array(193,$house_session['menus'])">
												<a title="删除" class="red" style="padding-right:8px;" href="{pigcms{:U('Weixin/del_image',array('pigcms_id'=>$vo['pigcms_id']))}">
													<i class="ace-icon fa fa-trash-o bigger-130"></i>
												</a>
												</if>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="3" >无内容</td></tr>
								</if>
							</tbody>
						</table>
						{pigcms{$page}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
$(function(){
	jQuery(document).on('click','#shopList a.red',function(){
		if(!confirm('确定要删除这条数据吗?不可恢复。')) return false;
	});
});
</script>
<include file="Public:footer"/>

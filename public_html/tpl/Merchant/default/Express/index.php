<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-truck"></i>
				<a href="{pigcms{:U('Express/index')}">运费模板管理</a>
			</li>
			<li>运费模板列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
		
			<div class="app">
				<div class="app-inner clearfix">
					<div class="app-init-container">
						<div class="freight-wrap">
							<div class="freight-list">
								<div class="freight-head">
									<a href="{pigcms{:U('Express/add')}" class="btn btn-success">新建运费模板</a>
								</div>
								<div class="freight-content">
									<div class="freight-template-list-wrap">
										<ul>
											<volist name="template_list" id="tlist">
											<li class="freight-template-item">
												<h4 class="freight-template-title">
													<b>{pigcms{$tlist['name']}</b>  
													<div class="right">
														<span class="c-gray">最后编辑时间 {pigcms{$tlist['dateline']|date='Y-m-d H:i:s',###}</span>&nbsp;&nbsp;
<!-- 														<a href="javascript:;" class="js-freight-copy" tpl-id="{pigcms{$tlist['id']}">复制模板</a> - -->
														<a href="javascript:;" data-href="{pigcms{:U('Express/edit', array('tid' => $tlist['id']))}" class="js-freight-edit" tpl-id="{pigcms{$tlist['id']}">修改</a> -
														<a href="javascript:;" class="js-freight-delete" tpl-id="{pigcms{$tlist['id']}">删除</a>
														<a href="javascript:;" class="js-freight-extend-toggle freight-extend-toggle freight-extend-toggle-extend"></a>
													</div>
												</h4>
												<table class="freight-template-table hide">
													<thead class="js-freight-cost-list-header">
														<tr>
															<th>可配送至</th>
															<th>运费</th>
															<th>满免（满多少元免邮费, 0表示不免邮）</th>
														</tr>
													</thead>
													<tbody>
														<volist name="tlist['value_list']" id="vlist">
														<tr>
															<td class="">
																<volist name="vlist['area_list']" id="vo">
																<span class="text-depth">{pigcms{$vo['area_name']}</span>,
																</volist>
															</td>
															<td>{pigcms{$vlist['freight']|floatval}</td>
															<td>{pigcms{$vlist['full_money']|floatval}</td>
														</tr>
														</volist>
													</tbody>
												</table>
											</li>
											</volist>
										</ul>
									</div>
								</div>
								{pigcms{$pagebar}
								<!--div class="js-page-list ui-box pagenavi"><span class="total">共 3 条，每页 15 条</span> </div-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>var delete_url = "{pigcms{:U('Express/delete')}", load_url = '';</script>
<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/delivery.js" charset="utf-8"></script>
<include file="Public:footer"/>
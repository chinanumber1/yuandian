<include file="Public:header"/>

<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-truck"></i>
				<a href="{pigcms{:U('Express/index')}">运费模板管理</a>
			</li>
			<li>模板编辑</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="app">
				<div class="app-inner clearfix">
					<div class="app-init-container">
						<div class="freight-wrap">
							<style type="text/css">
							.city{
								margin-left:20px;
							}
							.city ul li{padding:5px;color:grey;}
							.select_city{
								background-color: #d7d7d7;
							}
							</style>
							<div>
								<form class="form-horizontal freight-add-form" onkeypress="return event.keyCode != 13;" tpl-id="{pigcms{$template['id']}">
									<div class="control-group">
										<label class="control-label">
											模板名称：
										</label>
										<div class="controls">
											<input type="text" value="{pigcms{$template['name']}" name="name">
										</div>
									</div>
							
									<div class="control-group">
										<label class="control-label">
											配送区域：
										</label>
										<div class="controls">
											<table class="freight-template-table freight-template-item">
												<thead class="js-freight-cost-header freight-template-title">
													<tr>
														<th>可配送区域</th>
														<th>运费</th>
														<th>满免（满多少元免邮费, 0表示不免邮）</th>
													</tr>
												</thead>
												<tbody>
													<volist name="template['value_list']" id="vlist">
													<tr data-vid="{pigcms{$vlist['id']}">
														<td>
															<volist name="vlist['area_list']" id="vo">
															<span area-id="{pigcms{$vo['area_id']}" class="text-depth">{pigcms{$vo['area_name']}</span>、
															</volist>
															<div class="right"><a href="javascript:;" class="js-edit-cost-item">编辑</a> <a href="javascript:;" class="js-delete-cost-item">删除</a></div>
														</td>
														<td><input type="text" value="{pigcms{$vlist['freight']}" class="cost-input js-input-currency" name="freight" maxlength="8"></td>
														<td><input type="text" value="{pigcms{$vlist['full_money']}" class="cost-input js-input-currency" name="full_money" data-default="0" maxlength="5"></td>
													</tr>
													</volist>
												</tbody>
												<tfoot class="js-freight-tablefoot" style="display:table-footer-group;">
													<tr>
														<td><a href="javascript:;" class="js-assign-cost">指定可配送区域和运费</a></td>
														<td></td>
														<td></td>
													</tr>
												</tfoot>
											</table>
										</div>
									</div>
									<div class="control-group">
										<div class="controls">
											<button type="button" class="tbtn btn-primary btn-wide js-save-btn">保存</button>&nbsp;&nbsp;<a href="{pigcms{:U('Express/index')}" class="tbtn btn-wide">返回</a>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>var load_url = "{pigcms{:U('Express/index')}", add_url = "{pigcms{:U('Express/save')}";</script>
<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/area.min.js" charset="utf-8"></script>
<script type="text/javascript" src="{pigcms{$static_path}js/delivery.js" charset="utf-8"></script>
<include file="Public:footer"/>
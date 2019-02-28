<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Shop/index')}">店铺管理</a>
			</li>
			<li class="active">店铺优惠列表</li>
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
					<a class="btn btn-success" title="新建优惠" href="{pigcms{:U('Shop/discount_add', array('store_id' => $store['store_id']))}">新建优惠</a>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
								<th>编号</th>
								<th>类别</th>
								<th>满足金额</th>
								<th>优惠金额</th>
								<th>是否与限时优惠、店铺/分类折扣、会员优惠同享</th>
								<th>使用状态</th>
								<th class="textcenter">操作</th>
								</tr>
							</thead>
							<tbody>
							<if condition="is_array($discount_list)">
								<volist name="discount_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td><if condition="$vo['type'] eq 0">新单<elseif condition="$vo['type'] eq 1" />满减<else />配送</if></td>
										<td>{pigcms{$vo.full_money}</td>
										<td>{pigcms{$vo.reduce_money}</td>
										<td><if condition="$vo['is_share'] eq 1"><font color="green">同享</font><else/><font color="red">不同享</font></if></td>
										<td><if condition="$vo['status'] eq 1"><font color="green">启用</font><else/><font color="red">停用</font></if></td>
										<td class="button-column">
											<a style="width:80px;" class="label label-sm label-info" href="{pigcms{:U('Shop/discount_edit',array('store_id'=>$vo['store_id'], 'id' => $vo['id']))}">编辑</a>
										</td>
									</tr>
								</volist>
							<else/>
								<tr><td class="textcenter red" colspan="6">列表为空！</td></tr>
							</if>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<include file="Public:footer"/>

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
			<li class="active">商品销量统计</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<form id="myform" method="post" action="{pigcms{:U('Foodshop/total', array('store_id' => $now_store['store_id']))}" >
					<div class="form-group">
						<label class="col-sm-1">商品分类：</label>
						<select id="sort_id" name="sort_id">
							<option value="0" <if condition="0 eq $sort_id">selected="selected"</if>>全部</option>
							<volist name="sort_list" id="vo">
								<option value="{pigcms{$vo['sort_id']}" <if condition="$vo['sort_id'] eq $sort_id">selected="selected"</if>>{pigcms{$vo['sort_name']}</option>
							</volist>
						</select>
					</div>
					<div class="form-group">
						<label class="col-sm-1">开始结束时间 ：</label>
		                <input type="text" class="input fl" name="begin_time" style="width:160px;" value="{pigcms{$begin_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>&nbsp;&nbsp;&nbsp;
		                <input type="text" class="input fl" name="end_time" style="width:160px;" value="{pigcms{$end_time}" onfocus="WdatePicker({isShowClear:false,readOnly:true,dateFmt:'yyyy-MM-dd HH:mm:ss'})"/>&nbsp;&nbsp;&nbsp;
						<input type="submit" value="查看" id="search">
					</div>
					</form>
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="50">分类名称</th>
									<th width="80">商品名称</th>
									<th width="80">销量</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$goods_list">
									<volist name="goods_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.sort_name}</td>
											<td>{pigcms{$vo.name}</td>
											<td>{pigcms{$vo.num|floatval}</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="3" >暂时没有销量统计。</td></tr>
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
	$('#search').click(function(){
// 		location.href = "{pigcms{:U('Foodshop/total', array('store_id' => $now_store['store_id']))}&sort_id=" + $('#sort_id').val() + '&begin_time=' + $('#begin_time').val() + '&end_time=' + $('#end_time').val();
	});	
});
</script>
<include file="Public:footer"/>

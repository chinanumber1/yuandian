<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-cubes"></i>
				<a href="{pigcms{:U('Foodshop/index')}">{pigcms{$config.meal_alias_name}管理</a>
			</li>
			<li class="active"><a href="{pigcms{:U('Foodshop/package', array('store_id' => $now_store['store_id']))}">{pigcms{$now_store['name']}</a></li>
			<li class="active">查看套餐菜品详情</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-xs-12">
					<a class="btn btn-info" href="javascript:history.go(-1);"><i class="ace-icon fa fa-reply bigger-110"></i>返回</a>
					<div class="topic_box" style="border-top: 0;">
						<volist name="package['goods_detail']" id="goods_detail" key="out">
						<div class="question_box spec">
							<p class="question_info"><span>可选数：</span>
								<input type="text" class="txt" value="{pigcms{$goods_detail['num']}" disabled/>
							</p>
							<div class="optionul_r">
								<if condition="!empty($goods_detail['goods_list'])">
								<table class="table table-striped table-bordered table-hover">
									<tr>
										<td>菜品名称</td>
										<td>菜品价格</td>
										<!--td>规格</td-->
									</tr>
									<volist name="goods_detail['goods_list']" id="detail">
									<tr>
										<td>{pigcms{$detail['name']}</td>
										<td>{pigcms{$detail['price']|floatval}</td>
									</tr>
									</volist>
								</table>
								</if>
							</div>
						</div>
						</volist>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<link rel="stylesheet" href="{pigcms{$static_path}css/package.css">
<include file="Public:footer"/>
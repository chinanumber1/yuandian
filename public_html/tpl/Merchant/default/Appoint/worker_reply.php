<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-comments-o comments-o-icon"></i>
			<li class="active">预约管理</li>
			<li><a href="{pigcms{:U('Appoint/worker_reply')}">工作人员评论列表</a></li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="form-group">
					<select id="worker_id" name="worker_id">
						<option value="0">全部工作人员</option>
						<volist name="worker_list" id="vo">
							<option value="{pigcms{$key}" <if condition="$_GET['merchant_worker_id'] eq $key">selected="selected"</if>>{pigcms{$vo}</option>
						</volist>
					</select>
				</div>
				<div class="col-xs-12" style="padding-left:0px;padding-right:0px;">
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th width="10%">顾客ID</th>
									<th width="10%">订单号</th>
									<th>评论内容</th>
									<th width="10%">评论时间</th>
									<th width="10%">评论打分</th>
									<th width="10%">是否回复</th>
                                    <th width="10%">审核状态</th>
									<th class="button-column" width="10%">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$reply_list">
									<volist name="reply_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.uid}</td>
											<td>{pigcms{$vo.order_id}</td>
											<td>{pigcms{$vo.content|msubstr=###,0,50}</td>
											<td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
											<td>{pigcms{$vo.all_avg_score|intval}</td>
											<td><if condition="$vo['merchant_reply_time']">已回复<else/>未回复</if></td>
                                            <td><if condition="$vo['status'] == 0"><span class="red">未审核</span><elseif condition="$vo['status'] == 1" /><span class="green">审核通过</span><else/><span class="red">审核不通过</span></if></td>
											<td><a style="width: 60px;" class="label label-sm label-info" title="评论列表" href="{pigcms{:U('Appoint/worker_reply_detail',array('id'=>$vo['id']))}">评论详情</a></td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="7" >暂无评论！</td></tr>
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
<script type="text/javascript">
	$(function(){
		$('#worker_id').change(function(){
			var worker_id=$(this).val();
			var Url="{pigcms{:U('worker_reply')}"+"&merchant_worker_id="+worker_id;
			location.href=Url;
		});
	});
</script>
<include file="Public:footer"/>

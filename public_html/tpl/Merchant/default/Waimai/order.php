<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-comments-o comments-o-icon"></i>
			<li class="active"><a href="{pigcms{:U('Waimai/order')}">外卖管理</a></li>
			<!-- <li>{pigcms{$now_group.appoint_name}</li> -->
			<li>订单列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<!-- <form id="frmselect" method="get" action="" style="margin-bottom:0;">
					<input type="hidden" name="c" value="Group"/>
					<input type="hidden" name="a" value="order_list"/>
					<select id="group_id" name="group_id">
						<volist name="group_list" id="vo">
							<option value="{pigcms{$vo.group_id}" <if condition="$_GET['group_id'] eq $vo['group_id']">selected="selected"</if>>{pigcms{$vo.s_name}</option>
						</volist>
					</select>
				</form> -->
				<div class="col-xs-12" style="padding-left:0px;padding-right:0px;">
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>ID</th>
									<th>用户昵称</th>
									<th>价格</th>
									<th>订单号</th>
									<th>时间</th>
									<th>收货</th>
									<th>支付状态</th>
									<th>订单状态</th>
									<th>评论状态</th>
									<th class="button-column">操作</th>
								</tr>
							</thead>
							<tbody>
								<?php if(!empty($order_list)): ?>
								<volist name="order_list" id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										<td width="30">{pigcms{$vo.order_id}</td>
										<td width="100">{pigcms{$vo.nickname}</td>
										<td width="120">
											&nbsp;&nbsp;&nbsp;总价：￥ {pigcms{$vo.price}<br/>
											优惠后：￥ {pigcms{$vo.discount_price}
										</td>
										<td width="100">{pigcms{$vo.order_number}</td>
										<td width="130">
											创建时间：<br/>{pigcms{$vo.create_time}<br/>
											<if condition="$vo['pay_time']">支付时间：<br/>{pigcms{$vo.pay_time|date="Y-m-d H:i:s",###}</if>
										</td>
										<td width="150">
											<if condition="$vo['code']">收货码：{pigcms{$vo.code}</if><br/>
											<if condition="$vo['book_send_time']">预约配送时间：<br/>{pigcms{$vo.book_send_time|date="Y-m-d H:i:s",###}</if><br/>
											<if condition="$vo['order_send_time']">订单送达时间：<br/>{pigcms{$vo.order_send_time|date="Y-m-d H:i:s",###}</if>
										</td>
										<td width="120">
											<if condition="$vo['paid'] eq 0"><span style="color:blue">未支付</span>
											<elseif condition="$vo['paid'] eq 1" /><span style="color:green">已支付</span>
											<elseif condition="$vo['paid'] eq 3" /><span style="color:red">支付超时或失败</span>
											</if>
										</td>
										<td width="90">
											<if condition="$vo['order_status'] eq 0"><span style="color:red">订单失效</span>
											<elseif condition="$vo['order_status'] eq 1" /><span style="color:blue">订单完成</span>
											<elseif condition="$vo['order_status'] eq 2" /><span style="color:blue">商家未确认</span>
											<elseif condition="$vo['order_status'] eq 3" /><span style="color:green">商家已确认</span>
											<elseif condition="$vo['order_status'] eq 4" /><span style="color:green">订单已配送</span>
											<elseif condition="$vo['order_status'] eq 5" /><span style="color:green">正在配送</span>
											<elseif condition="$vo['order_status'] eq 6" /><span style="color:red">用户退单</span>
											</if>
										</td>
										<td width="90">
											<if condition="$vo['comment_status'] eq 0"><span style="color:blue">未评论</span>
											<elseif condition="$vo['comment_status'] eq 1" /><span style="color:blue">已评论</span>
											</if>
										</td>
										<td class="button-column" width="40">
											<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Waimai/order_detail',array('order_id'=>$vo['order_id']))}">
												<i class="ace-icon fa fa-search bigger-130"></i>
											</a>
										</td>
									</tr>
								</volist>
								<?php else : ?>
									<tr><td colspan="11" style="color:red;text-align:center;">暂无订单。</td></tr>
								<?php endif; ?>
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
				title:'操作订单',
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
		
		$('#group_id').change(function(){
			$('#frmselect').submit();
		});
	});
</script>
<include file="Public:footer"/>

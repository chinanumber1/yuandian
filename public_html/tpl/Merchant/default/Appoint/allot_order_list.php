<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-comments-o comments-o-icon"></i>
			<li class="active"><a href="{pigcms{:U('Appoint/index')}">预约列表</a></li>
			<!--<li>{pigcms{$now_group.appoint_name}</li>-->
			<li>订单列表</li>
		</ul>
	</div>

	<!--style type="text/css">
                    #shopList .table-striped{table-layout:fixed;word-break:keep-all;             /* 不换行 */
white-space:nowrap;            /* 不换行 */
overflow:hidden;                  /* 内容超出宽度时隐藏超出部分的内容 */
text-overflow:ellipsis;  }
                #shopList .table-striped td{width:100%;
word-break:keep-all;             /* 不换行 */
white-space:nowrap;            /* 不换行 */
overflow:hidden;                  /* 内容超出宽度时隐藏超出部分的内容 */
text-overflow:ellipsis;            /* 当对象内文本溢出时显示省略标记(...) ；需与overflow:hidden;一起使用。*/}
    </style-->
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">

				<div class="col-xs-12" style="padding-left:0px;padding-right:0px;">
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
							<tr>
								<th>订单编号</th>
								<th>定金</th>
								<!--<th>服务类型</th>-->
								<th>用户信息</th>
								<if condition="!C('butt_open')"><th>描述</th></if>
								<th>订单状态</th>
								<th class="button-column">操作</th>
							</tr>
							</thead>
							<tbody>
							<?php if(!empty($order_list)): ?>
								<volist name="order_list" id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
									<td width="70">{pigcms{$vo.order_id}</td>
									<td width="50">￥{pigcms{$vo.payment_money}</td>

									<!--<td>
                                        <if condition="$vo['appoint_type'] eq 0"><span style="color:red">到店</span>
                                        <elseif condition="$vo['appoint_type'] eq 1" /><span style="color:red">上门</span>
                                        </if>
                                    </td>-->
									<td>
										用户ID：{pigcms{$vo.uid}<br/>
										用户名：{pigcms{$vo.nickname}<br/>
										订单手机号：{pigcms{$vo.phone}<br/>
									</td>
									<if condition="!C('butt_open')"><td width="290">{pigcms{$vo.content}</td></if>
									<td>
										<if condition="$vo['paid'] eq 0"><span style="color:red">未支付</span>
											<elseif condition="$vo['paid'] eq 1" /><span style="color:green">已支付</span>
											<elseif condition="$vo['paid'] eq 2" /><span style="color:red">已退款</span>
										</if>


										<if condition='$vo["complete_source"] eq 2' >
											<if condition='$vo["service_status"] eq 1'>
												<font color="green">已服务</font>
												<else />
												<font color="red">技师已服务，用户未付余款</font>
											</if>

											<if condition='$vo["service_status"] eq 0'><a href="{pigcms{:U('appoint_verify',array('order_id'=>$vo['order_id']))}" class="group_verify_btn">验证服务</a></if>
											<elseif condition="$vo['service_status'] == 0"  />
											<font color="red">未服务</font>
											<if condition='$vo["store_id"] && ($vo["is_del"] eq 0)&& ($vo["paid"] eq 1)'><a href="{pigcms{:U('appoint_verify',array('order_id'=>$vo['order_id']))}" class="group_verify_btn">验证服务</a></if>
											<elseif condition="$vo['service_status'] == 1" />
											<font color="green">已服务</font>
											<elseif condition="$vo['service_status'] == 2" />
											<font color="green">已评价</font>
										</if>
										<br/>
										下单时间：{pigcms{$vo['order_time']|date='Y-m-d H:i:s',###}<br/>
										<?php if(empty($vo['paid'])): ?>
											付款时间：无
										<?php else : ?>
											付款时间：<if condition="$vo['pay_time']">{pigcms{$vo['pay_time']|date='Y-m-d H:i:s',###}<else/>无</if><br/>
										<?php endif; ?>
										<if condition='$vo["is_del"] neq 0'>
											<br/>
											<font color="red">
												<switch name='vo["is_del"]'>
													<case value="1">已取消【用户】【PC端】</case>
													<case value="2">已取消【平台】</case>
													<case value="3">已取消【商家】</case>
													<case value="4">已取消【店员】</case>
													<case value="5">已取消【用户】【WAP端】</case>
												</switch>
											</font>
										</if>
									</td>
									<td width="80"  style="text-align:center;">
										<a title="分配店铺" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Appoint/allot_store',array('order_id'=>$vo['order_id']))}">
											<i class="ace-icon fa fa-search bigger-130"></i>
										</a>

										<if condition='($vo["is_del"] eq 0) && ($vo["paid"] eq 0)'>
											<a href="javascript:void(0)" data-order-id="{pigcms{$vo['order_id']}" class="appoint_del" style="padding-right:8px;" class="red" title="取消订单">
												<i class="ace-icon fa fa-times-circle-o bigger-130"></i>
											</a>
										</if>
									</td>
									</tr>
								</volist>
							<?php else : ?>
								<tr><td colspan="<if condition="!C('butt_open')">6<else/>5</if>" style="color:red;text-align:center;">暂无订单。</td></tr>
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
				title:'分配店铺',
				padding: 0,
				width: 720,
				height: 700,
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


	$('#merchant_worker_id').change(function(){
		var merchant_worker_id = $(this).val();
		var Url="{pigcms{:U('Appoint/order_list')}"+'&merchant_worker_id='+merchant_worker_id;
		location.href=Url;
	});

	$('.appoint_del').click(function(){
		var url ="{pigcms{:U('ajax_merchant_del')}";
		var order_id = $(this).data('order-id');

		if(confirm('取消后，将无法恢复，是否确认取消？')){
			$.post(url,{'order_id':order_id},function(data){
				alert(data.msg);
				if(data.status){
					location.reload();
				}
			},'json')
		}

	});

</script>
<include file="Public:footer"/>

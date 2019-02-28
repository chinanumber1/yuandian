<include file="Public:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-comments-o comments-o-icon"></i>
			<li class="active"><a href="{pigcms{:U('Group/index')}">{pigcms{$config.group_alias_name}列表</a></li>
			<if condition="$now_group">
			<li>{pigcms{$now_group.s_name}</li>
			</if>
			<li>订单列表</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<form action="{pigcms{:U('Group/order_list')}" method="get">
					<input type="hidden" name="c" value="Group"/>
					<input type="hidden" name="a" value="order_list"/>
					
					
					搜索: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
					<select name="searchtype">
						<option value="real_orderid" <if condition="$_GET['searchtype'] eq 'real_orderid'">selected="selected"</if>>订单编号</option>
						<option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>支付流水号</option>
						<option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option>
						<option value="s_name" <if condition="$_GET['searchtype'] eq 's_name'">selected="selected"</if>>{pigcms{$config.group_alias_name}名称</option>
						<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>客户名称</option>
						<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>客户电话</option>
					</select>
					<font color="#000">日期筛选：</font>
					<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
					<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
					订单状态筛选: 
					<select id="status" name="status" >
						<volist name="status_list" id="vo">
							<option value="{pigcms{$key}" <if condition="$key eq $status">selected="selected"</if>>{pigcms{$vo}</option>
						</volist>
					</select>
					支付方式筛选: 
						<select id="pay_type" name="pay_type">
							<option value="" <if condition="$_GET['pay_type'] eq $pay_type">selected="selected"</if>>全部支付方式</option>
						<volist name="pay_method" id="vo">
							<option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
						</volist>
							<option value="balance" <if condition="$_GET['pay_type'] eq 'balance'">selected="selected"</if>>余额支付</option>
					</select>
					<input type="submit" value="查询" class="button"/>　　
					
					<a class="btn btn-success"  href="javascript:void(0)" onclick="exports()"  style="float:right;">导出订单</a>
				</form>
				<div class="col-xs-12" style="padding-left:0px;padding-right:0px;">
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>订单编号</th>
									<th>{pigcms{$config.group_alias_name}名称</th>
									<th>订单信息</th>
									<th>订单类型</th>
									<th>用户信息</th>
									<th>订单状态</th>
									<th class="button-column">操作</th>
								</tr>
							</thead>
							<tbody>
								<volist name="order_list" id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										<td width="100">{pigcms{$vo.real_orderid}</td>
										<td width="200"><a href="{pigcms{$config.site_url}/index.php?g=Group&c=Detail&group_id={pigcms{$vo.group_id}" target="_blank">{pigcms{$vo.s_name}</a></td>
										<td width="150">
											数量：{pigcms{$vo.num}<br/>
											总价：{pigcms{$vo.total_money}<br/>
										</td>
										<td width="200">
											<switch name="vo['tuan_type']">
												<case value="0">{pigcms{$config.group_alias_name}券</case>
												<case value="1">代金券</case>
												<case value="2">实物</case>
											</switch>
										</td>
										<td width="200">
											用户ID：{pigcms{$vo.uid}<br/>
											用户名：{pigcms{$vo.nickname}<br/>
											<!--用户手机号：{pigcms{$vo.phone}<br/-->
											订单手机号：{pigcms{$vo.group_phone}<br/>
										</td>
										<td width="200">
										<if condition="$vo['status'] eq 3">
												<font color="red">已取消</font>
											<elseif condition="$vo['paid']" />
												<if condition="$vo['pay_type'] eq 'offline' AND empty($vo['third_id'])" >
													<font color="red">线下支付&nbsp;未付款</font>
												<elseif condition="$vo['status'] eq 0" />
													<font color="green">已付款</font>&nbsp;
													<php>if($vo['tuan_type'] != 2){</php>
														<font color="red">未消费</font>
													<php>}else{</php>
														<php>if($vo['is_pick_in_store']){</php>
															<font color="red">未取货</font>
														<php>}else{</php>
																<php>if($vo['express_id']){</php>	
																	<font color="red">未确认收货</font>
																<php>}else{</php>
																	<font color="red">未发货</font>
																<php>}</php>
														<php>}</php>
													<php>}</php>
												<elseif condition="$vo['status'] eq 1"/>
													<php>if($vo['tuan_type'] != 2){</php>
														<font color="green">已消费</font>
													<php>}else{</php>
														<php>if($vo['is_pick_in_store']){</php>
															<font color="green">已取货</font>
														<php>}else{</php>
															<font color="green">已收货</font>
														<php>}</php>
													<php>}</php>&nbsp;
													<font color="red">待评价</font>
												<elseif condition="$vo['status'] eq 3"/>
													<font color="red">已退款</font>
												<elseif condition="$vo['status'] eq 4"/>
													<font color="red">用户已取消</font>
												<else/>
													<font color="green">已完成</font>
												</if>
											<else/>
												<font color="red">未付款</font>
											</if><br/>
											下单时间：{pigcms{$vo['add_time']|date='Y-m-d H:i:s',###}<br/>
											<if condition="$vo['paid']">付款时间：{pigcms{$vo['pay_time']|date='Y-m-d H:i:s',###}</if>
										</td>
										<td class="button-column" width="40">
											<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Group/order_detail',array('order_id'=>$vo['order_id']))}">
												<i class="ace-icon fa fa-search bigger-130"></i>
											</a>
										</td>
									</tr>
								</volist>
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
<script type="text/javascript" src="./static/layer/layer.js"></script>
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
				width: 800,
				height: 620,
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
	   var url = "{pigcms{$config.site_url}"
    var export_url = "{pigcms{:U('Group/export')}"
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/export.js"> </script>
<include file="Public:footer"/>

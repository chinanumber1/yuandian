<include file="Pick:header"/>
<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<li>
				<i class="ace-icon fa fa-gear gear-icon"></i>
				<a href="{pigcms{:U('Pick/index')}">自提点管理</a>
			</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<style>
				.ace-file-input a {display:none;}
			</style>
			<div class="row">
				<form action="{pigcms{:U('Pick/index')}" method="get">
					<input type="hidden" name="c" value="Pick"/>
					<input type="hidden" name="a" value="index"/>
					
					
					搜索: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
					<select name="searchtype">
						<option value="real_orderid" <if condition="$_GET['searchtype'] eq 'real_orderid'">selected="selected"</if>>订单编号</option>
						<option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>订单流水号</option>
						<option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option>
						<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>客户名称</option>
						<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>客户电话</option>
					</select>
					<font color="#000">日期筛选：</font>
					<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
					<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
					<input type="submit" value="查询" class="button"/>　　
					
					
				</form>
				<div class="col-xs-12">
					<div id="shopList" class="grid-view">
						<table class="table table-striped table-bordered table-hover">
							<thead>
								<tr>
									<th>订单号</th>
									<th>客户姓名</th>
									<th>客户电话</th>
									<th>客户地址</th>
									<th>配送类型</th>
									<th>订单总价</th>
									<th>已付金额</th>
									<th>未付金额</th>
									<th>查看订单详情</th>
									<th width="200" style="text-align:center;">操作</th>
								</tr>
							</thead>
							<tbody>
								<if condition="$order_list">
									<volist name="order_list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>{pigcms{$vo.real_orderid}</td>
											<td>{pigcms{$vo.username}</td>
											<td>{pigcms{$vo.userphone}</td>
											<td><if condition="$vo['is_pick_in_store'] eq 1">{pigcms{$vo.address}<else />--</if></td>
											<td><if condition="$vo['is_pick_in_store'] eq 1">配送<else />自提</if></td>
											<td>{pigcms{$vo['price']|floatval}</td>
											<td>{pigcms{$vo['pay_price']|floatval}</td>
											<td>{pigcms{$vo['offline_price']|floatval}</td>
											<td>
												<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Pick/order_detail',array('order_id'=>$vo['order_id']))}">
													<i class="ace-icon fa fa-search bigger-130"></i>
												</a>
											</td>
											<td>
											<if condition="$vo['pstatus'] eq 0">
											<span style="color: green">分配到该配送点</span>
											<elseif condition="$vo['pstatus'] eq 1" />
											<a title="操作订单" class="green edit_btn js-add-order js-add-order-{pigcms{$vo.order_id}" style="padding-right:8px;" href="javascript:;" js-order="{pigcms{$vo.order_id}">接货</a>
											<elseif condition="$vo['pstatus'] eq 2" />
												<if condition="$vo['is_pick_in_store'] eq 2">
												<a title="操作订单" class="green edit_btn js-send-order js-add-order-{pigcms{$vo.order_id}" style="padding-right:8px;" href="javascript:;" js-order="{pigcms{$vo.order_id}">确认提货</a>
												<else />
												<a title="操作订单" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Pick/deliver_user',array('order_id'=>$vo['order_id']))}">分配配送员</a>
												<a title="操作订单" class="red edit_btn js-send-order js-add-order-{pigcms{$vo.order_id}" style="padding-right:8px;" href="javascript:;" js-order="{pigcms{$vo.order_id}">确认提货</a>
												</if>
											
											<elseif condition="$vo['pstatus'] eq 3" />
											<a title="已接单" class="green edit_btn" style="padding-right:8px;" href="javascript:;" >配送中</a>
											<else />
											<span style="color: green">完成</span>
											</if>
											</td>
										</tr>
									</volist>
								<else/>
									<tr class="odd"><td class="button-column" colspan="11" >暂时没有订单！</td></tr>
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
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script type="text/javascript">
	$(function(){
		$('.handle_btn').live('click',function(){
			art.dialog.open($(this).attr('href'),{
				init: function(){
					var iframe = this.iframe.contentWindow;
					window.top.art.dialog.data('iframe_handle',iframe);
				},
				id: 'handle',
				title:'操作',
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
				opacity:'0.4', close: function(){location.reload();}
			});
			return false;
		});
		$('.js-add-order').click(function(){
			var order_id = $(this).attr('js-order');
			$('.js-add-order-'+order_id).html('处理中');
			$.post("{pigcms{:U('Pick/apick')}",{order_id:order_id},function(result){
				if(result.status == 1){
					$('.js-add-order-'+order_id).unbind('click');
					if (result.type == 1) {
						$('.js-add-order-'+order_id).removeClass('js-add-order').addClass('handle_btn').attr('href', "{pigcms{:U('Pick/deliver_user')}&order_id="+order_id).html('分配配送员');
						$('.js-add-order-'+order_id).after('<a class="red edit_btn js-send-order js-add-order-' + order_id + '" style="padding-right:8px;" href="javascript:;" js-order="' + order_id + '">确认提货</a>');
					} else {
						$('.js-add-order-'+order_id).removeClass('js-add-order').addClass('js-send-order').html('确认提货');
					}
				}else{
					bootbox.alert(result.info);
					$('.js-add-order-'+order_id).html('接货');
				}
			}, 'json');
		});
		$('.js-send-order').live('click',function(){
			var obj = $(this);
			var order_id = $(this).attr('js-order');
			obj.html('处理中');
			$.post("{pigcms{:U('Pick/check')}",{order_id:order_id},function(result){
				if(result.status == 1){
// 					$('.js-add-order-'+order_id).unbind('click');
// 					obj.unbind('click');
					obj.parent('td').html('<span style="color: green">完成</span>');
// 					obj.removeClass('js-send-order');
				}else{
					bootbox.alert(result.info);
					obj.html('确认提货');
				}
			});
		});
	});
</script>
<include file="Pick:footer"/>

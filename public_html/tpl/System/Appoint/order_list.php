<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Appoint/product_list')}">预约列表</a>
					<a href="{pigcms{:U('Appoint/order_list', array('appoint_id'=>$now_appoint['appoint_id']))}" class="on">订单列表</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Appoint/order_list')}" method="get">
							<input type="hidden" name="c" value="Appoint"/>
							<input type="hidden" name="a" value="order_list"/>
							<input type="hidden" name="appoint_id" value="{pigcms{$_GET.appoint_id}"/>
							
							搜索: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="order_id" <if condition="$_GET['searchtype'] eq 'order_id'">selected="selected"</if>>订单编号</option>
								<option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>订单流水号</option>
								<option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option>
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>客户名称</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>客户电话</option>
							</select>
							<font color="#000">日期筛选：</font>
							<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>　
							<!--订单状态筛选: 
							<select id="status" name="status">
								
								<option value="1" <if condition="$appoint.service_status eq 1">selected="selected"</if>>已服务</option>
								<option value="0" <if condition="$$appoint.service_status eq 0">selected="selected"</if>>未服务</option>
								
							</select>-->
							支付方式筛选: 
							<select id="pay_type" name="pay_type">
									<option value="" <if condition="'' eq $pay_type">selected="selected"</if>>全部支付方式</option>
								<volist name="pay_method" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
								</volist>
									<option value="balance" <if condition="'balance' eq $pay_type">selected="selected"</if>>余额支付</option>
							</select>
							<input type="submit" value="查询" class="button"/>　
						</form>
					</td>
					<td>
					<a href="javascript:void(0)" onclick="exports()"  class="button" style="float:right;margin-right: 10px;">导出订单</a>
					</td>
				</tr>
			</table>
			<div style="margin:15px 0;">
			<php>if($_GET['appoint_id']){</php>
				<b>商家ID：</b>{pigcms{$now_merchant.mer_id}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>商家名称：</b>{pigcms{$now_merchant.name}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>联系电话：</b>{pigcms{$now_merchant.phone}<br/><br/>
				<b>预约ID：</b>{pigcms{$now_appoint.appoint_id}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>预约名称：</b>{pigcms{$now_appoint.appoint_name}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>预约定金：</b>￥{pigcms{$now_appoint.payment_money|floatval=###}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>预约类型：</b>
				<switch name="now_appoint['appoint_type']">
					<case value="0">到店</case>
					<case value="1">上门</case>
				</switch>
				<php>}</php>
			</div>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>订单信息</th>
								<th>订单用户</th>
								<th>查看用户信息</th>
								<th>订单状态</th>
								<th>服务状态</th>
								<th>支付详情</th>
								<th>时间</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="!empty($order_list)">
								<volist name="order_list" id="vo">
									<tr>
										<td>{pigcms{$vo.order_id}</td>
										<td>
											<if condition='$vo["product_payment_price"] gt 0'>定金：￥ {pigcms{$vo.product_payment_price}<else />定金：￥ {pigcms{$vo.payment_money}</if><br/>
											<if condition='$vo["product_price"] gt 0'>总价：￥ {pigcms{$vo.product_price}<else />总价：￥ {pigcms{$vo.appoint_price}</if><br>
                                            类型：<if condition='$vo["type"] eq 1'><font class="red">自营</font><else/><font style="color:green">商家</font></if>
										</td>
										<td>
											用户昵称：{pigcms{$vo.nickname}<br/>
											手机号：{pigcms{$vo.phone}
										</td>
										<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/edit',array('uid'=>$vo['uid']))}','查看服务详情',680,560,true,false,false,false,'detail',true);">查看用户信息</a></td>
										<td>
											<if condition="$vo['paid'] eq 0"><span style="color:red">未支付</span>
											<elseif condition="$vo['paid'] eq 1" /><span style="color:green">已支付</span>
											<elseif condition="$vo['paid'] eq 2" /><span style="color:blue">已退款</span>
											</if>
											&nbsp;|&nbsp;
											<if condition="$vo['is_del'] eq 0"><span style="color:green">未取消</span>
											<elseif condition="($vo['is_del'] eq 1) || ($vo['is_del'] eq 5)" /><span style="color:red">已取消【用户】</span>
											<elseif condition="$vo['is_del'] eq 2" /><span style="color:red">已取消【平台】</span>
											<elseif condition="$vo['is_del'] eq 3" /><span style="color:red">已取消【商家】</span>
											<elseif condition="$vo['is_del'] eq 4" /><span style="color:red">已取消【店员】</span>
											</if>
										</td>
										<td>
											<if condition="$vo['service_status'] eq 0"><span style="color:red">未服务</span>
											<elseif condition="$vo['service_status'] eq 1" /><span style="color:green">已服务</span>
											<elseif condition="$vo['service_status'] eq 2" /><span style="color:green">已评价</span>
											</if>
										</td>
										<td>
											平台余额支付：{pigcms{$vo['balance_pay']+$vo['product_balance_pay']} <br>
									 		商家会员卡余额支付：{pigcms{$vo['merchant_balance']+$vo['product_card_give_money']+$vo['product_merchant_balance']+$vo['card_give_money']}<br>
									 		在线支付金额：<if condition="$vo['paid'] == 1" >{pigcms{$vo['pay_money']+$vo['product_payment_price']}<else/>0.00</if><br> 
										</td>
										<td>
											下单时间：{pigcms{$vo.order_time|date="Y-m-d H:i:s",###}<br/>
											<if condition="$vo.pay_time gt 0 && $vo.user_pay_time eq 0">付款时间：{pigcms{$vo.pay_time|date="Y-m-d H:i:s",###}</if>
											<if condition="$vo.user_pay_time gt 0 && $vo.pay_time eq 0">付款时间：{pigcms{$vo.user_pay_time|date="Y-m-d H:i:s",###}</if>
										</td>
										<td class="textcenter">
                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/order_detail',array('order_id'=>$vo['order_id'],'frame_show'=>true))}','查看服务详情',660,520,true,false,false,false,'detail',true);">查看详情</a>
											<if condition='($vo["is_del"] eq 0) && ($vo["paid"] eq 0)'>
											&nbsp;|&nbsp;
											<a href="javascript:void(0);" onclick="cancel_order({pigcms{$vo['order_id']})">取消订单</a>
											</if>
									  		<!-- <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/product_detail',array('appoint_id'=>$vo['appoint_id']))}','编辑预约信息',480,<if condition="$vo['appoint_id']">240<else/>340</if>,true,false,false,editbtn,'edit',true);">编辑</a> -->
									  	</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="11">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="11">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
		<script>
		function cancel_order(order_id){
			if(confirm('是否确认取消订单？')){
				var url = "{pigcms{:U('ajax_merchant_del')}";
				$.post(url,{'order_id':order_id},function(data){
					alert(data.msg);
					if(data.status){
						location.reload();
					}
				},'json')
			}
		}
		
		   var url = "{pigcms{$config.site_url}"
    var export_url = "{pigcms{:U('Appoint/export')}"
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/export.js"> </script>
<include file="Public:footer"/>
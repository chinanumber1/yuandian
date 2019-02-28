<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<title>{pigcms{$config.site_name} - 店铺管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/group_edit.css"/>
	</head>
	<body>
		<table>
			<tr>
				<th width="15%">订单编号</th>
				<td width="35%">{pigcms{$now_order.order_id}</td>
				<th width="15%">服务名称</th>
				<td width="35%">{pigcms{$now_order.appoint_name}<if condition='$now_order["product_name"]'>&nbsp;-&nbsp;{pigcms{$now_order["product_name"]}</if></td>
			</tr>
			<tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>订单信息：</b></td>
			</tr>
			<tr>
				<th width="15%">用户名</th>
				<td width="35%">{pigcms{$now_order.nickname}</td>
				<th width="15%">手机号</th>
				<td width="35%">{pigcms{$now_order.phone}</td>
			</tr>
			
			<if condition='$now_order["appoint_date"] && $now_order["appoint_time"]'>
			<tr>
				<th width="15%">预约日期</th>
				<td width="35%">{pigcms{$now_order.appoint_date}</td>
				<th width="15%">预约时间点</th>
				<td width="35%">{pigcms{$now_order.appoint_time}</td>
			</tr>
			</if>
            
            <if condition='$now_order["appoint_id"]'>
                <tr>
                    <th width="15%">下单时间</th>
                    <td width="35%">{pigcms{$now_order.order_time|date='Y-m-d H:i:s',###}</td>
                    <th width="15%">定金</th>
					<php>if($appoint_info['payment_status']==1){</php>
                     <if condition='$now_order["product_id"]'>
						<td width="35%">￥ {pigcms{$now_order.product_payment_price}</td>
					<else />
						<td width="35%">￥ {pigcms{$now_order.payment_money}</td>
					</if>
					<php>}else{</php>
						<td width="35%">不收定金</td>
					<php>}</php>
                </tr>
                <tr>
                    <th width="15%">服务类型</th>
                    <td width="35%">
                        <if condition="$now_order['appoint_type'] eq 0"><span style="color:red">到店</span>
                        <elseif condition="$now_order['appoint_type'] eq 1" /><span style="color:red">上门</span>
                        </if>
                    </td>
                    <th width="15%">总价</th>
                   <td width="35%">
				
				<if condition="$now_order['paid'] eq 1 AND $now_order['service_status'] eq 0 AND $now_order.is_appoint_price eq 0">￥<input type="text" name="price" value="{pigcms{$now_order.product_price|floatval}" class="input" style="    width: 100px;"><else />￥{pigcms{$now_order.product_price|floatval}</if>  <if condition="$now_order['paid'] eq 1 AND $now_order['service_status'] eq 0 AND $now_order['is_appoint_price'] eq 0"><button style="float:right;display:inline" onclick="change_price(this);">修改价格</button></if>
				</td>
                </tr>
                <tr>
				
                <th width="15%">订单状态</th>
                    <td width="35%">
                        <if condition="$now_order['paid'] == 0" >
                            <font color="red">未支付</font>
                        <elseif condition="$now_order['paid'] == 1" />
                            <font color="green">已支付</font>
                        <elseif condition="$now_order['paid'] == 2" />
                            <font color="red">已退款</font>
                        </if>
						
						<if condition="$now_order['service_status'] == 0" >
                                <font color="red">未服务</font>
                            <elseif condition="$now_order['service_status'] == 1" />
                                <font color="green">已服务</font>
							<elseif condition="$now_order['service_status'] == 2" />
								<font color="green">已评价</font>
						</if>
						
						
						<if condition='$now_order["is_del"] neq 0'>
					&nbsp;|&nbsp;
				<font color="red">
					<switch name='now_order["is_del"]'>
						<case value="1">已取消【用户】【PC端】</case>
						<case value="2">已取消【平台】</case>
						<case value="3">已取消【商家】</case>
						<case value="4">已取消【店员】</case>
						<case value="5">已取消【用户】【WAP端】</case>
					</switch>
					</font>
					</if>
                    </td>
                <th width="15%">买家留言</th>
                    <td width="35%">{pigcms{$now_order.content}</td>
                </tr>
                 <if condition='$now_order["del_time"]'>
				<tr>
					<th width="15%">取消时间</th>
					<td width="85%" colspan="3">{pigcms{$now_order.del_time|date='Y-m-d H:i:s',###}</td>
				</tr>
			</if>
            </if>
            
           <if condition="$merchant_workers_info OR $now_order['store_id']">
            <tr>
					<td colspan="4" style="padding-left:5px;color:black;"><b>分配信息：</b></td>
				</tr>
                <if condition='$now_order["store_id"]'>
                    <tr>
                        <th width="15%">店铺名称</th>
                        <td width="85%" colspan="3">
                            {pigcms{$store_list[$now_order['store_id']]}
                        </td>
                    </tr>
                </if>
                
                <if condition='$merchant_workers_info'>
                    <tr>
                        <th width="15%">服务工作人员</th>
                        <td width="35%">
                            {pigcms{$merchant_workers_info['name']}
                        </td>
                        <th width="15%">联系电话</th>
                        <td width="35%">
                        {pigcms{$merchant_workers_info['mobile']}
                        </td>
                    </tr>
                </if>
            </if>
            
            
			<if condition="!empty($now_order['use_time'])">		
				<tr>
					<th width="15%"><if condition="$now_order['tuan_type'] neq 2">消费<else/>发货</if>时间</th>
					<td width="35%">{pigcms{$now_order.use_time|date='Y-m-d H:i:s',###}</td>
					<th width="15%">操作店员：</th>
					<td width="35%">{pigcms{$now_order.last_staff}</td>
				</tr>
			</if>
			<if condition="$now_order['paid'] eq '1'">
				<tr>
					<td colspan="4" style="padding-left:5px;color:black;"><b>用户信息：</b></td>
				</tr>
				<tr>
					<th width="15%">用户ID</th>
					<td width="35%">{pigcms{$now_order.uid}</td>
					<th width="15%">用户名</th>
					<td width="35%">{pigcms{$now_order.nickname}</td>
				</tr>
				<tr>
					<th width="15%">用户手机号</th>
					<td width="35%">{pigcms{$now_order.phone}</td>
					<th width="15%">使用商家会员卡余额</th>
					<td width="85%" colspan="3">
					
					<if condition='$now_order["is_initiative"] AND $now_order["service_status"]'>
						{pigcms{$now_order['merchant_balance'] + $now_order['product_merchant_balance']}
					<elseif condition='$now_order["is_initiative"] AND !$now_order["service_status"]' />
						{pigcms{$now_order['product_merchant_balance']}
					<else />
						{pigcms{$now_order.merchant_balance}
					</if>
					</td>
				</tr>
				<tr>
					<th width="15%">余额支付金额</th>
					<td width="35%">
					<if condition='$now_order["is_initiative"] AND $now_order["service_status"]'>
						{pigcms{$now_order['balance_pay'] + $now_order['product_balance_pay']}
					<elseif condition='$now_order["is_initiative"] AND !$now_order["service_status"]' />
						{pigcms{$now_order['product_balance_pay']}
					<else />
						{pigcms{$now_order.balance_pay}
					</if>
					</td>
					
					<th width="15%">在线支付金额</th>
					<td width="35%">
					<if condition='$now_order["is_initiative"] AND $now_order["service_status"]'>
						{pigcms{$now_order['product_real_payment_price'] + $now_order['product_real_balace_price']}
					<elseif condition='$now_order["is_initiative"] AND !$now_order["service_status"]' />
						{pigcms{$now_order['product_payment_price']}
					<else />
						{pigcms{$now_order.pay_money}
					</if>
					</td>
				</tr>
				
				  
                <if condition='($now_order["product_id"]) AND ($now_order["paid"] == 1) AND ($now_order["pay_time"] gt 0)'>
					<tr>
						<th width="15%">商品规格实际支付定金</th>
						<td width="35%">{pigcms{$now_order.pay_money}</td>
						<th width="15%">商品规格实际支付定金时间</th>
						<td width="35%">{pigcms{$now_order.pay_time|date='Y-m-d H:i:s',###}</td>
					</tr>
				</if>
				<if condition='($now_order["product_id"]) AND ($now_order["paid"] == 1) AND ($now_order["service_status"] == 1)'>
					<tr>
						<th width="15%">商品规格实际支付余额</th>
						<td width="35%">{pigcms{$now_order.user_pay_money}</td>
						<th width="15%">商品规格实际支付余额时间</th>
						<td width="35%">{pigcms{$now_order.user_pay_time|date='Y-m-d H:i:s',###}</td>
					</tr>
				</if>
			</if>
			<if condition="$cue_list">
				<tr>
					<td colspan="4" style="padding-left:5px;color:black;"><b>自定义填写项：</b></td>
				</tr>
				<volist name="cue_list" id="val">
					<if condition="$val['type'] eq 2">
						<tr>
							<th width="15%">{pigcms{$val.name}</th>
							<td width="35%" colspan="3">
								地址：{pigcms{$val.address}
								<!--{pigcms{$val.value}-->
							</td>
						</tr>
					<else/>
						<tr>
							<th width="15%">{pigcms{$val.name}</th>
							<td width="35%" colspan="3">{pigcms{$val.value}</td>
						</tr>
					</if>
				</volist>
			</if>
            
            
            <if condition='($now_order["service_status"] eq 0) && ($now_order["is_del"] eq 0)'>
            	<if condition='($now_order["type"] neq 2)'>
                    <tr>
                        <td colspan="4" style="padding-left:5px;color:black;"><b>分配信息：</b></td>
                    </tr>
                        <tr>
                            <th width="15%">服务列表</th>
                            <td width="85%" colspan="3">
                                <select class="col-sm-1" style="margin-right:10px;" name="appoint_id" id="appoint_id" onChange="get_store_list()">
                                    <option value="0">&nbsp;请选择&nbsp;</option>
                                    <volist name='appoint_list' id='vo'>
                                        <option value="{pigcms{$key}">&nbsp;{pigcms{$vo}&nbsp;</option>
                                    </volist>
                                </select>
                            </td>
                        </tr>
                    <tr>
                        <th width="15%">店铺列表</th>
                        <td width="85%" colspan="3">
                            <select class="col-sm-1" style="margin-right:10px;" name="store_id" id="store_id" onChange="get_worker_list()">
                                <option value="0">&nbsp;请选择&nbsp;</option>
                            </select>
                            
                            <select class="col-sm-1" style="margin-right:10px;" name="merchant_worker_id" id="merchant_worker_id">
                                <option value="0">&nbsp;请选择&nbsp;</option>
                            </select>
                            <button onClick="allot_info()">确认分配</button>
                        </td>
                    </tr>
                  <else />
                  <tr>
				<td colspan="4" style="padding-left:5px;color:black;"><b>分配信息：</b></td>
				</tr>
            	<tr>
            	<th width="15%">店铺列表</th>
                <td width="85%" colspan="3">
                	<select class="col-sm-1" style="margin-right:10px;" name="store_id" id="store_id" onChange="get_worker_list()">
                    	<option value="0">&nbsp;请选择&nbsp;</option>
                        <volist name='store_list' id='vo'>
                        	<option value="{pigcms{$key}">&nbsp;{pigcms{$vo}&nbsp;</option>
                        </volist>
                    </select>
                    
                    <select class="col-sm-1" style="margin-right:10px;" name="merchant_worker_id" id="merchant_worker_id">
                    	<option value="0">&nbsp;请选择&nbsp;</option>
                    </select>
                    <button onClick="allot_info()">确认分配</button>
                </td>
            </tr>
            <input type="hidden" name="appoint_id" id="appoint_id" value="{pigcms{$now_order['appoint_id']}" />
                  </if>  
            </if>
		</table>
        
        <script type="text/javascript">
			function get_store_list(){
				var appoint_id = $('#appoint_id').val();
				var url ="{pigcms{:U('ajax_store')}";
				var shtml = '<option value="0">&nbsp;请选择&nbsp;</option>';
				$('#merchant_worker_id').html(shtml);
				$.post(url,{'appoint_id':appoint_id},function(data){
					if(data.status){
						var store_list = data['store_list'];
						for(var i in store_list){
							shtml+='<option value="'+i+'">'+store_list[i]+'</option>'
						}
					}
					$('#store_id').html(shtml);
				},'json')
			}
		
			function get_worker_list(){
				var store_id = $('#store_id').val();
				var appoint_id = $('#appoint_id').val();
				var is_store = "{pigcms{$now_order['is_store']}"
				var url = "{pigcms{:U('ajax_worker')}";
				var shtml = '<option value="0">&nbsp;请选择&nbsp;</option>';
				$('#merchant_worker_id').html(shtml);
				$.post(url,{'store_id':store_id,'appoint_id':appoint_id,'is_store':is_store},function(data){
					if(data.status){
						var worker_list = data['worker_list'];
						for(var i in worker_list){
							shtml+='<option value="'+i+'">'+worker_list[i]+'</option>'
						}
					}
					$('#merchant_worker_id').html(shtml);
				},'json')
			}
			
			function allot_info(){
				var store_id = $('#store_id').val();
				var merchant_worker_id = $('#merchant_worker_id').val();
				var order_id = "{pigcms{$_GET['order_id']}";
				var appoint_id = $('#appoint_id').val();
				var url = "{pigcms{:U('ajax_order_edit')}"
				var type = "{pigcms{$now_order.type}"
				
				if(!store_id){
					alert('请先选择店铺！');
					return;
				}
				
				$.post(url,{'store_id':store_id,'merchant_worker_id':merchant_worker_id,'order_id':order_id,'appoint_id':appoint_id,'type':type},function(data){
					if(data.status){
						alert(data.msg);
						location.reload();
					}else{
						alert(data.msg);
					}
				},'json')
			}
        </script>
	</body>
</html>
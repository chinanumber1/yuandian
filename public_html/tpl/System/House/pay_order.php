<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('House/village')}" >小区列表</a>
					
					<a href="{pigcms{:U('House/pay_order',array('village_id'=>$_GET['village_id']))}" class="on">缴费列表</a>
					<a href="{pigcms{:U('House/village_money_list',array('village_id'=>$_GET['village_id']))}" >社区余额</a>
					<a href="{pigcms{:U('House/merchant_order',array('type'=>'group','village_id'=>$_GET['village_id']))}" >{pigcms{$config.group_alias_name}订单</a>
					<a href="{pigcms{:U('House/merchant_order',array('type'=>'shop','village_id'=>$_GET['village_id']))}" >{pigcms{$config.shop_alias_name}订单</a>
					<a href="{pigcms{:U('House/merchant_order',array('type'=>'meal','village_id'=>$_GET['village_id']))}" >{pigcms{$config.meal_alias_name}订单</a>
					<a href="{pigcms{:U('House/merchant_order',array('type'=>'appoint','village_id'=>$_GET['village_id']))}" >{pigcms{$config.appoint_alias_name}订单</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td><button class="button">确认对账</button></td>
				</tr>
			</table>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('House/pay_order')}" method="get">
							<input type="hidden" name="c" value="House"/>
							<input type="hidden" name="a" value="pay_order"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>联系电话</option>
								<option value="order_name" <if condition="$_GET['searchtype'] eq 'order_name'">selected="selected"</if>>支付种类</option>								
							</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							支付状态: <select name="searchstatus">
								<option value="0" <if condition="$_GET['searchstatus'] eq 0">selected="selected"</if>>未对账</option>
								<option value="1" <if condition="$_GET['searchstatus'] eq '1'">selected="selected"</if>>已对账</option>								
								<option value="2" <if condition="$_GET['searchstatus'] eq '2'">selected="selected"</if>>全部</option>
							</select>
							
							
							<font color="#000">日期筛选：</font>
							<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
							
							
							<input type="hidden" name="village_id" value="{pigcms{$_GET['village_id']}">
							<input type="submit" value="查询" class="button"/>
							<input type="button" value="导出EXCEL" class="button" onclick="location.href='{pigcms{:U('export',$_GET)}'"/>
						</form>
						
						
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="{pigcms{:U(\'House/companypay\')}" method="post" onsubmit="return sumbit_sure()">
				<input type="hidden" id="com_pay_money"name="money" value="">
				<input type="hidden" name="village_id" value="{pigcms{$village_id}">
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
							<col/><col/>
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
								<tr>
                                	<th width="5%"><input id="all_select" type="checkbox"></th>
                                    <th width="5%">缴费项</th>
                                    <th width="5%">已缴金额</th>
                                    <th width="5%">支付时间</th>
                                    <th width="5%">业主名</th>
                                    <th width="5%">联系方式</th>
                                    <th width="10%">住址</th>
                                    <th width="5%">编号</th>
									<th width="5%">物业服务周期</th>
									<th width="10%">自定义内容/赠送物业服务时间</th>
									<th width="5%">服务时间</th>
                                    <th width="5%">对账状态</th>
                                </tr>
						</thead>
						<tbody>
							<if condition="is_array($order_list)">
									<volist name="order_list['order_list']" id="vo">
                                        <tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
                                        	<td><if condition="($vo['is_pay_bill'] eq 0) AND ($vo['pay_type'] neq 1)"><input type="checkbox" name="orderid[]" value="{pigcms{$vo.order_id}" class="select" data-price="{pigcms{$vo.money}"/></if></td>
                                            <td>{pigcms{$vo.order_name}</td>
                                            <td>{pigcms{$vo.money}</td>
                                            <td>{pigcms{$vo.time|date='Y-m-d H:i:s',###}</td>
                                            <td>{pigcms{$vo.username}</td>
                                            <td>{pigcms{$vo.phone}</td>
                                            <td>{pigcms{$vo.address}</td>
                                            <td>{pigcms{$vo.usernum}</td>
											<td><if condition='$vo["property_month_num"]'>{pigcms{$vo.property_month_num}</if></td>
											<if condition='!empty($vo["presented_property_month_num"]) AND ($vo["diy_type"] eq 0)'><td>{pigcms{$vo.presented_property_month_num}个月</td><elseif condition='$vo["diy_type"] eq 1' /><td>{pigcms{$vo.diy_content}</td><else /><td class="red">无</td></if>
											<td><if condition='$vo.property_time_str'>{pigcms{$vo.property_time_str}</if></td>
                                            <td><if condition="($vo['is_pay_bill'] eq 0) AND ($vo['pay_type'] neq 1)"><strong style="color: red">未对账</strong><else /><strong style="color: green"><strong type="color:green">已对账</strong></if></td>
                                        </tr>
                                    </volist>
                                    <tr class="even">
										<td colspan="19">
											本页总金额：<strong style="color: green">{pigcms{$total}</strong>　本页已出账金额：<strong style="color: red">{pigcms{$finshtotal}</strong><br/> 
											总金额：<strong style="color: green">{pigcms{$order_list.totalMoney.totalMoney}</strong>　总已出账金额：<strong style="color: red">{pigcms{$order_list.readyMoney.readyMoney}</strong><br/>
										</td>
									</tr>
                                    <tr class="odd">
										<td colspan="19" id="show_count"></td>
									</tr>
									<tr><td class="textcenter pagebar" colspan="12">{pigcms{$order_list.pagebar}</td></tr>	
							<else/>
								<tr><td class="textcenter red" colspan="12">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.select').attr('checked', false);

	$('#all_select').click(function(){
		if ($(this).attr('checked')){
			$('.select').attr('checked', true);
		} else {
			$('.select').attr('checked', false);
		}
		total_price();
	});
	$('.select').click(function(){total_price();});
	$('.button').click(function(){
		var strids = '';
		var pre = ''
		var village_id = $('input[name="village_id"]').val();
		$('.select').each(function(){
			if ($(this).attr('checked')) {
				strids += pre + $(this).val();
				pre = ',';
			}
		});
		if (strids.length > 0) {
			$.post("{pigcms{:U('House/change')}", {strids:strids,village_id:village_id}, function(data){
				if (data.error_code == 0) {
					location.reload();
				}
			}, 'json');
		}
	});
});
function sumbit_sure(){
	var gnl=confirm("确定要提交?");
	if (gnl==true){
		return true;
	}else{
		return false;
	}
}
function total_price()
{
	var total = 0;
	$('.select').each(function(){
		if ($(this).attr('checked')) {
			total += parseFloat($(this).attr('data-price'));
		}
	});
	total = Math.round(total * 100)/100;
	var percent = $('#percent').val();
	if (total > 0) {
		$('#show_count').html('账单总计金额：<strong style=\'color:red\'>￥' + total + '</strong><if condition="$config['company_pay_open']"><input type="submit" class="button" value="确认对帐并在线提现"></if>');
		$('#com_pay_money').val(total * 100);
	} else {
		$('#show_count').html('');
	}
}
</script>
<include file="Public:footer"/>
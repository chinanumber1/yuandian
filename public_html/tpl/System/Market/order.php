<include file="Public:header"/>	<div class="mainbox">		<div id="nav" class="mainnav_title">			<ul>				<a href="{pigcms{:U('Market/order')}" class="on">订单列表</a>			</ul>		</div>		<!--table class="search_table" width="100%">			<tr>				<td>					<form action="{pigcms{:U('Shop/order')}" method="get">						<input type="hidden" name="c" value="Shop"/>						<input type="hidden" name="a" value="order"/>
												搜索: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>						<select name="searchtype">							<option value="real_orderid" <if condition="$_GET['searchtype'] eq 'real_orderid'">selected="selected"</if>>订单编号</option>							<option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>订单流水号</option>							<option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option>							<option value="s_name" <if condition="$_GET['searchtype'] eq 's_name'">selected="selected"</if>>店铺名称</option>							<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>客户名称</option>							<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>客户电话</option>						</select>						<font color="#000">日期筛选：</font>						<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   						<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>						订单状态筛选:						<select id="status" name="status">							<volist name="status_list" id="vo">								<option value="{pigcms{$key}" <if condition="$key eq $status">selected="selected"</if>>{pigcms{$vo}</option>							</volist>						</select>
						支付方式筛选: 
						<select id="pay_type" name="pay_type">
								<option value="" <if condition="'' eq $pay_type">selected="selected"</if>>全部支付方式</option>
							<volist name="pay_method" id="vo">
								<option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
							</volist>
								<option value="balance" <if condition="'balance' eq $pay_type">selected="selected"</if>>余额支付</option>
						</select>
						<input type="submit" value="查询" class="button"/>　					</form>				</td>				<td>					<b>应收总金额：{pigcms{$total_price|floatval}</b>　					<b>在线支付总额：{pigcms{$online_price|floatval}</b>　					<b>线下支付总额：{pigcms{$offline_price|floatval}</b>				</td>				<td>				<a href="{pigcms{:U('Shop/export',$_GET)}" class="button" style="float:right;margin-right: 10px;">导出订单</a>				</td>			</tr>		</table-->		<form name="myform" id="myform" action="" method="post">			<div class="table-list">				<table width="100%" cellspacing="0">					<thead>						<tr>							<th>订单号</th>							<th>商品图片</th>							<th>商品名称</th>							<th>单价 (元)</th>							<th>批发数量</th>							<th>总价 (元)</th>							<th>优惠明细</th>							<th>卖家信息</th>							<th>买家信息</th>							<th>订单状态</th>							<th>操作</th>						</tr>					</thead>					<tbody>						<if condition="is_array($orders)">							<volist name="orders" id="vo">								<tr>									<td>{pigcms{$vo.order_id}</td>									<td><img src="{pigcms{$vo.pic}" width="50" height="50" class="view_msg"></td>									<td>{pigcms{$vo.name}</td>									<td>{pigcms{$vo.price|floatval}</td>									<td>{pigcms{$vo.num}({pigcms{$vo.unit})</td>									<td>{pigcms{$vo.money|floatval}</td>									<td>{pigcms{$vo.discount_info_txt}</td>									<td>									                 商家名:{pigcms{$vo.merchant_name}<br/>									                 商家电话:<b>{pigcms{$vo.merchant_phone}</b><br/>									                 店铺名:{pigcms{$vo.store_name}<br/>									                 店铺电话:<b>{pigcms{$vo.merchant_phone}</b><br/>									</td>									<td>									                 商家名:{pigcms{$vo.buy_merchant_name}<br/>									                 商家电话:<b>{pigcms{$vo.buy_merchant_phone}</b><br/>									                 联系人:{pigcms{$vo.username}<br/>									                 联系电话:<b>{pigcms{$vo.userphone}</b><br/>									                 收货地址:{pigcms{$vo.address}<br/>									</td>									<td class="status">{pigcms{$vo.status_txt}</td>									<td>									    <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Market/order_detail',array('order_id'=>$vo['order_id'],'frame_show'=>true))}','查看订单详情',720,520,true,false,false,false,'detail',true);">查看</a> 									    <if condition="$vo['status'] eq 2">									    <span>|</span> <a href="javascript:void(0);" data-href="{pigcms{:U('Market/pull', array('order_id'=>$vo['order_id']))}" class="refund">确认收货</a>									    </if>									</td>								</tr>							</volist>							<tr><td class="textcenter pagebar" colspan="15">{pigcms{$pagebar}</td></tr>						<else/>							<tr><td class="textcenter red" colspan="15">列表为空！</td></tr>						</if>					</tbody>				</table>			</div>		</form>	</div>	<script>$(function(){	$('.refund').click(function(){		var get_url = $(this).data('href'), obj = $(this);		window.top.art.dialog({			content: '您确定要将此单改成已收货吗',			lock: true,			ok: function () {				this.close();				$.get(get_url,function(response){					if (response.error_code == false) {						obj.parents('tr').find('.status').html('<span style="color:green">已收货</span>');						obj.prev('span').remove();						obj.remove();					} else {						window.top.art.dialog({							title: response.msg						});					}				},'json');				return false;			},			cancelVal: '取消',			cancel: true		});	});});</script><include file="Public:footer"/>
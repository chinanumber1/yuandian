<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
		<title>{pigcms{$config.site_name} - 店员管理中心</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
		<script type="text/javascript" src="{pigcms{$static_path}js/jquery.min.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/layer/layer.js"></script>
		<script type="text/javascript" src="{pigcms{$static_path}js/storestaffBase.js"></script>
		<script type="text/javascript" src="{pigcms{$static_public}js/date/WdatePicker.js"></script>
		<link rel="stylesheet" type="text/css" href="{pigcms{$static_path}css/storestaffBase.css"/>
	</head>
	<body>
		<div class="headerBox">
			<div class="txt">{pigcms{$config.shop_alias_name}</div>
			<div class="back urlLink" data-url="{pigcms{:U('index')}" title="返回首页"></div>
			<div class="reload urlLink" data-url="reload" title="刷新页面"></div>
		</div>
		<div class="mainBox">
			<div class="leftMenu">
				<ul>
					<li class="urlLink cur" data-url="{pigcms{:U('shop_list')}">
						<div class="icon order"></div>
						<div class="text">订单列表</div>
					</li>
					<li class="urlLink" data-url="{pigcms{:U('goods')}">
						<div class="icon list"></div>
						<div class="text">待发商品清单</div>
					</li>
					<li class="urlLink" data-url="{pigcms{:U('goods_sale')}">
						<div class="icon list"></div>
						<div class="text">商品销售统计</div>
					</li>
                    <if condition="$config['eleme_app_key'] OR $config['meituan_sign_key']">
                    <li class="urlLink" data-url="{pigcms{:U('shop_order_report_form')}">
                        <div class="icon list"></div>
                        <div class="text">各平台订单统计</div>
                    </li>
                    </if>
					<if condition="$config['pay_in_store']">
					<li class="urlLink" data-url="{pigcms{:U('market')}">
						<div class="icon list"></div>
						<div class="text">线下零售</div>
					</li>
					</if>
				</ul>
			</div>
			<div class="rightMain">
				<div class="alert waring" style="background-color:#f9cdcd;border-color:#f9cdcd;color:#8c2a2a;display:none;">
					<i class="ice-icon fa fa-volume-up bigger-130"></i>
					<p>您有部分商品库存小于10,请及时 <a title="库存报警商品列表"  data-title="库存报警商品列表" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('shop_goods_stock')}">查看</a>！</p>
				</div>
				<div class="alert alert-block alert-success">
					<p>
						注意:在每行的输入框里可以通过输入您想要搜索的订单的关键词<br/>
						在对应的标题下输入对应的关键词后按【Enter】即可搜索<br/>
						<br/>
						页面每5秒会请求一次是否有需要接单的新订单，若有会一直语音提醒，并有弹层提示，关闭提示会关闭语音。
					</p>
				</div>
				<div class="form-group clearfix">
					<form action="{pigcms{:U('Store/shop_list')}" method="get">
						<input type="hidden" name="c" value="Store"/>
						<input type="hidden" name="a" value="shop_list"/>
						<input type="hidden" name="appoint_id" value="{pigcms{$_GET.appoint_id}"/>

						<div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
							<select name="searchtype">
								<option value="real_orderid" <if condition="$_GET['searchtype'] eq 'real_orderid'">selected="selected"</if>>订单编号</option>
								<option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>支付流水号</option>
								<option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option>
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>客户名称</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>客户电话</option>
							</select>
							<input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}" style="width:200px;"/>
						</div>
						<div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
							<font color="#000">日期筛选：</font>
							<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;-&nbsp;			   
							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>
						</div>
						<div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
							<font color="#000">订单状态筛选：</font>
							<select id="status" name="status" >
								<volist name="status_list" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $status">selected="selected"</if>>{pigcms{$vo}</option>
								</volist>
							</select>
						</div>
						<div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
							<font color="#000">支付方式筛选：</font> 
							<select id="pay_type" name="pay_type">
									<option value="" <if condition="'' eq $pay_type">selected="selected"</if>>全部支付方式</option>
								<volist name="pay_method" id="vo">
									<option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
								</volist>
									<option value="balance" <if condition="'balance' eq $pay_type">selected="selected"</if>>余额支付</option>
							</select>
						</div>
						<div style="float:left;margin-right:20px;margin-bottom:20px;height:32px;">
							<input type="submit" value="查询" class="btn btn-success" style="padding:2px 14px;"/>　
							<a  href="javascript:void(0)" onclick="exports()"  class="down_excel" style="float:right;padding:8px 14px;border:1px solid #629b58;color:#629b58;">导出订单</a>
						</div>
					</form>				
				</div>
					
				<div class="alert alert-block alert-success" style="margin:10px 0;">统计已支付且非取消和退款的订单：
					<b>应收总金额：{pigcms{$total_price|floatval}</b>　
					<b>在线支付总额：{pigcms{$online_price|floatval}</b>　
					<b>线下支付总额：{pigcms{$offline_price|floatval}</b>
				</div>
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>									
								<th id="shopList_c1" width="50">取单号</th>
								<th id="shopList_c1" width="50">订单号</th>
								<th id="shopList_c1" width="80" class="hide_col">下单人信息</th>
								<th id="shopList_c0" width="80">订单来源/取货方式</th>
								<th id="shopList_c5" width="50">总价/应收现金</th>
								<th id="shopList_c3" width="90">
									<if condition="$type eq 'pay_time'">
										<if condition="$sort eq 'ASC'">
											<a href="{pigcms{:U('Store/shop_list', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}">支付时间<i class="menu-icon fa fa-sort-desc"></i></a>
										<elseif condition="$sort eq 'DESC'" />
											<a href="{pigcms{:U('Store/shop_list', array('type' => 'pay_time', 'sort' => 'ASC', 'status' => $status))}">支付时间<i class="menu-icon fa fa-sort-asc"></i></a>
										<else />
											<a href="{pigcms{:U('Store/shop_list', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}">支付时间<i class="menu-icon fa fa-sort"></i></a>
										</if>
									<else />
										<a href="{pigcms{:U('Store/shop_list', array('type' => 'pay_time', 'sort' => 'DESC', 'status' => $status))}">支付时间<i class="menu-icon fa fa-sort"></i></a>
									</if>
								</th>
								<th id="shopList_c3" width="80">期望送达时间</th>
								<th id="shopList_c4" width="70" class="hide_col">支付状态</th>
								<th id="shopList_c4" width="100">处理订单</th>
								<th id="shopList_c5" width="60" >查看商品详情</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$order_list">
								<volist name="order_list" id="vo">
									<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
										<td><div class="tagDiv"><if condition="!in_array($vo['status'], array(2,3,4,5)) AND $vo['fetch_number']">{pigcms{$vo.fetch_number}</if></div></td>
										<td style="text-align:center;"><div class="tagDiv">{pigcms{$vo.real_orderid}</div></td>
										<td class="hide_col"><div class="tagDiv"><if condition="$vo['username']">姓名：{pigcms{$vo.username}<br/>电话：{pigcms{$vo.userphone}</if></div></td>
										<td style="text-align:center;"><if condition="$vo['platform'] eq 1">饿了么/<php>if ($vo['is_pick_in_store'] == 5) {</php>{pigcms{$vo.deliverinfo}<php>} else {</php>--<php>}</php><elseif condition="$vo['platform'] eq 2"/>美团/<php>if ($vo['is_pick_in_store'] == 5) {</php>{pigcms{$vo.deliverinfo}<php>} else {</php>--<php>}</php><elseif condition="$vo['order_from'] eq 6"/>线下购买/{pigcms{$vo.deliverinfo}<elseif condition="$vo['order_from'] eq 1" />商城/{pigcms{$vo.deliverinfo}<else />{pigcms{$config.shop_alias_name}/{pigcms{$vo.deliverinfo}</if></td>
										<td style="text-align:center;">{pigcms{$vo.total_price|floatval} / <span style="color:green;"><strong>{pigcms{$vo['offline_price']|floatval}</strong>　
											<if condition="$vo['status'] eq 0 AND $vo['paid'] eq 0 AND $is_change eq 1 AND $vo['order_from'] neq 6">
												<a title="修改价格"  data-title="修改价格" class="green handle_btn" data-box_width="30%" data-box_height="95%" style="padding-right:8px;" href="{pigcms{:U('shop_change_price',array('order_id'=>$vo['order_id']))}">
													<i class="shortBtn">修改价格</i>
												</a>
											</if></span>
										</td>
										<if condition="$vo['pay_time']">
											<td style="text-align:center;">{pigcms{$vo.pay_time|date="m-d H:i:s",###}</td>
										<else />
											<td style="text-align:center;">未支付</td>
										</if>
										<if condition="$vo['expect_use_time']">
											<td style="text-align:center;">{pigcms{$vo.expect_use_time|date="m-d H:i:s",###}</td>
										<else />
											<td style="text-align:center;">尽快</td>
										</if>
										<td class="hide_col">{pigcms{$vo.pay_status}<br/><if condition="$vo['platform'] eq 0">{pigcms{$vo.pay_type_str}</if></td>
										<td>
										<if condition="$vo['status'] eq 0 && $vo['paid'] eq 1">
											<if condition="($vo['is_pick_in_store'] eq 2 OR $vo['is_pick_in_store'] eq 1) AND $now_store['is_open_pick'] eq 1">
											<a  data-title="分配自提点" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('Store/pick',array('order_id'=>$vo['order_id']))}">分配自提点</a>
											<elseif condition="$vo['is_pick_in_store'] eq 3 AND $now_store['deliver_type'] neq 5" />
											<a title="查看订单详情" data-title="修改配送方式" class="green handle_btn" data-box_width="30%" data-box_height="95%" style="padding-right:8px;" href="{pigcms{:U('mall_order_detail',array('order_id'=>$vo['order_id']))}">更换成<if condition="$now_store['deliver_type'] eq 0 OR $now_store['deliver_type'] eq 3">{pigcms{$config.deliver_name}<else/>商家配送</if></a>
											<elseif condition="$vo['is_pick_in_store'] neq 3" />
											<a data-title="接单" class="green edit_btn js-add-order js-add-order-{pigcms{$vo.order_id}" style="padding-right:8px;" href="javascript:;" js-order="{pigcms{$vo.order_id}">接单</a>
											</if>
										<elseif condition="$vo['status'] eq 1" />
										<a data-title="已接单" class="green edit_btn" style="padding-right:8px;" href="javascript:;" >已接单</a>
										<elseif condition="$vo['status'] eq 2" />
										<strong style="color: green">已消费</strong>
										<elseif condition="$vo['status'] eq 3" />
										<strong style="color: green">已评价</strong>
										<elseif condition="$vo['status'] eq 4" />
										<strong style="color: red">已退款</strong>
										<elseif condition="$vo['status'] eq 5" />
										<strong style="color: red">已取消</strong>
										<elseif condition="$vo['status'] eq 7 AND $vo['paid'] eq 1" />
										<a data-title="发货" class="green edit_btn js-send-order js-add-order-{pigcms{$vo.order_id}" style="padding-right:8px;" href="javascript:;" js-order="{pigcms{$vo.order_id}">发货</a>
										<elseif condition="$vo['status'] eq 8" />
										<strong style="color: green">已发货</strong>
										<elseif condition="$vo['status'] eq 9" />
										<strong style="color: green">自提点接货</strong>
										<elseif condition="$vo['status'] eq 10" />
										<strong style="color: green">配送中</strong>
										<else />
										<strong style="color: red">未支付，不接单</strong>
										</if>
										<!--label class="statusSwitch" style="display:inline-block;">
											<input name="switch-field-1" class="ace ace-switch ace-switch-6" type="checkbox" data-id="{pigcms{$vo.order_id}" <if condition="$vo['is_confirm'] eq 1">checked="checked" data-status="OPEN"<else/>data-status="CLOSED"</if>/>
											<span class="lbl"></span>
										</label-->
										</td>
										<td style="text-align:center;">
											<a title="查看订单详情" data-title="订单详情" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('shop_order_detail',array('order_id'=>$vo['order_id']))}">
												<i class="shortBtn">查看详情</i>
											</a>
										</td>
									</tr>
								</volist>
							<else/>
								<tr class="odd"><td class="button-column" colspan="30" >您的店铺暂时还没有订单。</td></tr>
							</if>
						</tbody>
					</table>
					{pigcms{$pagebar}
				</div>
			</div>
		</div>
	</body>
<script>
$(function(){
	if($(window).width() <= 1024){
		$('.hide_col').remove();
	}
});

document.onkeydown = function(event_e) {
	if(window.event) event_e = window.event;  
	var int_keycode = event_e.charCode||event_e.keyCode;  
	if(int_keycode ==13 && ($('#order_id').val() != '' || $('#phone').val() != '' || $('#name').val() != '' || $('#meal_pass').val() != '')) $('#queryForList').submit();
} 

//updateStatus(".statusSwitch .ace-switch", ".statusSwitch", "OPEN", "CLOSED", "shopstatus");

function getNewOrder(time){
	$.post("{pigcms{:U('Store/ajax_shop_neworder')}",{time:time},function(result){
		if(result.status == 1){
			playMp3Tip();
			layer.confirm('您有新的订单需要处理，请问是否刷新页面处理？<br/>点击按钮均可关闭声音。', {
			  btn: ['确认','关闭'] //按钮
			}, function(){
			  closeMp3Tip();
			  location.href="{pigcms{:U('Store/shop_list')}&status=0";
			}, function(){
			  closeMp3Tip();
			});
		}else{
			setTimeout(function(){
				getNewOrder(result.info);
			},5000);
		}
	});
}

function getRefundOrder(time){
    $.post("{pigcms{:U('Store/ajax_shop_refundorder')}",{time:time},function(result){
        if(result.status == 1){
            playMp3Tip();
            layer.confirm('您有新的退款订单需要处理！', {
              btn: ['确认','关闭'] //按钮
            }, function(){
              closeMp3Tip();
              location.href="{pigcms{:U('Store/shop_list')}&status=11";
            }, function(){
              closeMp3Tip();
            });
        }else{
            setTimeout(function(){
                getRefundOrder(result.info);
            },5000);
        }
    });
}

function check_shop_goods_stock()
{
	$.get("{pigcms{:U('Store/check_shop_goods_stock')}", function(result){
		if(result.status == 1){
			$('.waring').show();
		} else {
			$('.waring').hide();
		}
		setTimeout(function(){
			check_shop_goods_stock();
		},6000);
	}, 'json');
}
function playMp3Tip(){
	$('body').append('<audio style="display:none;" id="playMp3Tip" controls="true" loop="loop" src="{pigcms{$static_public}file/new_order.mp3"></audio>');
	$('#playMp3Tip').trigger('play');
}
function closeMp3Tip(){
	$('#playMp3Tip').trigger('pause');
	$('#playMp3Tip').remove();
}

$(document).ready(function(){
	check_shop_goods_stock();
	getNewOrder("{pigcms{$_SERVER.REQUEST_TIME}");
	getRefundOrder("{pigcms{$_SERVER.REQUEST_TIME}");
	$('.js-add-order').click(function(){
		var order_id = $(this).attr('js-order');
		$('.js-add-order-'+order_id).html('处理中');
		$.post("{pigcms{:U('Store/check_shop')}",{order_id:order_id,status:1},function(result){
			if(result.status == 1){
				$('.js-add-order-'+order_id).unbind('click');
				$('.js-add-order-'+order_id).html(result.info);
				$('.js-add-order').removeClass('js-add-order');
			}else{
				layer.msg(result.info);
				$('.js-add-order-'+order_id).html('接单');
			}
		});
	});
	$('.js-send-order').click(function(){
		var order_id = $(this).attr('js-order');
		$('.js-add-order-'+order_id).html('处理中');
		$.post("{pigcms{:U('Store/deliver_goods')}",{order_id:order_id,status:1},function(result){
			if(result.status == 1){
				$('.js-add-order-'+order_id).unbind('click');
				$('.js-add-order-'+order_id).html(result.info);
				$('.js-add-order').removeClass('js-add-order');
			}else{
				layer.msg(result.info);
				$('.js-add-order-'+order_id).html('发货');
			}
		});
	});
});
 var url = "{pigcms{$config.site_url}"
    var export_url = "{pigcms{:U('shop_export')}"
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/export.js"> </script>
</html>
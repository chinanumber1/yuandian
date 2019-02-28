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
			<div class="txt">{pigcms{$config.meal_alias_name}</div>
			<div class="back urlLink" data-url="{pigcms{:U('index')}" title="返回首页"></div>
			<div class="reload urlLink" data-url="reload" title="刷新页面"></div>
		</div>
		<div class="mainBox">
			<div class="leftMenu">
				<ul>
					<li class="urlLink cur" data-url="{pigcms{:U('foodshop')}">
						<div class="icon order"></div>
						<div class="text">订单列表</div>
					</li>
					<li class="urlLink" data-url="{pigcms{:U('tmp_table')}">
						<div class="icon table"></div>
						<div class="text">桌台列表</div>
					</li>
					<li class="urlLink" data-url="{pigcms{:U('queue')}">
						<div class="icon queue"></div>
						<div class="text">排号列表</div>
					</li>
				</ul>
			</div>
			<div class="rightMain">
                <div class="alert waring" style="background-color:#f9cdcd;border-color:#f9cdcd;color:#8c2a2a;display:none;">
                    <i class="ice-icon fa fa-volume-up bigger-130"></i>
                    <p>您有部分商品库存小于10,请及时 <a title="库存报警商品列表"  data-title="库存报警商品列表" class="green handle_btn" style="padding-right:8px;" href="{pigcms{:U('foodshop_goods_stock')}">查看</a>！</p>
                </div>
				<button class="btn btn-success handle_btn" data-layer_id="add_order" data-title="创建订单" data-box_width="420px" data-box_height="450px" href="{pigcms{:U('foodshop_order_before')}">创建订单</button>
				<br/>
				<br/>
				<br/>
				<div class="alert alert-block alert-success">
					<p>
						注意:在每行的输入框里可以通过输入您想要搜索的订单的关键词<br/>
						在对应的标题下输入对应的关键词后按【Enter】即可搜索<br/>
						<br/>
						页面每5秒会请求一次是否有需要店员操作的订单，若有会一直语音提醒，并有弹层提示，关闭提示会关闭语音。
					</p>
				</div>
				<div class="form-group">
					<form action="{pigcms{:U('Stroe/foodshop')}" method="get">
						<input type="hidden" name="c" value="Store"/>
						<input type="hidden" name="a" value="foodshop"/>
						<input type="hidden" name="appoint_id" value="{pigcms{$_GET.appoint_id}"/>
						
						搜索: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
						<select name="searchtype">
							<option value="real_orderid" <if condition="$_GET['searchtype'] eq 'real_orderid'">selected="selected"</if>>订单编号</option>
							<option value="orderid" <if condition="$_GET['searchtype'] eq 'orderid'">selected="selected"</if>>支付流水号</option>
							<option value="third_id" <if condition="$_GET['searchtype'] eq 'third_id'">selected="selected"</if>>第三方支付流水号</option>
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
								<option value="" <if condition="'' eq $pay_type">selected="selected"</if>>全部支付方式</option>
							<volist name="pay_method" id="vo">
								<option value="{pigcms{$key}" <if condition="$key eq $pay_type">selected="selected"</if>>{pigcms{$vo.name}</option>
							</volist>
								<option value="balance" <if condition="'balance' eq $pay_type">selected="selected"</if>>余额支付</option>
						</select>
						<input type="submit" value="查询" class="btn btn-success" style="padding:2px 14px;"/>　
						<a href="javascript:void(0)" onclick="exports()" class="btn btn-success" style="float:right;margin-right: 10px;">导出订单</a>
					</form>
					
				</div>
                <div class="alert alert-block alert-success" style="margin:10px 0;">
                    <b>应收总金额：{pigcms{$total_price|floatval}</b>　
                    <b>在线支付总额：{pigcms{$online_price|floatval}</b>　
                    <b>线下支付总额：{pigcms{$offline_price|floatval}</b>
                </div>
				<div class="grid-view">
					<table class="table table-striped table-bordered table-hover">
						<thead>
							<tr>
								<th width="50">订单编号</th>
								<th width="80">客户姓名</th>
								<th width="80">客户电话</th>
								<th width="80" class="button-column">预订金</th>
								<th width="80">预订时间</th>
								<th width="80" class="button-column">桌台类型</th>
								<th width="80" class="button-column">桌台名称</th>
								<th width="80" class="button-column">状态</th>
								<th width="80" class="button-column">订单总价</th>
								<th width="80" class="button-column">支付方式</th>
								<th width="80" class="button-column">查看订单详情</th>
							</tr>
						</thead>
						<tbody>
							<volist name="order_list" id="vo">
								<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
									<td>{pigcms{$vo.real_orderid}</td>
									<td>{pigcms{$vo.name}</td>
									<td>{pigcms{$vo.phone}</td>
									<td class="button-column">{pigcms{$vo.book_price|floatval}</td>
									<if condition="$vo['book_time']">
									<td>{pigcms{$vo.book_time|date='Y-m-d H:i:s',###}</td>
									<else />
									<td>--</td>
									</if>
									<td class="button-column">{pigcms{$vo.table_type_name}</td>
									<td class="button-column">{pigcms{$vo.table_name}</td>
									<td class="button-column">
										<if condition="$vo['status'] eq 0">
										<span>订单生成</span>
										<elseif condition="$vo['status'] eq 1" />
										<span style="color:green">已付定金</span>
										<elseif condition="$vo['status'] eq 2" />
										<span style="color:red">使用中</span>
										<elseif condition="$vo['status'] eq 3" />
										<span style="color:blue">已买单</span>
										<elseif condition="$vo['status'] eq 4" />
										<span style="color:green">已评价</span>
										<elseif condition="$vo['status'] eq 5" />
										<del style="color:red">已退款</del>
										</if>
									</td>
									<td class="button-column">
										<if condition="$vo['status'] eq 5">
											已退款
										<elseif condition="$vo['status'] egt 3" />
											{pigcms{$vo.price|floatval}
										<elseif condition="$vo['status'] egt 1" />
											已付定金
										<elseif condition="$vo['status'] lt 1"/>	
											暂未支付
										</if>
									</td>
									<td class="button-column">
									{pigcms{$vo['pay_type_show']}
									</td>
									<td class="button-column">
										<a title="查看订单详情"  data-title="订单详情" class="green handle_btn" style="padding-right:8px;" data-layer_id="edit_order" data-title="编辑订单" data-box_width="<if condition="$vo['status'] lt 3">95%<else />45%</if>" data-box_height="95%" href="{pigcms{:U('foodshop_order',array('order_id'=>$vo['order_id']))}">
											<i class="shortBtn">查看详情</i>
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
	</body>
	<script>
		$(function(){
            check_foodshop_goods_stock();
			$('#status').change(function(){
				location.href = "{pigcms{:U('foodshop', array('store_id' => $now_store['store_id'], 'type' => $type, 'sort' => $sort))}&status=" + $(this).val();
			});	
		});

		document.onkeydown = function(event_e) {
			if(window.event) event_e = window.event;  
			var int_keycode = event_e.charCode||event_e.keyCode;  
			if(int_keycode ==13 && ($('#order_id').val() != '' || $('#phone').val() != '' || $('#name').val() != '' || $('#meal_pass').val() != '')) $('#queryForList').submit();
		} 
		
		function getNewOrder(time){
			$.post("{pigcms{:U('Store/ajax_foodshop_storestaff_order')}",{time:time},function(result){
				if(result.status == 1){
					playMp3Tip();
					layer.confirm('您有新的订单需要处理，请问是否刷新页面处理？<br/>点击按钮均可关闭声音。', {
					  btn: ['确认','关闭'] //按钮
					}, function(){
						closeMp3Tip();
						location.href = "{pigcms{:U('foodshop', array('store_id' => $now_store['store_id'], 'type' => $type, 'sort' => $sort))}&status=11";
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
		function playMp3Tip(){
			$('body').append('<audio style="display:none;" id="playMp3Tip" controls="true" loop="loop" src="{pigcms{$static_public}file/new_order.mp3"></audio>');
			$('#playMp3Tip').trigger('play');
		}
		function closeMp3Tip(){
			$('#playMp3Tip').trigger('pause');
			$('#playMp3Tip').remove();
		}
		function check_foodshop_goods_stock()
		{
			$.get("{pigcms{:U('Store/check_foodshop_goods_stock')}", function(result){
				if(result.status == 1){
					$('.waring').show();
				} else {
					$('.waring').hide();
				}
				setTimeout(function(){
				    check_foodshop_goods_stock();
				},6000);
			}, 'json');
		}
		$(document).ready(function(){
			getNewOrder("{pigcms{$_SERVER.REQUEST_TIME}");
		});
	 var url = "{pigcms{$config.site_url}"
    var export_url = "{pigcms{:U('foodshop_export')}"
</script>
<script type="text/javascript" src="{pigcms{$static_public}js/export.js"> </script>
</html>
<include file="Public:header"/>

<div class="main-content">
	<!-- 内容头部 -->
	<div class="breadcrumbs" id="breadcrumbs">
		<ul class="breadcrumb">
			<i class="ace-icon fa fa-bar-chart-o bar-chart-o-icon"></i>
			<li class="active">我的短信</li>
		</ul>
	</div>
	<!-- 内容头部 -->
	<div class="page-content">
		<div class="page-content-area">
			<div class="row">
				<div class="col-sm-12">
					
					<div style="margin-top:10px;width:100%;height:160px;background-color:#81d2cf">
						<p style="text-align:center;font-size: 40px;color: #FFFFFF;padding-top: 20px;"><span style="font-size: 25px;">剩余短信数：</span> {pigcms{$now_store.now_sms_number} <span style="font-size: 25px;">&nbsp;( 条 )</span></p>
						<p style="text-align:center; padding-top: 20px;" class="my_money">
							<if condition="$config['buy_sms'] eq 1">
								<if condition="in_array(249,$house_session['menus'])">
									<a class="bind_info red" href="{pigcms{:U('Village_money/sms_buy')}"> <span >购买短信</span> </a>
								<else/>
									<button class="btn btn-success" disabled="disabled">购买短信</button>
								</if>
							<else/>
								<a style="" href="javascript:void(0);" onclick="alert('暂不支持')"> <span style="color: #777777;">购买短信</span> </a>
							</if>
							
							<a href="{pigcms{:U('Village_money/sms_note',array('type'=>'buy'))}"><span >购买记录</span></a>
						</p>
					</div>


					<div class="tabbable" style="margin-top:20px;">
						<ul class="nav nav-tabs" id="myTab" style="width:100%;">
							<li <if condition="$_GET['type'] neq 'buy'">class="active"</if>>
								<a href="{pigcms{:U('Village_money/sms_note')}">
									短信发送记录
								</a>
							</li>
							<li <if condition="$_GET['type'] eq 'buy'">class="active"</if>>
								<a href="{pigcms{:U('Village_money/sms_note',array('type'=>'buy'))}">
									短信购买记录
								</a>
							</li>
						</ul>
						
						<div class="tab-content" style="width:100%">
							<div class="tab-pane <if condition="$_GET['type'] neq 'buy'">active</if>">

								<div style="margin-top:10px;margin-bottom: 10px;" >
									<form action="{pigcms{:U('Stroe/group_list')}" method="get"> 
										<input type="hidden" name="c" value="Village_money"/>
										<input type="hidden" name="a" value="sms_note"/>&nbsp;&nbsp;
										日期筛选：
										<input type="text" class="input-text" name="start_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.start_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
										<input type="text" class="input-text" name="end_time" style="width:120px;margin-left: 5px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;&nbsp;
										状态:
										<select name="status">
											<option value="" <if condition="$_GET['status'] eq ''">selected="selected"</if>>发送状态</option>
											<option value="0" <if condition="$_GET['status'] eq '0'">selected="selected"</if>>发送成功</option>
											<option value="-1" <if condition="$_GET['status'] eq '-1'">selected="selected"</if>>验证失败未购买</option>
											<option value="-2" <if condition="$_GET['status'] eq '-2'">selected="selected"</if>>短信不足</option>
											<option value="-3" <if condition="$_GET['status'] eq '-3'">selected="selected"</if>>操作失败</option>
											<option value="-4" <if condition="$_GET['status'] eq '-4'">selected="selected"</if>>非法字符</option>
											<option value="-5" <if condition="$_GET['status'] eq '-5'">selected="selected"</if>>内容过多</option>
											<option value="-6" <if condition="$_GET['status'] eq '-6'">selected="selected"</if>>号码过多</option>
											<option value="-7" <if condition="$_GET['status'] eq '-7'">selected="selected"</if>>频率过快</option>
											<option value="-8" <if condition="$_GET['status'] eq '-8'">selected="selected"</if>>号码内容空</option>
											<option value="-9" <if condition="$_GET['status'] eq '-9'">selected="selected"</if>>账号冻结</option>
											<option value="-10" <if condition="$_GET['status'] eq '-10'">selected="selected"</if>>禁止频繁单条发送</option>
											<option value="-11" <if condition="$_GET['status'] eq '-11'">selected="selected"</if>>系统暂定发送</option>
											<option value="-12" <if condition="$_GET['status'] eq '-12'">selected="selected"</if>>有错误号码</option>
											<option value="-13" <if condition="$_GET['status'] eq '-13'">selected="selected"</if>>定时时间不对</option>
											<option value="-14" <if condition="$_GET['status'] eq '-14'">selected="selected"</if>>账号被锁，10分钟后登录</option>
											<option value="-15" <if condition="$_GET['status'] eq '-15'">selected="selected"</if>>连接失败</option>
											<option value="-16" <if condition="$_GET['status'] eq '-16'">selected="selected"</if>>禁止接口发送</option>
											<option value="-17" <if condition="$_GET['status'] eq '-17'">selected="selected"</if>>绑定IP不正确</option>
											<option value="-18" <if condition="$_GET['status'] eq '-18'">selected="selected"</if>>系统升级</option>
											<option value="-19" <if condition="$_GET['status'] eq '-19'">selected="selected"</if>>域名不对</option>
											<option value="-20" <if condition="$_GET['status'] eq '-20'">selected="selected"</if>>key不匹配</option>
											<option value="-21" <if condition="$_GET['status'] eq '-21'">selected="selected"</if>>用户不存在</option>
											<option value="-22" <if condition="$_GET['status'] eq '-22'">selected="selected"</if>>余额不足</option>
											<option value="-100" <if condition="$_GET['status'] eq '-100'">selected="selected"</if>>发送的token不合法</option>
											<option value="-999" <if condition="$_GET['status'] eq '-999'">selected="selected"</if>>频繁发送</option>
										</select>&nbsp;&nbsp;
										<input type="submit" value="查询" class="btn btn-success" style="padding:2px 14px;"/>　
										<if condition="in_array(250,$house_session['menus'])">
										<a class="btn btn-success" style="padding:2px 14px;" href="{pigcms{:U('Village_money/village_sms_send_export',$_GET)}">导出</a>
										<else/>
											<button class="btn btn-success" disabled="disabled" style="padding:2px 14px;">导出</button>
										</if>
									</form>	
								</div>

								<div class="grid-view">
									<table class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>编号</th>
												<th>发送到手机</th>
												<th>发送类型</th>
												<th>发送时间</th>
												<th>发送内容</th>
												<th>类型</th>
												<th>状态</th>
											</tr>
										</thead>
										<tbody>
											<volist name="record_list" id="vo">
												<tr>
													<td>{pigcms{$vo.pigcms_id}</td>
													<td>{pigcms{$vo.phone}</td>
													<td><if condition="$vo['sendto'] eq 'user'">顾客<else />商家</if></td>
													<td>{pigcms{$vo.time|date="Y-m-d H:i:s",###}</td>
													<td>{pigcms{$vo.text}</td>
													<td>
														<if condition="$vo['type'] eq 'food'">订餐
														<elseif condition="$vo['type'] eq 'takeout'" />外卖
														<elseif condition="$vo['type'] eq 'group'" />团购
														<elseif condition="$vo['type'] eq 'shop'" />快店
														<elseif condition="$vo['type'] eq 'village_express'" />社区快递
														<elseif condition="$vo['type'] eq 'village_vistor'" />社区访客
														<elseif condition="$vo['type'] eq 'meal'" />顺风车
														</if>
													</td>

													<td><if condition="isset($status[$vo['status']])">{pigcms{$status[$vo['status']]}<else/>{pigcms{$vo.status}</if></td>
												</tr>
											</volist>
											
										</tbody>
									</table>
									{pigcms{$pagebar}
								</div>

							</div>

							<div class="tab-pane <if condition="$_GET['type'] eq 'buy'">active</if>">
								<div style="margin-top:10px;margin-bottom: 10px;" >
									<form action="{pigcms{:U('Stroe/group_list')}" method="get"> 
										<input type="hidden" name="c" value="Village_money"/>
										<input type="hidden" name="a" value="sms_note"/>
										<input type="hidden" name="type" value="buy"/>&nbsp;&nbsp;
										日期筛选：
										<input type="text" class="input-text" name="start_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.start_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   
										<input type="text" class="input-text" name="end_time" style="width:120px;margin-left: 5px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>&nbsp;&nbsp;
										状态:
										<select name="paid">
											<option value="" <if condition="$_GET['status'] eq ''">selected="selected"</if>>状态</option>
											<option value="1" <if condition="$_GET['paid'] eq '1'">selected="selected"</if>>支付成功</option>
											<option value="3" <if condition="$_GET['paid'] eq '3'">selected="selected"</if>>管理员操作</option>
											
										</select>&nbsp;&nbsp;
										<input type="submit" value="查询" class="btn btn-success" style="padding:2px 14px;"/>　
										<if condition="in_array(251,$house_session['menus'])">
										<a class="btn btn-success" style="padding:2px 14px;" href="{pigcms{:U('Village_money/village_sms_buy_export',$_GET)}">导出</a>
										<else/>
											<button class="btn btn-success" disabled="disabled" style="padding:2px 14px;">导出</button>
										</if>
									</form>	
								</div>

								<div class="grid-view">
									<table class="table table-striped table-bordered table-hover">
										<thead>
											<tr>
												<th>编号</th>
												<th>订单号</th>
												<th>支付金额</th>
												<th>购买条数</th>
												<th>添加时间</th>
												<th>支付时间</th>
												<th>支付方式</th>
												<th>操作类型</th>
												
												<th>状态</th>
											</tr>
										</thead>
										<tbody>
											<volist name="orderList" id="vo">
												<tr>
													<td>{pigcms{$vo.order_id}</td>
													<td>{pigcms{$vo.orderid}</td>
													<td>{pigcms{$vo.payment_money}</td>
													<td>{pigcms{$vo.sms_number}</td>
													<td>{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</td>
													<td><if condition="$vo['pay_time']">{pigcms{$vo.pay_time|date="Y-m-d H:i:s",###}</if></td>
													<td><if condition="$vo['pay_type'] eq 'yue'">余额<elseif condition="$vo['pay_type'] eq 'weixin'" />微信<elseif condition="$vo['pay_type'] eq 'system'" />管理员操作</if></td>
													<td><if condition="$vo['set_type'] eq '1'"><span style="color: red;">减少</span><else/><span style="color: #42fb05;">增加</span></if></td>
													
													<td><if condition="$vo['paid'] eq '0'">未支付<elseif condition="$vo['paid'] eq '1'" />支付成功<else/>管理员操作</if></td>
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
		</div>
	</div>
</div>
<style>
	.my_money span{
		padding: 9px 42px;
		border: 1px solid #fff;
		margin-left: 10px;
		color:#fff;
		border-radius:1px;
	}
	.my_money a:hover {text-decoration:none;}
	.widget-header {
		height: 100px;
	}
	.widget-header div{
		text-align:center;
		float:left;
		height:100%;
	}
	.widget-header p{
		text-align:center;
	}
	.h_title{
		margin-top:10px;
		font-weight: 400;
		font-style: normal;
		font-size: 14px;
		color:#000;
	}
	
	.h_value{
		font-family: 'Arial Negreta', 'Arial';
		font-weight: 700;
		font-style: normal;
		font-size: 28px;
		color:#000;
	}
	.tab-content a.on {
		background: #498CD0;
		color: #FFF;
		padding: 4px 7px;
		text-decoration: none;
	}
</style>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/jquery.artDialog.js"></script>
<script type="text/javascript" src="{pigcms{$static_public}js/artdialog/iframeTools.js"></script>
<script>
	$('.bind_info').click(function(){
		art.dialog.open($(this).attr('href'),{
			init: function(){
				var iframe = this.iframe.contentWindow;
				window.top.art.dialog.data('iframe_handle',iframe);
			},
			id: 'handle',
			title:'购买短信',
			padding: 0,
			width: 800,
			height: 603,
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
</script>

<script src="{pigcms{$static_path}js/echarts-plain.js"></script>

<include file="Public:footer"/>

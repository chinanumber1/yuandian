<include file="Public:header"/>
	<div class="mainbox">
		<div id="nav" class="mainnav_title">
			<ul>
				<a href="{pigcms{:U('index')}">根列表</a>|
				<a href="{pigcms{:U('order',array('ride_id'=>$_GET['ride_id']))}" class="on">订单</a>|
			</ul>
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
						<col/>
						<col/>
						<col/>
						<col width="160" align="center"/>
					</colgroup>
					<thead>
						<tr>
							<th>编号</th>
							<th>乘客ID</th>
							<th>乘客姓名</th>
							<th>乘客电话</th>
							<th>预定几位</th>
							<th>预定时间</th>
							<th>取消时间</th>
							<th>完成时间</th>
							<th>支付状态</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<if condition="is_array($list)">
							<volist name="list" id="vo">
								<tr>
									<td>{pigcms{$vo.order_id}</td>
									<td>{pigcms{$vo.user_id}</td>
									<td>{pigcms{$vo.truename}</td>
									<td>{pigcms{$vo.phone}</td>
									<td>{pigcms{$vo.sit_number}</td>
									<td>{pigcms{$vo.order_time|date='Y-m-d H:i',###}</td>
									<td><if condition="$vo['update_time']">{pigcms{$vo.update_time|date='Y-m-d H:i',###}</if></td>
									<td><if condition="$vo['complete_time']">{pigcms{$vo.complete_time|date='Y-m-d H:i',###}</if></td>
									<td>
										<if condition="$vo['paid'] eq 1"><font color="green">已支付</font>
											<elseif condition="$vo['paid'] eq 2"/><font color="gray">已退款</font>
											<elseif condition="$vo['paid'] eq 3"/><font color="red">已付款给车主</font>
											<elseif condition="$vo['paid'] eq 0"/><font color="red">未支付</font>
										</if>
									</td>
									<td>
										<if condition="$vo['status'] eq 1"><font color="green">预定成功</font>
											<elseif condition="$vo['status'] eq 2"/><font color="gray">司机取消</font>
											<elseif condition="$vo['status'] eq 3"/><font color="red">乘客取消</font>
											<elseif condition="$vo['status'] eq 4"/><font color="red">已完成</font>
										</if>
									</td>
									<td>
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/money_list',array('uid'=>$vo['user_id'],'ask'=>2,'ask_id'=>$vo['ride_id']))}','查看用户余额',600,400,true,false,false,false,'detail',true);">用户余额</a>
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
<include file="Public:footer"/>
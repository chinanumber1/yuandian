<include file="Public:header"/>
	<div class="mainbox">
		<div id="nav" class="mainnav_title">
			<ul>
				<a href="{pigcms{:U('crow_list')}">根列表</a>|
				<a href="{pigcms{:U('crow_order',array('package_id'=>$_GET['package_id']))}" class="on">订单</a>
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
						<col width="140" align="center"/>
					</colgroup>
					<thead>
						<tr>
							<th>编号</th>
							<th>接单人ID</th>
							<th>接单人</th>
							<th>联系电话</th>
							<th>状态</th>
							<th>抢单时间</th>
							<th>收货时间</th>
							<th>到达时间</th>
							<th>完成时间</th>
							<th>取消时间</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<if condition="is_array($list)">
							<volist name="list" id="vo">
								<tr>
									<td>{pigcms{$vo.order_id}</td>
									<td>{pigcms{$vo.user_id}</td>
									<td>{pigcms{$vo.user_name}</td>
									<td>{pigcms{$vo.user_phone}</td>
									<td>
										<if condition="$vo['status'] eq 1"><font color="green">抢单成功</font>
											<elseif condition="$vo['status'] eq 2"/><font color="gray">已收货</font>
											<elseif condition="$vo['status'] eq 3"/><font color="gray">已送货</font>
											<elseif condition="$vo['status'] eq 4"/><font color="gray">完成(已付款)</font>
											<elseif condition="$vo['status'] eq 5"/><font color="gray">取消订单</font>
										</if>
									</td>
									<td><if condition="$vo['order_time']">{pigcms{$vo['order_time']|date='Y-m-d',###}</if></td>
									<td><if condition="$vo['collect_time']">{pigcms{$vo['collect_time']|date='Y-m-d',###}</if></td>
									<td><if condition="$vo['give_time']">{pigcms{$vo['give_time']|date='Y-m-d',###}</if></td>
									<td><if condition="$vo['complete_time']">{pigcms{$vo['complete_time']|date='Y-m-d',###}</if></td>
									<td><if condition="$vo['cancel_time']">{pigcms{$vo['cancel_time']|date='Y-m-d',###}</if></td>
									<td>
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/money_list',array('uid'=>$vo['user_id'],'ask'=>1,'ask_id'=>$vo['package_id']))}','查看用户余额',600,400,true,false,false,false,'detail',true);">用户余额</a>
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
<include file="Public:header"/>
	<div class="mainbox">
		<div id="nav" class="mainnav_title">
			<ul>
				<a href="{pigcms{:U('index')}" class="on">根列表</a>|
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
						<col/>
						<col width="160" align="center"/>
					</colgroup>
					<thead>
						<tr>
							<th>编号</th>
							<th>标题</th>
							<th>城市</th>
							<th>司机ID</th>
							<th>司机姓名</th>
							<th>司机电话</th>
							<th>出发地</th>
							<th>目的地</th>
							<th>天数</th>
							<th>出发时间</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<if condition="is_array($list)">
							<volist name="list" id="vo">
								<tr>
									<td>{pigcms{$vo.ride_id}</td>
									<td>{pigcms{$vo.ride_title}</td>
									<td>{pigcms{$vo.area_name}</td>
									<td>{pigcms{$vo.user_id}</td>
									<td>{pigcms{$vo.owner_name}</td>
									<td>{pigcms{$vo.owner_phone}</td>
									<td>{pigcms{$vo.departure_place}</td>
									<td>{pigcms{$vo.destination}</td>
									<td><if condition="$vo['ride_date_number'] eq 1"><font color="">当天</font><else/><font color="">全天</font></if></td>
									<td>{pigcms{$vo.start_time|date='Y-m-d H:i',###}</td>
									<td>
										<if condition="$vo['status'] eq 1"><font color="green">启用</font>
											<elseif condition="$vo['status'] eq 2"/><font color="gray">过期</font>
											<elseif condition="$vo['status'] eq 3"/><font color="red">人满</font>
											<elseif condition="$vo['status'] eq 4"/><font color="red">司机停止</font>
											<elseif condition="$vo['status'] eq 5"/><font color="red">司机暂停</font>
											<elseif condition="$vo['status'] eq 6"/><font color="red">关闭</font>
										</if>
									</td>
									<td>
										<!-- <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('show',array('ride_id'=>$vo['ride_id'],'frame_show'=>true))}','查看详细信息',600,400,true,false,false,false,'detail',true);">查看</a> -->

										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('show',array('ride_id'=>$vo['ride_id']))}','查看详细信息',580,450,true,false,false,editbtn,'add',true);">查看</a>

										| <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/money_list',array('uid'=>$vo['user_id'],'ask'=>2,'ask_id'=>$vo['ride_id']))}','查看用户余额',600,400,true,false,false,false,'detail',true);">用户余额</a>
										<if condition="$vo['order_id']">| <a href="{pigcms{:U('order',array('ride_id'=>$vo['ride_id']))}">订单</a></if>
									</td>
								</tr>
							</volist>
							<tr><td class="textcenter pagebar" colspan="12">{pigcms{$pagebar}</td></tr>
						<else/>
							<tr><td class="textcenter red" colspan="12">列表为空！</td></tr>
						</if>
					</tbody>
				</table>
			</div>
		</form>
	</div>
<include file="Public:footer"/>
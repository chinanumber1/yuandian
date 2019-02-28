<include file="Public:header"/>
		<div class="mainbox">
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col width="240" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>用户</th>
								<th>姓名</th>
								<th>手机</th>
								<th>人数</th>
								<th>响应时间</th>
								<th>状态</th>
								<th>有车</th>
								<th>响应人</th>
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
										<td>{pigcms{$vo.day_number}人</td>
										<td>{pigcms{$vo.create_time|date='Y-m-d H:i',###}</td>
										<td>
										<if condition="$vo['rela_status'] eq 1">
											<font color="green">已结伴</font>
										<elseif condition="$vo['rela_status'] eq 2"/>
											<font color="red">用户关闭</font>
										<elseif condition="$vo['rela_status'] eq 3"/>
											<font color="gray">已完成</font>
										<elseif condition="$vo['rela_status'] eq 4"/>
											<font color="gray">系统关闭</font>
										<elseif condition="$vo['rela_status'] eq 5"/>
											<font color="gray">响应人关闭</font>
										</if></td>
										<td><if condition="$vo['is_car'] eq 1">
											<font color="green">有车</font>
										<elseif condition="$vo['is_car'] eq 2"/>
											<font color="green">无车</font>
										</if></td>
										<td><if condition="$vo['is_mate'] eq 1">
											<font color="green">是</font>
										<elseif condition="$vo['is_mate'] eq 2"/>
											<font color="red">否</font>
										</if></td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="15">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="8">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
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
						</colgroup>
						<thead>
							<tr>
								<th>时间</th>
								<th>详情</th>
								<th>数量（ 条 ）</th>
								<th>类型</th>
								<th>当前条数</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($orderList)">
								<volist name="orderList" id="vo">
									<tr>
										<td>{pigcms{$vo.pay_time|date="Y-m-d H:i:s",###}</td>
										<td>{pigcms{$vo.operation} 管理员手动操作</td>
										<td>{pigcms{$vo.sms_number}</td>
										<td><if condition="$vo['set_type'] eq 1">减少<else/>增加</if></td>
										<td>{pigcms{$vo.current_number}</td>
									</tr>
								</volist>
							<else/>
								<tr><td class="textcenter red" colspan="9">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
		<script type="text/javascript" src="{pigcms{$static_path}js/area_pca.js"></script>
<include file="Public:footer"/>
<include file="Public:header"/>
	<div class="mainbox">
		<div id="nav" class="mainnav_title">
			<ul>
				<a href="{pigcms{:U('crow_list')}" class="on">根列表</a>|
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
						<col width="160" align="center"/>
					</colgroup>
					<thead>
						<tr>
							<th>编号</th>
							<th>物品名称</th>
							<th>出发地</th>
							<th>目的地</th>
							<th>运费</th>
							<th>押金</th>
							<th>实名</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<if condition="is_array($list)">
							<volist name="list" id="vo">
								<tr>
									<td>{pigcms{$vo.package_id}</td>
									<td>{pigcms{$vo.package_title}</td>
									<td>{pigcms{$vo.package_start}</td>
									<td>{pigcms{$vo.package_end}</td>
									<td>{pigcms{$vo.package_money}</td>
									<td>{pigcms{$vo.package_deposit}</td>
									<td><if condition="$vo['is_authentication'] eq 1"><font color="green">需实名</font><else/><font color="gray">不需实名</font></if></td>
									<td><if condition="$vo['package_status'] eq 1"><font color="green">启用</font><elseif condition="$vo['package_status'] eq 2"/><font color="gray">关闭</font><else/><font color="red">送货中</font></if></td>
									<td>
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('crow_show',array('package_id'=>$vo['package_id'],'frame_show'=>true))}','查看详细信息',600,400,true,false,false,false,'detail',true);">查看</a>
										| <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/money_list',array('uid'=>$vo['user_id'],'ask'=>1,'ask_id'=>$vo['package_id']))}','查看用户余额',600,400,true,false,false,false,'detail',true);">用户余额</a>
										<if condition="$vo['order_id']">| <a href="{pigcms{:U('crow_order',array('package_id'=>$vo['package_id']))}">订单</a></if>
									</td>
								</tr>
							</volist>
							<tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>
						<else/>
							<tr><td class="textcenter red" colspan="9">列表为空！</td></tr>
						</if>
					</tbody>
				</table>
			</div>
		</form>
	</div>
<include file="Public:footer"/>
<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Cardedit/index')}" >实体卡列表</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Cardedit/add_card')}','批量添加实体卡',680,560,true,false,false,addbtn,'edit',true);">批量添加实体卡</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Cardedit/mutil_bind_merid')}','批量添加实体卡',680,560,true,false,false,addbtn,'edit',true);">批量绑定商户</a>
					<a href="{pigcms{:U('Cardedit/log')}" class="on">实体卡操作记录</a>
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
							<col  align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>ID</th>
								<th>实体卡ID</th>
								<th>系统管理员</th>
								<th>商家</th>
								<th>店员</th>
								<th>描述</th>
								<th >添加时间</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($log_list)">
								<volist name="log_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.card_id}</td>
										<td>{pigcms{$vo.admin_name}</td>
										<td>{pigcms{$vo.mer_name}</td>
										<td>{pigcms{$vo.staff_name}</td>
										<td>{pigcms{$vo.des}</td>
										
										<td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
									
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="7">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="7">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
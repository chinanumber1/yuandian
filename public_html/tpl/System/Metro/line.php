<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Metro/line')}" class="on">地铁线列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Metro/createLine')}','添加地铁线',600,400,true,false,false,addbtn,'add',true);">添加地铁线</a>|
					<a href="{pigcms{:U('Metro/station')}">地铁站列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Metro/createStation')}','添加地铁站',600,400,true,false,false,addbtn,'store_add',true);">添加地铁站</a>
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
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>排序</th>
								<th>城市</th>
								<th>名称</th>
								<th>是否热门</th>
								<th>状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="!empty($lines)">
								<volist name="lines" id="line">
									<tr>
										<td>{pigcms{$line.id}</td>
										<td>{pigcms{$line.sort}</td>
										<td>{pigcms{$line.city}</td>
										<td>{pigcms{$line.name}</td>
										<td>
											<if condition="$line['is_hot'] eq 1"><font color="green">是</font><elseif condition="$line['is_hot'] eq 0"/><font color="red">否</font></if>
										</td>
										<td>
											<if condition="$line['status'] eq 0"><font color="red">禁止</font><elseif condition="$line['status'] eq 1"/><font color="green">正常</font></if>
										</td>
										<td class="textcenter">
											
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Metro/editLine',array('id'=>$line['id']))}','编辑',480,500,true,false,false,editbtn,'edit',true);">编辑</a> |
											<a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$line.id}" url="{pigcms{:U('Metro/destroyLine')}">删除</a>
									  	</td>
									</tr>
								</volist>
							<else/>
								<tr><td class="textcenter red" colspan="5">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
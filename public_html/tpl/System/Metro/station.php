<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Metro/line')}">地铁线列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Metro/createLine')}','添加地铁线',600,400,true,false,false,addbtn,'add',true);">添加地铁线</a>|
					<a href="{pigcms{:U('Metro/station')}" class="on">地铁站列表</a>|
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
								
								<th>城市</th>
								<th>名称</th>
								<th>状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="!empty($stations)">
								<volist name="stations" id="station">
									<tr>
										<td>{pigcms{$station.id}</td>
										
										<td>{pigcms{$station.city}</td>
										<td>{pigcms{$station.first_word}.{pigcms{$station.name}</td>
										
										<td>
											<if condition="$station['status'] eq 0"><font color="red">禁止</font><elseif condition="$station['status'] eq 1"/><font color="green">正常</font></if>
										</td>
										<td class="textcenter">
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Metro/editstation',array('id'=>$station['id'],'frame_show'=>true))}','查看',480,260,true,false,false,false,'detail',true);">查看</a> |
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Metro/editstation',array('id'=>$station['id']))}','编辑',480,260,true,false,false,editbtn,'store_add',true);">编辑</a> |
											<a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$station.id}" url="{pigcms{:U('Metro/destroyStation')}">删除</a>
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
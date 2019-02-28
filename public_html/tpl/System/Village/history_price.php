<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Village/index')}" class="on">历史价格</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Village/add_history_price',array('village_id'=>$village_id,'village_name'=>$village_name))}','添加历史价格',600,600,true,false,false,addbtn,'store_add',true);">添加历史价格</a>|
					
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
								<th>价格</th>
								<th>时间</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$history_price_list">
								<volist name="history_price_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td><?php echo isset($areas[$vo['city_id']])?$areas[$vo['city_id']]['area_name']:'';?></td>
										<td>{pigcms{$village_name}</td>
										<td>{pigcms{$vo.price}</td>
										<td>{pigcms{$vo.dateline|date="Y-m-d",###}</td>
										<td class="textcenter">
											<a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('Village/del_price')}">删除</a>
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
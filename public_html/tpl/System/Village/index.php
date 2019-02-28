<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Village/index')}" class="on">小区列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Village/create')}','添加小区',600,600,true,false,false,addbtn,'store_add',true);">添加小区</a>|
					
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
								<th>小区相册</th>
								<th>详情信息</th>
								<th>小区相册</th>
								<th>历史价格</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="!empty($villages)">
								<volist name="villages" id="village">
									<tr>
										<td>{pigcms{$village.village_id}</td>
										<td>{pigcms{$village.city}</td>
										<td>{pigcms{$village.first_word}.{pigcms{$village.name}</td>
										<td>{pigcms{$village.address}</td>
										<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Village/info_edit',array('id'=>$village['village_id']))}','详情信息',800,800,true,false,false,editbtn,'store_add',true);">详情信息</a</td>
										<td><a href="{pigcms{:U('Village/img_list',array('village_id'=>$village['village_id'],'name'=>$village['name']))}" >小区相册</a></td>
										<td><a href="{pigcms{:U('Village/history_price',array('village_id'=>$village['village_id'],'village_name'=>$village['name']))}">历史价格</a></td>
										<td class="textcenter">
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Village/edit',array('id'=>$village['village_id'],'frame_show'=>true))}','查看',600,700,true,false,false,false,'detail',true);">查看</a> |
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Village/edit',array('id'=>$village['village_id']))}','编辑',600,700,true,false,false,editbtn,'store_add',true);">编辑</a> |
											<a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$village.village_id}" url="{pigcms{:U('Village/destroy')}">删除</a>
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
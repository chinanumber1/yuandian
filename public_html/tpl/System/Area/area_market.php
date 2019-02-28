<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Area/index')}" <if condition="$_GET['type'] eq 1">class="on"</if>>根列表</a>|
					<a href="{pigcms{:U('Area/index',array('type'=>'4','pid'=>$now_area['area_pid']))}">上一级</a>
					<a href="{pigcms{:U('Area/area_market',array('pid'=>$area_id))}" class="on">商场列表</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Area/add_market',array('area_id'=>$area_id))}','添加商场',450,320,true,false,false,addbtn,'store_add',true);">添加商场</a>
				</ul>
			</div>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
							<col/>
							<if condition="$_GET['type'] eq 2 || $_GET['type'] eq 4">
								<col/>
							</if>
							<col/>
							<if condition="$_GET['type'] gt 1">
								<col/>
								<if condition="$_GET['type'] lt 4">
									<col/>
								</if>
							</if>
							<if condition="$_GET['type'] lt 4">
								<col/>
							</if>
							<col width="240" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>排序</th>
								<th>编号</th>
								<th>名称</th>
								<th>状态</th>
								<th>热门</th>
								<th>图片</th>
								<th>商场介绍</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($area_list)">
								<volist name="area_list" id="vo">
									<tr>
										<td>{pigcms{$vo.market_sort}</td>
										<td>{pigcms{$vo.market_id}</td>
										<td>{pigcms{$vo.market_name}</td>
										<td><if condition="$vo['is_open']"><font color="green">显示</font><else/><font color="red">隐藏</font></if></td>
										<td><if condition="$vo['is_hot']"><font color="green">是</font><else/><font color="red">否</font></if></td>
										<td><img style="width:50px;height:50px;" src="{pigcms{$vo.img}" /></td>
										<td style="width:300px;">{pigcms{$vo.introduce}</td>
										<td>
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Area/edit_market',array('market_id'=>$vo['market_id'],'area_id'=>$vo['area_id']))}','编辑商场',450,320,true,false,false,editbtn,'store_add',true);">编辑</a> |
											<a href="javascript:void(0);" class="delete_row" parameter="market_id={pigcms{$vo.market_id}" url="{pigcms{:U('Area/del_market')}">删除</a>
										</td>
									</tr>
								</volist>
							<else/>
								<tr><td class="textcenter red" colspan="8">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
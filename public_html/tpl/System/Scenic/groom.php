<include file="Public:header"/>
	<div class="mainbox">
		<div id="nav" class="mainnav_title">
			<ul>
				<a href="{pigcms{:U('groom')}" class="on">根列表</a>|
				<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('groom_add')}','添加分类',500,300,true,false,false,addbtn,'add',true);">添加分类</a>
			</ul>
		</div>
		<form name="myform" id="myform" action="" method="post">
			<div class="table-list">
				<table width="100%" cellspacing="0">
					<colgroup>
						<col/>
						<col/>
						<col/>
						<if condition="$many_city eq 1">
							<col/>
						</if>
						<col/>
						<col/>
						<col/>
						<col/>
						<col width="140" align="center"/>
					</colgroup>
					<thead>
						<tr>
							<th>排序</th>
							<th>编号</th>
							<th>分类名</th>
							<if condition="$many_city eq 1">
								<th>城市</th>
							</if>
							<th>补齐</th>
							<th>图片</th>
							<th>进入下级</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<if condition="is_array($list)">
							<volist name="list" id="vo">
								<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
									<td>{pigcms{$vo.cat_sort}</td>
									<td>{pigcms{$vo.cat_id}</td>
									<td>{pigcms{$vo.cat_name}</td>
									<if condition="$many_city eq 1">
										<if condition="$vo['city_id'] eq '通用'">
											<td style="color:red;">{pigcms{$vo.city_id}</td>
										<else/>
											<td>{pigcms{$vo.city_id}</td>
										</if>
									</if>
									<td><if condition="$vo.complete eq 1"><span style="color:red;">是</span><else/>否</if></td>
									<td><img style="width:50px;height:50px;" src="{pigcms{$vo['cat_img']}" /></td>
									<td><a href="{pigcms{:U('com_list',array('cat_id'=>$vo['cat_id']))}">商品列表</a></td>
									<td>
										<switch name="vo['status']">
											<case value="0"><span style="color:red;">关闭</span></case>
											<case value="1"><span style="color:green;">开启</span></case>
										</switch>
									</td>
									<td>
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('groom_edit',array('cat_id'=>$vo['cat_id']))}','编辑分类',500,300,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" url="{pigcms{:U('groom_del',array('cat_id'=>$vo['cat_id']))}">删除</a>
									</td>
								</tr>
							</volist>
							<tr><td class="textcenter pagebar" colspan="15">{pigcms{$pagebar}</td></tr>
						<else/>
							<tr><td class="textcenter red" colspan="15">列表为空！</td></tr>
						</if>
					</tbody>
				</table>
			</div>
		</form>
	</div>
<include file="Public:footer"/>
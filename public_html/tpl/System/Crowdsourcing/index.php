<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('index')}" class="on">根列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('add')}','添加分类',480,300,true,false,false,addbtn,'add',true);">添加分类</a>
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
							<col width="240" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>排序</th>
								<th>编号</th>
								<th>名称</th>
								<th>图片</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($list)">
								<volist name="list" id="vo">
									<tr>
										<td>{pigcms{$vo.category_sort}</td>
										<td>{pigcms{$vo.category_id}</td>
										<td>{pigcms{$vo.category_name}</td>
										<td><img style="withd:60px;height:60px;" src="{pigcms{$vo['category_img']}" /></td>
										<td><if condition="$vo['category_status']"><font color="green">启用</font><else/><font color="red">未用</font></if></td>
										<td>
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('edit',array('category_id'=>$vo['category_id']))}','编辑分类',450,320,true,false,false,editbtn,'add',true);">编辑</a> |
											<a href="javascript:void(0);" class="delete_row" parameter="category_id={pigcms{$vo.category_id}" url="{pigcms{:U('del')}">删除</a>
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
<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('circle/cateAdd')}','添加{pigcms{$now_type_str}',450,320,true,false,false,addbtn,'add',true);">添加新的动态</a>
				</ul>
			</div>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						
						<thead>
							<tr>
								<th>排序</th>
								<th>名称</th>
								<th>父类</th>
								<th>创建时间</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($cateList)">
								<volist name="cateList" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.name}</td>
										<td>{pigcms{$vo.fid}</td>
										<td>{pigcms{$vo.addTime|date='Y-m-d',###}</td>
										<td>编辑|删除</td>
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
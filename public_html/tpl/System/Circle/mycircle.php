<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a  href="javascript:void(0);" onclick="window.history.go(-1)">返回</a>|<a >圈子列表</a>
				</ul>
			</div>
            <form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						
						<thead>
							<tr>
								<th class="textcenter">排序</th>
								<th class="textcenter">分类</th>
								<th class="textcenter">小圈子</th>
								<th class="textcenter">状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($res)">
								<volist name="res" id="vo">
									<tr>
										<td class="textcenter">{pigcms{$vo.id}</td>
										<td class="textcenter">
										<volist name="fu" id="fuqin">
										<if condition="$vo['cate_id'] eq $fuqin['id']">{pigcms{$fuqin.name}</if> 
										</volist>
										</td>
										<td class="textcenter">{pigcms{$vo.name}</td>
										<td class="textcenter">
										<if condition="$vo['status'] eq 1">隐藏<else/>显示</if> 
										</td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Circle/modifyRelation',array('id'=>$vo['id']))}','修改状态',450,100,true,false,false,editbtn,'edit',true);">修改</a> </td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="5">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="5">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
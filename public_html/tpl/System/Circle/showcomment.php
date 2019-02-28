<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a  href="javascript:void(0);" onclick="window.history.go(-1)">返回</a>|<a >评论列表</a>
				</ul>
			</div>
            <form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						
						<thead>
							<tr>
								<th class="textcenter" style="width:5%;">排序</th>
								<th class="textcenter" style="width:10%;">评论文章</th>
								<th class="textcenter" style="width:5%;">所属圈子</th>
								<th class="textcenter" style="width:5%;">评论人</th>
								<th class="textcenter" style="width:55%;">评论内容</th>
								<th class="textcenter" style="width:5%;">评论时间</th>
								<th class="textcenter" style="width:5%;">评论状态</th>
								<th class="textcenter" style="width:10%;">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($res)">
								<volist name="res" id="vo">
									<tr>
										<td class="textcenter">{pigcms{$vo.id}</td>
										<td class="textcenter">{pigcms{$vo.title}</td>
										<td class="textcenter">{pigcms{$vo.quanzi}</td>
										<td class="textcenter">{pigcms{$vo.name}</td>
										<td class="textcenter">{pigcms{$vo.content}</td>
										<td class="textcenter">{pigcms{$vo.add_time|date='Y-m-d',###}</td>
									    <td class="textcenter">
										<if condition="$vo['status'] eq 1">隐藏<else/>显示</if> 
										</td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Circle/editcomment',array('id'=>$vo['id']))}','编辑评论',850,300,true,false,false,editbtn,'edit',true);">编辑评论</a> | <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('Circle/delcomment')}">删除</a></td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="8">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="8">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
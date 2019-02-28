<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
				   <li>
					<a  href="javascript:void(0);" onclick="window.history.go(-1)">返回</a>|<a>收藏列表</a>
					</li>
				</ul>
			</div>
            <form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						
						<thead>
							<tr>
								<th class="textcenter">排序</th>
								<th class="textcenter">文章标题</th>
								<th class="textcenter">收藏时间</th>
								<th class="textcenter">收藏状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($res)">
								<volist name="res" id="vo">
									<tr>
										<td class="textcenter">{pigcms{$vo.id}</td>
										<td class="textcenter">{pigcms{$vo.title}</td>
										<td class="textcenter">{pigcms{$vo.add_time|date='Y-m-d',###}</td>
										<td class="textcenter">
										<if condition="$vo['status'] eq 1">隐藏<else/>显示</if> 
										</td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Circle/seearticle',array('did'=>$vo['did']))}','查看文章',850,700,true,false,false,editbtn,'edit',true);">查看文章</a> | <a href="javascript:void(0);" class="delete_row" parameter="did={pigcms{$vo.id}" url="{pigcms{:U('Circle/delcollection')}">删除收藏</a>
										| <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Circle/modifyCollection',array('id'=>$vo['id']))}','修改收藏',450,100,true,false,false,editbtn,'edit',true);">修改收藏</a></td>
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
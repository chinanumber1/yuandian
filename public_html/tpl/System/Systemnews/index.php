<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Systemnews/index')}" class="on">平台快报</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/add_category')}','添加平台快报分类',800,460,true,false,false,addbtn,'add',true);">添加平台快报分类</a>
				</ul>
			</div>
			
			
			<!--<p>网站首页会显示最前面10条快报。置顶的快报会优先显示，并将悬浮在页面顶部。</p>-->
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>分类名称</th>
								<th>内容列表</th>
								<th>排序</th>
								<th>状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($category)">
								<volist name="category" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.name}</td>
										<td><a href="{pigcms{:U('Systemnews/news',array('category_id'=>$vo['id']))}">查看内容</a></td>
										<td>{pigcms{$vo.sort}</td>
										<td><if condition="$vo['status'] eq 1"><font color="green">启用</font><else/><font color="red">禁止</font></if></td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/edit_category',array('id'=>$vo['id']))}','编辑快报',800,460,true,false,false,editbtn,'edit',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="category_id={pigcms{$vo.id}" url="{pigcms{:U('Systemnews/del',array('category_id'=>$vo['id']))}">删除</a></td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="9">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Portal/article')}">资讯列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/article_add')}','添加资讯',780,450,true,false,false,addbtn,'add',true);">添加资讯</a>
					<a href="{pigcms{:U('Portal/article_label')}">标签列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/article_label_add')}','添加标签',480,200,true,false,false,addbtn,'add',true);">添加标签</a>
					<a href="{pigcms{:U('Portal/article_source')}" class="on">资讯来源列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/article_source_add')}','添加来源',480,200,true,false,false,addbtn,'add',true);">添加来源</a>
				</ul>
			</div>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
								<th class="textcenter">ID</th>
								<th class="textcenter">来源名称</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$lists">
							<volist name="lists" id="vo">
							<tr>
								<td class="textcenter">{pigcms{$vo.id}</td>
								<td class="textcenter">{pigcms{$vo.title}</td>
								<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/article_source_add',array('source_id'=>$vo['id']))}','编辑来源',480,200,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="source_id={pigcms{$vo.id}" url="{pigcms{:U('Portal/article_source_del')}">删除</a></td>
							</tr>
							</volist>
							<else/>
								<tr><td class="textcenter red" colspan="12">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
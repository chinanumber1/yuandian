<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Systemnews/index')}">平台快报</a>
					<a href="{pigcms{:U('Systemnews/news',array('category_id'=>$_GET['category_id']))}" class="on">快报分类-{pigcms{$category_name}</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/add_news',array('category_id'=>$_GET['category_id']))}','添加平台快报',800,500,true,false,false,addbtn,'add',true);">添加平台快报</a>
				</ul>
			</div>
			
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Systemnews/news')}" method="get">
							<input type="hidden" name="c" value="Systemnews"/>
							<input type="hidden" name="a" value="news"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="title" <if condition="$_GET['searchtype'] eq 'title'">selected="selected"</if>>快报标题</option>
								<option value="id" <if condition="$_GET['searchtype'] eq 'id'">selected="selected"</if>>快报ID</option>
							</select>
							<input type="submit" value="查询" class="button"/>
						</form>
					</td>
				</tr>
			</table>
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
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>标题</th>
								<th>添加时间</th>
								<th>最后修改时间</th>
								<th>排序</th>
								<th>状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($news_list)">
								<volist name="news_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.title}</td>
										<td>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</td>
										<td>{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</td>
										<td>{pigcms{$vo.sort}</td>
										<td><if condition="$vo['status'] eq 1"><font color="green">启用</font><else/><font color="red">禁用</font></if></td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/edit_news',array('id'=>$vo['id'],'frame_show'=>true))}','查看内容',1000,640,true,false,false,false,'add',true);">查看</a> | <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Systemnews/edit_news',array('id'=>$vo['id']))}','编辑快报',800,500,true,false,false,editbtn,'edit',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('Systemnews/del',array('id'=>$vo['id']))}">删除</a></td>
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
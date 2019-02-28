<include file="Public:header"/>
<style type="text/css">
.red{ color:red}
.green{ color:green}
</style>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
						<a href="{pigcms{:U('news_list')}" class="on">新闻列表</a>|
						<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('news_add')}','添加新闻',1000,600,true,false,false,addbtn,'add',true);">添加新闻</a>
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
                            <col/>
                            <col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>排序</th>
								<th>编号</th>
								<th>标题</th>
                                <th>发表时间</th>
                                <th>添加时间</th>
                                <th>最后修改时间</th>
								<th>状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$list['list']">
								<volist name="list['list']" id="vo">
									<tr>
										<td>{pigcms{$vo.sort}</td>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.title}</td>
                                        <td><if condition='$vo["publish_time"]'>{pigcms{$vo.publish_time|date='Y-m-d H:i:s',###}</if></td>
                                        <td><if condition='$vo["add_time"]'>{pigcms{$vo.add_time|date='Y-m-d H:i:s',###}</if></td>
                                        <td><if condition='$vo["last_time"]'>{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</if></td>
                                        <td><if condition='$vo["status"] eq 1'><span class="green">开启</span><else /><span class="red">关闭</span></if></td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/news_edit',array('id'=>$vo['id']))}','编辑新闻信息',1000,600,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('news_del')}">删除</a>
                                         </td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="9">{pigcms{$list.pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="9">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
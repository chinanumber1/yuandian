<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('mate')}">根列表</a>|
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
							<col/>
							<col width="240" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>省</th>
								<th>市</th>
								<th>景区</th>
								<th>响应人数</th>
								<th>留言人数</th>
								<th>开始时间</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($list)">
								<volist name="list" id="vo">
									<tr>
										<td>{pigcms{$vo.mate_id}</td>
										<td>{pigcms{$vo.province_id}</td>
										<td>{pigcms{$vo.city_id}</td>
										<td>{pigcms{$vo.scenic_id}</td>
										<td>{pigcms{$vo.people_number}人</td>
										<td>{pigcms{$vo.words_number}</td>
										<td>{pigcms{$vo.start_time}</td>
										<td>
										<if condition="$vo['mate_status'] eq 1">
											<font color="green">未开始</font>
										<elseif condition="$vo['mate_status'] eq 2"/>
											<font color="red">进行中</font>
										<elseif condition="$vo['mate_status'] eq 3"/>
											<font color="gray">已结束</font>
										<elseif condition="$vo['mate_status'] eq 4"/>
											<font color="gray">系统关闭</font>
										<elseif condition="$vo['mate_status'] eq 5"/>
											<font color="gray">响应人关闭</font>
										</if></td>
										<td>
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('mate_order',array('mate_id'=>$vo['mate_id']))}','查看响应',700,400,true,false,false,editbtn,'add',true);">查看响应</a><if condition="$vo['mate_status'] eq 1"> |
											<a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('mate_close',array('mate_id'=>$vo['mate_id']))}">关闭</a></if>
										</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="15">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="8">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
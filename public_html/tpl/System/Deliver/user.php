<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/user')}" class="on">配送员管理</a>|
					<a href="{pigcms{:U('Deliver/reply')}">配送员评论列表</a>|
					<a href="{pigcms{:U('Deliver/cancel')}">配送员扔回订单列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('user_add')}','添加配送员',800,560,true,false,false,editbtn,'edit',true);">添加配送员</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Deliver/user')}" method="get">
							<input type="hidden" name="c" value="Deliver"/>
							<input type="hidden" name="a" value="user"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="uid" <if condition="$_GET['searchtype'] eq 'uid'">selected="selected"</if>>用户ID</option>
								<option value="nickname" <if condition="$_GET['searchtype'] eq 'nickname'">selected="selected"</if>>昵称</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>手机号</option>
							</select>
							<input type="submit" value="查询" class="button"/>
						</form>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>ID</th>
								<th>排序</th>
								<th>昵称</th>
								<th>手机号</th>
								<th>常驻地址</th>
								<th>最后修改时间</th>
								<th>配送范围（公里）</th>
								<th class="textcenter">状态</th>
								<if condition="$system_session['level'] eq 2">
									<th class="textcenter">配送总量</th>
									<th class="textcenter">本月单量</th>
								</if>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($user_list)">
								<volist name="user_list" id="vo">
									<tr>
										<td>{pigcms{$vo.uid}</td>
										<td>{pigcms{$vo.sort|intval}</td>
										<td>{pigcms{$vo.name}</td>
										<td>{pigcms{$vo.phone}</td>
										<td>{pigcms{$vo.site}</td>
										<td>{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</td>
                                        <if condition="$vo['delivery_range_type'] eq 1">
                                            <td>自定义</td>
                                        <else/>
										    <td>{pigcms{$vo.range}</td>
                                        </if>
										<td class="textcenter"><if condition="$vo['status'] eq 1"><font color="green">正常</font><else/><font color="red">禁止</font></if></td>
										<if condition="$system_session['level'] eq 2">
											<td class="textcenter">{pigcms{$vo.num}</td>
											<td class="textcenter">{pigcms{$vo.month_count}</td>
										</if>
										<td class="textcenter">
										
										<if condition="$system_session['level']==2 OR in_array('258',$menus)">
										<a style="width: 60px;" class="" href="{pigcms{:U('Deliver/count_log',array('uid'=>$vo['uid']))}">历史记录统计</a>　 | 　</if>
										<if condition="$system_session['level']==2 OR in_array('259',$menus)">
										<a style="width: 60px;" class="" href="{pigcms{:U('Deliver/log_list',array('uid'=>$vo['uid']))}">查看配送记录</a>　 | </if>　
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Deliver/user_edit',array('uid'=>$vo['uid']))}','编辑用户信息',800,560,true,false,false,editbtn,'edit',true);">编辑</a>
										　 | 　<a href="javascript:void(0);" class="delete_row" parameter="uid={pigcms{$vo.uid}" url="{pigcms{:U('Deliver/del')}">删除</a>
										</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="11">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="11">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Deliver/user')}">配送员管理</a>|
					<a href="{pigcms{:U('Deliver/count_log',array('uid'=>$user['uid']))}" class="on">【{pigcms{$user['name']}】的每日配送量统计</a>
				</ul>
			</div>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
								<th>日期</th>
								<th>配送量</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($count_list)">
								<volist name="count_list" id="vo">
									<tr>
										<td>{pigcms{$vo.today}</td>
										<td>{pigcms{$vo.num}</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="2">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="2">该配送员还没有统计记录</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
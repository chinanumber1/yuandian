<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('scenic_list')}">根列表</a>|
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td style="width:50%;">
						<form action="{pigcms{:U('scenic_list')}" method="get">
							<input type="hidden" name="c" value="Scenic"/>
							<input type="hidden" name="a" value="scenic_list"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="scenic_title" <if condition="$_GET['searchtype'] eq 'scenic_title'">selected="selected"</if>>景区名</option>
<!--								<option value="scenic_account" <if condition="$_GET['searchtype'] eq 'scenic_account'">selected="selected"</if>>公司名</option>-->
								<option value="scenic_name" <if condition="$_GET['searchtype'] eq 'scenic_name'">selected="selected"</if>>管理员</option>
								<option value="scenic_phone" <if condition="$_GET['searchtype'] eq 'scenic_phone'">selected="selected"</if>>手机号</option>
							</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							状态: <select name="searchstatus">
								<option value="0" <if condition="$_GET['searchstatus'] eq '0'">selected="selected"</if>>全部</option>
								<option value="1" <if condition="$_GET['searchstatus'] eq '1'">selected="selected"</if>>正常</option>
								<option value="2" <if condition="$_GET['searchstatus'] eq '2'">selected="selected"</if>>关闭</option>
							</select>
							<input type="submit" value="查询" class="button"/>
						</form>
					</td>
				</tr>
			</table>
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
								<th>景区名</th>
								<th>公司名</th>
								<th>账号</th>
								<th>管理员</th>
								<th>访问后台</th>
								<th>佣金比例</th>
								<th>手机号</th>
								<th>等级</th>
								<th>热门景区</th>
								<th>状态</th>
								<th>开启地图</th>
								<th>开启车位</th>
								<th>景内向导</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($list)">
								<volist name="list" id="vo">
									<tr>
										<td>{pigcms{$vo.scenic_id}</td>
										<td>{pigcms{$vo.scenic_title}</td>
										<td>{pigcms{$vo.company_name}</td>
										<td>{pigcms{$vo.scenic_account}</td>
										<td>{pigcms{$vo.scenic_name}</td>
										<td><if condition="$vo['scenic_status'] eq 1"><a href="{pigcms{:U('Scenic/scenic_login',array('scenic_id'=>$vo['scenic_id']))}" class="__full_screen_link" target="_blank"><font style="color:red;">访问</font></a><else/><a href="javascript:alert('商户状态不正常，无法访问！请先修改商户状态。');" class="__full_screen_link">访问</a></if></td>
										<td>{pigcms{$vo.spread_rate}</td>
										<td>{pigcms{$vo.scenic_phone}</td>
										<td>{pigcms{$vo.level}</td>
										<td><if condition="$vo['is_hot'] eq 1">
											<font color="green">是</font>
										<elseif condition="$vo['is_hot'] eq 2"/>
											<font color="gray">否</font>
										</if></td>
										<td><if condition="$vo['scenic_status'] eq 1">
											<font color="green">开启</font>
										<elseif condition="$vo['scenic_status'] eq 2"/>
											<font color="gray">关闭</font>
										</if></td>
										<td><if condition="$vo['panorama_map'] eq 1">
											<font color="green">开启</font>
										<elseif condition="$vo['panorama_map'] eq 2"/>
											<font color="gray">关闭</font>
										</if></td>
										<td><if condition="$vo['is_parking'] eq 1">
											<font color="green">开启</font>
										<elseif condition="$vo['is_parking'] eq 2"/>
											<font color="gray">关闭</font>
										</if></td>
										<td><if condition="$vo['is_guide'] eq 1">
											<font color="green">开启</font>
										<elseif condition="$vo['is_guide'] eq 2"/>
											<font color="gray">关闭</font>
										</if></td>
										<td>
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('scenic_edit',array('scenic_id'=>$vo['scenic_id']))}','编辑公司',750,580,true,false,false,editbtn,'add',true);">编辑</a>
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
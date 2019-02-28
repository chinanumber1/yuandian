<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('aguide')}">根列表</a>|
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td style="width:50%;">
						<form action="{pigcms{:U('aguide')}" method="get">
							<input type="hidden" name="c" value="Scenic"/>
							<input type="hidden" name="a" value="aguide"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="guide_name" <if condition="$_GET['searchtype'] eq 'guide_name'">selected="selected"</if>>向导名</option>
								<option value="guide_phone" <if condition="$_GET['searchtype'] eq 'guide_phone'">selected="selected"</if>>手机号</option>
							</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							商户状态: <select name="searchstatus">
								<option value="0" <if condition="$_GET['searchstatus'] eq '0'">selected="selected"</if>>全部</option>
								<option value="1" <if condition="$_GET['searchstatus'] eq '1'">selected="selected"</if>>正常</option>
								<option value="2" <if condition="$_GET['searchstatus'] eq '2'">selected="selected"</if>>审核中</option>
								<option value="3" <if condition="$_GET['searchstatus'] eq '3'">selected="selected"</if>>关闭</option>
								<option value="4" <if condition="$_GET['searchstatus'] eq '3'">selected="selected"</if>>审核不通过</option>
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
							<col width="240" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>名字</th>
								<th>手机号</th>
								<th>单价</th>
								<th>服务次数</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($list)">
								<volist name="list" id="vo">
									<tr>
										<td>{pigcms{$vo.guide_id}</td>
										<td>{pigcms{$vo.guide_name}</td>
										<td>{pigcms{$vo.guide_phone}</td>
										<td>{pigcms{$vo.guide_price|floatval=###}元/天</td>
										<td>{pigcms{$vo.guide_service_number}</td>
										<td>
										<if condition="$vo['guide_status'] eq 1">
											<font color="green">正常</font>
										<elseif condition="$vo['guide_status'] eq 3"/>
											<font color="red">关闭</font>
										<elseif condition="$vo['guide_status'] eq 2"/>
										<font color="green">待审核</font>
										<elseif condition="$vo['guide_status'] eq 4"/>
										<font color="red">不通过</font>
										</if></td>
										<td>
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U("aguide_edit",array('guide_id'=>$vo['guide_id']))}','修改向导',500,520,true,false,false,editbtn,'add',true);">查看</a>
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
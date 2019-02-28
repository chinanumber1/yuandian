<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('index')}">根列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('add')}','添加公司',500,400,true,false,false,addbtn,'add',true);">添加公司</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td style="width:50%;">
						<form action="{pigcms{:U('index')}" method="get">
							<input type="hidden" name="c" value="Scenic"/>
							<input type="hidden" name="a" value="index"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="company_name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>公司名称</option>
								<option value="account" <if condition="$_GET['searchtype'] eq 'account'">selected="selected"</if>>公司帐号</option>
								<option value="company_phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>公司电话</option>
								<option value="company_id" <if condition="$_GET['searchtype'] eq 'company_id'">selected="selected"</if>>公司编号</option>
							</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							商户状态: <select name="searchstatus">
								<option value="0" <if condition="$_GET['searchstatus'] eq '0'">selected="selected"</if>>全部</option>
								<option value="1" <if condition="$_GET['searchstatus'] eq '1'">selected="selected"</if>>正常</option>
								<option value="2" <if condition="$_GET['searchstatus'] eq '2'">selected="selected"</if>>待审核</option>
								<option value="3" <if condition="$_GET['searchstatus'] eq '3'">selected="selected"</if>>审核不通过</option>
								<option value="4" <if condition="$_GET['searchstatus'] eq '3'">selected="selected"</if>>禁止登陆</option>
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
							<col width="240" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>账号</th>
								<th>公司名</th>
								<th>电话</th>
								<th>后台</th>
								<th>地址</th>
								<th>邮箱</th>
								<th>景区数量</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($list)">
								<volist name="list" id="vo">
									<tr>
										<td>{pigcms{$vo.company_id}</td>
										<td>{pigcms{$vo.account}</td>
										<td>{pigcms{$vo.company_name}</td>
										<td>{pigcms{$vo.company_phone}</td>
										<td class="textcenter"><if condition="$vo['status'] eq 1"><a href="{pigcms{:U('Scenic/login',array('company_id'=>$vo['company_id']))}" class="__full_screen_link" target="_blank"><font style="color:red;">访问</font></a><else/><a href="javascript:alert('商户状态不正常，无法访问！请先修改商户状态。');" class="__full_screen_link">访问</a></if></td>
										<td>{pigcms{$vo.company_address}</td>
										<td>{pigcms{$vo.company_email}</td>
										<td>{pigcms{$vo.scenic_number}</td>
										<td>
										<if condition="$vo['status'] eq 1">
											<font color="green">正常</font>
										<elseif condition="$vo['status'] eq 2"/>
											<font color="red">待审核</font>
										<elseif condition="$vo['status'] eq 3"/>
											<font color="gray">审核不通过</font>
										<elseif condition="$vo['status'] eq 4"/>
											<font color="gray">禁止登陆</font>
										</if></td>
										<td>
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('edit',array('company_id'=>$vo['company_id']))}','编辑公司',500,500,true,false,false,editbtn,'add',true);">编辑</a> |
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('scenic_add',array('company_id'=>$vo['company_id']))}','增加景点',750,420,true,false,false,editbtn,'add',true);">新增景点</a>
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
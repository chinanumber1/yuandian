<include file="Public:header"/>
		<div class="mainbox">
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('store_list')}" method="get">
							<input type="hidden" name="c" value="Appoint"/>
							<input type="hidden" name="a" value="store_list"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>商户名称</option>								
								<option value="account" <if condition="$_GET['searchtype'] eq 'account'">selected="selected"</if>>商户帐号</option>
								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>联系电话</option>
								<option value="mer_id" <if condition="$_GET['searchtype'] eq 'mer_id'">selected="selected"</if>>商家编号</option>
							</select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							商户状态: <select name="searchstatus">
								<option value="0" <if condition="$_GET['searchstatus'] eq 0">selected="selected"</if>>正常</option>
								<option value="1" <if condition="$_GET['searchstatus'] eq '1'">selected="selected"</if>>待审核</option>
								<option value="2" <if condition="$_GET['searchstatus'] eq '2'">selected="selected"</if>>关闭</option>
								<option value="3" <if condition="$_GET['searchstatus'] eq '3'">selected="selected"</if>>全部</option>
							</select>
							<input type="submit" value="查询" class="button"/>
						</form>
					</td>
				</tr>
			</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup><col> <col> <col> <col><col><col width="240" align="center"> </colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>商户帐号</th>
								<th>商户名称</th>
								<th>联系电话</th>
								<th>状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($merchant_list)">
								<volist name="merchant_list" id="vo">
									<tr>
										<td>{pigcms{$vo.mer_id}</td>
										<td>{pigcms{$vo.account}</td>
										<td>{pigcms{$vo.name}</td>
										<td>{pigcms{$vo.phone}</td>
										<td><if condition="$vo['status'] eq 1"><font color="green">启用</font><elseif condition="$vo['status'] eq 2"/><font color="red">待审核</font><else/><font color="red">关闭</font></if></td>
										<td class="textcenter">
										<a href="{pigcms{:U('store',array('mer_id'=>$vo['mer_id']))}">店铺列表</a> 
										</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="12">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="12">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
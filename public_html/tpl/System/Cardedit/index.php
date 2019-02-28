<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Cardedit/index')}" class="on">实体卡列表</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Cardedit/add_card')}','批量添加实体卡',680,560,true,false,false,addbtn,'edit',true);">批量添加实体卡</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Cardedit/mutil_bind_merid')}','批量添加实体卡',680,560,true,false,false,addbtn,'edit',true);">批量绑定商户</a>
					<a href="{pigcms{:U('Cardedit/log')}" >实体卡操作记录</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="{pigcms{:U('Cardedit/index')}" method="get">
							<input type="hidden" name="c" value="Cardedit"/>
							<input type="hidden" name="a" value="index"/>
							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>
							<select name="searchtype">
								<option value="cardid" <if condition="$_GET['searchtype'] eq 'cardid'">selected="selected"</if>>卡ID</option>
								<option value="uid" <if condition="$_GET['searchtype'] eq 'uid'">selected="selected"</if>>用户ID</option>
								<option value="merid" <if condition="$_GET['searchtype'] eq 'merid'">selected="selected"</if>>商家ID</option>
								<option value="regtime" <if condition="$_GET['searchtype'] eq 'regtime'">selected="selected"</if>>激活时间</option>
								<!--<option value="status" <if condition="$_GET['searchtype'] eq 'status'">selected="selected"</if>>状态</option>--->
							</select>
							<input type="submit" value="查询" class="button"/> (*绑定用户且状态为正常时，实体卡才会被激活)
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
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>ID</th>
								<th>实体卡ID</th>
								<th>用户ID</th>
								<th>商家ID</th>
								<th>激活时间</th>
								<th>最后修改时间</th>
								<th class="textcenter">卡余额</th>
								<th class="textcenter">状态</th>
								<th class="textcenter">编辑</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($card_list)">
								<volist name="card_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.cardid}</td>
										<td><empty name="vo.uid"><font color="red">没有绑定用户</font><else /> <font color="blue">{pigcms{$vo.uid}<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/edit',array('uid'=>$vo['uid']))}','编辑用户信息',680,560,true,false,false,editbtn,'edit',true);">查看用户信息</a></font></empty></td>
										<td><empty name="vo.merid"><font color="red">没有绑定商户</font><else /> <font color="blue">{pigcms{$vo.merid}</font></empty></td>
										<td><empty name="vo.regtime"><else />{pigcms{$vo.regtime|date='Y-m-d H:i:s',###}</empty></td>
										<td>{pigcms{$vo.last_time|date='Y-m-d H:i:s',###}</td>
										<td class="textcenter">￥{pigcms{$vo.balance_money|floatval=###}</td>
										<td class="textcenter"><if condition="$vo['status'] eq 1"><font color="green">正常</font><elseif condition="$vo['status'] eq 2" /><font color="red">未审核</font><else /><font color="red">禁用</font></if></td>
										<td class="textcenter">
										
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Cardedit/edit_card',array('cardid'=>$vo['cardid']))}','编辑实体卡信息',680,560,true,false,false,editbtn,'add',true);"><if condition="($vo['status'] eq 2) or ($vo['status'] eq 0)">审核<else />编辑</if></a>
										<a href="javascript:void(0);" class="delete_row" parameter="id=10" url="{pigcms{:U('Cardedit/del_card',array('cardid'=>$vo['cardid']))}">删除</a>

									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="8">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
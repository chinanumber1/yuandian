<include file="Public:header"/>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>					<a href="{pigcms{:U('User/score_all_list')}" >积分记录列表</a>					<if condition="$config.open_score_fenrun eq 1"><a href="{pigcms{:U('User/fenrun_list')}" class="on">分润记录列表</a></if>					<a href="{pigcms{:U('User/award_list')}" >佣金记录列表</a><a href="{pigcms{:U('User/free_award_list')}" >佣金解冻记录</a><a href="{pigcms{:U('User/system_fenrun_list')}" >系统分润列表</a></if>				</ul>			</div>			<table class="search_table" width="100%">				<tr>					<td>						<form action="{pigcms{:U('fenrun_list')}" method="get">							<input type="hidden" name="c" value="User"/>							<input type="hidden" name="a" value="fenrun_list"/>							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>							<select name="searchtype">								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>客户名称</option>								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>客户电话</option>							</select>							<select name="type">								<option value="1" <if condition="$_GET['type'] eq 1">selected="selected"</if>>积分到分润</option>								<option value="2" <if condition="$_GET['type'] eq 2">selected="selected"</if>>分润转余额</option>							</select>							<font color="#000">日期筛选：</font>							<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>													<input type="submit" value="查询" class="button"/>　　分润总额：{pigcms{$all_fenrun}						</form>																	</td>				</tr>			</table>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<style>					.table-list td{line-height:22px;padding-top:5px;padding-bottom:5px;}					</style>					<table width="100%" cellspacing="0">						<thead>							<tr>								<th>编号</th>								<th>昵称</th>								<th>电话</th>								<th>积分数量</th>								<th>分润金额</th>								<th>描述</th>								<th>查看用户信息</th>								<th>时间</th>														</tr>						</thead>						<tbody>							<if condition="is_array($order_list)">								<volist name="order_list" id="vo">									<tr>										<td>{pigcms{$vo.id}</td>										<td>{pigcms{$vo.nickname}</td>										<td>{pigcms{$vo.phone}</td>										<td><if condition="$vo.type eq 1"><font color="red"></if>{pigcms{$vo.score_count|floatval}</font></td>										<td><if condition="$vo.type eq 1"><font color="green"><elseif condition="$vo.type eq 2" /><font color="red">-</if>{pigcms{$vo.fenrun_money|floatval}</font></td>										<td>{pigcms{$vo.des}</td>										<td>											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/edit',array('uid'=>$vo['uid']))}','编辑用户信息',680,560,true,false,false,editbtn,'edit',true);">查看用户信息</a>										</td>										<td>											{pigcms{$vo['add_time']|date='Y-m-d H:i:s',###}<br/>																				</td>																	</tr>								</volist>								<tr><td class="textcenter pagebar" colspan="8">{pigcms{$pagebar}</td></tr>							<else/>								<tr><td class="textcenter red" colspan="8">列表为空！</td></tr>							</if>						</tbody>					</table>				</div>			</form>		</div><script>$(function(){	$('#status').change(function(){		location.href = "{pigcms{:U('User/recharge_list')}&status=" + $(this).val();	});});</script><include file="Public:footer"/>
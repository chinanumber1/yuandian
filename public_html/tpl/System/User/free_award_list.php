<include file="Public:header"/>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>					<a href="{pigcms{:U('User/score_all_list')}" >积分记录列表</a>					<if condition="$config.open_score_fenrun eq 1"><a href="{pigcms{:U('User/fenrun_list')}" >分润记录列表</a><a href="{pigcms{:U('User/award_list')}"  >佣金记录列表</a><a href="{pigcms{:U('User/free_award_list')}"  class="on">佣金解冻记录</a><a href="{pigcms{:U('User/system_fenrun_list')}">系统分润列表</a></if>				</ul>			</div>			<table class="search_table" width="100%">				<tr>					<td>						<form action="{pigcms{:U('free_award_list')}" method="get">							<input type="hidden" name="c" value="User"/>							<input type="hidden" name="a" value="free_award_list"/>							筛选: <input type="text" name="keyword" class="input-text" value="{pigcms{$_GET['keyword']}"/>							<select name="searchtype">								<option value="name" <if condition="$_GET['searchtype'] eq 'name'">selected="selected"</if>>客户名称</option>								<option value="phone" <if condition="$_GET['searchtype'] eq 'phone'">selected="selected"</if>>客户电话</option>							</select>							<font color="#000">日期筛选：</font>							<input type="text" class="input-text" name="begin_time" style="width:120px;" id="d4311"  value="{pigcms{$_GET.begin_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>			   							<input type="text" class="input-text" name="end_time" style="width:120px;" id="d4311" value="{pigcms{$_GET.end_time}" onfocus="WdatePicker({readOnly:true,dateFmt:'yyyy-MM-dd'})"/>													<input type="submit" value="查询" class="button"/>　　						</form>					</td>				</tr>			</table>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<style>					.table-list td{line-height:22px;padding-top:5px;padding-bottom:5px;}					</style>					<table width="100%" cellspacing="0">						<thead>							<tr>								<th>编号</th>								<th>昵称</th>								<th>电话</th>								<th>金额</th>								<th>描述</th>								<th>查看用户信息</th>								<th>时间</th>														</tr>						</thead>						<tbody>							<if condition="is_array($order_list)">								<volist name="order_list" id="vo">									<tr>										<td>{pigcms{$vo.id}</td>										<td>{pigcms{$vo.nickname}</td>										<td>{pigcms{$vo.phone}</td>										<td><font color="green">{pigcms{$vo.money|floatval}</font></td>										<td><if condition="$vo.type eq 1">推广用户消费解冻，<elseif condition="$vo.type eq 2" />推广商家验证消费解冻，</if>{pigcms{$vo.des}</td>										<td>											<if condition="$vo.type eq 1">												<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/edit',array('uid'=>$vo['type_id']))}','编辑用户信息',680,560,true,false,false,editbtn,'add',true);">查看用户信息</a>											<elseif condition="$vo.type eq 2" />												<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Merchant/edit',array('mer_id'=>$vo['type_id']))}','编辑商户信息',800,560,true,false,false,editbtn,'add',true);">查看商家信息</a>																																	</if>																					</td>										<td>											{pigcms{$vo['add_time']|date='Y-m-d H:i:s',###}<br/>																				</td>																	</tr>								</volist>								<tr><td class="textcenter pagebar" colspan="7">{pigcms{$pagebar}</td></tr>							<else/>								<tr><td class="textcenter red" colspan="7">列表为空！</td></tr>							</if>						</tbody>					</table>				</div>			</form>		</div><script>$(function(){	$('#status').change(function(){		location.href = "{pigcms{:U('User/recharge_list')}&status=" + $(this).val();	});});</script><include file="Public:footer"/>
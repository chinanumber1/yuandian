<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('infoList')}">信息发布列表</a>
					<a href="{pigcms{:U('assure_order_list')}" class="on">订单列表</a>
				</ul>
			</div>
			<table class="search_table" width="100%">
				<tr>
					<td>
						<form action="/admin.php?g=System&c=Classify&a=assure_order_list" method="get">
						<input type="hidden" value="Classify" name="c"></input>
							<input type="hidden" value="assure_order_list" name="a"></input>
							<if condition="$now_area['area_type'] lt 2 OR $system_session['level'] eq 2 ">
								选择城市：
								<div id="choose_pca" province_idss="{pigcms{$_GET.province_idss}" city_idss="{pigcms{$_GET.city_idss}"  style="display:inline"></div>
								<input type="submit" value="查询" class="button"/>		
							</if>
						</form>
					</td>
					</tr>
				</table>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<style>
					.table-list td{line-height:22px;padding-top:5px;padding-bottom:5px;}
					</style>
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col/>
							<col width="200" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>订单编号</th>
								<th>订单信息</th>
								<th>订单用户</th>
								<th>查看用户信息</th>
								<th>订单状态</th>
								<th>时间</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($order_list)">
								<volist name="order_list" id="vo">
									<tr>
										<td>{pigcms{$vo.order_id}</td>
										<td>数量：{pigcms{$vo.num}<br/>总价：￥{pigcms{$vo.total_price|floatval=###}</td>
										<td>用户名：{pigcms{$vo.contact_name}<br/>订单手机号：{pigcms{$vo.phone}</td>
										<td>
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/edit',array('uid'=>$vo['uid']))}','编辑用户信息',680,560,true,false,false,editbtn,'edit',true);">查看用户信息</a>
										</td>
										<td>
											<if condition="$vo['paid'] eq 0">
												<font color="red">未付款</font>
											<elseif condition='($vo["paid"] eq 1) AND ($vo["status"] eq 1)' />
												<font color="green">已收货</font>
											<elseif condition="$vo['paid'] eq 1"/>
												<font color="green">已付款</font>
											</if>
										</td>
										<td>
											下单时间：{pigcms{$vo['order_time']|date='Y-m-d H:i:s',###}<br/>
											<if condition="$vo['paid']">付款时间：{pigcms{$vo['pay_time']|date='Y-m-d H:i:s',###}</if>
										<td class="textcenter">
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('order_detail',array('order_id'=>$vo['order_id']))}','查看订单详情',660,490,true,false,false,false,'order_edit',true);">查看详情</a>
										
										<if condition="(($vo['paid'] == 1) AND (empty($vo['status'])))" >
										|
											<a href="javascript:void(0);" onclick="chk_order({pigcms{$vo.order_id})">确认收货</a>
											</td>
										</if>
									</tr>
										</td>
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
		
<script type="text/javascript">
function chk_order(order_id){
	if(confirm('该操作应由买家操作，确认后台操作确认？')){
		var url = "{pigcms{:U('ajax_chk_order')}"
		$.post(url,{'order_id':order_id},function(data){
			alert(data.msg);
			if(data.status){
				location.reload();
			}
		},'json')
	}
}
</script>
<script type="text/javascript" src="{pigcms{$static_path}js/area_pca.js?{pigcms{:time()}"></script>
<include file="Public:footer"/>
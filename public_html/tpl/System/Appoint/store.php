<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Appoint/store_list')}">商户列表</a>|
					<a href="{pigcms{:U('Merchant/store',array('mer_id'=>$merchant['mer_id']))}" class="on">{pigcms{$merchant.name} - 店铺列表</a>
				</ul>
			</div>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup><col> <col> <col><col><col width="180" align="center"> </colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>店铺名称</th>
								<th>联系电话</th>
								<th>状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($store_list)">
								<volist name="store_list" id="vo">
									<tr>
										<td>{pigcms{$vo.store_id}</td>
										<td>{pigcms{$vo.name}</td>
										<td>{pigcms{$vo.phone}</td>
										
										<td><if condition="$vo['status'] eq 1"><font color="green">启用</font><elseif condition="$vo['status'] eq 2"/><font color="red">审核中</font><else/><font color="red">关闭</font></if></td>
										<td class="textcenter"><a href="{pigcms{:U('worker_list',array('store_id'=>$vo['store_id']))}">查看技师</a></td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="5">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="5">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('User/authentication')}" <if condition="$status neq 1">class="on"</if>>待审核列表</a>
					<a href="{pigcms{:U('User/authentication',array('status'=>1))}" <if condition="$status eq 1">class="on"</if>>已审核列表</a>
				</ul>
			</div>
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
								<th>用户ID</th>
								<th>真实姓名</th>
								<th>身份证号</th>
								<th>身份证图片</th>
								<th>状态</th>
								<th>认证时间</th>
								<th>审核时间</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($card_list)">
								<volist name="card_list" id="vo">
									<tr>
										<td>{pigcms{$vo.authentication_id}</td>
										<td>{pigcms{$vo.uid}</td>
										<td>{pigcms{$vo.user_truename}</td>
										<td>{pigcms{$vo.user_id_number}</td>
										<td><img style="width:70px;height:70px;" src="{pigcms{$vo.authentication_img}" /></td>
										<if condition="$vo['authentication_status'] eq 0">
											<td style="color:blue">待审核</td>
										<elseif condition="$vo['authentication_status'] eq 1"/>
											<td style="color:green">审核通过</td>
										<else/>
											<td style="color:red">审核不通过</td>
										</if>
										<if condition="$vo['authentication_time'] eq 0">
											<td></td>
										<else/>
											<td>{pigcms{$vo.authentication_time|date='Y-m-d H:i:s',###}</td>
										</if>
										<if condition="$vo['examine_time'] eq 0">
											<td></td>
										<else/>
											<td>{pigcms{$vo.examine_time|date='Y-m-d H:i:s',###}</td>
										</if>
										<if condition="$vo['authentication_status'] eq 0">
											<td class="textcenter">
												<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/check',array('authentication_id'=>$vo['authentication_id']))}','审核实名认证',680,560,true,false,false,editbtn,'edit',true);">审核</a>
											</td>
										<else/>
											<td class="textcenter">
												<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('User/check',array('authentication_id'=>$vo['authentication_id'],'status'=>1))}','查看实名认证',680,560,true,false,false);">查看</a>
											</td>
										</if>
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
<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('NewHouse/index')}" class="on">楼盘列表</a>|
					<a href="{pigcms{:U('NewHouse/cate')}" >聚合首页新房分类</a>
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
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>楼盘名称</th>
								<th>城市</th>
								<th>管理员状态</th>
								<th>楼盘状态</th>
								<th>分类</th>
							</tr>
						</thead>
						<tbody>
							<?php
								// 管理员状态
								$admin_status = array(0=>'审核中',1=>'正常',2=>'禁止');
							?>
							<if condition="is_array($building_list)">
								<volist name="building_list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.title}</td>
										<td><?php echo $village_list[$vo['village_id']]?></td>
										<td>
											<select onchange="check_admin($(this).val(),<?php echo $vo['id']?>)">
											<if condition="$admin_status">
											<volist name="admin_status" id="_status">
												<option value="{pigcms{$key}" <?php echo $key==$vo['admin_status']?'selected':'';?>>{pigcms{$_status}</option>
											</volist>
											</if>
											</select>
										</td>
										<td>
											<if condition="$vo['status'] eq 1"><font color="green">正常</font><elseif condition="$vo['status'] eq 0"/><font color="red">禁止</font></if>
										</td>
										<td>
											<select onchange="recommend_top(this)" building_id="{pigcms{$vo.id}">
												<option value="0">分类</option>
												<if condition="$newhouse_cates">
												<volist name="newhouse_cates" id="vo2">
													<option value="{pigcms{$vo2.id}" <?php echo $vo2['id']==$vo['cate_id']?'selected':'';?>>{pigcms{$vo2.name}</option>
												</volist>
												</if>
											</select>
										</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="7">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="4">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script type="text/javascript">
	// 审核楼盘管理员
	function check_admin(status,building_id){
		$.post("{pigcms{:U('NewHouse/do_check_admin')}",{'building_id':building_id,'status':status},function(response){
			if(response.code>0){
				alert(response.msg);
			}
		},'json');
	}

	function recommend_top(obj){
		var building_id = $(obj).attr('building_id');
		$.post("{pigcms{:U('NewHouse/dorecommend')}",{'building_id':building_id,'cate_id':$(obj).val()},function(response){
			if(response.err_code == 0){
				if(response.flag == 0){
					//$(obj).html('<span style="color:green;">推荐</span>');
				}else{
					//$(obj).html('<span style="color:red;">取消</span>');
				}
			}
		},'json');
	}
</script>
<include file="Public:footer"/>
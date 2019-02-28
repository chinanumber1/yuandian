<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('circle/cateAdd')}','添加分类{pigcms{$now_type_str}',450,160,true,false,false,addbtn,'add',true);">添加新分类</a>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('circle/circleAdd')}','添加分类{pigcms{$now_type_str}',550,260,true,false,false,addbtn,'add',true);">添加圈子</a>
				</ul>
			</div>
			
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						
						<thead>
							<tr>
								<th>排序</th>
								<th>名称</th>
								<th>图标</th>
								<!--<th>父类</th>-->
								<th>创建时间</th>
								<th>状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($class)">
								
								<volist name="class" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.name}</td>
										<td><img src="{pigcms{$config.site_url}{pigcms{$vo.icon_value}" style="width:30px;height:30px;"></td>
										<!--<td>{pigcms{$vo.fid}</td>-->
										<td>{pigcms{$vo.addTime|date='Y-m-d',###}</td>
										<td>
                                        <if condition="$vo['status'] eq 1"><font color="red">已禁用</font><elseif condition="$vo['status'] eq 0"/><font color="green">已启用</font></if>
										</td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Circle/editcateory',array('id'=>$vo['id'],'name'=>$vo['name']))}','编辑分类信息',480,240,true,false,false,editbtn,'edit',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('Circle/delcateory')}">删除</a></td>
										
									</tr>
									<volist name="mycircle" id="vo1">
                                    <if condition="$vo['id'] eq $vo1['cate_id']">
									<tr>
										<td>{pigcms{$vo1.id}</td>
										<td>|----　{pigcms{$vo1.name}</td>
										<td><img src="{pigcms{$vo1.logo}" style="width:30px;height:30px;"></td>
										<!--<td>{pigcms{$vo1.fid}</td>-->
										<td>{pigcms{$vo1.addTime|date='Y-m-d',###}</td>
										<td>
										<if condition="$vo1['status'] eq 1"><font color="red">已禁用</font><elseif condition="$vo1['status'] eq 0"/><font color="green">已启用</font></if>
										</td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Circle/editCircle',array('id'=>$vo1['id']))}','编辑圈子信息',480,240,true,false,false,editbtn,'edit',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo1.id}" url="{pigcms{:U('Circle/delCircle')}">删除</a></td>
									</tr>
									</if>
									</volist>
								</volist>
							<else/>
								<tr><td class="textcenter red" colspan="6">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
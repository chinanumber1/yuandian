<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('foot_menu')}" class="on">底部菜单列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('foot_menu_add')}','添加底部菜单',500,300,true,false,false,addbtn,'add',true);">添加底部菜单</a>
				</ul>
			</div>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup><col> <col><col> <col><col><col>  <col width="180" align="center"> </colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>名称</th>
								<th>图片</th>
                                <th>选中图片</th>
                                <th>排序值(排序值越小，越向前显示)</th>
                                <th>状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$list">
								<volist name="list" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.name}</td>
										<td><img src="{pigcms{$config.site_url}/upload/slider/{pigcms{$vo.pic_path}" width="25px" height="25px"></td>
										<td><if condition='$vo["hover_pic_path"]'><img src="{pigcms{$config.site_url}/upload/slider/{pigcms{$vo.hover_pic_path}" width="25px" height="25px"><else />暂无图片</if></td>
                                        <td>{pigcms{$vo.sort}</td>
                                        <td><if condition='$vo.status eq 0'><span class="red">关闭</span><else /><span style="color:green">开启</span></if></td>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('foot_menu_edit',array('id'=>$vo['id'],'frame_show'=>true))}','查看底部菜单',500,300,true,false,false,false,'add',true);">查看</a> | <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('foot_menu_edit',array('id'=>$vo['id']))}','编辑底部菜单',500,380,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('foot_menu_del')}">删除</a></td>
									</tr>
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
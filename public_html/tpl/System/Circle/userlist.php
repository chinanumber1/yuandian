<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<!--<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('circle/cateAdd')}','添加{pigcms{$now_type_str}',450,320,true,false,false,addbtn,'add',true);">添加圈子新分类</a>-->
				</ul>
			</div>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						
						<thead>
							<tr>
								<th style="width:5%;" class="textcenter">排序</th>
								<th style="width:5%;" class="textcenter">昵称</th>
								<th style="width:5%;" class="textcenter">生日</th>
								<th style="width:20%;" class="textcenter">签名</th>
								<th style="width:5%;" class="textcenter">手机号</th>
								<th style="width:5%;" class="textcenter"> 性别</th>
								<th style="width:5%;" class="textcenter">省份</th>
								<th style="width:5%;" class="textcenter">城市</th>
								<th style="width:20%;" class="textcenter">头像</th>
								<th style="width:5%;" class="textcenter">APPID</th>
								<th style="width:5%;" class="textcenter">我的收藏</th>
								<th style="width:5%;" class="textcenter">我的圈子</th>
								<th style="width:5%;" class="textcenter">状态</th>
								<th class="textcenter" style="width:5%;">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($res)">
								<volist name="res" id="vo">
									<tr>
										<td class="textcenter">{pigcms{$vo.id}</td>
										<td class="textcenter">{pigcms{$vo.nickName}</td>
										<td class="textcenter">{pigcms{$vo.date}</td>
										<td class="textcenter">{pigcms{$vo.content}</td>
										<td class="textcenter">{pigcms{$vo.phone}</td>
										<td class="textcenter"><if condition="$vo['gender'] eq 1">男<else />女</if></td>
										<td class="textcenter">{pigcms{$vo.province}</td>
										<td class="textcenter">{pigcms{$vo.city}</td>
										<td class="textcenter"><img src="{pigcms{$vo.avatarUrl}" style="width:30px;height:30px;"></td>
										<td class="textcenter">{pigcms{$vo.openid}</td>
										<td class="textcenter"><a href="/admin.php?g=System&c=Circle&a=usercollection&uid={pigcms{$vo.id}">查看收藏</a></td>
										<td class="textcenter"><a href="/admin.php?g=System&c=Circle&a=mycircle&uid={pigcms{$vo.id}">查看圈子</a></td>
										<td class="textcenter"><if condition="$vo['status'] eq 1">禁用<else/>正常</if> </td>
										<td class="textcenter"> <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Circle/useredit',array('id'=>$vo['id']))}','编辑用户信息',800,500,true,false,false,editbtn,'edit',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('Circle/deluser')}">删除</a></td>
										
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="14">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="14">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
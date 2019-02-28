<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('advert_cat_index')}">根列表</a>|
					<a href="{pigcms{:U('advert',array('cat_id'=>$_GET['cat_id']))}" class="on">广告列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('add_advert',array('cat_id'=>$_GET['cat_id']))}','添加广告',500,400,true,false,false,addbtn,'add',true);">添加广告</a>
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
							<col/>
							<col/>
							<col width="100" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>排序</th>
								<th>城市</th>
								<th>标题</th>
								<th>图片(以下为强制小图，点击图片查看大图)</th>
								<th>添加时间</th>
								<th>更新时间</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($list)">
								<volist name="list" id="vo">
									<tr>
										<td>{pigcms{$vo.advert_id}</td>
										<td>{pigcms{$vo.sort}</td>
										<if condition="$vo['city_id'] eq '通用'">
											<td style="color:red;">{pigcms{$vo.city_id}</td>
										<else/>
											<td>{pigcms{$vo.city_id}</td>
										</if>
										<td>{pigcms{$vo.advert_title}</td>
										<td><img style="width:100px;" src="{pigcms{$vo.advert_img}" class="view_msg"></td>
										<td>{pigcms{$vo.add_time}</td>
										<td>{pigcms{$vo.update_time}</td>
										<td>
										<if condition="$vo['advert_status'] eq 1">
											<font color="green">正常</font>
										<elseif condition="$vo['advert_status'] eq 2"/>
											<font color="red">关闭</font>
										</if></td>
										<td>
											<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('edit_advert',array('advert_id'=>$vo['advert_id'],'cat_id'=>$_GET['cat_id']))}','编辑广告',500,500,true,false,false,editbtn,'add',true);">编辑</a>
										</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="9">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
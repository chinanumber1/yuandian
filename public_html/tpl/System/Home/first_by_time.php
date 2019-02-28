<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Home/first_by_time')}" class="on" >分时段关注自动回复</a>
				
					<a href="{pigcms{:U('Home/first')}" >其他时段关注自动回复</a>
						<!--a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Group/cat_add',array('cat_fid'=>intval($_GET['cat_fid'])))}','添加子分类',520,370,true,false,false,addbtn,'add',true);">添加子分类</a-->
					</if>
				</ul>
			</div>
	
			<form name="myform" id="myform" action="" method="post">
					<a href="{pigcms{:U('Home/add_first_by_time')}" class="button">添加分时段关注自动回复</a>
					<b style="color:red;">分时段关注自动回复功能即用户在不同时间段关注会自动回复不同内容，支持单图文、多图文、纯文本与定义链接</b>
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
						
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th id="shopList_c1" width="100">回复时段</th>
								<th id="shopList_c1" width="100">回复类型</th>
								<th id="shopList_c1" width="100">回复内容</th>
								<th id="shopList_c1" width="100">添加时间</th>
								<th id="shopList_c1" width="100">修改时间</th>
								<th id="shopList_c11" width="180">操作</th>
							</tr>
						</thead>
						<tbody>
							
							
							<if condition="$first">
									<volist name="first" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>
											{pigcms{$vo.start_time}-{pigcms{$vo.end_time}
											</td>
											<td>
											{pigcms{$type[$vo['type']]}
											</td>
											<td>
											<if condition="$vo['type'] eq 0">{pigcms{$vo['content']}
											
											<elseif condition="$vo.type eq 4" />
											
												
											<volist name="image_text_list" id="vv">
												<php>if($vv['pigcms_id'] == $vo['image_text_id']){</php>
												【{pigcms{$vv.list.0.title}】
													 <php>if($vv['type']==1){</php>  
														多图文
													  <php>}else{  </php> 
														单图文
													  <php>}  </php> 
												  
												  
												  <php>}  </php> 
											</volist>
											<elseif condition="$vo.type eq 2" />
											
												{pigcms{$web_arr[$vo['fromid']]}</if>
											</td>
											<td>
											<if condition="$vo.add_time gt 0">{pigcms{$vo.add_time|date="Y-m-d H:i:s",###}</if>
											</td>
											<td>
											<if condition="$vo.last_time gt 0">{pigcms{$vo.last_time|date="Y-m-d H:i:s",###}</if>
											</td>
											
											<td class="button-column" nowrap="nowrap">
												
												<a href="{pigcms{:U('Home/add_first_by_time',array('id'=>$vo['id']))}" >编辑</a>
											
												 <a href="javascript:void(0);" class="delete_row" parameter="cat_id={pigcms{$vo.cat_id}" url="{pigcms{:U('Home/del_first',array('id'=>$vo['id']))}">删除</a>
											</td>
										</tr>
									</volist>
									<tr><td class="textcenter pagebar" colspan="9">{pigcms{$page}</td></tr>
								<else/>
									<tr><td class="textcenter red" colspan="3">列表为空！</td></tr>
								</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>

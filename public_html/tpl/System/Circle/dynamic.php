<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('circle/dynamicAdd')}','添加{pigcms{$now_type_str}',850,700,true,false,false,addbtn,'add',true);">添加新的动态</a>
				</ul>
			</div>

           <table class="search_table" width="100%">
				<tr>
					<td>
						<form action="/admin.php?g=System&c=Circle&a=dynamic" method="post">
						<input type="hidden" value="biaoshi" name="biaoshi"></input>
						
						    选择分类：
							<select name="circle_id" style="width:200px;" onchange="WO(this.value);">
							<option value="all" >选择分类</option>
							<option value="xitong" >系统发布</option>
							<volist name="quanzi" id="vo">
							<option value="{pigcms{$vo['id']}" >{pigcms{$vo['name']}</option>
						    </volist>
							</select>
							
							<input type="submit" value="查询" />
						</form>
					</td>
				</tr>
			</table>


			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						
						<thead>
							<tr>
								<th class="textcenter">排序</th>
								<th class="textcenter">标题</th>
								<th class="textcenter">封面</th>
								<th class="textcenter">圈子</th>
								<th class="textcenter">用户</th>
								<th class="textcenter">发布时间</th>
								<th class="textcenter">修改时间</th>
								<th class="textcenter">是否置顶</th>
								<th class="textcenter">状态</th>
								<th class="textcenter">评论</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($dynamicList)">
								<volist name="dynamicList" id="vo">
									<tr>
										<td class="textcenter">{pigcms{$vo.id}</td>
										<td class="textcenter">{pigcms{$vo.title}</td>
										<td class="textcenter">
										<img src="{pigcms{$vo.image}" style="width:30px;height:30px;">
										</td>
										<td class="textcenter">{pigcms{$vo.name}</td>
										<td class="textcenter">{pigcms{$vo.nickname}</td>
										<td class="textcenter">{pigcms{$vo.add_time|date='Y-m-d',###}</td>
										<td class="textcenter">
										<?php if($vo['update_time']) { ?>
										{pigcms{$vo.update_time|date='Y-m-d',###}
										<?php }else{ ?>
										尚未修改
										<?php } ?>
										</td>
										<td class="textcenter">
					                    <if condition="$vo['ding'] eq 1">未置顶<else/>置顶</if> 
					                    </td>
										<td class="textcenter">
										<if condition="$vo['status'] eq 1">隐藏<else/>显示</if> 
										</td>
										<th class="textcenter"><a href="/admin.php?g=System&c=Circle&a=showcomment&dyid={pigcms{$vo.id}">查看评论</a></th>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Circle/dynamicadd',array('id'=>$vo['id']))}','编辑信息',850,700,true,false,false,editbtn,'edit',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('Circle/deldynamic')}">删除</a></td>
									</tr>
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
<include file="Public:footer"/>
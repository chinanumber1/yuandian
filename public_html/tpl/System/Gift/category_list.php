<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<if condition="empty($now_category)">
						<a href="{pigcms{:U('category_list')}" class="on">分类列表</a>|
						<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('category_add',array('cat_fid'=>intval($_GET['cat_fid'])))}','添加主分类',520,370,true,false,false,addbtn,'add',true);">添加主分类</a>
					<else/>
						<a href="{pigcms{:U('category_list')}">分类列表</a>|
						<a href="{pigcms{:U('category_list',array('cat_fid'=>$_GET['cat_fid']))}" class="on">{pigcms{$now_category.cat_name} - 子分类列表</a>|
						<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('category_add',array('cat_fid'=>intval($_GET['cat_fid'])))}','添加子分类',700,470,true,false,false,addbtn,'add',true);">添加子分类</a>
					</if>
				</ul>
			</div>
			<if condition="!empty($_GET['cat_fid'])">
				<div style="height:30px;line-height:30px;">提示：若主分类下只有一个子分类，网站上子分类不会显示。</div>
			</if>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
							<if condition="empty($_GET['cat_fid'])">
								<col/>
							</if>
							<col/>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>排序</th>
								<th>编号</th>
								<th>名称</th>
								<if condition="empty($_GET['cat_fid'])">
									<th>查看子分类</th>
									<!-- <th>商品字段管理</th> -->
								</if>
								<th>状态</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($category_list['list'])">
								<volist name="category_list['list']" id="vo">
									<tr>
										<td>{pigcms{$vo.cat_sort}</td>
										<td>{pigcms{$vo.cat_id}</td>
										<td><if condition="$vo['is_hot']"><font color="red">{pigcms{$vo.cat_name}</font><else/>{pigcms{$vo.cat_name}</if></td>
										<if condition="empty($_GET['cat_fid'])">
											<td><a href="{pigcms{:U('category_list',array('cat_fid'=>$vo['cat_id']))}">查看子分类</a></td>
										</if>
										
										<td><if condition="$vo['cat_status'] eq 1"><font color="green">启用</font><elseif condition="$vo['cat_status'] eq 2"/><font color="red">待审核</font><else/><font color="red">关闭</font></if></td>
                                        
                                        
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('category_edit',array('cat_id'=>$vo['cat_id'],'frame_show'=>true))}','查看分类信息',520,480,true,false,false,false,'detail',true);">查看</a> | <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('category_edit',array('cat_id'=>$vo['cat_id']))}','编辑分类信息',700,<if condition="$vo['cat_fid']">480<else/>420</if>,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="cat_id={pigcms{$vo.cat_id}" url="{pigcms{:U('category_del')}">删除</a>
                                         </td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" <if condition='$_GET["cat_fid"]'>colspan="7"<else />colspan="6"</if>>{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" <if condition='$_GET["cat_fid"]'>colspan="7"<else />colspan="6"</if>>列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<if condition="empty($now_category)">
						<a href="{pigcms{:U('Appoint/index')}" class="on">分类列表</a>|
						<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/cat_add',array('cat_fid'=>intval($_GET['cat_fid'])))}','添加主分类',520,370,true,false,false,addbtn,'add',true);">添加主分类</a>
					<else/>
						<a href="{pigcms{:U('Appoint/index')}">分类列表</a>|
						<a href="{pigcms{:U('Appoint/index',array('cat_fid'=>$_GET['cat_fid']))}" class="on">{pigcms{$now_category.cat_name} - 子分类列表</a>|
						<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/cat_add',array('cat_fid'=>intval($_GET['cat_fid'])))}','添加子分类',700,470,true,false,false,addbtn,'add',true);">添加子分类</a>
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
								<col/>
							</if>
							<col/>
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>排序</th>
								<th>编号</th>
								<th>名称</th>
								<th>短标记(url)</th>
								<if condition="empty($_GET['cat_fid'])">
									<th>查看子分类</th>
									<!-- <th>商品字段管理</th> -->
								</if>
								<if condition="!empty($_GET['cat_fid'])"><th>预约表单填写项</th></if>
								<th>状态</th>
                                <if condition='$_GET["cat_fid"]'><th>平台运营类别</th></if>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($category_list)">
								<volist name="category_list" id="vo">
									<tr>
										<td>{pigcms{$vo.cat_sort}</td>
										<td>{pigcms{$vo.cat_id}</td>
										<td><if condition="$vo['is_hot']"><font color="red">{pigcms{$vo.cat_name}</font><else/>{pigcms{$vo.cat_name}</if></td>
										<td>{pigcms{$vo.cat_url}</td>
										<if condition="empty($_GET['cat_fid'])">
											<td><a href="{pigcms{:U('Appoint/index',array('cat_fid'=>$vo['cat_id']))}">查看子分类</a></td>
										</if>
										
										<if condition="!empty($_GET['cat_fid'])">
											<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/cue_field',array('cat_id'=>$vo['cat_id']))}','预约表单填写项',580,420,true,false,false,false,'detail',true);">预约表单填写项</a></td>
										</if>
										<td><if condition="$vo['cat_status'] eq 1"><font color="green">启用</font><elseif condition="$vo['cat_status'] eq 2"/><font color="red">待审核</font><else/><font color="red">关闭</font></if></td>
                                        
                                        <if condition='$_GET["cat_fid"]'>
                                        <td><if condition='$vo["is_autotrophic"] eq 1'><font color="green">平台自营</font>&nbsp;&nbsp;&nbsp;
                                         <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/pc_cat_edit',array('cat_id'=>$vo['cat_id']))}','PC端编辑',1000,600,true,false,false,editbtn,'add',true);">PC端编辑</a>&nbsp;|&nbsp;<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/wap_cat_edit',array('cat_id'=>$vo['cat_id']))}','WAP端编辑',1000,600,true,false,false,editbtn,'add',true);">WAP端编辑</a>
                                         <elseif condition='$vo["is_autotrophic"] eq 0' />
                                         	<font color="red">入驻商家</font>
                                         <else />
                                         	<font color="blue">第三方入驻</font>
                                            <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/pc_cat_edit',array('cat_id'=>$vo['cat_id']))}','PC端编辑',1000,600,true,false,false,editbtn,'add',true);">PC端编辑</a>&nbsp;|&nbsp;<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/wap_cat_edit',array('cat_id'=>$vo['cat_id']))}','WAP端编辑',1000,600,true,false,false,editbtn,'add',true);">WAP端编辑</a>
                                         </if></td>
                                         </if>
										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/cat_edit',array('cat_id'=>$vo['cat_id'],'frame_show'=>true))}','查看分类信息',520,480,true,false,false,false,'detail',true);">查看</a> | <a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Appoint/cat_edit',array('cat_id'=>$vo['cat_id']))}','编辑分类信息',700,<if condition="$vo['cat_fid']">480<else/>420</if>,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="cat_id={pigcms{$vo.cat_id}" url="{pigcms{:U('Appoint/cat_del')}">删除</a>
                                         </td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" <if condition='$_GET["cat_fid"]'>colspan="8"<else />colspan="7"</if>>{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" <if condition='$_GET["cat_fid"]'>colspan="8"<else />colspan="7"</if>>列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>
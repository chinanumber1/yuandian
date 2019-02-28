<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
						<a href="{pigcms{:U('Weixin_article/one')}" >新建单图文素材</a>　
						<a href="{pigcms{:U('Weixin_article/multi')}" >新建多图文素材</a>
						<!--a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Group/cat_add',array('cat_fid'=>intval($_GET['cat_fid'])))}','添加子分类',520,370,true,false,false,addbtn,'add',true);">添加子分类</a-->
					</if>
				</ul>
			</div>
	
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<colgroup>
							<col/>
							<col/>
						
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th id="shopList_c1" width="100">标题</th>
								<th id="shopList_c1" width="100">创建时间</th>
								<th id="shopList_c11" width="180">操作</th>
							</tr>
						</thead>
						<tbody>
							
							
							<if condition="$list">
									<volist name="list" id="vo">
										<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
											<td>
											<volist name="vo['list']" id="row">
											<a href="{pigcms{$config['site_url']}/wap.php?c=Article&a=index&imid={pigcms{$row['pigcms_id']}" target="_blank">{pigcms{$row['title']}</a> 阅读量:{pigcms{$row['read_quantity']}<br/>
											</volist>
											</td>
											<td>{pigcms{$vo.dateline}</td>
											<td class="button-column" nowrap="nowrap">
												<if condition="empty($vo['type'])">
											
												<a href="{pigcms{:U('Weixin_article/one',array('pigcms_id'=>$vo['pigcms_id']))}" >编辑</a>
												</if>
											
												 <a href="javascript:void(0);" class="delete_row" parameter="cat_id={pigcms{$vo.cat_id}" url="{pigcms{:U('Weixin_article/del_image',array('pigcms_id'=>$vo['pigcms_id']))}">删除</a>
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

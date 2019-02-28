<include file="Public:header"/>
	<div class="mainbox">
		<div id="nav" class="mainnav_title">
			<ul>
				<a href="{pigcms{:U('groom')}">根列表</a>|
				<a  class="on" href="{pigcms{:U('com_list',array('cat_id'=>$_GET['cat_id']))}">商品列表</a>|
				<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('com_add',array('cat_id'=>$_GET['cat_id']))}','添加商品',500,300,true,false,false,addbtn,'add',true);">添加商品</a>
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
						<col width="140" align="center"/>
					</colgroup>
					<thead>
						<tr>
							<th>排序</th>
							<th>编号</th>
							<th>商品名</th>
							<th>图片</th>
							<th>价格</th>
							<th>跳转地址</th>
							<th>状态</th>
							<th>操作</th>
						</tr>
					</thead>
					<tbody>
						<if condition="is_array($list)">
							<volist name="list" id="vo">
								<tr class="<if condition="$i%2 eq 0">odd<else/>even</if>">
									<td>{pigcms{$vo.sort}</td>
									<td>{pigcms{$vo.com_id}</td>
									<td>{pigcms{$vo.com_name}</td>
									<td><img style="width:50px;height:50px;" src="{pigcms{$vo['com_img']}" /></td>
									<td>{pigcms{$vo.price}</td>
									<td><a target="_Blank" href="{pigcms{$vo['url']}">跳转地址</a></td>
									<td>
										<switch name="vo['status']">
											<case value="0"><span style="color:red;">关闭</span></case>
											<case value="1"><span style="color:green;">开启</span></case>
										</switch>
									</td>
									<td>
										<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('com_edit',array('com_id'=>$vo['com_id'],'cat_id'=>$_GET['cat_id']))}','编辑商品',500,300,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" url="{pigcms{:U('com_del',array('com_id'=>$vo['com_id']))}">删除</a>
									</td>
								</tr>
							</volist>
							<tr><td class="textcenter pagebar" colspan="8">{pigcms{$pagebar}</td></tr>
						<else/>
							<tr><td class="textcenter red" colspan="8">列表为空！</td></tr>
						</if>
					</tbody>
				</table>
			</div>
		</form>
	</div>
<include file="Public:footer"/>
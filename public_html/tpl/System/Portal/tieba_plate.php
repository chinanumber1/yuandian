<include file="Public:header"/>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>					<if condition="empty($now_category)">						<a href="{pigcms{:U('Portal/tieba_plate')}" class="on">贴吧板块</a>|						<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/tieba_plate_add')}','添加主板块',580,450,true,false,false,addbtn,'add',true);">添加板块</a>					<else/>						<a href="{pigcms{:U('Portal/tieba_plate')}">贴吧板块</a>|						<a href="{pigcms{:U('Portal/tieba_plate',array('pid'=>$pid))}" class="on">{pigcms{$now_category.plate_name} - 子板块列表</a>|						<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/tieba_plate_add',array('pid'=>$pid))}','添加子板块',580,450,true,false,false,addbtn,'add',true);">添加子板块</a>					</if>				</ul>			</div>			<if condition="!empty($_GET['cat_fid'])">				<div style="height:30px;line-height:30px;">提示：若主分类下只有一个子分类，网站上子分类不会显示。</div>			</if>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<table width="100%" cellspacing="0">						<colgroup>							<col/>							<col/>							<col/>							<col/>							<if condition="empty($_GET['pid'])">								<!-- <col/> -->								<col/>							</if>							<col/>						</colgroup>						<thead>							<tr>								<th>排序</th>								<th>编号</th>								<th>名称</th>								<if condition="!is_array($tieba_plate)">									<!-- <th>查看子分类</th> -->									<th>管理员</th>								</if>								<th>状态</th>								<th class="textcenter">操作</th>							</tr>						</thead>						<tbody>							<if condition="is_array($plateList)">								<volist name="plateList" id="vo">									<tr>										<td>{pigcms{$vo.sort}</td>										<td>{pigcms{$vo.plate_id}</td>										<td>{pigcms{$vo.plate_name}</td>										<if condition="!is_array($tieba_plate)">											<!-- <td><a href="{pigcms{:U('Portal/tieba_plate',array('pid'=>$vo['plate_id']))}">查看子分类</a></td> -->											<td><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/search_user',array('plate_id'=>$vo['plate_id']))}','管理员',1000,780,true,false,false,false,'add',true);">管理员</a></td>										</if>										<td><if condition="$vo['status'] eq 1"><font color="green">启用</font><else/><font color="red">关闭</font></if></td>										<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/tieba_plate_edit',array('plate_id'=>$vo['plate_id']))}','编辑分类信息',580,450,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="plate_id={pigcms{$vo.plate_id}" url="{pigcms{:U('Portal/tieba_plate_del')}">删除</a></td>									</tr>								</volist>								<tr><td class="textcenter pagebar" colspan="9">{pigcms{$pagebar}</td></tr>							<else/>								<tr><td class="textcenter red" colspan="9">列表为空！</td></tr>							</if>						</tbody>					</table>				</div>			</form>		</div><include file="Public:footer"/>
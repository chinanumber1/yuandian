<include file="Public:header"/>		<div class="mainbox">			<div id="nav" class="mainnav_title">				<ul>					<a href="{pigcms{:U('Bus/bus_line')}" class="on">公交线列表</a>|					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Bus/bus_line_add')}','添加分类',480,260,true,false,false,addbtn,'add',true);">添加公交线</a>|					<a href="{pigcms{:U('Bus/bus_station')}">公交站列表</a>|					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Bus/bus_station_add')}','添加分类',480,260,true,false,false,addbtn,'add',true);">添加公交站</a>				</ul>			</div>			<form name="myform" id="myform" action="" method="post">				<div class="table-list">					<table width="100%" cellspacing="0">						<colgroup>							<col/>							<col/>							<col/>							<col/>							<col width="180" align="center"/>						</colgroup>						<thead>							<tr>								<th>编号</th>								<th>名称</th>								<th>是否热门</th>								<th>状态</th>								<th class="textcenter">操作</th>							</tr>						</thead>						<tbody>							<if condition="is_array($category_list)">															<else/>								<tr><td class="textcenter red" colspan="4">列表为空！</td></tr>							</if>						</tbody>					</table>				</div>			</form>		</div><include file="Public:footer"/>
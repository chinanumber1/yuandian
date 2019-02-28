<include file="Public:header"/>
	<form id="myform" method="post" frame="true" refresh="true">
	
		<table cellpadding="0" cellspacing="0" class="frame_form" width="100%">
			<thead>
				<tr>
					<th>标题</th>
					<th>图片</th>
					<th>描述</th>
					<th>链接</th>
				</tr>
			</thead>
			<tbody>
			<volist name="chanel_content" id="vo">
			<tr>
				<td style="width:10%;">{pigcms{$vo.title}</td>
				<td style="width:30%;"><img src="{pigcms{$vo.img}" style="width:70px;"/></td>
				<td style="width:30%;">{pigcms{$vo.des}</td>
				<td style="width:30%;">{pigcms{$vo.url}</td>
			</tr>
			</volist>
			</tbody>
		</table>

	</form>
<include file="Public:footer"/>
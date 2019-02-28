<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Recommend/index')}" class="on">推荐楼盘列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Recommend/recommend_add')}','添加推荐',600,300,true,false,false,addbtn,'add',true);">添加推荐</a>|
					<a href="{pigcms{:U('Recommend/adv_index')}">首页广告</a>
					<a href="javascript:;" onclick="window.top.artiframe('{pigcms{:U('Recommend/adv_add')}','添加广告',600,300,true,false,false,addbtn,'add',true);" >添加广告</a>
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
							<col width="180" align="center"/>
						</colgroup>
						<thead>
							<tr>
								<th>编号</th>
								<th>栏目名称</th>
								<th>楼盘名称</th>
								<th>推荐时间</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$recommend_list">
								<volist name="recommend_list" id="vo">
									<tr id="tr_{pigcms{$vo.id}">
										<td>{pigcms{$vo.id}</td>
										<td><?php echo isset($column_list[$vo['column_id']])?$column_list[$vo['column_id']]['title']:'';?></td>
										<td><a href="{pigcms{$vo.url}" target="_blank"><?php echo isset($building_list[$vo['building_id']])?$building_list[$vo['building_id']]['title']:'';?></a></td>
										<td>{pigcms{$vo.dateline|date='Y-m-d H:i:s',###}</td>
										<td class="textcenter">
											<a href="javascript:;" onclick="del({pigcms{$vo.id})">删除</a>
									  	</td>
									</tr>
								</volist>
								<tr><td class="textcenter pagebar" colspan="7">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="4">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<script type="text/javascript">
// 删除
function del(id){
	if(!confirm('确定要删除吗？')){
		return;
	}
	$.post("{pigcms{:U('Recommend/del')}",{'id':id},function(response){
		if(response.err_code>0){
			alert(response.err_msg);
		}else{
			$('#tr_'+id).remove();
		}
	},'json');
}
</script>
<include file="Public:footer"/>
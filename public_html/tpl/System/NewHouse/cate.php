<include file="Public:header"/>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('NewHouse/index')}">楼盘列表</a>|
					<a href="{pigcms{:U('NewHouse/cate')}" class="on">聚合首页新房分类</a>|
					<a href="javascript:;" onclick="window.top.artiframe('/admin.php?g=System&c=NewHouse&a=add_cate','添加新房分类',600,400,true,false,false,addbtn,'store_add',true);">添加新房分类</a>
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
								<th>分类名称</th>
								<th>URL</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="is_array($newhouse_cates)">
								<volist name="newhouse_cates" id="vo">
									<tr>
										<td>{pigcms{$vo.id}</td>
										<td>{pigcms{$vo.name}</td>
										<td>{pigcms{$vo.url}</td>
										<td>
											<if condition="$vo['status'] eq 1"><font color="red">禁止</font><elseif condition="$vo['status'] eq 0"/><font color="green">正常</font></if>
										</td>
										<td>
											<a href="javascript:;" onclick="window.top.artiframe('/admin.php?g=System&c=NewHouse&a=add_cate&cate_id={pigcms{$vo.id}','编辑新房分类',600,400,true,false,false,addbtn,'store_add',true);">编辑</a>
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

	function recommend(obj){
		var id = $(obj).attr('label_id');
		$.post("{pigcms{:U('Label/dorecommend')}",{'label_id':id},function(response){
			if(response.err_code == 0){
				if(response.flag == 0){
					$(obj).html('<span style="color:green;">推荐</span>');
				}else{
					$(obj).html('<span style="color:red;">取消</span>');
				}
			}
		},'json');
	}
</script>
<include file="Public:footer"/>
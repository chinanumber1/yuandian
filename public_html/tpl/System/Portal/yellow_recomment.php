<include file="Public:header"/>
<script type="text/javascript" src="//apps.bdimg.com/libs/layer/2.1/layer.js"></script>
		<div class="mainbox">
			<!-- <div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Portal/article')}" class="on">资讯列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/article_add')}','添加资讯',780,450,true,false,false,addbtn,'add',true);">添加资讯</a>
					<a href="{pigcms{:U('Portal/article_label')}">标签列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/article_label_add')}','添加标签',480,200,true,false,false,addbtn,'add',true);">添加标签</a>
				</ul>
			</div> -->
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
								<th class="textcenter">评论ID</th>
								<th class="textcenter">黄页ID</th>
								<th class="textcenter">用户ID</th>
								<th class="textcenter">评论内容</th>
								<th class="textcenter">评论时间</th>
								<th class="textcenter">评论用户</th>
								<th class="textcenter">头像</th>
								<th class="textcenter">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$comment_list">
							<volist name="comment_list" id="vo">
							<tr>
								<td class="textcenter">{pigcms{$vo.id}</td>
								<td class="textcenter">{pigcms{$vo.yellow_id}</td>
								<td class="textcenter">{pigcms{$vo.uid}</td>
								<td class="textcenter">{pigcms{$vo.msg}</td>
								<td class="textcenter">{pigcms{$vo.dateline|date="Y-m-d H:i:s",###}</td>
								<td class="textcenter">{pigcms{$vo.nickname}</td>
								<td class="textcenter"><img style="width: 45px; height: 45px;" src="{pigcms{$vo.avatar}" alt=""></td>
								<td class="textcenter"><a href="javascript:void(0);" class="delete_row" parameter="id={pigcms{$vo.id}" url="{pigcms{:U('Portal/yellow_comment_del')}">删除</a></td>
							</tr>
							</volist>
								<tr><td class="textcenter pagebar" colspan="12">{pigcms{$pagebar}</td></tr>
							<else/>
								<tr><td class="textcenter red" colspan="12">列表为空！</td></tr>
							</if>
						</tbody>
					</table>
				</div>
			</form>
		</div>
<include file="Public:footer"/>

<script type="text/javascript">
	// 特别推荐
	function isrecommend(obj){
		var aid = $(obj).attr('aid');
		var ischecked = $(obj).is(':checked') ? 1 : 0;
		$.post("{pigcms{:U('Portal/article_recommend')}",{'aid':aid,'isrecommend':ischecked},function(response){
			if(response.code > 0){
				layer.alert(response.msg);
			}else{
				layer.msg(response.msg);
			}
		},'json');
	}
</script>
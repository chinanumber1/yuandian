<include file="Public:header"/>
<script type="text/javascript" src="//apps.bdimg.com/libs/layer/2.1/layer.js"></script>
		<div class="mainbox">
			<div id="nav" class="mainnav_title">
				<ul>
					<a href="{pigcms{:U('Portal/article')}" class="on">资讯列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/article_add')}','添加资讯',780,450,true,false,false,addbtn,'add',true);">添加资讯</a>
					<a href="{pigcms{:U('Portal/article_label')}">标签列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/article_label_add')}','添加标签',480,200,true,false,false,addbtn,'add',true);">添加标签</a>
					<a href="{pigcms{:U('Portal/article_source')}">资讯来源列表</a>|
					<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/article_source_add')}','添加来源',480,200,true,false,false,addbtn,'add',true);">添加来源</a>
				</ul>
			</div>
			<form name="myform" id="myform" action="" method="post">
				<div class="table-list">
					<table width="100%" cellspacing="0">
						<thead>
							<tr>
								<th class="textcenter" width="30%">标题</th>
								<th class="textcenter" width="5%">来源</th>
								<th class="textcenter" width="5%">分类</th>
								<th class="textcenter" width="5%">父级分类</th>
								<th class="textcenter" width="5%">缩略图</th>
								<th class="textcenter" width="5%">查看量</th>
								<th class="textcenter" width="10%">时间</th>
								<th class="textcenter" width="5%">状态</th>
								<th class="textcenter" width="5%">特别推荐</th>
								<th class="textcenter" width="10%">操作</th>
							</tr>
						</thead>
						<tbody>
							<if condition="$article_list">
							<volist name="article_list" id="vo">
							<tr>
								<td class="textcenter">{pigcms{$vo.title}</td>
								<td class="textcenter">{pigcms{$vo.source_name}</td>
								<td class="textcenter">{pigcms{$vo.cat_name}</td>
								<td class="textcenter">{pigcms{$vo.fcat_name}</td>
								<td class="textcenter"><if condition="$vo['thumb'] neq './tpl/System/Static/images/addimg.jpg'">有<else/>无</if></td>
								<td class="textcenter">{pigcms{$vo.PV}</td>
								<td class="textcenter">{pigcms{$vo.dateline|date="Y-m-d H:i:s",###}</td>
								<td class="textcenter"><if condition="$vo['status'] eq 1"><font color="green">已发布</font><else/><font color="red">草稿</font></if></td>
								<td class="textcenter"><label><input aid="{pigcms{$vo.aid}" type="checkbox" onclick="isrecommend(this)" <if condition="$vo.recommend eq 1">checked</if> ></label></td>
								<td class="textcenter"><a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/article_add',array('aid'=>$vo['aid']))}','编辑资讯',780,450,true,false,false,editbtn,'add',true);">编辑</a> | <a href="javascript:void(0);" class="delete_row" parameter="aid={pigcms{$vo.aid}" url="{pigcms{:U('Portal/article_del')}">删除</a> | 
								<a href="javascript:void(0);" onclick="window.top.artiframe('{pigcms{:U('Portal/portal_recomment',array('aid'=>$vo['aid'],'type'=>0,'frame_show'=>true))}','评论列表',1000,500,true,false,false,false,'detail',true);">评论管理</a>
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